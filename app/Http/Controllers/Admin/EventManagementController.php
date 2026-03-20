<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class EventManagementController extends Controller
{
    /**
     * Display a listing of all events
     */
    public function index(Request $request)
    {
        $query = Event::with(['organizer', 'category', 'ticketTypes']);

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('location', 'like', '%' . $request->search . '%');
        }

        // Sort by date descending
        $events = $query->orderBy('date', 'desc')->paginate(15);
        $categories = EventCategory::where('is_active', true)->get();
        $statuses = ['draft', 'published', 'cancelled'];

        return view('admin.events.index', compact('events', 'categories', 'statuses'));
    }

    /**
     * Show the form for creating a new event
     */
    public function create()
    {
        $categories = EventCategory::where('is_active', true)->get();
        $organizers = User::where('role', 'organizer')->get();

        return view('admin.events.create', compact('categories', 'organizers'));
    }

    /**
     * Store a newly created event
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organizer_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:event_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after:today',
            'location' => 'required|string|max:255',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,cancelled',
        ]);

        // Handle banner upload
        if ($request->hasFile('banner')) {
            $banner = $request->file('banner');
            $path = $banner->store('events/banners', 'public');
            $validated['banner_url'] = '/storage/' . $path;
        }

        Event::create($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dibuat');
    }

    /**
     * Show the form for editing an event
     */
    public function edit(Event $event)
    {
        $event->load(['organizer', 'category', 'ticketTypes']);
        $categories = EventCategory::where('is_active', true)->get();
        $organizers = User::where('role', 'organizer')->get();

        return view('admin.events.edit', compact('event', 'categories', 'organizers'));
    }

    /**
     * Update an event
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'organizer_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:event_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,cancelled',
        ]);

        // Handle banner upload
        if ($request->hasFile('banner')) {
            // Delete old banner
            if ($event->banner_url) {
                $oldPath = str_replace('/storage/', '', $event->banner_url);
                Storage::disk('public')->delete($oldPath);
            }

            $banner = $request->file('banner');
            $path = $banner->store('events/banners', 'public');
            $validated['banner_url'] = '/storage/' . $path;
        }

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diupdate');
    }

    /**
     * Delete an event
     */
    public function destroy(Event $event)
    {
        // Delete banner
        if ($event->banner_url) {
            $path = str_replace('/storage/', '', $event->banner_url);
            Storage::disk('public')->delete($path);
        }

        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus');
    }

    /**
     * Show event detail
     */
    public function show(Event $event)
    {
        $event->load(['organizer', 'category', 'ticketTypes', 'orders']);

        // Calculate statistics
        $totalTicketsSold = $event->ticketTypes()->sum('quantity_sold');
        $totalTicketsAvailable = $event->ticketTypes()->sum('quantity_total');
        $totalRevenue = $event->orders()
            ->where('status', 'paid')
            ->sum('total_amount');

        // Revenue by ticket type
        $revenueByType = $event->ticketTypes()
            ->with(['orderItems'])
            ->get()
            ->map(function ($type) {
                return [
                    'name' => $type->name,
                    'sold' => $type->quantity_sold,
                    'available' => $type->availableStock(),
                    'revenue' => $type->orderItems->sum('subtotal'),
                ];
            });

        return view('admin.events.show', compact(
            'event',
            'totalTicketsSold',
            'totalTicketsAvailable',
            'totalRevenue',
            'revenueByType'
        ));
    }

    /**
     * Manage ticket types for an event
     */
    public function manageTickets(Event $event)
    {
        $event->load('ticketTypes');
        return view('admin.events.manage-tickets', compact('event'));
    }

    /**
     * Store ticket type
     */
    public function storeTicket(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity_total' => 'required|integer|min:1',
        ]);

        $event->ticketTypes()->create($validated);

        return back()->with('success', 'Ticket type berhasil ditambahkan');
    }

    /**
     * Update ticket type
     */
    public function updateTicket(Request $request, Event $event)
    {
        $validated = $request->validate([
            'ticket_id' => 'required|exists:ticket_types,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity_total' => 'required|integer|min:0',
        ]);

        $ticket = $event->ticketTypes()->find($validated['ticket_id']);
        $ticket->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'quantity_total' => $validated['quantity_total'],
        ]);

        return back()->with('success', 'Ticket type berhasil diupdate');
    }

    /**
     * Delete ticket type
     */
    public function deleteTicket(Event $event, $ticketId)
    {
        $ticket = $event->ticketTypes()->find($ticketId);
        if ($ticket) {
            $ticket->delete();
            return back()->with('success', 'Ticket type berhasil dihapus');
        }

        return back()->with('error', 'Ticket type tidak ditemukan');
    }
}
