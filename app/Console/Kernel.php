<?php

namespace App\Console;

use App\Console\Commands\Send;
use App\Console\Commands\Stock;
use App\Service\StockService;
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
        Stock::class,
        Send::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $arr = config("user");
            $arr_uid = array_column($arr, 'uid');
            foreach ($arr_uid as $key => $uid) {
                StockService::worm($uid);
            }
        })->everyMinute()->name('collect-stock')->withoutOverlapping();;
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
