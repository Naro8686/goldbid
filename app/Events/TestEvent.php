<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TestEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $data;

    /**
     * Create a new event instance.
     *
     * @param $data
     */
    public function __construct(array $data = [])
    {

        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //return new Channel('auction.'.$this->auction["title"]);
        return new Channel('test-channel');
    }
//    public function broadcastWith()
//    {
//        return [
//            'title' => $this->data['title'],
//        ];
//    }
//    public function broadcastAs()
//    {
//        return 'test';
//    }
}
