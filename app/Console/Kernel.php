<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;


class Kernel extends ConsoleKernel
{
    /**
     * Register Artisan commands.
     */
    protected $commands = [
        // Your custom commands
        \App\Console\Commands\AccrueMonthlyLeaves::class,
        \App\Console\Commands\CarryForwardLeaves::class,
        \App\Console\Commands\SendBirthdayEmails::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * NOTE: Scheduling is now handled in routes/console.php in Laravel 11.
     */
    protected function schedule(\Illuminate\Console\Scheduling\Schedule $schedule)
    {
        // Empty: all scheduling moved to routes/console.php
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        // Load console routes (schedules)
        require base_path('routes/console.php');
    }
}
