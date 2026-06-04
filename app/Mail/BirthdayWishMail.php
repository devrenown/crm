<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BirthdayWishMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $companyName;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $companyName)
    {
        $this->user = $user;
        $this->companyName = $companyName;
    }

    /**
     * Mail Subject
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Happy Birthday '.$this->user->firstname.'!'
        );
    }

    /**
     * Mail Content
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.birthday',
            with: [
                'user'        => $this->user,
                'companyName' => $this->companyName,
            ]
        );
    }

    /**
     * Attachments
     */
    public function attachments(): array
    {
        return [];
    }
}