<?php

namespace App\Http\Controllers;

use App\Models\Event;
<<<<<<< HEAD
use App\Models\TicketType;
=======
>>>>>>> 2f5d83ff45da2a0b3e68ae99aade8a7880dd8a40
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
<<<<<<< HEAD
        // Ambil 8 event terbaru untuk landing page (Urut berdasarkan waktu acara)
        $events = Event::orderBy('schedule_time', 'desc')->take(8)->get();

        // Ambil tiket milik user (jika login)
        $myTickets = auth()->check() ? \App\Models\Ticket::whereHas('order', function ($query) {
            $query->where('user_id', auth()->id());
        })->with(['ticketType.event', 'order'])->get() : collect();

        return view('user', compact('events', 'myTickets'));
=======
        // Ambil event yang statusnya published
        $events = Event::where('status', 'published')->orderBy('date', 'asc')->get();
        return view('user', compact('events'));
>>>>>>> 2f5d83ff45da2a0b3e68ae99aade8a7880dd8a40
    }

    public function showEvent(Event $event)
    {
<<<<<<< HEAD
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
=======
        // Pastikan event published atau user adalah owner/admin
        if ($event->status !== 'published') {
            if (!auth()->check() || (!auth()->user()->isAdmin() && auth()->id() !== $event->organizer_id)) {
                abort(404);
            }
        }

        $template = $event->template_id ?: 1;
        
        // Cek apakah file blade template ada, jika tidak default ke concert1
        $viewName = "concert" . $template;
        if (!view()->exists($viewName)) {
            $viewName = "concert1";
        }

        return view($viewName, compact('event'));
>>>>>>> 2f5d83ff45da2a0b3e68ae99aade8a7880dd8a40
    }
}
