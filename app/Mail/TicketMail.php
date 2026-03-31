<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class TicketMail extends Mailable
{
    use Queueable;

    public function __construct(
        public User $user,
        public Order $order,
        public $tickets
    ) {}

    public function envelope(): Envelope
    {
        // Use order->event relationship we just added
        $eventTitle = $this->order->event->title ?? ($this->tickets->first()?->event->title ?? 'TIXLY');
        
        return new Envelope(
            to: [$this->user->email],
            subject: 'Tiket Konser Anda - ' . $eventTitle,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket',
            with: [
                'user' => $this->user,
                'order' => $this->order,
                'tickets' => $this->tickets,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
