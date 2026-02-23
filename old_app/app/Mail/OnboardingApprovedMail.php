<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class OnboardingApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $loginUrl;
    public $supportEmail;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->loginUrl = route('login');
        $this->supportEmail = 'hr@renownpower.com';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 Your Onboarding Has Been Approved!',
            from: new \Illuminate\Mail\Mailables\Address(env('MAIL_FROM_ADDRESS') ?? 'aman.kumar@renownsystem.com',
            env('MAIL_FROM_NAME') ?? 'Renown System'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.approved',
            with: [
                'user' => $this->user,
                'loginUrl' => $this->loginUrl,
                'supportEmail' =>  $this->supportEmail
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
