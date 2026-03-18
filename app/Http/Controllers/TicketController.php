<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    /**
     * Generate QR code for ticket
     */
    public function generateQrCode(Ticket $ticket)
    {
        // Check authorization
        if (Auth::user()->id !== $ticket->order->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        try {
            $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                ->size(300)
                ->generate($ticket->ticket_number);

            // Save QR code to storage
            $filename = 'qr-codes/' . $ticket->ticket_number . '.png';
            Storage::disk('public')->put($filename, $qrCode);

            $ticket->update([
                'qr_code' => $ticket->ticket_number,
                'qr_code_path' => $filename,
            ]);

            return response($qrCode)
                ->header('Content-Type', 'image/png')
                ->header('Content-Disposition', 'attachment; filename="' . $ticket->ticket_number . '.png"');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate QR code'], 500);
        }
    }

    /**
     * Download ticket with QR code
     */
    public function download(Ticket $ticket)
    {
        // Authorization
        if (Auth::user()->id !== $ticket->order->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        // Generate QR if not exists
        if (!$ticket->qr_code_path || !Storage::disk('public')->exists($ticket->qr_code_path)) {
            $this->generateQrCode($ticket);
        }

        $path = Storage::disk('public')->path($ticket->qr_code_path);

        return response()->download($path, $ticket->ticket_number . '.png');
    }

    /**
     * View ticket details
     */
    public function view(Ticket $ticket)
    {
        // Authorization
        if (Auth::user()->id !== $ticket->order->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        // Generate QR if not exists
        if (!$ticket->qr_code_path || !Storage::disk('public')->exists($ticket->qr_code_path)) {
            $this->generateQrCode($ticket);
        }

        $ticket->load('event', 'ticketCategory', 'order');
        $qrCodeUrl = Storage::disk('public')->url($ticket->qr_code_path);

        return view('tickets.view', compact('ticket', 'qrCodeUrl'));
    }

    /**
     * Validate ticket (scan QR code) - Admin/Event Staff
     */
    public function validate(Request $request)
    {
        $user = Auth::user();

        // Only admin and organizers can validate
        if (!$user->isAdmin() && !$user->isOrganizer()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $ticketNumber = $request->input('ticket_number');

        if (!$ticketNumber) {
            return response()->json(['error' => 'Ticket number required'], 400);
        }

        $ticket = Ticket::where('ticket_number', $ticketNumber)->first();

        if (!$ticket) {
            return response()->json(['error' => 'Ticket not found'], 404);
        }

        // If organizer, check if they own the event
        if ($user->isOrganizer() && $ticket->event->organizer_id !== $user->id) {
            return response()->json(['error' => 'You can only validate tickets for your events'], 403);
        }

        if (!$ticket->isActive()) {
            return response()->json(['error' => 'Ticket is not active'], 400);
        }

        // Mark as used
        $ticket->validate($user->name);

        return response()->json([
            'success' => true,
            'message' => 'Ticket validated successfully',
            'ticket' => [
                'number' => $ticket->ticket_number,
                'event' => $ticket->event->title,
                'category' => $ticket->ticketCategory->name,
                'validated_at' => $ticket->validated_at,
            ],
        ]);
    }

    /**
     * Scan QR code (AJAX endpoint)
     */
    public function scan(Request $request)
    {
        return $this->validate($request);
    }

    /**
     * List user tickets
     */
    public function myTickets()
    {
        $tickets = Ticket::whereHas('order', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->with('event', 'ticketCategory', 'order')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('tickets.my-tickets', compact('tickets'));
    }
}
