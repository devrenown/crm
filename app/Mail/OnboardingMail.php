<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use App\Models\User;

class OnboardingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $onboarding_url;
    public $company; 
    public $theme;

    public function __construct(User $user, $onboarding_url)
    {
        $this->user             = $user;
        $this->onboarding_url   = $onboarding_url; 
        $this->company          = app(\App\Settings\CompanySettings::class);
        $this->theme            = app(\App\Settings\ThemeSettings::class);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Onboarding Mail From ' . $this->company->name ?? 'Company Name',
            from: new Address('noreply@renownsystem.com', $this->company->name ?? 'Company Name'),
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
            view: 'mail.onboard',
            with: [
                'user'              => $this->user,
                'onboarding_url'    => $this->onboarding_url,
                'company'           => $this->company,
                'companyLogo'       => $this->theme->logo_dark ?? $this->theme->logo_light
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
