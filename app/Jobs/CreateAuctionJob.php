<?php

namespace App\Jobs;

use App\Models\Auction\Auction;
use App\Models\Auction\Product;
use App\Models\Bots\Bot;
use App\Models\Bots\BotName;
use App\Models\Pages\Page;
use App\Settings\ImageTrait;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Log;

class  CreateAuctionJob implements ShouldQueue
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
        try {
            $auction = $this->product->auction();
            $path = 'site/img/auction';
            if (!is_dir(public_path($path))) mkdir(public_path($path), 0777, true);
            $img_1 = $this->imageCopy($this->product->img_1, 'site/img/product', $path);
            $img_2 = $this->imageCopy($this->product->img_2, 'site/img/product', $path);
            $img_3 = $this->imageCopy($this->product->img_3, 'site/img/product', $path);
            $img_4 = $this->imageCopy($this->product->img_4, 'site/img/product', $path);

            list($min_price, $max_price) = array_pad(str_replace(' ', '', explode('-', $this->product->bot_shutdown_price)), 2, null);
            $bot_shutdown_price = rand($min_price, $max_price);
            list($min_count, $max_count) = array_pad(str_replace(' ', '', explode('-', $this->product->bot_shutdown_count)), 2, null);
            $bot_shutdown_count = rand($min_count, $max_count);
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
                'bot_shutdown_price' => $bot_shutdown_price,
                'bot_shutdown_count' => $bot_shutdown_count,
                'bid_seconds' => $this->product->step_time,
                'top' => $this->product->top,
                'step_price' => $this->product->step_price,
                'start' => Carbon::now("Europe/Moscow")->addMinutes((int)$this->product->to_start),
                'exchange' => (bool)$this->product->exchange,
                'buy_now' => (bool)$this->product->buy_now,
                'status' => ((int)$this->product->to_start === 0) ? Auction::STATUS_ACTIVE : Auction::STATUS_PENDING,
            ];
            /** @var Auction $new */
            $new = $auction->create($data);
            Page::query()->where('slug', $new->id)->firstOrCreate([
                'slug' => $new->id,
                'title' => $new->title,
            ]);
            $this->createBots($new);
        } catch (Exception $exception) {
            Log::error('CreateAuctionJob ' . $exception->getMessage());
        }
    }

    private function createBots(Auction $new)
    {
        $bots = Bot::query()->where('is_active', true)->get();
        foreach ($bots as $bot) {
            $names = [];
            foreach ($new->bots()->get(['name']) as $name) $names[] = $name->name;
            $random = BotName::query()->whereNotIn('name', $names)->inRandomOrder()->first();
            if (!is_null($bot->change_name)) {
                list($min, $max) = array_pad(str_replace(' ', '', explode('-', $bot->change_name)), 2, null);
                $bot->change_name = rand($min, $max);
            }
            if (!is_null($bot->num_moves)) {
                list($min, $max) = array_pad(str_replace(' ', '', explode('-', $bot->num_moves)), 2, null);
                $bot->num_moves = rand($min, $max);
            }
            if (!is_null($bot->num_moves_other_bot)) {
                list($min, $max) = array_pad(str_replace(' ', '', explode('-', $bot->num_moves_other_bot)), 2, null);
                $bot->num_moves_other_bot = rand($min, $max);
            }
            if (!is_null($random)){
                $new->bots()->create([
                    'bot_id' => $bot->id,
                    'name' => $random->name,
                    'time_to_bet' => $bot->time_to_bet,
                    'change_name' => $bot->change_name,
                    'num_moves' => $bot->num_moves,
                    'num_moves_other_bot' => $bot->num_moves_other_bot,
                ]);
            }
        }
    }
}
