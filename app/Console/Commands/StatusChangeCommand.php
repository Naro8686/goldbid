<?php

namespace App\Console\Commands;

use App\Events\StatusChangeEvent;
use App\Models\Auction\Auction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class StatusChangeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status:change';

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
        while (true) {
            $this->statusChange(Carbon::now());
            sleep(1);
        }
    }

    /**
     * @param Carbon $current
     * @return mixed|void
     */
    public function statusChange(Carbon $current)
    {
        try {
            $pending = DB::table('auctions')
                ->select(['id'])
                ->where([
                    ['active', '=', true],
                    ['status', '=', Auction::STATUS_PENDING],
                    ['step_time', '=', null],
                    ['start', '<=', $current]
                ]);
            if ($pending->exists()) $this->pending($pending);

            $active = DB::table('auctions')
                ->select(['id'])
                ->where([
                    ['active', '=', true],
                    ['status', '=', Auction::STATUS_ACTIVE],
                    ['step_time', '<>', null],
                    ['step_time', '<=', $current]
                ]);
            if ($active->exists()) $this->active($active);
        } catch (Throwable $throwable) {
            Log::error('error statusChange ' . $throwable->getMessage());
        }
    }


    /**
     * @param $auction
     */
    private function pending($auction)
    {
        try {
            $ids = $auction->pluck('id');
            $update = $auction->update([
                'status' => Auction::STATUS_ACTIVE,
                'step_time' => DB::raw('NOW() + INTERVAL bid_seconds SECOND')
            ]);
            if ($update) {
                foreach ($ids as $id) {
                    event(new StatusChangeEvent(['status_change' => true, 'auction_id' => $id]));
                }
            }
        } catch (Throwable $e) {
            Log::error('status_change_pending ' . $e->getMessage());
        }
    }

    /**
     * @param $auction
     */
    private function active($auction)
    {
        try {
            $ids = $auction->pluck('id');
            $update = $auction->update([
                'status' => Auction::STATUS_FINISHED,
                'end' => DB::raw('NOW()'),
                'top' => false
            ]);
            if ($update) {
                foreach ($ids as $id) {
                    event(new StatusChangeEvent(['status_change' => true, 'auction_id' => $id]));
                }
            }
        } catch (Throwable $e) {
            Log::error('status_change_active_command ' . $e->getMessage());
        }
    }
}
