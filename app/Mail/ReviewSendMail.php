<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewSendMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    protected $data;
    public $subject = 'Отзывы';

    /**
     * Create a new message instance.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->data;
        $mail = $this->view('emails.review', compact('data'))
            ->subject($this->subject);
        if (isset($data['file'])) {
            $mail->attach($data['file']->getRealPath(),
                [
                    'as' => $data['file']->getClientOriginalName(),
                    'mime' => $data['file']->getClientMimeType(),
                ]);
        }
        return $mail;
    }
}
