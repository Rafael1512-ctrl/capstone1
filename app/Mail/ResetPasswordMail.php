<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ResetPasswordMail extends Mailable implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $user,
        public string $resetToken
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [$this->user->email],
            subject: 'Reset Password Anda - TIXLY',
        );
    }

    public function content(): Content
    {
        $resetUrl = route('show-reset-password', [
            'token' => $this->resetToken,
            'email' => $this->user->email,
        ]);

        return new Content(
            view: 'emails.reset-password',
            with: [
                'user' => $this->user,
                'resetUrl' => $resetUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
