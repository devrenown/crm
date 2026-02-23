<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\BirthdayWishMail;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Scopes\TenantScope;
use Illuminate\Support\Facades\DB;

class SendBirthdayEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-birthday-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send birthday emails to users whose birthday is today';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        $users = DB::table('users')
        ->join('employee_details', 'employee_details.user_id', '=', 'users.id')
        ->whereNull('users.birthday_mail_sent_at')
        ->whereNotNull('users.email')
        ->whereMonth('employee_details.dob', $today->month)
        ->whereDay('employee_details.dob', $today->day)
        ->select(
            'users.id',
            'users.firstname',
            'users.lastname',
            'users.email',
            'users.avatar',
            'employee_details.user_id',
            'employee_details.dob'
        )
        ->get();

        if ($users->isEmpty()) {
            $this->info('No birthdays today.');
            return Command::SUCCESS;
        }

        foreach ($users as $user) {
            try {

               $send = Mail::to($user->email)->send(new BirthdayWishMail($user));
               $this->info($send);

                DB::table('users')
                ->where('id', $user->id)
                ->update(['birthday_mail_sent_at' => now()]);
            }catch (\Throwable $e) {

                $this->error('Failed for user ID ' . $user->id . ': ' . $e->getMessage());
            }
        }

        $this->info('Birthday emails sent successfully: ' . $users->count());
        return Command::SUCCESS;
    }
}
