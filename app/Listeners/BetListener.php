<?php

namespace App\Listeners;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\Auction\Auction;
use App\Models\Auction\AutoBid;
use App\Models\Bots\AuctionBot;
use App\Models\Auction\Order;
use App\Events\BetEvent;
use Carbon\Carbon;
use Throwable;
use Log;

//use Illuminate\Contracts\Queue\ShouldQueue;
//use Illuminate\Queue\InteractsWithQueue;

class BetListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param BetEvent $event
     * @return BetEvent
     * @throws Throwable
     */
    public function handle(BetEvent $event)
    {
        $jobs = [];
        $auction = $event->auction;
        try {
            DB::beginTransaction();
            if (isset($auction) && !$auction->jobExists() && $auction->status === Auction::STATUS_ACTIVE) {
                if ($auction->bid()->exists() && $auction->bots()->exists()) {
                    if ($bot = $this->selectBot($auction)) {
                        $bot->update(['status' => AuctionBot::WORKED]);
                        $jobs[] = [
                            "DB" => $bot,
                            "job" => "BotBidJob",
                            "delay" => Carbon::now("Europe/Moscow")->addSeconds($bot->timeToBet())
                        ];
                    }
                }
                if ($auction->autoBid()->exists()) {
                    if ($next = $this->selectAutoBid($auction)) {
                        $next->update(['status' => AutoBid::WORKED]);
                        $jobs[] = [
                            "DB" => $next,
                            "job" => "AutoBidJob",
                            "delay" => Carbon::now("Europe/Moscow")->addSeconds($next->timeToBet())
                        ];
                    }
                }
                usort($jobs, function ($a, $b) {
                    if ($a["delay"] === $b["delay"]) return 0;
                    return ($a["delay"] < $b["delay"]) ? -1 : 1;
                });
                if (isset($jobs[1])) $jobs[1]['DB']->update(['status' => AutoBid::PENDING]);
                if (isset($jobs[0])) {
                    $job = $jobs[0]['job'];
                    $class = "\\App\\Jobs\\$job";
                    $db = $jobs[0]['DB'];
                    $delay = $jobs[0]['delay'];
                    $class::dispatch($db)->delay($delay);
                }
            }
            DB::commit();
        } catch (Throwable $throwable) {
            Log::error('AutoBidListener ' . $throwable->getMessage());
            DB::rollBack();
        }

        return $event;
    }

    /**
     * @param Auction $auction
     * @return AuctionBot|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     * @throws Throwable
     */
    public function selectBot(Auction $auction)
    {
        /** @var AuctionBot|null $bot */
        $bot = null;
        $run = DB::table('auction_bots')->where([
            ['auction_id', '=', $auction->id],
            ['status', '=', AuctionBot::WORKED],
        ])->exists();
        if ($run) return $bot;
        try {
            $stopBotOne = (int)$auction->bot_shutdown_count;
            $stopBotTwoThree = (int)$auction->bot_shutdown_price;
            $sumBids = (int)($auction->bid->sum('bet') * Auction::BET_RUB);
            $bidBot = DB::table('bids')->where([
                ['auction_id', '=', $auction->id],
                ['is_bot', '=', true],
            ])->orderBy('id', 'desc')
                //->sharedLock()
                ->first(['bot_num']);
            $bots = new Collection();
            if ($stopBotOne >= 1) {
                /** @var AuctionBot $botOne */
                $botOne = $auction->botNum(1);
                if (!is_null($botOne)) {
                    if ($botOne->change_name < 1) $botOne = $botOne->botRefresh();
                    $bots->put(1, $botOne);
                }
            }
            if ($stopBotTwoThree > $sumBids) {
                /** @var AuctionBot $botTwo */
                /** @var AuctionBot $botThree */
                $botTwo = $auction->botNum(2);
                $botThree = $auction->botNum(3);
                if (!is_null($botTwo)) {
                    $allMoves = $botTwo->num_moves_other_bot + $botTwo->num_moves;
                    if (is_null($botThree)) {
                        if ($allMoves < 1) $botTwo = $botTwo->botRefresh();
                        $bots->put(2, $botTwo);
                    } else {
                        if ($allMoves > 0) $bots->put(2, $botTwo);
                        elseif ($allMoves < 1 && $botThree->num_moves < 1) {
                            $bots->put(2, $botTwo = $botTwo->botRefresh());
                        }
                    }
                }
                if (!is_null($botThree)) {
                    $allMoves = $botThree->num_moves_other_bot + $botThree->num_moves;
                    if (is_null($botTwo)) {
                        if ($allMoves < 1) $botThree = $botThree->botRefresh();
                        $bots->put(3, $botThree);
                    } else {
                        if ($botTwo->num_moves < 1 || ($botThree->num_moves < 1 && $botThree->num_moves_other_bot > 0)) {
                            if ($allMoves < 1) $botThree = $botThree->botRefresh();
                            $bots->put(3, $botThree);
                        }
                    }
                }
            }
            if ($bots->isNotEmpty()) {
                $first = $bots->first();
                if (!is_null($bidBot) && !is_null($bidBot->bot_num)) {
                    $array = $bots->toArray();
                    $next = current(array_slice($array, array_search($bidBot->bot_num, array_keys($array)) + 1, 1));
                    $bot = (($next === false) ? $first : $bots->firstWhere('id', '=', $next['id']));
                } else {
                    $bot = $first;
                }
            }
        } catch (Throwable $exception) {
            Log::error('select bot ' . $exception->getMessage());
        }
        return !is_null($bot) ? AuctionBot::whereId($bot->id)->lockForUpdate()->first() : null;
    }

    /**
     * @param Auction $auction
     * @return AutoBid|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasMany|object|null
     */
    private function selectAutoBid(Auction $auction)
    {
        try {
            $run = DB::table('auto_bids')->where([
                ['auction_id', '=', $auction->id],
                ['status', '=', AutoBid::WORKED],
            ])->exists();
            if ($run) return null;
            foreach ($auction->autoBid as $autoBid) {
                $user = $autoBid->user;
                $balance = $user->balance();
                $stop = $user->auctionOrder()
                        ->where('orders.auction_id', '=', $auction->id)
                        ->where('orders.status', '=', Order::SUCCESS)
                        ->exists() || ($auction->full_price($user->id) <= 1);
                if ($autoBid->count <= 0 || ($balance->bet + $balance->bonus) <= 0 || $stop) $autoBid->delete();
            }
        } catch (Throwable $exception) {
            Log::error('selectAutoBid: ' . $exception->getMessage());
        }
        return $auction->autoBid()
            ->where('auto_bids.user_id', '<>', $auction->winner()->user_id)
            ->where('auto_bids.status', '=', AutoBid::PENDING)
            ->orderBy('auto_bids.bid_time')
            ->orderBy('auto_bids.id')
            ->lockForUpdate()
            ->first();
    }
}
