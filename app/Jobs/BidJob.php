<?php

namespace App\Jobs;

use App\Events\BetEvent;
use App\Models\Auction\Auction;
use App\Models\Auction\AutoBid;
use App\Models\Balance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BidJob implements ShouldQueue
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
     * @throws \Exception
     */
    public function handle()
    {
        $auction = $this->auction;
        try {
            DB::beginTransaction();
            if ($auction->status === Auction::STATUS_ACTIVE && $auction->winner()->nickname !== $this->nickname) {
                $data['price'] = $auction->new_price();
                $data['title'] = $auction->title;
                $data['nickname'] = $this->nickname;
                if (!is_null($this->user)) {
                    $user = $this->user;
                    $balance = $user->balance();
                    if (($balance->bet + $balance->bonus) >= self::BID_COUNT) {
                        $bid_type = $balance->bet > 0 ? 'bet' : 'bonus';
                        $user->balanceHistory()->create([
                            $bid_type => self::BID_COUNT,
                            'type' => Balance::MINUS,
                        ]);
                        $data[$bid_type] = self::BID_COUNT;
                        $data['user_id'] = $user->id;
                    } else {
                        throw new \Exception('no money');
                    }
                } else {
                    $data['bet'] = self::BID_COUNT;
                    $data['is_bot'] = true;
                }
                $last = $auction->bid()->create($data);
                $time = $last->created_at->addSeconds($auction->bid_seconds);
                $auction->update(['step_time' => $time]);
            } else {
                throw new \Exception('duplicated nickname');
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
        }
        event(new BetEvent($auction));
    }
}
