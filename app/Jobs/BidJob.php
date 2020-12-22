<?php

namespace App\Jobs;

use App\Events\BetEvent;
use App\Models\Auction\Auction;
use App\Models\Auction\AutoBid;
use App\Models\Auction\Bid;
use App\Models\Auction\Order;
use App\Models\Balance;
use App\Models\Bots\AuctionBot;
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
use Throwable;

class BidJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const BID_COUNT = 1;
    /**
     * @var string
     */
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
     * @var bool
     */
    private $is_bot;

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
        $this->is_bot = is_null($user);
    }

    /**
     * @return bool
     */
    private function middleware(): bool
    {
        $checkBalance = true;
        $this->auction = $auction = $this->auction->refresh();
        if (!$this->is_bot) {
            $this->user = $user = $this->user->refresh();
            $balance = $user->balance();
            $ordered = $user->auctionOrder()
                ->where('orders.auction_id', '=', $this->auction->id)
                ->where('orders.status', '=', Order::SUCCESS)
                ->doesntExist();
            if ($checkBalance = ((int)($balance->bet + $balance->bonus) >= self::BID_COUNT && $ordered))
                $this->bid_type = $balance->bet > 0 ? 'bet' : 'bonus';
        }
        return (
            !is_null($auction) &&
            $checkBalance &&
            $auction->winner()->nickname !== $this->nickname &&
            $auction->status === Auction::STATUS_ACTIVE
        );
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Throwable
     */
    public function handle()
    {

        try {
            DB::beginTransaction();
            if ($this->middleware()) {
                $auction = $this->auction->refresh();
                $updated = $auction->update([
                    'step_time' => Carbon::now("Europe/Moscow")
                        ->addSeconds($auction->bid_seconds + 1)
                ]);
                $bid = $auction->bid()->create($this->setData($auction));
                if (!$bid instanceof Bid || !$updated)
                    throw new Exception('$bid not instanceof Bid');
            }
            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error('Bid Job ' . $exception->getMessage());
        }
        if ($auction = $this->auction->refresh()) {
            event(new BetEvent($auction));
        }
    }

    /**
     * @param Auction $auction
     * @return array
     * @throws Exception
     */
    private function setData(Auction $auction): array
    {
        $data = [
            $this->bid_type => self::BID_COUNT,
            'price' => $auction->new_price(),
            'title' => $auction->title,
            'nickname' => $this->nickname,
            'is_bot' => $this->is_bot,
        ];
        if (!$this->is_bot) {
            $user = $this->user;
            $data['user_id'] = $user->id;
            $balance = $user->balanceHistory()->create([
                $this->bid_type => self::BID_COUNT,
                'type' => Balance::MINUS
            ]);
            if (!$balance instanceof Balance) throw new Exception('$balance not instanceof Balance');
        } elseif ($this->is_bot) {
            $data['bot_num'] = $this->botNum;
        } else throw new Exception('undefined');

        return $data;
    }
}
