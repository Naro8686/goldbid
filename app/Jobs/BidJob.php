<?php

namespace App\Jobs;

use App\Events\BetEvent;
use App\Models\Auction\Auction;
use App\Models\Auction\Order;
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
use Throwable;

class BidJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const BID_COUNT = 1;
    public $bid_type = 'bet';
    /**
     * @var User|null
     */
    public $user;
    /**
     * @var string
     */
    public $nickname;
    /**
     * @var Auction
     */
    public $auction;
    /**
     * @var int|null
     */
    private $botNum;

    /**
     * Create a new job instance.
     *
     * @param Auction $auction
     * @param string $nickname
     * @param User|null $user
     * @param int|null $botNum
     */
    public function __construct(Auction $auction, string $nickname, User $user = null, int $botNum = null)
    {
        $this->user = $user;
        $this->auction = $auction;
        $this->nickname = $nickname;
        $this->botNum = $botNum;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Throwable
     */
    public function handle()
    {
        if (!is_null($this->auction) && $this->auction->status === Auction::STATUS_ACTIVE) {
            try {
                DB::transaction(function () {
                    $update = false;
                    $this->auction = Auction::whereId($this->auction->id)->lockForUpdate()->first();
                    if ($this->auction->winner()->nickname !== $this->nickname && $this->auction->bid->where('price', $this->auction->new_price())->isEmpty()) {
                        $data = [
                            'price' => $this->auction->new_price(),
                            'title' => $this->auction->title,
                            'nickname' => $this->nickname,
                            'is_bot' => is_null($this->user),
                            'user_id' => (is_null($this->user) ? null : $this->user->id)
                        ];
                        if (!$data['is_bot']) {
                            $user = $this->user;
                            $balance = $user->balance();
                            $ordered = $user->auctionOrder()
                                ->where('orders.auction_id', '=', $this->auction->id)
                                ->where('orders.status', '=', Order::SUCCESS)
                                ->doesntExist();
                            if ((int)($balance->bet + $balance->bonus) >= self::BID_COUNT && $ordered && ($this->auction->full_price($user->id) > 1)) {
                                $this->bid_type = $balance->bet > 0 ? 'bet' : 'bonus';
                                $data[$this->bid_type] = self::BID_COUNT;
                                $this->user->balanceHistory()->create([
                                    $this->bid_type => self::BID_COUNT,
                                    'type' => Balance::MINUS
                                ]);
                                $update = true;
                            }
                        } elseif ($data['is_bot']) {
                            $data[$this->bid_type] = self::BID_COUNT;
                            $data['bot_num'] = $this->botNum;
                            $update = true;
                        }
                        if ($update && $this->auction->update(['step_time' => Carbon::now("Europe/Moscow")->addSeconds($this->auction->bid_seconds + 1)])) {
                            $this->auction->bid()->create($data);
                        }
                    }
                    if (!$update) $this->auction->touch();
                });
            } catch (Throwable $exception) {
                Log::error('Bid Job ' . $exception->getMessage());
            }
            event(new BetEvent($this->auction));
        }
    }
}
