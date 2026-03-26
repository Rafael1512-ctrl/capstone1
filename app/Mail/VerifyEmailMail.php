<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class VerifyEmailMail extends Mailable implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $user,
        public string $verificationToken
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [$this->user->email],
            subject: 'Verifikasi Email Anda - TIXLY',
        );
    }

    public function content(): Content
    {
        $verificationUrl = route('verify-email', [
            'token' => $this->verificationToken,
            'email' => $this->user->email,
        ]);

        return new Content(
            view: 'emails.verify-email',
            with: [
                'user' => $this->user,
                'verificationUrl' => $verificationUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
