<?php

namespace App\Console\Commands;

use App\Jobs\AutoBidJob;
use App\Models\Auction\Auction;
use App\Models\Auction\AutoBid;
use Illuminate\Console\Command;

class AutoBidCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autobid:start';

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
//        while (true) {
//            if ($this->stop()) {
//                $this->info('end');
//                break;
//            }
//            $this->start();
//            sleep(1);
//        }
    }

    public function stop()
    {
        return !Auction::query()->whereHas('autoBid')->where('status', Auction::STATUS_ACTIVE)->exists();
    }

    public function autoBid(){
        AutoBid::all()->groupBy('auction_id');
    }



    public function start()
    {
        $pending = null;
        $auctions = Auction::query()
            ->where('status', Auction::STATUS_ACTIVE)
            ->whereHas('autoBid')
            ->get();
        foreach ($auctions as $auction) {
            foreach ($auction->autoBid as $autoBid) {
                if ($pending = $autoBid->pivot->where('auto_bids.status', AutoBid::PENDING)->first()) break;
            }
        }
        if ($pending) {
            $auction = Auction::query()->find($pending->auction_id);
            $rand = rand(1, $auction->bid_seconds);
            $time = $auction->created_at->addSeconds($rand);
            if ($auction->winner()->created_at)
                $time = $auction->winner()->created_at->addSeconds($rand);
//            dispatch(new AutoBidJob([
//                "auction_id" => $pending->auction_id,
//                "user_id" => $pending->user_id,
//                "count" => $pending->count,
//                "status" => $pending->status
//            ]))->delay($time);
        }
    }
}
