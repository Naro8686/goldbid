<?php

namespace App\Mail;

use App\Models\Auction\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AuctionOrderSendMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $subject = '';
    public $auction_order;

    public function __construct(Order $order)
    {
        $this->auction_order = $order;
        $this->subject = "Заказ: {$this->auction_order->auction->title}, № {$this->auction_order->order_num}";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.auction_order')
            ->subject($this->subject)
            ->with('auction_order', $this->auction_order);
    }
}
