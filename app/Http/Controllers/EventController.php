<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display all events
     */
    public function index()
    {
        $events = Event::published()->upcoming()->paginate(15);
        return view('events.index', compact('events'));
    }

    /**
     * Show event detail
     */
    public function show(Event $event)
    {
        $event->load('organizer', 'ticketCategories');
        $event->tickets_sold = $event->getTotalSoldAttribute();
        $event->total_revenue = $event->getTotalRevenueAttribute();
        $event->available_tickets = $event->getAvailableTicketsAttribute();

        return view('events.show', compact('event'));
    }

    /**
     * Show create event form (Organizer only)
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store new event (Organizer)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'brief_description' => ['nullable', 'string', 'max:500'],
            'location' => ['required', 'string'],
            'venue_name' => ['nullable', 'string'],
            'event_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
            'total_capacity' => ['required', 'integer', 'min:1'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $validated['organizer_id'] = Auth::id();
        $validated['status'] = 'draft';

        $event = Event::create($validated);

        return redirect()->route('events.show', $event)->with('success', 'Event created successfully! Add ticket categories next.');
    }

    /**
     * Show edit form
     */
    public function edit(Event $event)
    {
        $this->authorize('update', $event);
        return view('events.edit', compact('event'));
    }

    /**
     * Update event
     */
    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'brief_description' => ['nullable', 'string', 'max:500'],
            'location' => ['required', 'string'],
            'venue_name' => ['nullable', 'string'],
            'event_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
            'total_capacity' => ['required', 'integer', 'min:1'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:draft,published,ongoing,completed,cancelled'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($validated);

        return redirect()->route('events.show', $event)->with('success', 'Event updated successfully!');
    }

    /**
     * Delete event
     */
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        
        if ($event->image && Storage::disk('public')->exists($event->image)) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->route('dashboard')->with('success', 'Event deleted successfully!');
    }

    /**
     * Publish event
     */
    public function publish(Event $event)
    {
        $this->authorize('update', $event);

        if ($event->ticketCategories()->count() === 0) {
            return back()->with('error', 'You must add ticket categories before publishing the event.');
        }

        $event->update(['status' => 'published']);

        return back()->with('success', 'Event published successfully!');
    }
}
