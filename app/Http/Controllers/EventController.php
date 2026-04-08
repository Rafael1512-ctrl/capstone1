<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * Menampilkan daftar event yang published
     */
    public function index()
    {
        Event::updateOverdueEvents();
        
        $events = Event::active()
            ->with(['organizer', 'category', 'ticketTypes'])
            ->orderBy('schedule_time', 'desc')
            ->paginate(15);

        return view('events.index', compact('events'));
    }

    /**
     * Menampilkan detail event
     */
    public function show($id)
    {
        $event = Event::with(['organizer', 'category', 'ticketTypes', 'orders'])
            ->findOrFail($id);

        return view('events.show', compact('event'));
    }

    /**
     * Form membuat event baru
     */
    public function create()
    {
        $categories = EventCategory::where('is_active', true)->get();
        
        // If admin, they can choose any organizer. If organizer, they are the organizer.
        $organizers = null;
        if (Auth::user()->isAdmin()) {
            $organizers = \App\Models\User::where('role_id', 2)->get();
        }

        return view('events.create', compact('categories', 'organizers'));
    }

    /**
     * Menyimpan event baru ke database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'              => ['required', 'string', 'max:200'],
            'description'       => ['required', 'string', 'max:1000'],
            'category_id'       => ['nullable', 'exists:kategori_acara,category_id'],
            'schedule_time'     => ['required', 'date_format:Y-m-d\TH:i', 'after:now'],
            'location'          => ['required', 'string', 'max:500'],
            'maps_url'          => ['nullable', 'string', 'max:1000'],
            'ticket_quota'      => ['required', 'integer', 'min:1', 'max:999999'],
            'banner_url'        => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'organizer_id'      => ['nullable', 'exists:users,user_id'],
            
            'batch1_start_at'   => ['required', 'date_format:Y-m-d\TH:i'],
            'batch1_ended_at'   => ['nullable', 'date_format:Y-m-d\TH:i', 'after:batch1_start_at'],
            'batch1_regular_quota' => ['required', 'integer', 'min:0'],
            'batch1_regular_price' => ['required', 'numeric', 'min:0'],
            'batch1_vip_quota'    => ['required', 'integer', 'min:0'],
            'batch1_vip_price'    => ['required', 'numeric', 'min:0'],
            'batch1_vvip_price'   => ['required', 'numeric', 'min:0'],
            'batch1_regular_waiting_quota' => ['required', 'integer', 'min:0'],
            'batch1_vip_waiting_quota'    => ['required', 'integer', 'min:0'],
            'batch1_vvip_waiting_quota'   => ['required', 'integer', 'min:0'],
 
            'batch2_start_at'   => ['required', 'date_format:Y-m-d\TH:i', 'after:batch1_start_at'],
            'batch2_ended_at'   => ['nullable', 'date_format:Y-m-d\TH:i', 'after:batch2_start_at'],
            'batch2_regular_quota' => ['required', 'integer', 'min:0'],
            'batch2_regular_price' => ['required', 'numeric', 'min:0'],
            'batch2_vip_quota'    => ['required', 'integer', 'min:0'],
            'batch2_vip_price'    => ['required', 'numeric', 'min:0'],
            'batch2_vvip_quota'   => ['required', 'integer', 'min:0'],
            'batch2_vvip_price'   => ['required', 'numeric', 'min:0'],
        ]);

        $data = $validated;
        
        // Logic for organizer_id
        if (Auth::user()->isAdmin() && $request->filled('organizer_id')) {
            $data['organizer_id'] = $request->organizer_id;
        } else {
            $data['organizer_id'] = Auth::user()->user_id;
        }
        
        $data['status'] = 'Non-Active';

        // Handle banner upload
        if ($request->hasFile('banner_url')) {
            $path = $request->file('banner_url')->store('events/' . date('Y/m'), 'public');
            $data['banner_url'] = '/storage/' . $path;
        } else {
            $data['banner_url'] = null;
        }

        DB::beginTransaction();
        try {
            // Generate ID menggunakan Stored Procedure dengan fallback/safety
            try {
                DB::statement('CALL GenerateEventID(@new_id)');
                $result = DB::select('SELECT @new_id AS event_id');
                $data['event_id'] = $result[0]->event_id ?? ('EV-' . time());
                
                // Final safety check: if ID already exists, modify the suffix to avoid error
                // The column limit is exactly 14 characters (VARCHAR(14)). We cannot append.
                if (Event::where('event_id', $data['event_id'])->exists()) {
                    // Original: TC-YYMMDD-XXXX (14 chars). Replace XXXX with random.
                    $data['event_id'] = substr($data['event_id'], 0, 10) . strtoupper(Str::random(4));
                }
            } catch (\Exception $e) {
                // Fallback jika SP tidak ada atau gagal
                $data['event_id'] = 'EV-' . time();
            }

            $event = Event::create($data);

            // Sync Ticket Types for these batches
            $categories = ['Regular', 'VIP', 'VVIP'];
            
            foreach ($categories as $cat) {
                // Batch 1
                $event->ticketTypes()->create([
                    'name' => $cat,
                    'price' => $data['batch1_'.strtolower($cat).'_price'],
                    'quantity_total' => $data['batch1_'.strtolower($cat).'_quota'],
                    'quantity_sold' => 0,
                    'batch_number' => 1,
                    'waiting_list_quota' => $data['batch1_'.strtolower($cat).'_waiting_quota'] ?? 0
                ]);

                // Batch 2
                $event->ticketTypes()->create([
                    'name' => $cat,
                    'price' => $data['batch2_'.strtolower($cat).'_price'],
                    'quantity_total' => $data['batch2_'.strtolower($cat).'_quota'],
                    'quantity_sold' => 0,
                    'batch_number' => 2
                ]);
            }

            DB::commit();
            
            return redirect()->route('events.show', $event->event_id)
                ->with('success', 'Event berhasil dibuat! Silakan tambahkan tipe tiket.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal membuat event: ' . $e->getMessage());
        }
    }

    /**
     * Form edit event
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);

        // Otorisasi: hanya organizer atau admin yang bisa edit
        if ($event->organizer_id !== Auth::user()->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak berhak mengubah event ini.');
        }

        $categories = EventCategory::where('is_active', true)->get();
        
        // If admin, they can change the organizer
        $organizers = null;
        if (Auth::user()->isAdmin()) {
            $organizers = \App\Models\User::where('role_id', 2)->get();
        }

        return view('events.edit', compact('event', 'categories', 'organizers'));
    }

    /**
     * Update event di database
     */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        // Otorisasi
        if ($event->organizer_id !== Auth::user()->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak berhak mengubah event ini.');
        }

        $validated = $request->validate([
            'title'              => ['required', 'string', 'max:200'],
            'description'       => ['required', 'string', 'max:1000'],
            'category_id'       => ['nullable', 'exists:kategori_acara,category_id'],
            'schedule_time'     => ['required', 'date_format:Y-m-d\TH:i'],
            'location'          => ['required', 'string', 'max:500'],
            'maps_url'          => ['nullable', 'string', 'max:1000'],
            'ticket_quota'      => ['required', 'integer', 'min:1', 'max:999999'],
            'status'            => ['required', 'in:draft,published,cancelled,Non-Active,Active'],
            'banner_url'        => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'organizer_id'      => ['nullable', 'exists:users,user_id'],
            
            'batch1_start_at'   => ['required', 'date_format:Y-m-d\TH:i'],
            'batch1_ended_at'   => ['nullable', 'date_format:Y-m-d\TH:i', 'after:batch1_start_at'],
            'batch1_regular_quota' => ['required', 'integer', 'min:0'],
            'batch1_regular_price' => ['required', 'numeric', 'min:0'],
            'batch1_vip_quota'    => ['required', 'integer', 'min:0'],
            'batch1_vip_price'    => ['required', 'numeric', 'min:0'],
            'batch1_vvip_quota'   => ['required', 'integer', 'min:0'],
            'batch1_vvip_price'   => ['required', 'numeric', 'min:0'],
            'batch1_regular_waiting_quota' => ['required', 'integer', 'min:0'],
            'batch1_vip_waiting_quota'    => ['required', 'integer', 'min:0'],
            'batch1_vvip_waiting_quota'   => ['required', 'integer', 'min:0'],

            'batch2_start_at'   => ['required', 'date_format:Y-m-d\TH:i', 'after:batch1_start_at'],
            'batch2_ended_at'   => ['nullable', 'date_format:Y-m-d\TH:i', 'after:batch2_start_at'],
            'batch2_regular_quota' => ['required', 'integer', 'min:0'],
            'batch2_regular_price' => ['required', 'numeric', 'min:0'],
            'batch2_vip_quota'    => ['required', 'integer', 'min:0'],
            'batch2_vip_price'    => ['required', 'numeric', 'min:0'],
            'batch2_vvip_quota'   => ['required', 'integer', 'min:0'],
            'batch2_vvip_price'   => ['required', 'numeric', 'min:0'],
        ]);

        $data = $validated;

        // Only admins can change the organizer
        if (!Auth::user()->isAdmin() || !$request->filled('organizer_id')) {
            unset($data['organizer_id']);
        }

        // Handle banner upload
        if ($request->hasFile('banner_url')) {
            // Hapus banner lama jika ada
            if ($event->banner_url) {
                $oldPath = str_replace('/storage/', '', $event->banner_url);
                Storage::disk('public')->delete($oldPath);
            }
            // Upload banner baru
            $path = $request->file('banner_url')->store('events/' . date('Y/m'), 'public');
            $data['banner_url'] = '/storage/' . $path;
        } else {
            // Jika tidak ada file baru, pertahankan yang lama
            $data['banner_url'] = $event->banner_url;
        }

        // Auto-calculate total ticket quota from batches
        $data['ticket_quota'] = $data['batch1_regular_quota'] + $data['batch1_vip_quota'] + $data['batch1_vvip_quota'] +
                               $data['batch2_regular_quota'] + $data['batch2_vip_quota'] + $data['batch2_vvip_quota'];

        $event->update($data);

        // Sync Ticket Types for these batches
        $categories = ['Regular', 'VIP', 'VVIP'];
        
        foreach ($categories as $cat) {
            // Batch 1
            $event->ticketTypes()->updateOrCreate(
                ['batch_number' => 1, 'name' => $cat],
                [
                    'price' => $data['batch1_'.strtolower($cat).'_price'],
                    'quantity_total' => $data['batch1_'.strtolower($cat).'_quota'],
                    'waiting_list_quota' => $data['batch1_'.strtolower($cat).'_waiting_quota'] ?? 0,
                ]
            );

            // Batch 2
            $event->ticketTypes()->updateOrCreate(
                ['batch_number' => 2, 'name' => $cat],
                [
                    'price' => $data['batch2_'.strtolower($cat).'_price'],
                    'quantity_total' => $data['batch2_'.strtolower($cat).'_quota'],
                ]
            );
        }

        return redirect()->route('events.show', $event->event_id)
            ->with('success', 'Event berhasil diperbarui (Batch synced).');
    }

    /**
     * Hapus event dan file terkaitnya
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        // Otorisasi
        if ($event->organizer_id !== Auth::user()->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak berhak menghapus event ini.');
        }

        // Hapus banner jika ada
        if ($event->banner_url && Storage::disk('public')->exists($event->banner_url)) {
            Storage::disk('public')->delete($event->banner_url);
        }

        // Hapus relationships jika ada
        $event->ticketTypes()->delete();
        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event berhasil dihapus.');
    }

    /**
     * Publikasikan event dari draft ke published
     */
    public function publish($id)
    {
        $event = Event::findOrFail($id);

        // Otorisasi
        if ($event->organizer_id !== Auth::user()->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        // Validasi: harus ada minimal satu tipe tiket
        if ($event->ticketTypes()->count() === 0) {
            return back()->with('error', 'Tambahkan minimal satu tipe tiket sebelum publikasi event.');
        }

        $event->update(['status' => 'published']);

        return back()->with('success', 'Event berhasil dipublikasikan.');
    }

    /**
     * Batalkan event
     */
    public function cancel($id)
    {
        $event = Event::findOrFail($id);

        // Otorisasi
        if ($event->organizer_id !== Auth::user()->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $event->update(['status' => 'cancelled']);

        return back()->with('success', 'Event dibatalkan.');
    }

    /**
     * API: Get events untuk organizer dashboard
     */
    public function myEvents()
    {
        $events = Event::where('organizer_id', Auth::user()->user_id)
            ->with(['category', 'ticketTypes'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('events.my-events', compact('events'));
    }
}
