<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        // Register your console commands here
        \App\Console\Commands\DeletePastScheduledVacancies::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('app:delete-past-scheduled-vacancies')->daily(); // Adjust frequency as needed
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
