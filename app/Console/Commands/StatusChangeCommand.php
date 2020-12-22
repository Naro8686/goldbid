<?php

namespace App\Console\Commands;

use Throwable;
use Carbon\Carbon;
use App\Jobs\StatusChangeJob;
use App\Models\Auction\Auction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
    protected $description = 'Changing the status of auctions';

    protected static $run = true;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        do {
            self::statusChange(Carbon::now("Europe/Moscow"));
            sleep(1);
        } while (self::$run);
    }

    /**
     * @param Carbon $current
     * @return mixed|void
     */
    public static function statusChange(Carbon $current)
    {
        try {
            //    DB::transaction(function () use ($current) {
            $active = DB::table('auctions')
                ->select(['id', 'bid_seconds'])
                ->where([
                    ['active', '=', true],
                    ['status', '=', Auction::STATUS_PENDING],
                    ['step_time', '=', null],
                    ['start', '<=', $current]
                ]);
            if ($active->exists()) self::active($active);

            $finished = DB::table('auctions')
                ->select(['id'])
                ->where([
                    ['active', '=', true],
                    ['status', '=', Auction::STATUS_ACTIVE],
                    ['step_time', '<>', null],
                    ['step_time', '<=', $current]
                ]);

            if ($finished->exists()) self::finished($finished);
            //  });

        } catch (Throwable $throwable) {
            Log::error('error statusChange ' . $throwable->getMessage());
        }
    }


    private static function active($auctions)
    {
        try {
            $ids = $auctions->get()->pluck('id');
            $updated = $auctions->update([
                'status' => Auction::STATUS_ACTIVE,
                'step_time' => DB::raw('NOW() + INTERVAL (`bid_seconds` + 1) SECOND')
            ]);
            if ($updated) foreach ($ids as $sec => $id) {
                StatusChangeJob::dispatch($id);
            }
        } catch (Throwable $e) {
            Log::error('status_change_pending ' . $e->getMessage());
        }
    }

    /**
     * @param $auctions
     * @throws Throwable
     */
    private static function finished($auctions)
    {
        try {
            //while (DB::transactionLevel() > 0) DB::rollBack();
            //DB::transaction(function () use ($auctions) {
            $ids = $auctions->get()->pluck('id');
            $updated = $auctions->update([
                'status' => Auction::STATUS_FINISHED,
                'end' => DB::raw('NOW() + INTERVAL 1 SECOND'),
                'top' => false
            ]);
            if ($updated) foreach ($ids as $sec => $id) {
                if ($auction = Auction::find($id, ['id'])) {
                    $winner = $auction->winner();
                    if (!is_null($winner->nickname)) {
                        $winner->win = true;
                        $winner->save(['timestamp' => false]);
                    }
                    StatusChangeJob::dispatch($id);
                }
            }
            //});
        } catch (Throwable $e) {
            Log::error('status_change_active_command ' . $e->getMessage());
        }
    }
}
