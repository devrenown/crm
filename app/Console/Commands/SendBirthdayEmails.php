<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

use App\Mail\BirthdayWishMail;
use App\Models\Tenant;
use App\Services\TenantService;
use App\Settings\CompanySettings;

class SendBirthdayEmails extends Command
{
    protected $signature = 'app:send-birthday-emails';

    protected $description = 'Send birthday emails to active employees whose birthday is today';

    public function handle()
    {
        Tenant::chunk(50, function ($tenants) {

            foreach ($tenants as $tenant) {

                /*
                |--------------------------------------------------------------------------
                | Tenant Context
                |--------------------------------------------------------------------------
                */
                app()->instance('tenant', $tenant);

                $tz = TenantService::timezone($tenant->id);

                $now = now($tz);
                $todayDate = $now->toDateString();

                $this->info("Tenant {$tenant->id} → Birthday check for {$todayDate}");

                /*
                |--------------------------------------------------------------------------
                | Company Name
                |--------------------------------------------------------------------------
                */
                $companyName = app(CompanySettings::class)->name ?? 'Company';

                /*
                |--------------------------------------------------------------------------
                | Fetch Birthday Users
                |--------------------------------------------------------------------------
                */
                $users = DB::table('users')
                    ->join('employee_details', 'employee_details.user_id', '=', 'users.id')
                    ->where('users.tenant_id', $tenant->id)
                    ->where('users.is_active', 1)
                    ->where('users.is_onboarding_complete', 1)
                    ->whereNull('employee_details.date_exit')
                    ->whereNotNull('users.email')

                    ->whereMonth('employee_details.dob', $now->month)
                    ->whereDay('employee_details.dob', $now->day)

                    ->where(function ($q) use ($todayDate) {
                        $q->whereNull('users.birthday_mail_sent_at')
                          ->orWhereDate('users.birthday_mail_sent_at', '!=', $todayDate);
                    })

                    ->select(
                        'users.id',
                        'users.firstname',
                        'users.lastname',
                        'users.email',
                        'users.avatar',
                        'employee_details.dob'
                    )
                    ->get();

                /*
                |--------------------------------------------------------------------------
                | No Results
                |--------------------------------------------------------------------------
                */
                if ($users->isEmpty()) {

                    $this->info("Tenant {$tenant->id} → No birthdays today.");

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Send Emails
                |--------------------------------------------------------------------------
                */
                foreach ($users as $user) {

                    try {

                        Mail::to($user->email)
                            ->queue(
                                new BirthdayWishMail(
                                    $user,
                                    $companyName
                                )
                            );

                        /*
                        |--------------------------------------------------------------------------
                        | Mark Sent
                        |--------------------------------------------------------------------------
                        */
                        DB::table('users')
                            ->where('id', $user->id)
                            ->update([
                                'birthday_mail_sent_at' => $now,
                            ]);

                        $this->info("Sent → {$user->email}");

                    } catch (\Throwable $e) {

                        echo PHP_EOL;
                        echo "================ ERROR ================".PHP_EOL;
                        echo "User ID : ".$user->id.PHP_EOL;
                        echo "Message : ".$e->getMessage().PHP_EOL;
                        echo "File    : ".$e->getFile().PHP_EOL;
                        echo "Line    : ".$e->getLine().PHP_EOL;
                        echo "=======================================".PHP_EOL;
                    }
                }

                $this->info(
                    "Tenant {$tenant->id} → Sent {$users->count()} birthday emails"
                );
            }
        });

        return Command::SUCCESS;
    }
}