<?php

namespace App\Console\Commands;

use App\Events\StatusChangeEvent;
use App\Jobs\CreateAuctionJob;
use App\Jobs\DeleteAuctionInNotWinner;
use App\Mail\MailingSendMail;
use App\Models\Auction\Auction;
use App\Models\Mailing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class StatusChangeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status:change';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        while (true) {
            $this->statusChange();
            sleep(1);
        }
    }

    public function statusChange()
    {
        $current = Carbon::now();
        $pending = DB::table('auctions')->where([
            ['active', '=', true],
            ['status', '=', Auction::STATUS_PENDING],
            ['start', '<=', $current]
        ])->first();
        $active = DB::table('auctions')->where([
            ['active', '=', true],
            ['status', '=', Auction::STATUS_ACTIVE],
            ['step_time', '<=', $current]
        ])->first();

        if (isset($pending))
            $this->pending($pending, $current);
        if (isset($active))
            $this->active($active, $current);
    }

    public function pending($auction, $now)
    {
        try {
            DB::beginTransaction();
            if ($pending = Auction::query()->find($auction->id))
                $pending->update([
                    'status' => Auction::STATUS_ACTIVE,
                    'step_time' => $now->addSeconds($auction->bid_seconds)
                ]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('status_change_pending' . $e->getMessage());
        }
        event(new StatusChangeEvent(['status_change' => true, 'auction_id' => $auction->id]));
    }

    public function active($auction, $end)
    {
        /** @var User $user */
        try {
            DB::beginTransaction();
            if($finished = Auction::query()->find($auction->id)){
                if ($finished->update(['status' => Auction::STATUS_FINISHED, 'end' => $end, 'top' => false]))
                    CreateAuctionJob::dispatchIf((isset($finished->product) && $finished->product->visibly), $finished->product);
                if ($bid = $finished->bid->last()) {
                    $bid->win = true;
                    $bid->save(['timestamp' => false]);
                    if (!$bid->is_bot && isset($bid->user_id) && $user = User::query()->find($bid->user_id)) {
                        if ($user->email)
                            Mail::to($user->email)->queue(new MailingSendMail(Mailing::VICTORY, ['auction' => $bid->auction_id]));
                    }
                } else
                    DeleteAuctionInNotWinner::dispatchIf(isset($finished), $finished)->delay(now()->addSeconds(5));
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('status_change_active' . $e->getMessage());
        }
        event(new StatusChangeEvent(['status_change' => true, 'auction_id' => $auction->id]));
    }
}
