<?php

namespace App\Console;

use App\Actions\CancelStaleSubmissions;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
//        $schedule->call(function () {
//            app(CancelStaleSubmissions::class)->execute();
//        })->everyFiveMinutes();


        $schedule->command('backup:run')->daily();
        $schedule->command('backup:clean')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
