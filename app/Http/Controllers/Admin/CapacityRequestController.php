<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CapacityRequest;
use App\Models\TicketType;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CapacityRequestController extends Controller
{
    /**
     * Display a listing of requests.
     */
    public function index()
    {
        $requests = CapacityRequest::with(['event', 'ticketType'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.capacity.index', compact('requests'));
    }

    /**
     * Approve the request.
     */
    public function approve(CapacityRequest $capacityRequest)
    {
        if ($capacityRequest->status !== 'pending') {
            return back()->with('error', 'Request is already processed.');
        }

        try {
            DB::beginTransaction();

            $ticketType = $capacityRequest->ticketType;
            $event = $capacityRequest->event;

            // 1. Update Ticket Type Capacity
            $diff = $capacityRequest->requested_capacity - $ticketType->quantity_total;
            $ticketType->quantity_total = $capacityRequest->requested_capacity;
            $ticketType->save();

            // 2. Update Event Total Quota (if relevant)
            if ($event) {
                $event->ticket_quota += $diff;
                $event->save();
            }

            // 3. Mark request as approved
            $capacityRequest->update([
                'status' => 'approved',
                'admin_notes' => 'Approved by admin on ' . now()->format('d M Y H:i'),
            ]);

            DB::commit();

            return back()->with('success', 'Capacity request approved! Ticket slots updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve: ' . $e->getMessage());
        }
    }

    /**
     * Reject the request.
     */
    public function reject(Request $request, CapacityRequest $capacityRequest)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        if ($capacityRequest->status !== 'pending') {
            return back()->with('error', 'Request is already processed.');
        }

        $capacityRequest->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Capacity request rejected.');
    }
}
