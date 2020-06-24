<?php

namespace App\Mail;

use App\Models\Mailing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscribeSendMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $mailing;

    /**
     * Create a new message instance.
     *
     * @param Mailing $mailing
     */
    public function __construct(Mailing $mailing)
    {
        $this->mailing = $mailing;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->mailing->subject;
        $text = $this->mailing->text;
        return $this->view('emails.mailing', compact('text'))
            ->subject($subject);
    }
}
