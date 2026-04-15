<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use BaconQrCode\Encoder\Encoder as BaconEncoder;
use BaconQrCode\Common\ErrorCorrectionLevel;

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
     * Download tiket sebagai file QR (legacy)
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
            $ticket->refresh();
        }

        $path = Storage::disk('public')->path($ticket->qr_code);
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return response()->download($path, $ticket->ticket_id . '.' . $extension);
    }

    /**
     * Download tiket sebagai PDF (DomPDF — 1 tiket per klik)
     */
    public function downloadPdf(Ticket $ticket)
    {
        $user = Auth::user();
        $isOwner = $user->user_id === $ticket->order->user_id;
        $isAdmin = $user->isAdmin();
        $isOrganizer = $user->isOrganizer() && ($ticket->ticketType->event->organizer_id ?? null) === $user->user_id;

        if (!$isOwner && !$isAdmin && !$isOrganizer) {
            abort(403);
        }

        $ticket->load('ticketType.eventWithTrashed', 'order.user');

        $event       = $ticket->ticketType->eventWithTrashed;
        $transaction = $ticket->order;
        $qrUrl       = route('tickets.scan.direct', $ticket->ticket_id);

        // ── QR Code: render as HTML table (100% DomPDF-compatible, no Imagick needed)
        // BaconQrCode Encoder gives us the raw matrix (0=white, 1=black)
        $qrHtml = null;
        try {
            $qrEncoded = BaconEncoder::encode($qrUrl, ErrorCorrectionLevel::M());
            $matrix    = $qrEncoded->getMatrix();
            $size      = $matrix->getWidth();
            $cellPx    = max(2, (int) floor(130 / $size)); // ~4px per cell
            $totalPx   = $cellPx * $size;

            $tbl = '<table cellspacing="0" cellpadding="0" style="border-collapse:collapse;width:' . $totalPx . 'px;height:' . $totalPx . 'px;margin:0 auto;">';
            for ($y = 0; $y < $size; $y++) {
                $tbl .= '<tr>';
                for ($x = 0; $x < $size; $x++) {
                    $bg   = ($matrix->get($x, $y) === 1) ? '#000000' : '#ffffff';
                    $tbl .= '<td style="width:' . $cellPx . 'px;height:' . $cellPx . 'px;background:' . $bg . ';padding:0;font-size:0;"></td>';
                }
                $tbl .= '</tr>';
            }
            $tbl   .= '</table>';
            $qrHtml = $tbl;
        } catch (\Exception $e) {}

        // ── Event image: base64 for DomPDF ────────────────────────────────────
        $imgBase64 = null;
        if ($event->banner_url) {
            try {
                if (filter_var($event->banner_url, FILTER_VALIDATE_URL)) {
                    $src = \App\Models\SiteSetting::forceDirectUrl($event->banner_url);
                    $ctx = stream_context_create(['http' => [
                        'timeout'        => 12,
                        'follow_location' => true,
                        'header'         => "User-Agent: Mozilla/5.0\r\n",
                    ]]);
                    $imgContent = @file_get_contents($src, false, $ctx);
                } else {
                    $rel       = ltrim($event->banner_url, '/');
                    $localPath = str_starts_with($rel, 'storage/')
                        ? public_path($rel)
                        : storage_path('app/public/' . $rel);
                    $imgContent = file_exists($localPath) ? file_get_contents($localPath) : false;
                }
                if ($imgContent) {
                    $finfo = new \finfo(FILEINFO_MIME_TYPE);
                    $mime  = $finfo->buffer($imgContent);
                    if (str_starts_with($mime, 'image/')) {
                        $imgBase64 = 'data:' . $mime . ';base64,' . base64_encode($imgContent);
                    }
                }
            } catch (\Exception $e) {}
        }

        $ticketData = [[
            'ticket'       => $ticket,
            'event'        => $event,
            'transaction'  => $transaction,
            'qrHtml'       => $qrHtml,
            'imgBase64'    => $imgBase64,
            'scheduleText' => $event->schedule_time
                ? $event->schedule_time->format('d M Y | H:i') . ' WIB'
                : 'TBA',
        ]];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('tickets.ticket-pdf', compact('ticket', 'ticketData'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('TIXLY_' . $ticket->ticket_id . '.pdf');
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

        $ticket->load('ticketType.eventWithTrashed', 'order.user');
        
        // Explicitly get ALL tickets in this transaction to ensure none are missed
        // Menggunakan eventWithTrashed agar event yang di-soft delete tetap tampil
        $ticketsInOrder = Ticket::where('transaction_id', $ticket->transaction_id)
            ->with(['ticketType.eventWithTrashed', 'order.user'])
            ->get();

        return view('tickets.view', compact('ticket', 'ticketsInOrder'));
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

        $uniqueCode = trim($request->input('unique_code'));

        if (!$uniqueCode) {
            return response()->json(['error' => 'Kode unik diperlukan'], 400);
        }

        // Try exact match first
        $ticket = Ticket::where('ticket_id', $uniqueCode)->first();

        // Fallback: try case-insensitive or without common prefixes like '#' or 'TC-' or 'T-'
        if (!$ticket) {
            $cleanCode = $uniqueCode;
            $cleanCode = ltrim($cleanCode, '# ');
            $cleanCode = preg_replace('/^(TC-|T-)/i', '', $cleanCode); 
            
            $ticket = Ticket::where('ticket_id', 'LIKE', $cleanCode)->first();
        }

        if (!$ticket) {
            return response()->json(['error' => "Tiket dengan ID '{$uniqueCode}' tidak ditemukan. Pastikan ID sudah benar."], 404);
        }

        $ticket->load(['ticketType.event', 'order.user']);
        $event = $ticket->ticketType->event;

        // Organizer hanya bisa validasi tiket eventnya sendiri
        if ($user->isOrganizer() && $event->organizer_id !== $user->user_id) {
            return response()->json(['error' => 'Anda hanya bisa memvalidasi tiket event Anda'], 403);
        }

        if (!$ticket->isActive()) {
            return response()->json(['error' => "Tiket #{$ticket->ticket_id} sudah digunakan atau tidak aktif."], 400);
        }

        $ticket->validate();

        return response()->json([
            'success' => true,
            'message' => 'Tiket berhasil divalidasi',
            'ticket'  => [
                'unique_code'  => $ticket->ticket_id,
                'buyer'        => $ticket->order->user->name ?? 'Guest',
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
     * Detail tiket untuk tampilan Admin (Sidebar & Dashboard style)
     */
    public function showAdminTicket(Ticket $ticket)
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isOrganizer()) {
            abort(403);
        }

        $ticket->load(['ticketType.event', 'order.user', 'order.tickets.ticketType']);
        
        $transactionId = $ticket->transaction_id;
        $orderTickets = $ticket->order->tickets;
        $totalTicketsInOrder = $orderTickets->count();
        $usedTicketsInOrder = $orderTickets->where('ticket_status', 'Used')->count();

        return view('admin.tickets.show', compact('ticket', 'totalTicketsInOrder', 'usedTicketsInOrder', 'orderTickets'));
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
                  })
                  ->orWhereHas('ticketType', function($qt) use ($search) {
                      $qt->where('batch_number', 'LIKE', "%{$search}%");
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
            $order = $ticket->order;
            $priceToSubtract = $ticket->ticketType->price ?? 0;
            
            // Delete associated QR code file if it exists
            if ($ticket->qr_code && Storage::disk('public')->exists($ticket->qr_code)) {
                Storage::disk('public')->delete($ticket->qr_code);
            }

            // Perform deletion
            $ticket->delete();

            // IF ORDER EXISTS, SYNC THE TOTALS
            if ($order) {
                // Update total ticket count
                $order->total_ticket = max(0, $order->total_ticket - 1);
                
                // Update total amount
                $order->total_amount = max(0, $order->total_amount - $priceToSubtract);
                
                $order->save();
            }

            return response()->json([
                'success' => true,
                'message' => "Ticket #{$ticketId} has been deleted successfully and order totals updated."
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

        $totalCount  = (clone $baseQuery)->count();
        $activeCount = (clone $baseQuery)->where('ticket_status', 'Active')->count();
        $usedCount   = (clone $baseQuery)->where('ticket_status', 'Used')->count();

        // Group by order/transaction — one card per purchase
        // Menggunakan eventWithTrashed agar event yang sudah di-soft delete
        // oleh admin tetap bisa diakses di history tiket user
        $allTickets = $baseQuery->with(['ticketType.eventWithTrashed', 'order.user'])->get();

        // Group tickets by transaction_id, picking one representative ticket per order
        $orders = $allTickets->groupBy('transaction_id')->map(function($ticketsInOrder) {
            $first = $ticketsInOrder->first();
            // Gunakan eventWithTrashed agar event soft-deleted tetap bisa diakses
            $event = $first->ticketType->eventWithTrashed ?? $first->ticketType->event ?? null;
            $order = $first->order ?? null;
            $types = $ticketsInOrder->groupBy(fn($t) => $t->ticketType->name ?? 'Standard');

            return [
                'transaction_id'     => $first->transaction_id,
                'representative_id'  => $first->ticket_id, // for the details link
                'event'              => $event,
                'order'              => $order,
                'ticket_count'       => $ticketsInOrder->count(),
                'ticket_types'       => $types->map(fn($t, $name) => ['name' => $name, 'count' => $t->count(), 'id' => $t->first()->ticket_type_id]),
                'has_active'         => $ticketsInOrder->contains('ticket_status', 'Active'),
                'all_used'           => $ticketsInOrder->every(fn($t) => $t->ticket_status === 'Used'),
                'purchased_at'       => $order?->payment_date,
                'total_amount'       => $order?->total_amount,
                'event_deleted'      => $event && $event->trashed(), // flag jika event sudah dihapus
            ];
        })->sortByDesc(fn($o) => $o['purchased_at'])->values();

        return view('tickets.my-tickets', compact('orders', 'totalCount', 'activeCount', 'usedCount'));
    }
}
