<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index(Request $request)
    {
        $categories = \Illuminate\Support\Facades\DB::table('kategori_acara')->get();
        
        $query = Event::orderBy('schedule_time', 'desc');

        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Ambil semua untuk "View All", tapi default landing tampilkan 8/serapah
        $events = $query->get();

        // Ambil tiket milik user (jika login)
        $myTickets = auth()->check() ? \App\Models\Ticket::whereHas('order', function ($query) {
            $query->where('user_id', auth()->id());
        })->with(['ticketType.event', 'order'])->get() : collect();

        return view('user', compact('events', 'myTickets', 'categories'));
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
