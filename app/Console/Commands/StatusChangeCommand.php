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
            $this->statusChange(Carbon::now("Europe/Moscow"));
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
            //    DB::transaction(function () use ($current) {
            $pending = DB::table('auctions')
                ->select(['id', 'bid_seconds'])
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
                ])->lockForUpdate();
            if ($active->exists()) $this->active($active);
            //  });

        } catch (Throwable $throwable) {
            Log::error('error statusChange ' . $throwable->getMessage());
        }
    }


    private function pending($auctions)
    {
        try {
            $ids = $auctions->get()->pluck('id');

            $auctions->update([
                'status' => Auction::STATUS_ACTIVE,
                'step_time' => DB::raw('NOW() + INTERVAL bid_seconds SECOND')
            ]);

            $ids->map(function ($id) {
                event(new StatusChangeEvent(['status_change' => true, 'auction_id' => $id]));
            });
        } catch (Throwable $e) {
            Log::error('status_change_pending ' . $e->getMessage());
        }
    }

    private function active($auctions)
    {
        try {
            $ids = $auctions->get()->pluck('id');
            DB::transaction(function () use ($auctions) {
                $auctions->update([
                    'status' => Auction::STATUS_FINISHED,
                    'end' => DB::raw('NOW()'),
                    'top' => false
                ]);
            });
            $ids->map(function ($id) {
                event((new StatusChangeEvent(['status_change' => true, 'auction_id' => $id])));
            });
        } catch (Throwable $e) {
            Log::error('status_change_active_command ' . $e->getMessage());
        }
    }
}
