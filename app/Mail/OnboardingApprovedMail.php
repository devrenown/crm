<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use App\Models\User;

class OnboardingApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $loginUrl;
    public $company;

    public function __construct(User $user)
    {
        $this->user     = $user;
        $this->loginUrl = route('login');
        $this->company  = app(\App\Settings\CompanySettings::class);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Onboarding Has Been Approved!',
            from: new Address(env('MAIL_FROM_ADDRESS'), $this->company->name ?? 'Company Name'),
            replyTo: [
                new Address(
                    $this->company->email ?? 'company@gmail.com',
                    $this->company->name ?? 'Company Name'
                )
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.approved',
            with: [
                'user'          => $this->user,
                'loginUrl'      => $this->loginUrl,
                'supportEmail'  =>  $this->company->email ?? 'support@company.com'
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
