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
            'ticket_quota' => 'nullable|integer|min:1',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,cancelled',
            'regular_quota' => 'required|integer|min:0',
            'regular_price' => 'required|integer|min:0',
            'vip_quota' => 'required|integer|min:0',
            'vip_price' => 'required|integer|min:0',
            'vvip_quota' => 'required|integer|min:0',
            'vvip_price' => 'required|integer|min:0',
        ]);

        // Handle banner upload
        if ($request->hasFile('banner')) {
            $banner = $request->file('banner');
            $path = $banner->store('events/banners', 'public');
            $validated['banner_url'] = '/storage/' . $path;
        }

        // Handle performers for festival events
        $performers = [];
        if ($request->has('performers') && is_array($request->performers)) {
            foreach ($request->performers as $performer) {
                if (isset($performer['name']) && isset($performer['role'])) {
                    $performerData = [
                        'name' => $performer['name'],
                        'role' => $performer['role'],
                        'description' => $performer['description'] ?? '',
                        'photo' => null,
                    ];
                    
                    // Handle performer photo upload if provided
                    // Note: File uploads via form array need special handling
                    $performers[] = $performerData;
                }
            }
        }
        if (!empty($performers)) {
            $validated['performers'] = $performers;
        }

        // Calculate total ticket quota
        $totalQuota = $validated['regular_quota'] + $validated['vip_quota'] + $validated['vvip_quota'];
        $validated['ticket_quota'] = $totalQuota;

        // Generate Event ID via SP
        DB::statement('CALL GenerateEventID(@new_id)');
        $newIdResult = DB::select('SELECT @new_id AS new_id');
        $validated['event_id'] = $newIdResult[0]->new_id;

        // Create the event
        $event = Event::create($validated);

        // Create ticket types
        if ($validated['regular_quota'] > 0) {
            TicketType::create([
                'event_id' => $event->event_id,
                'name' => 'Regular',
                'price' => $validated['regular_price'],
                'quantity_total' => $validated['regular_quota'],
                'quantity_sold' => 0,
            ]);
        }

        if ($validated['vip_quota'] > 0) {
            TicketType::create([
                'event_id' => $event->event_id,
                'name' => 'VIP',
                'price' => $validated['vip_price'],
                'quantity_total' => $validated['vip_quota'],
                'quantity_sold' => 0,
            ]);
        }

        if ($validated['vvip_quota'] > 0) {
            TicketType::create([
                'event_id' => $event->event_id,
                'name' => 'VVIP',
                'price' => $validated['vvip_price'],
                'quantity_total' => $validated['vvip_quota'],
                'quantity_sold' => 0,
            ]);
        }

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dibuat');
    }

    /**
     * Show the form for editing an event
     */
    public function edit($event_id)
    {
        $event = Event::with('ticketTypes')->findOrFail($event_id);
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
            'ticket_quota' => 'nullable|integer|min:1',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,cancelled',
            'regular_quota' => 'required|integer|min:0',
            'regular_price' => 'required|integer|min:0',
            'vip_quota' => 'required|integer|min:0',
            'vip_price' => 'required|integer|min:0',
            'vvip_quota' => 'required|integer|min:0',
            'vvip_price' => 'required|integer|min:0',
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

        // Handle performers for festival events
        $performers = [];
        if ($request->has('performers') && is_array($request->performers)) {
            foreach ($request->performers as $performer) {
                if (isset($performer['name']) && isset($performer['role'])) {
                    $performerData = [
                        'name' => $performer['name'],
                        'role' => $performer['role'],
                        'description' => $performer['description'] ?? '',
                        'photo' => $performer['photo'] ?? null,
                    ];
                    
                    $performers[] = $performerData;
                }
            }
        }
        if (!empty($performers)) {
            $validated['performers'] = $performers;
        } else {
            $validated['performers'] = null;
        }

        // Calculate total ticket quota
        $totalQuota = $validated['regular_quota'] + $validated['vip_quota'] + $validated['vvip_quota'];
        $validated['ticket_quota'] = $totalQuota;

        $event->update($validated);

        // Update or create ticket types
        // Regular Ticket
        $regularTicket = $event->ticketTypes->where('name', 'Regular')->first();
        if ($validated['regular_quota'] > 0) {
            if ($regularTicket) {
                $regularTicket->update([
                    'price' => $validated['regular_price'],
                    'quantity_total' => $validated['regular_quota'],
                ]);
            } else {
                TicketType::create([
                    'event_id' => $event->event_id,
                    'name' => 'Regular',
                    'price' => $validated['regular_price'],
                    'quantity_total' => $validated['regular_quota'],
                    'quantity_sold' => 0,
                ]);
            }
        } elseif ($regularTicket) {
            $regularTicket->delete();
        }

        // VIP Ticket
        $vipTicket = $event->ticketTypes->where('name', 'VIP')->first();
        if ($validated['vip_quota'] > 0) {
            if ($vipTicket) {
                $vipTicket->update([
                    'price' => $validated['vip_price'],
                    'quantity_total' => $validated['vip_quota'],
                ]);
            } else {
                TicketType::create([
                    'event_id' => $event->event_id,
                    'name' => 'VIP',
                    'price' => $validated['vip_price'],
                    'quantity_total' => $validated['vip_quota'],
                    'quantity_sold' => 0,
                ]);
            }
        } elseif ($vipTicket) {
            $vipTicket->delete();
        }

        // VVIP Ticket
        $vvipTicket = $event->ticketTypes->where('name', 'VVIP')->first();
        if ($validated['vvip_quota'] > 0) {
            if ($vvipTicket) {
                $vvipTicket->update([
                    'price' => $validated['vvip_price'],
                    'quantity_total' => $validated['vvip_quota'],
                ]);
            } else {
                TicketType::create([
                    'event_id' => $event->event_id,
                    'name' => 'VVIP',
                    'price' => $validated['vvip_price'],
                    'quantity_total' => $validated['vvip_quota'],
                    'quantity_sold' => 0,
                ]);
            }
        } elseif ($vvipTicket) {
            $vvipTicket->delete();
        }

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
            ->join('ticket_type', 'transaksi.ticket_id', '=', 'ticket_type.id')
            ->where('ticket_type.event_id', $event_id)
            ->where('transaksi.payment_status', 'Verified')
            ->sum('transaksi.total_amount');

        return view('admin.events.show', compact('event', 'totalTicketsSold', 'totalTicketsAvailable', 'totalRevenue'));
    }

    public function manageTickets()
    {
        // logika untuk menampilkan atau mengelola tiket
        return view('admin.tickets.index');
    }
}
