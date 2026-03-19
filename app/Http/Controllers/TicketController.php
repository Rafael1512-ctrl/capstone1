<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketController extends Controller
{
    /**
     * Generate QR code untuk tiket
     */
    public function generateQrCode(Ticket $ticket)
    {
        if (Auth::id() !== $ticket->order->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        try {
            $qrCode = QrCode::format('png')->size(300)->generate($ticket->unique_code);

            $filename = 'qr-codes/' . $ticket->unique_code . '.png';
            Storage::disk('public')->put($filename, $qrCode);

            $ticket->update(['qr_code_url' => $filename]);

            return response($qrCode)
                ->header('Content-Type', 'image/png')
                ->header('Content-Disposition', 'inline; filename="' . $ticket->unique_code . '.png"');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal membuat QR code: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Download tiket sebagai file gambar QR
     */
    public function download(Ticket $ticket)
    {
        if (Auth::id() !== $ticket->order->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        // Generate QR jika belum ada
        if (!$ticket->qr_code_url || !Storage::disk('public')->exists($ticket->qr_code_url)) {
            $this->generateQrCode($ticket);
            $ticket->refresh(); // reload data setelah update
        }

        $path = Storage::disk('public')->path($ticket->qr_code_url);

        return response()->download($path, $ticket->unique_code . '.png');
    }

    /**
     * Lihat detail tiket di browser
     */
    public function view(Ticket $ticket)
    {
        if (Auth::id() !== $ticket->order->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        // Generate QR jika belum ada
        if (!$ticket->qr_code_url || !Storage::disk('public')->exists($ticket->qr_code_url)) {
            $this->generateQrCode($ticket);
            $ticket->refresh();
        }

        $ticket->load('ticketType.event', 'order');

        // Gunakan asset() dengan path relatif ke public disk
        $qrCodeUrl = asset('storage/' . $ticket->qr_code_url);

        return view('tickets.view', compact('ticket', 'qrCodeUrl'));
    }

    /**
     * Validasi tiket via scan QR (Admin/Organizer)
     */
    public function validate(Request $request)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isOrganizer()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $uniqueCode = $request->input('unique_code');

        if (!$uniqueCode) {
            return response()->json(['error' => 'Kode unik diperlukan'], 400);
        }

        $ticket = Ticket::where('unique_code', $uniqueCode)->first();

        if (!$ticket) {
            return response()->json(['error' => 'Tiket tidak ditemukan'], 404);
        }

        $event = $ticket->ticketType->event;

        // Organizer hanya bisa validasi tiket eventnya sendiri
        if ($user->isOrganizer() && $event->organizer_id !== $user->id) {
            return response()->json(['error' => 'Anda hanya bisa memvalidasi tiket event Anda'], 403);
        }

        if (!$ticket->isActive()) {
            return response()->json(['error' => 'Tiket sudah digunakan'], 400);
        }

        $ticket->validate();

        return response()->json([
            'success' => true,
            'message' => 'Tiket berhasil divalidasi',
            'ticket'  => [
                'unique_code'  => $ticket->unique_code,
                'event'        => $event->title,
                'ticket_type'  => $ticket->ticketType->name,
                'used_at'      => $ticket->used_at,
            ],
        ]);
    }

    /**
     * Alias scan → validate
     */
    public function scan(Request $request)
    {
        return $this->validate($request);
    }

    /**
     * Daftar tiket milik user yang sedang login
     */
    public function myTickets()
    {
        $tickets = Ticket::whereHas('order', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->with(['ticketType.event', 'order'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('tickets.my-tickets', compact('tickets'));
    }
}
