<?php

namespace App\Jobs;

use App\Events\StatusChangeEvent;
use App\Models\Auction\Auction;
use App\Models\Auction\Product;
use App\Models\Pages\Page;
use App\Settings\ImageTrait;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class CreateAuctionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ImageTrait;

    /**
     * @var Product
     */
    public $product;

    /**
     * Create a new job instance.
     *
     * @param Product $product
     */

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $auction = $this->product->auction();
        $path = 'site/img/auction';
        if (!is_dir($path)) mkdir($path, 0777, true);
        $img_1 = $this->imageCopy($this->product->img_1, 'site/img/product', $path);
        $img_2 = $this->imageCopy($this->product->img_2, 'site/img/product', $path);
        $img_3 = $this->imageCopy($this->product->img_3, 'site/img/product', $path);
        $img_4 = $this->imageCopy($this->product->img_4, 'site/img/product', $path);

        $data = [
            'title' => $this->product->title,
            'short_desc' => $this->product->short_desc,
            'desc' => $this->product->desc,
            'specify' => $this->product->specify,
            'terms' => $this->product->terms,
            'img_1' => $img_1,
            'img_2' => $img_2,
            'img_3' => $img_3,
            'img_4' => $img_4,
            'alt_1' => $this->product->alt_1,
            'alt_2' => $this->product->alt_2,
            'alt_3' => $this->product->alt_3,
            'alt_4' => $this->product->alt_4,
            'start_price' => $this->product->start_price,
            'full_price' => $this->product->full_price,
            'bot_shutdown_price' => $this->product->bot_shutdown_price,
            'bid_seconds' => $this->product->step_time,
            'step_price' => $this->product->step_price,
            'start' => Carbon::now()->addMinutes((int)$this->product->to_start),
            'exchange' => (bool)$this->product->exchange,
            'status' => ((int)$this->product->to_start === 0) ? Auction::STATUS_ACTIVE : Auction::STATUS_PENDING,
        ];

        $new = $auction->create($data);
        Page::query()->where('slug', $new->id)->firstOrCreate([
            'slug' => $new->id,
            'title' => $new->title,
        ]);
    }
}
