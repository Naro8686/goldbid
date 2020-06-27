<?php

namespace App\Mail;

use App\Models\User;
use App\Settings\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CodeConfirmSendMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $code;
    public $subject = 'Подтверждение электронной почты';

    public function __construct(User $user)
    {
        $user->update(['email_code'=>Setting::emailRandomCode()]);
        $this->code =  $user->email_code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.send_code')
            ->subject($this->subject)
            ->with('code', $this->code);
    }
}
