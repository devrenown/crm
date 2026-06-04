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
| Runs hourly
| Command itself checks tenant local timezone/date
*/

Schedule::command('leaves:accrue-monthly')
    ->dailyAt('00:05')
    ->name('monthly-leave-accrual')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/leaves-accrual.log'));


/*
|--------------------------------------------------------------------------
| Yearly Leave Carry Forward
|--------------------------------------------------------------------------
| Runs hourly
| Command itself checks:
| - tenant local timezone
| - April 1st
*/

Schedule::command('leaves:carry-forward')
    ->dailyAt('00:05')
    ->name('year-end-leave-carry-forward')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/leaves-carry-forward.log'));

/*
|--------------------------------------------------------------------------
| Reset Birthday & Anniversary Flags
|--------------------------------------------------------------------------
| Runs hourly
| Callback checks tenant local April 1st
*/

Schedule::call(function () {

    $now = now();

    /*
    |--------------------------------------------------------------------------
    | Reset only once yearly on April 1st at 00:30 server time
    |--------------------------------------------------------------------------
    */

    if (
        $now->month === 4 &&
        $now->day === 1 &&
        $now->format('H:i') === '00:30'
    ) {

        DB::table('users')->update([
            'birthday_mail_sent_at'    => null,
            'anniversary_mail_sent_at' => null,
        ]);
    }

})
->hourly()
->name('birthday-anniversary-reset')
->withoutOverlapping();

/*
|--------------------------------------------------------------------------
| Birthday Emails
|--------------------------------------------------------------------------
| Runs hourly
| Command itself checks tenant local 09:00 AM
*/

Schedule::command('app:send-birthday-emails')
    ->hourly()
    ->name('send-birthday-emails')
    ->withoutOverlapping();

/*
|--------------------------------------------------------------------------
| Auto Clock-out Unsigned Attendances
|--------------------------------------------------------------------------
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
