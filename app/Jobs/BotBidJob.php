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

    /**
     * Execute the job.
     *
     * @return void
     * @throws Throwable
     */
    public function handle()
    {
        if ($auctionBot = $this->auctionBot) {
            try {
                $auction = Auction::find($auctionBot->auction->id);
                $auction->bots()->where('status', AuctionBot::WORKED)->update(['status' => AuctionBot::PENDING]);
                if ($this->action($auctionBot, $this->first))
                    BidJob::dispatch($auction, $auctionBot->name, null, $auctionBot->number());
                else event(new BetEvent($auction));
            } catch (Throwable $exception) {
                Log::error('auctionBotJob ' . $exception->getMessage());
            }
        }
    }

    public function action(AuctionBot $auctionBot, $first)
    {
        if ($auctionBot->auction->winner()->nickname === $auctionBot->name) event(new BetEvent($auctionBot->auction));
        $run = false;
        switch ($auctionBot->number()) {
            case 1:
                $run = $this->botOne($auctionBot, $first);
                break;
            case 3:
            case 2:
                $run = $this->botTwoThree($auctionBot);
                break;
        }
        return $run;
    }

    public function botOne(AuctionBot $auctionBot, $first)
    {
        $run = false;
        try {
            if ($auctionBot->auction && (int)$auctionBot->change_name > 0 && (int)$auctionBot->auction->bot_shutdown_count > 0) {
                if (!$first) {
                    $run = $auctionBot->minus('change_name');
                }
                if ($first && $auctionBot->auction->bid()->doesntExist()) {
                    $run = $auctionBot->minus('change_name');
                }
                if ($run) {
                    $auctionBot->auction->decrement('bot_shutdown_count');
                }
            }
        } catch (Throwable $e) {
            Log::error($e->getMessage());
        }
        return (bool)$run;
    }

    public function botTwoThree(AuctionBot $auctionBot)
    {
        $run = false;
        try {
            if ($auctionBot->num_moves + $auctionBot->num_moves_other_bot > 0) {
                $key = $auctionBot->num_moves > 0 ? "num_moves" : "num_moves_other_bot";
                if ($auctionBot->$key > 0) {
                    $auctionBot->$key = $auctionBot->$key - 1;
                    $run = $auctionBot->save();
                }
            }
        } catch (Throwable $e) {
            Log::error($e->getMessage());
        }
        return (bool)$run;
    }
}
