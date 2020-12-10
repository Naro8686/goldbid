<?php

namespace App\Jobs;

use App\Events\StatusChangeEvent;
use App\Mail\MailingSendMail;
use App\Models\Auction\Auction;
use App\Models\Balance;
use App\Models\Bots\AuctionBot;
use App\Models\Mailing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class StatusChangeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    private $auctionID;

    /**
     * Create a new job instance.
     *
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->auctionID = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            if ($auction = Auction::find($this->auctionID)) {
                switch ((int)$auction->status) {
                    case Auction::STATUS_ACTIVE:
                        $this->active($auction);
                        break;
                    case Auction::STATUS_FINISHED:
                        $this->finish($auction);
                        break;
                }
                event(new StatusChangeEvent($auction));
            }
        } catch (Throwable $e) {
            Log::error('status_change_active line = ' . $e->getLine() . ' Message = ' . $e->getMessage());
        }
    }

    /**
     * @param Auction $auction
     * @return Auction
     */
    private function finish(Auction $auction)
    {
        CreateAuctionJob::dispatchIf((isset($auction->product) && $auction->product->visibly), $auction->product);
        $delete = false;
        $bid = $auction->winner();
        if (!is_null($bid->nickname)) {
            $bid->win = true;
            $bid->save(['timestamp' => false]);
            if (!$bid->is_bot && $bid->user_id) {
                $user = User::find($bid->user_id);
                if ($auction->shutdownBots()) {
                    if (!is_null($user) && !is_null(config('mail.from.address')) && $user->email) {
                        try {
                            Mail::to($user->email)->queue(new MailingSendMail(Mailing::VICTORY, ['auction' => $bid->auction_id]));
                        } catch (Throwable $e) {
                            Log::error('auction finish send mail to winner ' . $e->getMessage());
                        }
                    }
                } else {
                    $auction->update(['status' => Auction::STATUS_ERROR]);
                    $count = $auction->bid()->where('user_id', $bid->user_id);
                    $bet = $count->sum('bet');
                    $bonus = $count->sum('bonus');
                    $user->balanceHistory()->create([
                        'reason' => Balance::RETURN_REASON,
                        'type' => Balance::PLUS,
                        'bet' => $bet,
                        'bonus' => $bonus,
                    ]);
                    $delete = true;
                }
            }
        } else $delete = true;
        if ($auction->userFavorites()->exists()) $auction->userFavorites()->detach();
        //if ($auction->autoBid()->exists()) $auction->autoBid()->delete();
        if ($delete) DeleteAuctionInNotWinner::dispatch($auction)->delay(Carbon::now("Europe/Moscow")->addSeconds(5));
        return $auction;
    }

    /**
     * @param Auction $auction
     * @return Auction
     */
    private function active(Auction $auction)
    {
        if ($auction->bid()->doesntExist() && $bot = $auction->botNum(1)) {
            dispatch(new BotBidJob($bot))->delay(Carbon::now("Europe/Moscow")->addSeconds($auction->step_time() - 1));
        }
        return $auction;
    }
}
