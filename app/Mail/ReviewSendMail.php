<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewSendMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $data;

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

        $mail = $this->view('emails.review')
            ->subject('Отзывы');
        if (isset($this->data['file'])) {
            $mail->attach($this->data['file']->getRealPath(),
                [
                    'as' => $this->data['file']->getClientOriginalName(),
                    'mime' => $this->data['file']->getClientMimeType(),
                ]);
        }
        return $mail;
    }
}
