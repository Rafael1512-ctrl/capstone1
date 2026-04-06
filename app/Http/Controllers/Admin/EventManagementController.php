<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class EventManagementController extends Controller
{
    /**
     * Display a listing of all events
     */
    public function index(Request $request)
    {
        // Auto-update overdue events
        Event::updateOverdueEvents();

        $query = Event::with(['organizer']);

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            if ($request->status == 'active') {
                $query->whereIn('status', ['published', 'Active'])
                      ->where(function($q) {
                          $q->where('batch1_start_at', '<=', now())
                            ->orWhereNull('batch1_start_at');
                      });
            } elseif ($request->status == 'scheduled') {
                $query->whereIn('status', ['published', 'Active'])
                      ->whereNotNull('batch1_start_at')
                      ->where('batch1_start_at', '>', now());
            } elseif ($request->status == 'overdue') {
                $query->where('status', 'overdue');
            } elseif ($request->status == 'non-active') {
                $query->whereIn('status', ['draft', 'cancelled', 'Non-Active']);
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
            'location' => 'required|string|max:500',
            'maps_url' => 'nullable|string|max:1000',
            'ticket_quota' => 'nullable|integer|min:1',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,cancelled',
            
            'batch1_start_at'   => ['required', 'date'],
            'batch1_regular_quota' => ['required', 'integer', 'min:0'],
            'batch1_regular_price' => ['required', 'integer', 'min:0'],
            'batch1_vip_quota'    => ['required', 'integer', 'min:0'],
            'batch1_vip_price'    => ['required', 'integer', 'min:0'],
            'batch1_vvip_quota'   => ['required', 'integer', 'min:0'],
            'batch1_vvip_price'   => ['required', 'integer', 'min:0'],

            'batch2_start_at'   => ['required', 'date', 'after:batch1_start_at'],
            'batch2_regular_quota' => ['required', 'integer', 'min:0'],
            'batch2_regular_price' => ['required', 'integer', 'min:0'],
            'batch2_vip_quota'    => ['required', 'integer', 'min:0'],
            'batch2_vip_price'    => ['required', 'integer', 'min:0'],
            'batch2_vvip_quota'   => ['required', 'integer', 'min:0'],
            'batch2_vvip_price'   => ['required', 'integer', 'min:0'],
        ]);

        // Handle banner upload or external URL
        if ($request->hasFile('banner')) {
            $banner = $request->file('banner');
            $path = $banner->store('events/banners', 'public');
            $validated['banner_url'] = '/storage/' . $path;
        } elseif ($request->filled('banner_url_external')) {
            $validated['banner_url'] = $this->resolveImageUrl($request->banner_url_external);
        }

        // Handle performers for festival events
        $performers = [];
        if ($request->has('performers') && is_array($request->performers)) {
            foreach ($request->performers as $index => $performer) {
                if (isset($performer['name']) && isset($performer['role'])) {
                    $performerData = [
                        'name' => $performer['name'],
                        'role' => $performer['role'],
                        'description' => $performer['description'] ?? '',
                        'photo' => null,
                    ];

                    // Handle performer photo upload or external URL
                    if ($request->hasFile("performers.{$index}.photo")) {
                        $photo = $request->file("performers.{$index}.photo");
                        $path = $photo->store('events/performers', 'public');
                        $performerData['photo'] = '/storage/' . $path;
                    } elseif (!empty($performer['photo_external'])) {
                        $performerData['photo'] = $this->resolveImageUrl($performer['photo_external']);
                    }

                    $performers[] = $performerData;
                }
            }
        }
        if (!empty($performers)) {
            $validated['performers'] = $performers;
        }

        // Improved Google Maps Cleaner to ensure it works in iframes
        if (!empty($validated['maps_url'])) {
            $mapsUrl = $validated['maps_url'];

            // Extract src from iframe tag if pasted
            if (preg_match('/src="([^"]+)"/', $mapsUrl, $matches)) {
                $mapsUrl = $matches[1];
            }

            // If provided a standard link (like maps.app.goo.gl), convert to a functional embed via search
            if (!str_contains($mapsUrl, 'output=embed') && !str_contains($mapsUrl, '/embed/')) {
                $mapsUrl = "https://maps.google.com/maps?q=" . urlencode($validated['location']) . "&output=embed";
            }

            $validated['maps_url'] = $mapsUrl;
        } elseif (!empty($validated['location'])) {
            // If no URL provided but location exists, create a default embed
            $validated['maps_url'] = "https://maps.google.com/maps?q=" . urlencode($validated['location']) . "&output=embed";
        }

        // Calculate total ticket quota across all batches and categories
        $totalQuota = $validated['batch1_regular_quota'] + $validated['batch1_vip_quota'] + $validated['batch1_vvip_quota'] +
                      $validated['batch2_regular_quota'] + $validated['batch2_vip_quota'] + $validated['batch2_vvip_quota'];
        $validated['ticket_quota'] = $totalQuota;

        // Use a transaction for reliability and to ensure all-or-nothing completion
        DB::beginTransaction();
        try {
            // Generate Event ID via Stored Procedure
            try {
                DB::statement('CALL GenerateEventID(@new_id)');
                $newIdResult = DB::select('SELECT @new_id AS new_id');
                $eventId = $newIdResult[0]->new_id ?? ('EV-' . time());

                // Final safety check: if ID already exists, modify the suffix to avoid error
                // The column limit is exactly 14 characters (VARCHAR(14)). We cannot append.
                if (Event::where('event_id', $eventId)->exists()) {
                    // Original: TC-YYMMDD-XXXX (14 chars). Replace XXXX with random.
                    $eventId = substr($eventId, 0, 10) . strtoupper(Str::random(4));
                }
            } catch (\Exception $e) {
                // Fallback if SP fails completely
                $eventId = 'EV-' . time();
            }

            $validated['event_id'] = $eventId;

            // Create the event
            $event = Event::create($validated);

            // Create ticket types for both batches
            $categories = ['Regular', 'VIP', 'VVIP'];
            foreach ([1, 2] as $batch) {
                foreach ($categories as $cat) {
                    $keyBase = "batch{$batch}_" . strtolower($cat);
                    TicketType::create([
                        'event_id' => $event->event_id,
                        'name' => $cat,
                        'price' => $validated[$keyBase . '_price'],
                        'quantity_total' => $validated[$keyBase . '_quota'],
                        'quantity_sold' => 0,
                        'batch_number' => $batch
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.events.index')->with('success', 'Event berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal membuat event: ' . $e->getMessage());
        }
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
            'location' => 'required|string|max:500',
            'maps_url' => 'nullable|string|max:1000',
            'ticket_quota' => 'nullable|integer|min:1',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,cancelled',
            
            'batch1_start_at'   => ['required', 'date'],
            'batch1_regular_quota' => ['required', 'integer', 'min:0'],
            'batch1_regular_price' => ['required', 'integer', 'min:0'],
            'batch1_vip_quota'    => ['required', 'integer', 'min:0'],
            'batch1_vip_price'    => ['required', 'integer', 'min:0'],
            'batch1_vvip_quota'   => ['required', 'integer', 'min:0'],
            'batch1_vvip_price'   => ['required', 'integer', 'min:0'],

            'batch2_start_at'   => ['required', 'date', 'after:batch1_start_at'],
            'batch2_regular_quota' => ['required', 'integer', 'min:0'],
            'batch2_regular_price' => ['required', 'integer', 'min:0'],
            'batch2_vip_quota'    => ['required', 'integer', 'min:0'],
            'batch2_vip_price'    => ['required', 'integer', 'min:0'],
            'batch2_vvip_quota'   => ['required', 'integer', 'min:0'],
            'batch2_vvip_price'   => ['required', 'integer', 'min:0'],
        ]);

        // Handle banner upload or external URL
        if ($request->hasFile('banner')) {
            if ($event->banner_url && !filter_var($event->banner_url, FILTER_VALIDATE_URL)) {
                $oldPath = str_replace('/storage/', '', $event->banner_url);
                Storage::disk('public')->delete($oldPath);
            }

            $banner = $request->file('banner');
            $path = $banner->store('events/banners', 'public');
            $validated['banner_url'] = '/storage/' . $path;
        } elseif ($request->filled('banner_url_external')) {
            $validated['banner_url'] = $this->resolveImageUrl($request->banner_url_external);
        }

        // Handle performers for festival events
        $performers = [];
        if ($request->has('performers') && is_array($request->performers)) {
            foreach ($request->performers as $index => $performer) {
                if (isset($performer['name']) && isset($performer['role'])) {
                    $performerData = [
                        'name' => $performer['name'],
                        'role' => $performer['role'],
                        'description' => $performer['description'] ?? '',
                        'photo' => $performer['existing_photo'] ?? null,
                    ];

                    // Handle new performer photo upload or external URL
                    if ($request->hasFile("performers.{$index}.photo")) {
                        // Delete old local photo if exists
                        if (!empty($performerData['photo']) && !filter_var($performerData['photo'], FILTER_VALIDATE_URL)) {
                            $oldPath = str_replace('/storage/', '', $performerData['photo']);
                            Storage::disk('public')->delete($oldPath);
                        }

                        $photo = $request->file("performers.{$index}.photo");
                        $path = $photo->store('events/performers', 'public');
                        $performerData['photo'] = '/storage/' . $path;
                    } elseif (!empty($performer['photo_external'])) {
                        $performerData['photo'] = $this->resolveImageUrl($performer['photo_external']);
                    }

                    $performers[] = $performerData;
                }
            }
        }
        if (!empty($performers)) {
            $validated['performers'] = $performers;
        } else {
            $validated['performers'] = null;
        }

        // Improved Google Maps Cleaner
        if (!empty($validated['maps_url'])) {
            $mapsUrl = $validated['maps_url'];
            if (preg_match('/src="([^"]+)"/', $mapsUrl, $matches)) {
                $mapsUrl = $matches[1];
            }

            if (!str_contains($mapsUrl, 'output=embed') && !str_contains($mapsUrl, '/embed/')) {
                $mapsUrl = "https://maps.google.com/maps?q=" . urlencode($validated['location']) . "&output=embed";
            }
            $validated['maps_url'] = $mapsUrl;
        } elseif (!empty($validated['location'])) {
            $validated['maps_url'] = "https://maps.google.com/maps?q=" . urlencode($validated['location']) . "&output=embed";
        }

        // Calculate total ticket quota
        $totalQuota = $validated['batch1_regular_quota'] + $validated['batch1_vip_quota'] + $validated['batch1_vvip_quota'] +
                      $validated['batch2_regular_quota'] + $validated['batch2_vip_quota'] + $validated['batch2_vvip_quota'];
        $validated['ticket_quota'] = $totalQuota;

        $event->update($validated);

        // Update or create ticket types
        $categories = ['Regular', 'VIP', 'VVIP'];
        foreach ([1, 2] as $batch) {
            foreach ($categories as $cat) {
                $keyBase = "batch{$batch}_" . strtolower($cat);
                TicketType::updateOrCreate(
                    ['event_id' => $event->event_id, 'batch_number' => $batch, 'name' => $cat],
                    [
                        'price' => $validated[$keyBase . '_price'],
                        'quantity_total' => $validated[$keyBase . '_quota'],
                    ]
                );
            }
        }

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diupdate');
    }

    /**
     * Delete an event
     */
    public function destroy($event_id)
    {
        $event = Event::findOrFail($event_id);

        // Delete banner if exists
        if ($event->banner_url) {
            $path = str_replace('/storage/', '', $event->banner_url);
            Storage::disk('public')->delete($path);
        }

        // Delete performer photos if exist
        if ($event->performers && is_array($event->performers)) {
            foreach ($event->performers as $performer) {
                if (!empty($performer['photo'])) {
                    $path = str_replace('/storage/', '', $performer['photo']);
                    Storage::disk('public')->delete($path);
                }
            }
        }

        // Use transaction for reliability with foreign key check disabled temporarily
        DB::beginTransaction();
        try {
            // Disable foreign key checks to handle circular dependencies and complex relations
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            // Get all ticket type IDs belonging to this event
            $ticketTypeIds = TicketType::where('event_id', $event_id)->pluck('id');
            // Get all ticket IDs belonging to these types
            $ticketIds = DB::table('ticket')->whereIn('ticket_type_id', $ticketTypeIds)->pluck('ticket_id');

            // 1. Get all transaction IDs related to these tickets
            $transactionIdsFromTickets = DB::table('ticket')
                ->whereIn('ticket_type_id', $ticketTypeIds)
                ->pluck('transaction_id')
                ->filter()
                ->unique();

            $transactionIdsFromOrders = DB::table('transaksi')
                ->whereIn('ticket_id', $ticketIds)
                ->pluck('transaction_id')
                ->filter()
                ->unique();

            $allTransactionIds = $transactionIdsFromTickets->merge($transactionIdsFromOrders)->unique();

            // 2. Perform Exhaustive Dependency Cleanup (Checking table existence first)

            // Cleanup: Report (CRITICAL: Often causes FK failure)
            if (Schema::hasTable('report')) {
                DB::table('report')->where('event_id', $event_id)->delete();
            }

            // Cleanup: Waiting Lists
            if (Schema::hasTable('waiting_lists')) {
                DB::table('waiting_lists')->where('event_id', $event_id)->delete();
                if ($ticketTypeIds->isNotEmpty()) {
                    DB::table('waiting_lists')->whereIn('ticket_type_id', $ticketTypeIds)->delete();
                }
            } else if (Schema::hasTable('waiting_list')) { // Also check for singular name mapping
                DB::table('waiting_list')->where('event_id', $event_id)->delete();
            }

            // Cleanup: Ticket Reservations
            if (Schema::hasTable('ticket_reservations') && $ticketTypeIds->isNotEmpty()) {
                DB::table('ticket_reservations')->whereIn('ticket_type_id', $ticketTypeIds)->delete();
            }

            // Cleanup: Order Items
            if (Schema::hasTable('order_items') && $ticketTypeIds->isNotEmpty()) {
                DB::table('order_items')->whereIn('ticket_type_id', $ticketTypeIds)->delete();
            }

            // Cleanup: Payments
            if (Schema::hasTable('payments') && $allTransactionIds->isNotEmpty()) {
                DB::table('payments')->whereIn('order_id', $allTransactionIds)->delete();
            }

            // Cleanup: Transactions/Orders (transaksi)
            if ($allTransactionIds->isNotEmpty()) {
                DB::table('transaksi')->whereIn('transaction_id', $allTransactionIds)->delete();
            }

            // Fallback for transactions that might only be linked by old ticket_id field
            DB::table('transaksi')->whereIn('ticket_id', $ticketIds)->delete();

            // 3. Delete associated tickets
            DB::table('ticket')->whereIn('ticket_type_id', $ticketTypeIds)->orWhere('event_id', $event_id)->delete();

            // 4. Delete the ticket types themselves
            TicketType::where('event_id', $event_id)->delete();

            // 5. Delete the event record (acara)
            $event->delete();

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            DB::commit();
            return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus beserta semua data terkait.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Ensure foreign key checks are re-enabled even on failure
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            return redirect()->route('admin.events.index')->with('error', 'Gagal menghapus event: ' . $e->getMessage());
        }
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

    /**
     * Resolve image URL to direct link for common services like Google Drive
     */
    private function resolveImageUrl($url)
    {
        if (empty($url)) return null;

        // Handle Google Drive
        if (str_contains($url, 'drive.google.com')) {
            $fileId = null;
            // Pattern for /file/d/ID/view
            if (preg_match('/\/file\/d\/([^\/?]+)/', $url, $matches)) {
                $fileId = $matches[1];
            }
            // Pattern for id=ID
            elseif (preg_match('/id=([^\/&?]+)/', $url, $matches)) {
                $fileId = $matches[1];
            }

            if ($fileId) {
                // Using thumbnail link is much more reliable for embedding than uc?export=view
                // It bypasses some "large file" warnings and works better in CSS background-image
                return "https://drive.google.com/thumbnail?id=" . $fileId . "&sz=w1600";
            }
        }

        return $url;
    }

    /**
     * Terminate a ticket batch manually
     */
    public function endBatch(Request $request, $event_id, $batch)
    {
        $event = Event::findOrFail($event_id);
        $column = "batch{$batch}_ended_at";
        
        if (!in_array($batch, [1, 2])) {
            return back()->with('error', 'Batch tidak valid.');
        }

        $event->update([
            $column => now()
        ]);

        return back()->with('success', "Batch {$batch} berhasil diakhiri.");
    }
}
