<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

     protected $commands = [
        \App\Console\Commands\FacebookFollow::class,
        \App\Console\Commands\insSocialPointCalculation::class,
        \App\Console\Commands\youtubeSocialPointCalculation::class,
     

    ];
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('facebook_follow:run')->daily();
        $schedule->command('insSocialPointCalculation:run')->daily();
        $schedule->command('youtubeSocialPointCalculation:run')->daily();
      
       
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
