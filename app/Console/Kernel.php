<?php

namespace App\Console;

use App\Console\Commands\AutoBidCommand;
use App\Console\Commands\CheckAuctionsStatus;
use App\Console\Commands\StatusChangeCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        StatusChangeCommand::class,
        CheckAuctionsStatus::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('check:auctions')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
    protected function scheduleTimezone()
    {
        return "Europe/Moscow";
    }
}
