<?php

namespace App\Jobs;

use App\Events\TestEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AuctionHomepageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $auctions;

    /**
     * Create a new job instance.
     *
     * @param $auctions
     */
    public function __construct($auctions)
    {
        $this->auctions = $auctions;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        event(new TestEvent($this->auctions));
    }
}
