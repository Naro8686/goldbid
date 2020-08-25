<?php

namespace App\Jobs;

use App\Events\BetEvent;
use App\Models\Auction\Auction;
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
     * @throws Throwable
     */
    public function handle()
    {
        $update = false;
        try {
            DB::beginTransaction();
            if ($this->auction->winner()->nickname !== $this->nickname) {
                $data = [
                    'price' => $this->auction->new_price(),
                    'title' => $this->auction->title,
                    'nickname' => $this->nickname,
                    'is_bot' => is_null($this->user),
                    'user_id' => (is_null($this->user) ? null : $this->user->id)
                ];
                if (!$data['is_bot']) {
                    $balance = $this->user->balance();
                    if ((int)($balance->bet + $balance->bonus) >= self::BID_COUNT) {
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
                    $update = true;
                }
                if ($update && $this->auction->update(['step_time' => Carbon::now()->addSeconds($this->auction->bid_seconds)])) {
                    $this->auction->bid()->create($data);
                }
            }
            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error('Bid Job ' . $exception->getMessage());
        }
        event(new BetEvent($this->auction));
    }
}
