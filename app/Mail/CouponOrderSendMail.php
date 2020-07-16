<?php

namespace App\Mail;

use App\Models\CouponOrder;
use App\Settings\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CouponOrderSendMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $subject = '';
    /**
     * @var CouponOrder
     */
    public $coupon_order;

    public function __construct(CouponOrder $order)
    {
        $this->coupon_order = $order;
        $this->subject = "Заказ: Пакет ставок {$order->coupon->bet}, N {$order->order}";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.coupon_order')
            ->subject($this->subject)
            ->with('coupon_order', $this->coupon_order);
    }
}
