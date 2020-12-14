<?php

namespace App\Jobs;

use App\Events\BetEvent;
use App\Models\Auction\Auction;
use App\Models\Bots\AuctionBot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class BotBidJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $auctionBot;
    /**
     * @var bool
     */
    private $first;

    /**
     * BotBidJob constructor.
     * @param AuctionBot $auctionBot
     * @param bool $first
     */
    public function __construct(AuctionBot $auctionBot, $first = false)
    {
        $this->auctionBot = $auctionBot;
        $this->first = $first;
    }

    public function fail($exception = null)
    {
        $this->auctionBot->update(['status' => AuctionBot::PENDING]);
        event(new BetEvent($this->auctionBot->auction->refresh()));
        if (!is_null($exception)) Log::info('BotBidJob fail ' . $exception);
    }


    /**
     * Execute the job.
     *
     * @return void
     * @throws Throwable
     */
    public function handle()
    {
        if ($auctionBot = $this->auctionBot->refresh()) {
            try {
                $auction = $auctionBot->auction;
                $this->action($auctionBot) ? BidJob::dispatchNow($auction, $auctionBot->name, null, $auctionBot->number()) : $this->fail('fail');
            } catch (Throwable $exception) {
                $this->fail($exception->getMessage());
            }
        }
    }

    /**
     * @param AuctionBot $auctionBot
     * @return bool
     */
    public function action(AuctionBot $auctionBot): bool
    {
        $run = false;
        switch ($auctionBot->number()) {
            case 1:
                return $this->botOne($auctionBot);
            case 2:
            case 3:
                return $this->botTwoThree($auctionBot);
            default:
                return $run;
        }
    }

    /**
     * @param AuctionBot $auctionBot
     * @return bool
     */
    public function botOne(AuctionBot $auctionBot): bool
    {
        $run = false;
        try {
            if ((int)$auctionBot->change_name <= 0) $auctionBot = $auctionBot->botRefresh();
            if (!is_null($auctionBot) && !is_null($auctionBot->auction) && (int)$auctionBot->change_name > 0) {
                $auctionBot->change_name -= 1;
                $run = $auctionBot->save();
            }
        } catch (Throwable $e) {
            $this->fail($e->getMessage());
        }
        return ($run !== false);
    }

    /**
     * @param AuctionBot $auctionBot
     * @return bool
     */
    public function botTwoThree(AuctionBot $auctionBot): bool
    {
        $run = false;
        try {
            if (((int)$auctionBot->num_moves + (int)$auctionBot->num_moves_other_bot) <= 0) $auctionBot = $auctionBot->botRefresh();
            if (!is_null($auctionBot)) {
                $key = ($auctionBot->num_moves > 0 ? "num_moves" : "num_moves_other_bot");
                if ($auctionBot->$key > 0) {
                    $auctionBot->$key -= 1;
                    $run = $auctionBot->save();
                }
            }
        } catch (Throwable $e) {
            $this->fail($e->getMessage());
        }
        return ($run !== false);
    }
}
