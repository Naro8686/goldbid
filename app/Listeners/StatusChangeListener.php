<?php

namespace App\Listeners;

use App\Events\StatusChangeEvent;
use App\Jobs\BotBidJob;
use App\Jobs\CreateAuctionJob;
use App\Jobs\DeleteAuctionInNotWinner;
use App\Mail\MailingSendMail;
use App\Models\Auction\Auction;
use App\Models\Bots\AuctionBot;
use App\Models\Mailing;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Log;

class StatusChangeListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param StatusChangeEvent $event
     * @return StatusChangeEvent
     */
    public function handle(StatusChangeEvent $event)
    {
        if (!empty($event->data) && $event->data['status_change'] && isset($event->data['auction_id'])) {
            try {
                $auction = Auction::where('id', '=', $event->data['auction_id'])->first();
                if (!is_null($auction)) {
                    switch ($auction->status) {
                        case Auction::STATUS_ACTIVE:
                            $this->active($auction);
                            break;
                        case Auction::STATUS_FINISHED:
                            $this->finish($auction);
                            break;

                    }
                }
            } catch (Exception $e) {
                Log::error('status_change_active ' . $e->getMessage());
            }
        }
        return $event;
    }

    /**
     * @param Auction $auction
     */
    private function finish(Auction $auction)
    {
        CreateAuctionJob::dispatchIf((isset($auction->product) && $auction->product->visibly), $auction->product);
        if ($bid = $auction->bid->last()) {
            $bid->win = true;
            $bid->save(['timestamp' => false]);
            if (!$bid->is_bot && isset($bid->user_id) && $user = User::query()->find($bid->user_id)) {
                if ($user->email) {
                    try {
                        Mail::to($user->email)->queue(new MailingSendMail(Mailing::VICTORY, ['auction' => $bid->auction_id]));
                    } catch (Exception $e) {
                        Log::error('auction finish send mail to winner ' . $e->getMessage());
                    }
                }
            }
        } else DeleteAuctionInNotWinner::dispatchIf(isset($auction), $auction);
    }

    private function active(Auction $auction)
    {
        try {
            /** @var AuctionBot $bot */
            $delay = Carbon::now()->addSeconds($auction->step_time() - 1);
            $bot = $auction->botNum(1);
            if (!is_null($bot)) {
                dispatch(new BotBidJob($bot, true))->delay($delay);
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

    }
}
