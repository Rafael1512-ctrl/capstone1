<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        // Ambil 8 event terbaru untuk landing page (Urut berdasarkan waktu acara)
        $events = Event::orderBy('schedule_time', 'desc')->take(8)->get();

        // Ambil tiket milik user (jika login)
        $myTickets = auth()->check() ? \App\Models\Ticket::whereHas('order', function ($query) {
            $query->where('user_id', auth()->id());
        })->with(['ticketType.event', 'order'])->get() : collect();

        return view('user', compact('events', 'myTickets'));
    }

    public function showEvent(Event $event)
    {
        // Selalu gunakan template 'concert' tunggal
        return view('concert', compact('event'));
    }

    public function showTicket(Event $event)
    {
        // Load ticket types with the event
        $event->load('ticketTypes');
        return view('ticket', compact('event'));
    }

    public function showCheckout(Event $event, TicketType $ticketType)
    {
        // Pastikan ticketType milik event tersebut
        if ($ticketType->event_id !== $event->event_id) {
            abort(404);
        }

        return view('checkout', compact('event', 'ticketType'));
    }
}
