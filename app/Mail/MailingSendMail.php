<?php

namespace App\Mail;

use App\Models\Mailing;
use App\Models\User;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailingSendMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var Mailing $mailing
     */
    private $mailing;
    private $user;


    /**
     * MailingSendMail constructor.
     * @param User $user
     * @param int $type
     * @throws Exception
     */
    public function __construct(User $user, int $type)
    {
        $this->mailing = Mailing::no_ads($type)
            ->where('visibly', true)
            ->first();
        if (is_null($this->mailing)) throw new Exception();
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->mailing->subject;
        $text = $this->mailing->textReplace($this->user);
        return $this->view('emails.mailing', compact('text'))
            ->subject($subject);
    }
}
