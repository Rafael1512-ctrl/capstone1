<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;

class TicketCategoryController extends Controller
{
    /**
     * Tampilkan semua tipe tiket untuk sebuah event
     */
    public function index(Event $event)
    {
        $this->authorize('update', $event);
        $ticketTypes = $event->ticketTypes()->get();
        return view('ticket-categories.index', compact('event', 'ticketTypes'));
    }

    /**
     * Form buat tipe tiket baru
     */
    public function create(Event $event)
    {
        $this->authorize('update', $event);
        return view('ticket-categories.create', compact('event'));
    }

    /**
     * Simpan tipe tiket baru
     */
    public function store(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'price'          => ['required', 'numeric', 'min:0'],
            'quantity_total' => ['required', 'integer', 'min:1'],
        ]);

        $validated['event_id']       = $event->id;
        $validated['quantity_sold']  = 0;

        TicketType::create($validated);

        return redirect()->route('events.show', $event)
            ->with('success', 'Tipe tiket berhasil ditambahkan!');
    }

    /**
     * Form edit tipe tiket
     */
    public function edit(Event $event, TicketType $category)
    {
        $this->authorize('update', $event);
        return view('ticket-categories.edit', compact('event', 'category'));
    }

    /**
     * Update tipe tiket
     */
    public function update(Request $request, Event $event, TicketType $category)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'price'          => ['required', 'numeric', 'min:0'],
            'quantity_total' => ['required', 'integer', 'min:' . $category->quantity_sold],
        ]);

        $category->update($validated);

        return redirect()->route('events.show', $event)
            ->with('success', 'Tipe tiket berhasil diupdate!');
    }

    /**
     * Hapus tipe tiket
     */
    public function destroy(Event $event, TicketType $category)
    {
        $this->authorize('update', $event);

        if ($category->quantity_sold > 0) {
            return back()->with('error', 'Tidak bisa menghapus tipe tiket yang sudah terjual.');
        }

        $category->delete();

        return back()->with('success', 'Tipe tiket berhasil dihapus!');
    }
}
