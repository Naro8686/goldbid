<?php

namespace App\Listeners;

use App\Events\BetEvent;
use App\Jobs\BotBidJob;
use App\Models\Auction\Auction;
use App\Models\Balance;
use App\Models\Bots\AuctionBot;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Log;
use Throwable;


class BetListener implements ShouldQueue
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
        $auction = $event->auction;
        if ($auction && $auction->bid()->exists() && $auction->bots()->exists() && $auction->status === Auction::STATUS_ACTIVE) {
            $run = DB::table('auction_bots')->where([
                ['auction_id', '=', $auction->id],
                ['status', '=', AuctionBot::WORKED],
            ])->doesntExist();
            if ($run) {
                $bot = $this->select($auction);
                if (!is_null($bot)) {
                    $bot->update(['status' => AuctionBot::WORKED]);
                    $delay = Carbon::now()->addSeconds($bot->timeToBet());
                    BotBidJob::dispatch($bot)->delay($delay);
                }
            }
            $this->duplicate($auction);
        }

        return $event;
    }

    /**
     * @param Auction $auction
     * @return AuctionBot|null
     * @throws Throwable
     */
    public function select(Auction $auction)
    {
        /** @var AuctionBot|null $bot */
        $bot = null;
        try {

            $stopBotOne = (int)$auction->bot_shutdown_count;
            $stopBotTwoThree = (int)$auction->bot_shutdown_price;
            $sumBids = $auction->bid->sum('bet') / Auction::BET_RUB;
            $bidBot = DB::table('bids')->where([
                ['auction_id', '=', $auction->id],
                ['is_bot', '=', true],
            ])->orderBy('id', 'desc')->first(['bot_num']);
            $bots = new Collection();
            if ($stopBotOne >= 1) {
                $botOne = $auction->botNum(1);
                if (!is_null($botOne)) {
                    if ($botOne->change_name < 1) $botOne = $botOne->botRefresh();
                    $bots->put(1, $botOne);
                }
            }

            if ($stopBotTwoThree > $sumBids) {
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
                if ($bidBot) {
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
        return $bot;
    }

    private function duplicate(Auction $auction)
    {
        try {
            $duplicate = null;
            foreach ($auction->bid->pluck('price') as $price) {
                $bids = $auction->bid->where('price', $price);
                if ($bids->count() > 1) {
                    $duplicate = $bids;
                    break;
                }
            }
            if (!is_null($duplicate)) {
                $last = $duplicate->last();
                if ($last->is_bot) {
                    if ($last->bot_num === 1) {
                        $auction->bot_shutdown_count += 1;
                    } else {
                        $auction->bot_shutdown_price += 10;
                    }
                    $auction->save(['timestamp' => false]);
                } else {
                    $user = User::find($last->user_id);
                    $type = $last->bonus ? 'bonus' : 'bet';
                    $user->balanceHistory()->create([
                        $type => 1,
                        'type' => Balance::PLUS
                    ]);
                    if ($user->autoBid()->exists()) {
                        $user->autoBid()->where('auto_bids.auction_id', $last->auction_id)
                            ->update(['auto_bids.count' => DB::raw('auto_bids.count + 1')]);
                    }
                }
                $last->delete();
            }
        } catch (Exception $exception) {
            Log::warning('Bet duplicate delete ' . $exception->getMessage());
        }
    }

}
