<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Inspiring;
use App\Models\User;
use App\Models\Tenant;
use App\Jobs\AutoClockoutUnsignedAttendances;

/*
|--------------------------------------------------------------------------
| Default Inspire Command
|--------------------------------------------------------------------------
*/
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})
->purpose('Display an inspiring quote')
->hourly();

/*
|--------------------------------------------------------------------------
| Monthly Leave Accrual
|--------------------------------------------------------------------------
| Runs daily at 00:10 (server time)
| Tenant timezone logic handled inside command
*/
Schedule::command('leaves:accrue-monthly')
    ->dailyAt('00:10')
    ->name('monthly-leave-accrual') //  name for overlapping
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/leaves-accrual.log'));

/*
|--------------------------------------------------------------------------
| Year-end Leave Carry Forward
|--------------------------------------------------------------------------
| Runs daily at 00:20
*/
Schedule::command('leaves:carry-forward')
    ->dailyAt('00:20')
    ->name('year-end-leave-carry-forward') //  name for overlapping
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/leaves-carry-forward.log'));

/*
|--------------------------------------------------------------------------
| Yearly Birthday & Anniversary Reset
|--------------------------------------------------------------------------
| Runs daily at 00:30
*/
Schedule::call(function () {
    DB::table('users')->update([
        'birthday_mail_sent_at'    => null,
        'anniversary_mail_sent_at' => null,
    ]);
})
->dailyAt('00:30')
->name('birthday-anniversary-reset') //  name for overlapping
->withoutOverlapping();

/*
|--------------------------------------------------------------------------
| Birthday Notification Emails
|--------------------------------------------------------------------------
| Runs daily at 21:00
*/
Schedule::command('app:send-birthday-emails')
    ->dailyAt('21:00')
    ->name('send-birthday-emails') // name for overlapping
    ->withoutOverlapping();

/*
|--------------------------------------------------------------------------
| Auto Clock-out Unsigned Attendances
|--------------------------------------------------------------------------
| Runs every 5 minutes
| Heavy logic runs in queue (database connection)
*/

Schedule::call(function () {
    dispatch(new AutoClockoutUnsignedAttendances());
        
})
->everyFiveMinutes()
->name('auto-clockout-unsigned-attendances')
->withoutOverlapping();



// use App\Jobs\AutoClockoutUnsignedAttendances;
// use App\Models\User;
// use App\Models\Tenant;
// use Illuminate\Foundation\Inspiring;
// use Illuminate\Support\Facades\Artisan;
// use Illuminate\Support\Facades\Schedule;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();

// // Auto clock-out unsigned attendances
// // Schedule::call(function () {
// //     AutoClockoutUnsignedAttendances::dispatch();
// // })
// // ->everyFifteenMinutes()
// // ->name('auto-clockout-unsigned-attendances')
// // ->withoutOverlapping();

// // Inactive user cleanup
// Schedule::call(function () {

//     Tenant::chunk(50, function ($tenants) {

//         foreach ($tenants as $tenant) {

//             // Bind tenant context
//             app()->instance('tenant', $tenant);

//             User::where('is_online', true)
//                 ->where(
//                     'last_activity_at',
//                     '<',
//                     now()->subMinutes(config('session.lifetime'))
//                 )
//                 ->update(['is_online' => false]);
//         }
//     });

// })
// ->everyFifteenMinutes()
// ->name('inactive-user-cleanup')
// ->withoutOverlapping();
