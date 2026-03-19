<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Listing semua event yang published
     */
    public function index()
    {
        $events = Event::where('status', 'published')
            ->with('ticketTypes')
            ->orderBy('date')
            ->paginate(15);

        return view('events.index', compact('events'));
    }

    /**
     * Detail event
     */
    public function show(Event $event)
    {
        $event->load(['organizer', 'ticketTypes']);

        return view('events.show', compact('event'));
    }

    /**
     * Form buat event baru (Organizer)
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Simpan event baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'date'        => ['required', 'date', 'after:now'],
            'location'    => ['required', 'string'],
            'banner'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $data = $request->except('banner');
        $data['organizer_id'] = Auth::id();
        $data['status'] = 'draft';

        if ($request->hasFile('banner')) {
            $data['banner_url'] = $request->file('banner')->store('events', 'public');
        }

        $event = Event::create($data);

        return redirect()->route('events.show', $event)
            ->with('success', 'Event berhasil dibuat! Tambahkan tipe tiket sekarang.');
    }

    /**
     * Form edit event
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
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'date'        => ['required', 'date'],
            'location'    => ['required', 'string'],
            'status'      => ['required', 'in:draft,published,cancelled'],
            'banner'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $data = $request->except('banner');

        if ($request->hasFile('banner')) {
            if ($event->banner_url) {
                Storage::disk('public')->delete($event->banner_url);
            }
            $data['banner_url'] = $request->file('banner')->store('events', 'public');
        }

        $event->update($data);

        return redirect()->route('events.show', $event)
            ->with('success', 'Event berhasil diupdate!');
    }

    /**
     * Hapus event
     */
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        if ($event->banner_url && Storage::disk('public')->exists($event->banner_url)) {
            Storage::disk('public')->delete($event->banner_url);
        }

        $event->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Event berhasil dihapus!');
    }

    /**
     * Publish event
     */
    public function publish(Event $event)
    {
        $this->authorize('update', $event);

        if ($event->ticketTypes()->count() === 0) {
            return back()->with('error', 'Tambahkan tipe tiket terlebih dahulu sebelum mempublish event.');
        }

        $event->update(['status' => 'published']);

        return back()->with('success', 'Event berhasil dipublish!');
    }
}
