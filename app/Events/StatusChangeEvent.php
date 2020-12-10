<?php

namespace App\Events;

use App\Models\Auction\Auction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class StatusChangeEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $auction;

    /**
     * Create a new event instance.
     *
     * @param $auction
     */
    public function __construct(Auction $auction)
    {
        $this->auction = $auction;
    }

    public function broadcastWith()
    {
        //return $this->auction->transformAuction(1)->toArray();
        //'desc', 'specify', 'terms'bet, bonus,price
        return $this->auction->statusChangeData()->except(['desc', 'specify', 'terms'])->toArray();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('status-change');
    }
}
