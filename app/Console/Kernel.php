<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\Module\ModuleMake::class,
        \App\Console\Commands\Module\ModuleDelete::class,
        \App\Console\Commands\Module\ModuleInstall::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('passport:purge')->withoutOverlapping();
        $schedule->command("payment:charge-recurring")->everyFourHours()->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . DIRECTORY_SEPARATOR . 'Commands');

        require base_path('routes/console.php');
    }
}
