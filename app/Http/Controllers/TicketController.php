<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketController extends Controller
{
    /**
     * Generate QR code untuk tiket
     */
    public function generateQrCode(Ticket $ticket)
    {
        $user = Auth::user();
        $isOwner = $user->user_id === $ticket->order->user_id;
        $isAdmin = $user->isAdmin();
        $isOrganizer = $user->isOrganizer() && ($ticket->ticketType->event->organizer_id ?? null) === $user->user_id;

        if (!$isOwner && !$isAdmin && !$isOrganizer) {
            abort(403);
        }

        try {
            // Encode a full URL with a unique signature in the QR code
            $url = route('tickets.scan.direct', $ticket->ticket_id) . '?sig=' . Str::random(16);
            
            // Generate as SVG for better compatibility
            $qrCode = QrCode::size(300)->generate($url);

            $filename = 'qr-codes/' . $ticket->ticket_id . '.svg';
            Storage::disk('public')->put($filename, $qrCode);

            $ticket->update(['qr_code' => $filename]);

            return response($qrCode)
                ->header('Content-Type', 'image/svg+xml');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal membuat QR code: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Download tiket sebagai file gambar QR
     */
    public function download(Ticket $ticket)
    {
        $user = Auth::user();
        $isOwner = $user->user_id === $ticket->order->user_id;
        $isAdmin = $user->isAdmin();
        $isOrganizer = $user->isOrganizer() && ($ticket->ticketType->event->organizer_id ?? null) === $user->user_id;

        if (!$isOwner && !$isAdmin && !$isOrganizer) {
            abort(403);
        }

        // Generate QR jika belum ada
        if (!$ticket->qr_code || !Storage::disk('public')->exists($ticket->qr_code)) {
            $this->generateQrCode($ticket);
            $ticket->refresh(); // reload data setelah update
        }

        $path = Storage::disk('public')->path($ticket->qr_code);
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return response()->download($path, $ticket->ticket_id . '.' . $extension);
    }

    /**
     * Lihat detail tiket di browser
     */
    public function view(Ticket $ticket)
    {
        $user = Auth::user();
        $isOwner = $user->user_id === $ticket->order->user_id;
        $isAdmin = $user->isAdmin();
        $isOrganizer = $user->isOrganizer() && ($ticket->ticketType->event->organizer_id ?? null) === $user->user_id;

        if (!$isOwner && !$isAdmin && !$isOrganizer) {
            abort(403);
        }

        // Generate QR jika belum ada
        if (!$ticket->qr_code || !Storage::disk('public')->exists($ticket->qr_code)) {
            $this->generateQrCode($ticket);
            $ticket->refresh();
        }

        $ticket->load('ticketType.event', 'order');

        // Gunakan asset() dengan path relatif ke public disk
        $qrCodeUrl = asset('storage/' . $ticket->qr_code);

        return view('tickets.view', compact('ticket', 'qrCodeUrl'));
    }

    /**
     * Validasi tiket via scan QR (Admin/Organizer)
     */
    public function verifyTicket(Request $request)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isOrganizer()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $uniqueCode = $request->input('unique_code');

        if (!$uniqueCode) {
            return response()->json(['error' => 'Kode unik diperlukan'], 400);
        }

        $ticket = Ticket::where('ticket_id', $uniqueCode)->first(); // Wait, unique_code is now ticket_id?

        if (!$ticket) {
            return response()->json(['error' => 'Tiket tidak ditemukan'], 404);
        }

        $event = $ticket->ticketType->event;

        // Organizer hanya bisa validasi tiket eventnya sendiri
        if ($user->isOrganizer() && $event->organizer_id !== $user->user_id) {
            return response()->json(['error' => 'Anda hanya bisa memvalidasi tiket event Anda'], 403);
        }

        if (!$ticket->isActive()) {
            return response()->json(['error' => 'Tiket sudah digunakan/kadaluwarsa'], 400);
        }

        $ticket->validate();

        return response()->json([
            'success' => true,
            'message' => 'Tiket berhasil divalidasi',
            'ticket'  => [
                'unique_code'  => $ticket->ticket_id,
                'event'        => $event->title,
                'ticket_type'  => $ticket->ticketType->name,
            ],
        ]);
    }

    /**
     * Alias scan → verifyTicket
     */
    public function scan(Request $request)
    {
        return $this->verifyTicket($request);
    }

    /**
     * Scan direct (GET) from Phone Camera
     */
    public function scanDirect(Ticket $ticket)
    {
        $user = Auth::user();

        // Organizer/Admin only
        if (!$user || (!$user->isAdmin() && !$user->isOrganizer())) {
            return view('tickets.scan-result', [
                'success' => false,
                'message' => 'Unauthorized. Please login as an Organizer or Admin to validate tickets.',
                'ticket' => $ticket
            ]);
        }

        if (!$ticket->isActive()) {
            return view('tickets.scan-result', [
                'success' => false,
                'message' => 'This ticket has already been used or is inactive.',
                'ticket' => $ticket
            ]);
        }

        // Mark as used
        $ticket->validate();

        return view('tickets.scan-result', [
            'success' => true,
            'message' => 'Ticket verified successfully! Access granted.',
            'ticket' => $ticket
        ]);
    }

    /**
     * Scanner Page for Organizer/Admin
     */
    public function showScanner()
    {
        return view('tickets.scanner');
    }

    /**
     * AJAX Validate Ticket from Check-in Table
     */
    public function validateAjax(Ticket $ticket)
    {
        $user = Auth::user();

        // 1. Permission check
        if (!$user->isAdmin() && !$user->isOrganizer()) {
             return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // 2. Organizer specific check
        if ($user->isOrganizer() && $ticket->ticketType->event->organizer_id !== $user->user_id) {
             return response()->json(['success' => false, 'message' => 'Unauthorized: This ticket belongs to another event.'], 403);
        }

        // 3. Status check
        if (!$ticket->isActive()) {
            return response()->json(['success' => false, 'message' => 'Ticket is already used or inactive.'], 400);
        }

        // 4. Update status
        $ticket->validate();

        return response()->json([
            'success' => true, 
            'message' => "Ticket #{$ticket->ticket_id} for {$ticket->order->user->name} has been marked as used."
        ]);
    }

    /**
     * All tickets in the system (Admin only)
     */
    public function allTickets(Request $request)
    {
        $query = Ticket::with(['order.user', 'ticketType.event']);

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('ticket_id', 'LIKE', "%{$search}%")
                  ->orWhereHas('order.user', function($qu) use ($search) {
                      $qu->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('ticketType.event', function($qe) use ($search) {
                      $qe->where('title', 'LIKE', "%{$search}%");
                  });
            });
        }

        $tickets = $query->orderBy('ticket_id', 'desc')->paginate(20);
        return view('admin.tickets.index', compact('tickets'));
    }

    /**
     * Delete a ticket (Admin only)
     */
    public function destroy(Ticket $ticket)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $ticketId = $ticket->ticket_id;
            
            // Optional: delete associated QR code file if it exists
            if ($ticket->qr_code && Storage::disk('public')->exists($ticket->qr_code)) {
                Storage::disk('public')->delete($ticket->qr_code);
            }

            $ticket->delete();

            return response()->json([
                'success' => true,
                'message' => "Ticket #{$ticketId} has been deleted successfully."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete ticket: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Daftar tiket milik user yang sedang login
     */
    public function myTickets()
    {
        $userId = Auth::id();
        
        // Base query for stats (no pagination)
        $baseQuery = Ticket::whereHas('order', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        });

        $totalCount = (clone $baseQuery)->count();
        $activeCount = (clone $baseQuery)->where('ticket_status', 'Active')->count();
        $usedCount = (clone $baseQuery)->where('ticket_status', 'Used')->count();

        // Paginated list
        $tickets = $baseQuery->with(['ticketType.event', 'order'])
            ->orderBy('ticket_id', 'desc')
            ->paginate(12);

        return view('tickets.my-tickets', compact('tickets', 'totalCount', 'activeCount', 'usedCount'));
    }
}
