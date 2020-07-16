<?php

namespace App\Console\Commands;

use App\Events\StatusChangeEvent;
use App\Jobs\CreateAuctionJob;
use App\Jobs\DeleteAuctionInNotWinner;
use App\Models\Auction\Auction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $auctions = Auction::all()->where('active', true);
        foreach ($auctions as $key => $auction) {
            if ($auction->status === Auction::STATUS_PENDING && !$auction->start->diff(now())->invert)
                $this->pending($auction);
            if ($auction->status === Auction::STATUS_ACTIVE && !$auction->step_time->diff(now())->invert)
                $this->active($auction, $key);
        }
    }

    public function pending($auction)
    {
        try {
            DB::beginTransaction();
            $auction->update([
                'status' => Auction::STATUS_ACTIVE,
                'step_time' => Carbon::now()->addSeconds($auction->bid_seconds)
            ]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::info($e->getMessage());
        }
        event(new StatusChangeEvent(['status_change' => true]));
    }

    public function active($auction, $key)
    {
        try {
            DB::beginTransaction();
            $end = Carbon::now();
            $auction->update(['status' => Auction::STATUS_FINISHED, 'end' => $end]);
            if ($auction->winner()->nickname) {
                $winner = $auction->winner();
                $winner->win = true;
                $winner->created_at = $end;
                $winner->updated_at = $end;
                $winner->save(['timestamp' => false]);
                CreateAuctionJob::dispatchIf($auction->product->visibly, $auction->product)
                    ->delay(now()->addSeconds($key * 2));
            } else
                DeleteAuctionInNotWinner::dispatch($auction)
                    ->delay(now()->addSeconds($key * 1));
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::info($e->getMessage());
        }
        event(new StatusChangeEvent(['status_change' => true]));
    }
}
