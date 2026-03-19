<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Listing events (API)
     */
    public function __invoke(Request $request)
    {
        $events = Event::with('ticketTypes')
            ->where('status', 'published')
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%");
            })
            ->when($request->date_from, function ($query, $date) {
                $query->whereDate('date', '>=', $date);
            })
            ->when($request->date_to, function ($query, $date) {
                $query->whereDate('date', '<=', $date);
            })
            ->orderBy('date')
            ->paginate(10);

        return response()->json($events);
    }

    /**
     * Detail event (API)
     */
    public function show($id)
    {
        $event = Event::with('ticketTypes')
            ->where('status', 'published')
            ->findOrFail($id);

        return response()->json($event);
    }
}