<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Menampilkan daftar event yang published
     */
    public function index()
    {
        $events = Event::where('status', 'published')
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
        $organizer = Auth::user();

        return view('events.create', compact('categories', 'organizer'));
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
            'location'          => ['required', 'string', 'max:255'],
            'ticket_quota'      => ['required', 'integer', 'min:1', 'max:999999'],
            'banner_url'        => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
        ]);

        $data = $validated;
        $data['organizer_id'] = Auth::user()->user_id;
        $data['status'] = 'draft';

        // Handle banner upload
        if ($request->hasFile('banner_url')) {
            $path = $request->file('banner_url')->store('events/' . date('Y/m'), 'public');
            $data['banner_url'] = '/storage/' . $path;
        } else {
            $data['banner_url'] = null;
        }

        // Generate ID menggunakan Stored Procedure
        try {
            DB::statement('CALL GenerateEventID(@new_id)');
            $result = DB::select('SELECT @new_id AS event_id');
            $data['event_id'] = $result[0]->event_id ?? null;
        } catch (\Exception $e) {
            // Fallback jika SP tidak ada
            $data['event_id'] = 'EV' . time() . rand(1000, 9999);
        }

        $event = Event::create($data);

        return redirect()->route('events.show', $event->event_id)
            ->with('success', 'Event berhasil dibuat! Silakan tambahkan tipe tiket.');
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

        return view('events.edit', compact('event', 'categories'));
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
            'location'          => ['required', 'string', 'max:255'],
            'ticket_quota'      => ['required', 'integer', 'min:1', 'max:999999'],
            'status'            => ['required', 'in:draft,published,cancelled'],
            'banner_url'        => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
        ]);

        $data = $validated;

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

        $event->update($data);

        return redirect()->route('events.show', $event->event_id)
            ->with('success', 'Event berhasil diperbarui.');
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
