<?php

namespace App\Jobs;

use App\Models\Auction\Auction;
use App\Models\Pages\Page;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DeleteAuctionInNotWinner implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Auction
     */
    public $auction;

    /**
     * Create a new job instance.
     *
     * @param Auction $auction
     */
    public function __construct(Auction $auction)
    {
        $this->auction = $auction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            if (is_file(public_path($this->auction->img_1))) @unlink(public_path($this->auction->img_1));
            if (is_file(public_path($this->auction->img_2))) @unlink(public_path($this->auction->img_2));
            if (is_file(public_path($this->auction->img_3))) @unlink(public_path($this->auction->img_3));
            if (is_file(public_path($this->auction->img_4))) @unlink(public_path($this->auction->img_4));
            Page::query()->where('slug',$this->auction->id)->delete();
            $this->auction->delete();
        } catch (Exception $e) {
            Log::info('delete_auction_job'.$e->getMessage());
        }
    }
}
