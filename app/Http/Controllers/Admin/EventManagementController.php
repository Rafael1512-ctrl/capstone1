<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class EventManagementController extends Controller
{
    /**
     * Display a listing of all events
     */
    public function index(Request $request)
    {
        $query = Event::with(['organizer']);

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter by status (active/non-active mapping)
        if ($request->has('status') && $request->status) {
            if ($request->status == 'active') {
                $query->where('status', 'published');
            } elseif ($request->status == 'non-active') {
                $query->whereIn('status', ['draft', 'cancelled']);
            }
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')  // Keep title search correct
                    ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        // Sort by date descending
        $events = $query->orderBy('schedule_time', 'desc')->paginate(15);
        $categories = DB::table('kategori_acara')->get();

        return view('admin.events.index', compact('events', 'categories'));
    }

    /**
     * Show the form for creating a new event
     */
    public function create()
    {
        $categories = DB::table('kategori_acara')->get();
        $organizers = User::where('role_id', 2)->get();

        return view('admin.events.create', compact('categories', 'organizers'));
    }

    /**
     * Store a newly created event
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organizer_id' => 'required|exists:users,user_id',
            'category_id' => 'required|exists:kategori_acara,category_id',
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'schedule_time' => 'required|date|after:today',
            'location' => 'required|string|max:150',
            'ticket_quota' => 'required|integer|min:1',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,cancelled',
        ]);

        // Handle banner upload
        if ($request->hasFile('banner')) {
            $banner = $request->file('banner');
            $path = $banner->store('events/banners', 'public');
            $validated['banner_url'] = '/storage/' . $path;
        }

        // Generate Event ID via SP
        DB::statement('CALL GenerateEventID(@new_id)');
        $newIdResult = DB::select('SELECT @new_id AS new_id');
        $validated['event_id'] = $newIdResult[0]->new_id;

        Event::create($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dibuat');
    }

    /**
     * Show the form for editing an event
     */
    public function edit($event_id)
    {
        $event = Event::findOrFail($event_id);
        $categories = DB::table('kategori_acara')->get();
        $organizers = User::where('role_id', 2)->get();

        return view('admin.events.edit', compact('event', 'categories', 'organizers'));
    }

    /**
     * Update an event
     */
    public function update(Request $request, $event_id)
    {
        $event = Event::findOrFail($event_id);

        $validated = $request->validate([
            'organizer_id' => 'required|exists:users,user_id',
            'category_id' => 'required|exists:kategori_acara,category_id',
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'schedule_time' => 'required|date',
            'location' => 'required|string|max:150',
            'ticket_quota' => 'required|integer|min:1',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,cancelled',
        ]);

        // Handle banner upload
        if ($request->hasFile('banner')) {
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
    public function destroy($event_id)
    {
        $event = Event::findOrFail($event_id);
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
    public function show($event_id)
    {
        $event = Event::with(['organizer', 'ticketTypes'])->findOrFail($event_id);

        $totalTicketsSold = DB::table('ticket_type')->where('event_id', $event_id)->sum('quantity_sold');
        $totalTicketsAvailable = DB::table('ticket_type')->where('event_id', $event_id)->sum('quantity_total');
        $totalRevenue = DB::table('transaksi')
            ->where('event_id', $event_id)
            ->where('payment_status', 'Verified')
            ->sum('total_amount');

        return view('admin.events.show', compact('event', 'totalTicketsSold', 'totalTicketsAvailable', 'totalRevenue'));
    }
}
