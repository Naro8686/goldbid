<?php

namespace App\Listeners;

use App\Events\StatusChangeEvent;
use App\Jobs\BotBidJob;
use App\Jobs\CreateAuctionJob;
use App\Jobs\DeleteAuctionInNotWinner;
use App\Mail\MailingSendMail;
use App\Models\Auction\Auction;
use App\Models\Balance;
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
        if (!empty($event->data) && isset($event->data['auction_id'])) {
            try {
                if ($auction = Auction::where('id', $event->data['auction_id'])->first()) {
                    switch ((int)$auction->status) {
                        case Auction::STATUS_ACTIVE:
                            $this->active($auction);
                            break;
                        case Auction::STATUS_FINISHED:
                            $this->finish($auction);
                            break;
                        default:
                            Log::info('ID:' . $auction->id . ' status:' . $auction->status);
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
        try {
            CreateAuctionJob::dispatchIf((isset($auction->product) && $auction->product->visibly), $auction->product);
            $this->fixAfterBid($auction);
            if ($auction->userFavorites->isNotEmpty()) $auction->userFavorites()->detach();
            if ($auction->bid->isNotEmpty()) {
                $bid = $auction->bid->last();
                $bid->win = true;
                $bid->save(['timestamp' => false]);
                if (!$bid->is_bot && isset($bid->user_id)) {
                    $user = User::query()->find($bid->user_id);
                    if ($auction->shutdownBots()) {
                        if (!is_null($user) && $user->email) {
                            try {
                                Mail::to($user->email)->queue(new MailingSendMail(Mailing::VICTORY, ['auction' => $bid->auction_id]));
                            } catch (Exception $e) {
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
                        event(new StatusChangeEvent(['status_change' => true, 'auction_id' => $auction->id]));
                        DeleteAuctionInNotWinner::dispatchIf(isset($auction), $auction)->delay(Carbon::now("Europe/Moscow")->addSeconds(5));
                    }
                }
            } else DeleteAuctionInNotWinner::dispatchIf(isset($auction), $auction);
        } catch (Exception $exception) {
            Log::error('StatusChangeListener finish ' . $exception->getMessage());
        }
    }

    private function active(Auction $auction)
    {
        try {
            /** @var AuctionBot $bot */
            $delay = Carbon::now("Europe/Moscow")->addSeconds($auction->step_time() - 1);
            $bot = $auction->botNum(1);
            if (!is_null($bot)) {
                dispatch(new BotBidJob($bot, true))->delay($delay);
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

    }

    private function fixAfterBid(Auction $auction)
    {
        try {
            $bids = $auction->bid()->where('bids.created_at', '>', $auction->end)->get();
            foreach ($bids as $item) {
                if (!is_null($item->user)) {
                    /** @var User $user */
                    $user = $item->user;
                    $user->balanceHistory()
                        ->create([
                            'type' => Balance::PLUS,
                            'bet' => $item->bet,
                            'bonus' => $item->bonus,
                        ]);
                    $item->delete();
                }
            }
        } catch (Exception $exception) {
            Log::error('fixAfterBid ' . $exception->getMessage());
        }
    }
}
