<?php

namespace App\Jobs;

use App\Events\BetEvent;
use App\Models\Auction\Auction;
use App\Models\Auction\AutoBid;
use App\Models\Balance;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BidJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const BID_COUNT = 1;
    public $auction;
    public $user;
    public $nickname;

    /**
     * Create a new job instance.
     *
     * @param Auction $auction
     * @param User|null $user
     * @param $nickname
     */
    public function __construct(Auction $auction, string $nickname, User $user = null)
    {
        $this->user = $user;
        $this->auction = $auction;
        $this->nickname = $nickname;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        $auction = $this->auction;
        $update = true;
        try {
            DB::beginTransaction();
            if ($auction->status === Auction::STATUS_ACTIVE && $auction->winner()->nickname !== $this->nickname) {
                $bid_type = 'bet';
                $data['price'] = $auction->new_price();
                $data['title'] = $auction->title;
                $data['nickname'] = $this->nickname;
                $data['is_bot'] = is_null($this->user);
                $data['user_id'] = null;
                if (!$data['is_bot']) {
                    $user = $this->user;
                    $balance = $user->balance();
                    if (($balance->bet + $balance->bonus) >= self::BID_COUNT) {
                        $bid_type = $balance->bet > 0 ? 'bet' : 'bonus';
                        $data['user_id'] = $user->id;
                        $user->balanceHistory()->create([
                            $bid_type => self::BID_COUNT,
                            'type' => Balance::MINUS,
                        ]);
                    } else {
                        throw new Exception('no money');
                    }
                }
                $data[$bid_type] = self::BID_COUNT;
                $auction->bid()->create($data);
            } else {
                throw new Exception('duplicated nickname');
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error('Bid Job ' . $exception->getMessage());
            $update = false;
        }
        if ($update) $auction->update(['step_time' => Carbon::now()->addSeconds($auction->bid_seconds)]);
        event(new BetEvent($auction));
    }
}
