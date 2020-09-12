<?php

namespace App\Jobs;

use App\Models\Bots\AuctionBot;
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
                $auction = $auctionBot->auction;
                $auction->bots()->where('status', AuctionBot::WORKED)->update(['status' => AuctionBot::PENDING]);
                if ($this->action($auctionBot, $this->first))
                    BidJob::dispatch($auction, $auctionBot->name, null, $auctionBot->number());
            } catch (Throwable $exception) {
                Log::error('auctionBotJob ' . $exception->getMessage());
            }
        }
    }

    public function action(AuctionBot $auctionBot, $first)
    {
        if ($auctionBot->auction->winner()->nickname === $auctionBot->name) return true;
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
        if ($first) {
            if ($auctionBot->auction->bid()->doesntExist()) {
                $run = $auctionBot->update(['change_name' => DB::raw('change_name - 1')]);
            }
        } else {
            $run = $auctionBot->update(['change_name' => DB::raw('change_name - 1')]);
        }
        if ($run) $run = $auctionBot->auction->update(['bot_shutdown_count' => DB::raw('bot_shutdown_count - 1')]);
        return $run;
    }

    public function botTwoThree(AuctionBot $auctionBot)
    {
        $run = false;
        try {
            if ($auctionBot->num_moves + $auctionBot->num_moves_other_bot > 0) {
                $key = $auctionBot->num_moves > 0 ? 'num_moves' : 'num_moves_other_bot';
                $auctionBot->$key = $auctionBot->$key - 1;
                $run = $auctionBot->save();
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        return $run;
    }
}
