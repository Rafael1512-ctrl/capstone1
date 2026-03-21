<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('status', 'published')
            ->with('ticketTypes')
            ->orderBy('schedule_time')
            ->paginate(15);

        return view('events.index', compact('events'));
    }

    public function show($id)
    {
        $event = Event::with(['organizer', 'ticketTypes'])->findOrFail($id);
        return view('events.show', compact('event'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:300'],
            'schedule_time'=> ['required', 'date', 'after:now'],
            'location'    => ['required', 'string', 'max:100'],
            'ticket_quota'=> ['required', 'integer', 'min:1'],
            'banner'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $data = $request->except('banner');
        $data['organizer_id'] = Auth::user()->user_id;
        $data['status'] = 'draft';

        if ($request->hasFile('banner')) {
            $data['banner_url'] = $request->file('banner')->store('events', 'public');
        }

        // Generate ID using User's SP
        DB::statement('CALL GenerateEventID(@new_id)');
        $result = DB::select('SELECT @new_id AS new_id')[0];
        $data['event_id'] = $result->new_id;

        $event = Event::create($data);

        return redirect()->route('events.show', $event->event_id)
            ->with('success', 'Event berhasil dibuat! Tambahkan tipe tiket sekarang.');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        if ($event->organizer_id !== Auth::user()->user_id) abort(403);

        return view('events.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        if ($event->organizer_id !== Auth::user()->user_id) abort(403);

        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:300'],
            'schedule_time'=> ['required', 'date'],
            'location'    => ['required', 'string', 'max:100'],
            'ticket_quota'=> ['required', 'integer', 'min:1'],
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

        return redirect()->route('events.show', $event->event_id)
            ->with('success', 'Event berhasil diupdate!');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        if ($event->organizer_id !== Auth::user()->user_id) abort(403);

        if ($event->banner_url && Storage::disk('public')->exists($event->banner_url)) {
            Storage::disk('public')->delete($event->banner_url);
        }

        $event->delete();

        return back()->with('success', 'Event berhasil dihapus!');
    }

    public function publish($id)
    {
        $event = Event::findOrFail($id);
        if ($event->organizer_id !== Auth::user()->user_id) abort(403);

        if ($event->ticketTypes()->count() === 0) {
            return back()->with('error', 'Tambahkan tipe tiket terlebih dahulu sebelum mempublish event.');
        }

        $event->update(['status' => 'published']);

        return back()->with('success', 'Event berhasil dipublish!');
    }
}
