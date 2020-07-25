<?php

namespace App\Mail;

use App\Models\Mailing;
use App\Models\User;
use App\Settings\Setting;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailingSendMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var Mailing $mailing
     */
    private $mailing;
    /**
     * @var User $user
     */
    private $user;
    /**
     * @var array
     */
    public $data;


    /**
     * MailingSendMail constructor.
     * @param User|null $user
     * @param array $data
     * @param int $type
     * @throws Exception
     */
    public function __construct(int $type, array $data = [], User $user = null)
    {
        $this->user = $user;
        $this->data = $data;
        $this->mailing = Mailing::no_ads($type)
            ->where('visibly', true)
            ->first();
        if (is_null($this->mailing)) throw new Exception();
        if ($type === Mailing::MAIL_CONFIRM && !is_null($user)) {
            $code = Setting::emailRandomCode();
            $this->data['email_code'] = $code;
            $this->data['nickname'] = $this->user->nickname;
            $this->user->update(['email_code' => $code]);
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->mailing->subject;
        $text = $this->mailing->textReplace($this->data);
        return $this->view('emails.mailing', compact('text'))
            ->subject($subject);
    }
}
