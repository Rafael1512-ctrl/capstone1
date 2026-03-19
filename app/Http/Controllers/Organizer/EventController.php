<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('organizer_id', Auth::id())->get();
        return response()->json($events);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'date' => 'required|date|after:now',
            'location' => 'required|string|max:255',
            'banner' => 'nullable|image|max:2048',
            'status' => 'sometimes|in:draft,published,cancelled'
        ]);

        $data = $request->except('banner');
        $data['organizer_id'] = Auth::id();

        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('events', 'public');
            $data['banner_url'] = $path;
        }

        $event = Event::create($data);

        return response()->json($event, 201);
    }

    public function show($id)
    {
        $event = Event::where('organizer_id', Auth::id())->findOrFail($id);
        return response()->json($event);
    }

    public function update(Request $request, $id)
    {
        $event = Event::where('organizer_id', Auth::id())->findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string|max:200',
            'description' => 'nullable|string',
            'date' => 'sometimes|date|after:now',
            'location' => 'sometimes|string|max:255',
            'banner' => 'nullable|image|max:2048',
            'status' => 'sometimes|in:draft,published,cancelled'
        ]);

        $data = $request->except('banner');
        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('events', 'public');
            $data['banner_url'] = $path;
        }

        $event->update($data);

        return response()->json($event);
    }

    public function destroy($id)
    {
        $event = Event::where('organizer_id', Auth::id())->findOrFail($id);
        $event->delete();

        return response()->json(['message' => 'Event deleted']);
    }
}