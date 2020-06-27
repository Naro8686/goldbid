<?php

namespace App\Jobs;

use App\Mail\ReviewSendMail;
use App\Mail\SubscribeSendMail;
use App\Models\Mailing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class MailingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $mailing;
    protected $users;

    /**
     * Create a new job instance.
     *
     * @param Mailing $mailing
     */
    public function __construct(Mailing $mailing)
    {
        $this->mailing = $mailing;
        $this->users = $this->mailing->users()->get();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->users as $user) {
            if ($user->email)
                Mail::to($user->email)
                    ->send(new SubscribeSendMail($this->mailing));
        }

    }
}
