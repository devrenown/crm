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
        \App\Console\Commands\AccrueMonthlyLeaves::class,
        \App\Console\Commands\CarryForwardLeaves::class,
        \App\Console\Commands\SendBirthdayEmails::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        /*
        |--------------------------------------------------------------------------
        | Monthly Leave Accrual
        |--------------------------------------------------------------------------
        | Runs every day at 00:10 server time
        | Tenant timezone logic handled inside command
        */
        $schedule->command('leaves:accrue-monthly')
            ->dailyAt('00:10')
            ->withoutOverlapping()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/leaves-accrual.log'));

        /*
        |--------------------------------------------------------------------------
        | Year-end Leave Carry Forward
        |--------------------------------------------------------------------------
        | Runs daily at 00:20
        | Actual year rollover is tenant-timezone based
        */
        $schedule->command('leaves:carry-forward')
            ->dailyAt('00:20')
            ->withoutOverlapping()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/leaves-carry-forward.log'));

        /*
        |--------------------------------------------------------------------------
        | Yearly birthday & anniversary reset
        |--------------------------------------------------------------------------
        | Runs daily, resets only when tenant hits Jan 1
        */
        $schedule->call(function () {
            DB::table('users')->update([
                'birthday_mail_sent_at'    => null,
                'anniversary_mail_sent_at' => null,
            ]);
        })->dailyAt('00:30');

        /*
        |--------------------------------------------------------------------------
        | Birthday notification
        |--------------------------------------------------------------------------
        */
        $schedule->command('app:send-birthday-emails')
            ->dailyAt('21:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
