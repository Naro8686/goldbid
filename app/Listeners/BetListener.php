<?php

namespace App\Listeners;

use DB;
use Log;
use Throwable;
use Carbon\Carbon;
use App\Jobs\BotBidJob;
use App\Events\BetEvent;
use App\Jobs\AutoBidJob;
use App\Models\Auction\Bid;
use App\Models\Auction\Auction;
use App\Models\Auction\AutoBid;
use App\Models\Bots\AuctionBot;
use Illuminate\Support\Collection;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;


class BetListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @var Carbon
     */
    private $now;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->now = Carbon::now("Europe/Moscow");
    }

    /**
     * Handle the event.
     *
     * @param BetEvent $event
     * @return BetEvent
     * @throws Throwable
     */
    public function handle(BetEvent $event): BetEvent
    {

        $jobs = [];
        $auction = $event->auction->refresh();
        $winner = $auction->winner()->refresh();
        try {
            if ($auction->autoBid()->exists() && $autoBid = self::selectAutoBid($auction, $winner)) {
                $jobs[] = [
                    "DB" => $autoBid['model'],
                    "lastBet" => $autoBid['lastBet'],
                    "job" => $autoBid['job'],
                ];
            }
            if ($auction->bots()->exists() && $bot = self::selectBot($auction, $winner)) {
                $jobs[] = [
                    "DB" => $bot['model'],
                    "lastBet" => $bot['lastBet'],
                    "job" => $bot['job'],
                ];
            }
            if (count($jobs) > 1) usort($jobs, function ($a, $b) {
                if ($a["lastBet"] === $b["lastBet"]) return 0;
                return ($a["lastBet"] < $b["lastBet"]) ? -1 : 1;
            });
            if (isset($jobs[1])) $jobs[1]['DB']->update(['status' => AutoBid::PENDING]);
            if (isset($jobs[0])) {
                /** @var AutoBidJob|BotBidJob $job */
                /** @var AutoBid|AuctionBot $db */
                $job = $jobs[0]['job'];
                $db = $jobs[0]['DB'];
                $job::dispatch($db)->delay($this->now->addSeconds($db->timeToBet()));
            }

        } catch (Throwable $throwable) {
            Log::error('BetListener ' . $throwable->getMessage() . ' line ' . $throwable->getLine());
        }

        return $event;
    }

    public function shouldQueue(BetEvent $event): bool
    {
        $auction = $event->auction->refresh();
        return !($auction->jobExists() || $auction->status !== $auction::STATUS_ACTIVE);
    }


    /**
     * @param Auction $auction
     * @param Bid $winner
     * @return array|null
     * @throws Throwable
     */
    private static function selectBot(Auction $auction, Bid $winner): ?array
    {
        $result = null;
        $bot = null;
        try {
            DB::beginTransaction();
            $bots = new Collection();
            $stopBotOne = (int)$auction->bot_shutdown_count;
            $stopBotTwoThree = (int)$auction->bot_shutdown_price;
            $bids = $auction->bid();
            $sumBids = (int)($bids->sum('bet') * Auction::BET_RUB);
            $bidBot = $bids
                ->where('bids.is_bot', '=', true)
                ->orderByDesc('bids.id')
                ->first(['bids.bot_num', 'bids.updated_at']);

            $botOne = $auction->botNum(1);

            if (!is_null($botOne) && $stopBotOne > $auction->botCountBet()) {
                $bots->put(1, $botOne);
            }
            if ($stopBotTwoThree > $sumBids) {
                $botTwo = $auction->botNum(2);
                $botThree = $auction->botNum(3);
                if (!is_null($botTwo)) {
                    if (is_null($botThree)) $bots->put(2, $botTwo);
                    else {
                        if (($botTwo->num_moves_other_bot + $botTwo->num_moves) > 0) $bots->put(2, $botTwo);
                        else if ($botThree->num_moves <= 0) $bots->put(2, $botTwo);
                    }
                }
                if (!is_null($botThree)) {
                    if (is_null($botTwo)) $bots->put(3, $botThree);
                    else if ($botTwo->num_moves <= 0 || ($botThree->num_moves <= 0 && $botThree->num_moves_other_bot > 0)) $bots->put(3, $botThree);
                }
            }
            if ($bots->isNotEmpty()) {
                /** @var AuctionBot $bot */
                $first = $bots->first();
                if (!is_null($bidBot)) {
                    $next = current(array_slice($bots->toArray(), array_search($bidBot->bot_num, $bots->keys()->toArray()) + 1, 1));
                    $bot = (($next === false) ? $first : $bots->firstWhere('id', '=', $next['id']));
                } else $bot = $first;
                if (is_null($winner->bot_num) || !$bot->number((int)$winner->bot_num)) {
                    $bot->status = AuctionBot::WORKED;
                    $bot->save();
                    $result = [
                        'model' => AuctionBot::with(['auction'])->find($bot->id),
                        'lastBet' => $auction->lastBid($bot->name)->timestamp,
                        'job' => BotBidJob::class
                    ];
                }
            }
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            Log::error('selectBot ' . $throwable->getMessage());
        }
        return $result;
    }


    /**
     * @param Auction $auction
     * @param Bid $winner
     * @return array|null
     * @throws Throwable
     */
    private static function selectAutoBid(Auction $auction, Bid $winner): ?array
    {
        /** @var AutoBid $autoBet */
        $result = null;
        try {
            DB::beginTransaction();
            $autoBet = $auction->autoBid()->where([
                ['auto_bids.count', '>', 0],
                ['auto_bids.user_id', '<>', $winner->user_id],
                ['auto_bids.status', '=', AutoBid::PENDING],
            ])->orderBy('auto_bids.bid_time')->orderBy('auto_bids.id')->first();

            if (!is_null($autoBet)) {
                $autoBet->status = AuctionBot::WORKED;
                $autoBet->save();
                $result = [
                    'model' => $autoBet,
                    'lastBet' => $auction->lastBid($autoBet->user->nickname)->timestamp,
                    'job' => AutoBidJob::class
                ];
            }
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            Log::error('selectAutoBid ' . $throwable->getMessage());
        }
        return $result;
    }
}
