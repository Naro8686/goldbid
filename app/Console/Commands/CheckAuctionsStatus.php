<?php

namespace App\Console\Commands;

use App\Jobs\DeleteAuctionInNotWinner;
use App\Models\Auction\Auction;
use App\Models\Setting as ConfigSite;
use Illuminate\Console\Command;

class CheckAuctionsStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:auctions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $config = ConfigSite::query()->whereNotNull('storage_period_month')->first();
        $auctions = Auction::query()->whereNotNull('end')->get();
        foreach ($auctions as $key => $auction) {
            if ($auction->active && !(bool)$auction->end->addHours(72)->diff(now())->invert)
                $auction->update(['active' => false, 'timestamp' => false]);
            elseif (!$auction->active && !is_null($config)) {
                $delete = !(bool)$auction->end->addMonths((int)$config->storage_period_month)->diff(now())->invert;
                DeleteAuctionInNotWinner::dispatchIf($delete, $auction)->delay(now()->addSeconds($key * 2));
            }
        }
    }
}
