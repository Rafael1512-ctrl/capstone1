<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketCategoryController extends Controller
{
    /**
     * Show all ticket categories for an event
     */
    public function index(Event $event)
    {
        $categories = $event->ticketCategories()->get();
        return view('ticket-categories.index', compact('event', 'categories'));
    }

    /**
     * Show create ticket category form
     */
    public function create(Event $event)
    {
        $this->authorize('update', $event);
        return view('ticket-categories.create', compact('event'));
    }

    /**
     * Store new ticket category
     */
    public function store(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'total_tickets' => ['required', 'integer', 'min:1'],
        ]);

        $validated['event_id'] = $event->id;
        $validated['available_tickets'] = $validated['total_tickets'];
        $validated['status'] = 'active';

        TicketCategory::create($validated);

        return redirect()->route('events.show', $event)->with('success', 'Ticket category added successfully!');
    }

    /**
     * Show edit form
     */
    public function edit(Event $event, TicketCategory $category)
    {
        $this->authorize('update', $event);
        return view('ticket-categories.edit', compact('event', 'category'));
    }

    /**
     * Update category
     */
    public function update(Request $request, Event $event, TicketCategory $category)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'total_tickets' => ['required', 'integer', 'min:' . $category->sold_tickets],
        ]);

        // If total_tickets increased, add to available_tickets
        if ($validated['total_tickets'] > $category->total_tickets) {
            $difference = $validated['total_tickets'] - $category->total_tickets;
            $validated['available_tickets'] = $category->available_tickets + $difference;
        }

        $category->update($validated);

        return redirect()->route('events.show', $event)->with('success', 'Ticket category updated successfully!');
    }

    /**
     * Delete ticket category
     */
    public function destroy(Event $event, TicketCategory $category)
    {
        $this->authorize('update', $event);

        if ($category->sold_tickets > 0) {
            return back()->with('error', 'Cannot delete category with sold tickets.');
        }

        $category->delete();

        return back()->with('success', 'Ticket category deleted successfully!');
    }
}
