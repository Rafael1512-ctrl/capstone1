@extends('layouts.landingpageconcert.landingconcert')

@section('contentlandingconcert')
<style>
    :root {
        --org-crimson: #dc143c;
        --org-dark: #0d0d0d;
        --org-card-bg: #111111;
        --org-border: rgba(220, 20, 60, 0.2);
    }
    
    .guestlist-section {
        background: var(--org-dark);
        padding: 100px 0 60px;
        min-height: 80vh;
    }
    
    .guestlist-card {
        background: var(--org-card-bg);
        border: 1px solid var(--org-border);
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
    
    .guestlist-header {
        padding: 30px;
        border-bottom: 1px solid var(--org-border);
    }
    
    .guestlist-title {
        font-family: 'DM Serif Display', serif;
        font-size: 2.5rem;
        color: #fff;
    }
    
    .guestlist-title span {
        color: var(--org-crimson);
    }
    
    .search-input {
        background: rgba(255,255,255,0.05);
        border: 1px solid var(--org-border);
        color: #fff;
        border-radius: 30px;
        padding: 12px 25px;
        transition: all 0.3s;
    }
    
    .search-input:focus {
        background: rgba(255,255,255,0.08);
        border-color: var(--org-crimson);
        box-shadow: 0 0 15px rgba(220, 20, 60, 0.2);
        color: #fff;
    }
    
    .btn-org-search {
        background: linear-gradient(90deg, #dc143c, #8b0000);
        border: none;
        color: #fff;
        border-radius: 30px;
        padding: 12px 30px;
        font-weight: 700;
        transition: all 0.3s;
    }
    
    .btn-org-search:hover {
        opacity: 0.9;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(220, 20, 60, 0.4);
    }
    
    .org-table {
        margin-bottom: 0;
    }
    
    .org-table thead th {
        background: rgba(255,255,255,0.02);
        color: rgba(255,255,255,0.4);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 11px;
        padding: 15px 20px;
        border-bottom: 1px solid var(--org-border);
    }
    
    .org-table tbody td {
        padding: 18px 20px;
        color: rgba(255,255,255,0.8);
        border-bottom: 1px solid rgba(255,255,255,0.05);
        vertical-align: middle;
    }
    
    .org-table tbody tr:hover {
        background: rgba(220, 20, 60, 0.03);
    }
    
    .badge-status {
        font-weight: 700;
        font-size: 10px;
        padding: 5px 12px;
        border-radius: 20px;
        letter-spacing: 0.5px;
    }
    
    .badge-active { background: rgba(34,197,94,0.15); color: #22c55e; border: 1px solid rgba(34,197,94,0.2); }
    .badge-used { background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.4); border: 1px solid rgba(255,255,255,0.1); }
    
    .ticket-id { font-family: 'Roboto Mono', monospace; font-size: 13px; color: var(--org-crimson); }
    
    /* Premium Action Buttons */
    .btn-action-group {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
    
    .btn-action {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        text-decoration: none !important;
        border: 1px solid transparent;
        font-size: 14px;
    }
    
    .btn-action-view {
        background: rgba(255, 255, 255, 0.05);
        color: rgba(255, 255, 255, 0.7);
        border-color: rgba(255, 255, 255, 0.1);
    }
    
    .btn-action-view:hover {
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
        border-color: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }
    
    .btn-action-checkin {
        background: linear-gradient(135deg, #dc143c 0%, #a30f2d 100%);
        color: #fff;
        box-shadow: 0 4px 12px rgba(220, 20, 60, 0.25);
    }
    
    .btn-action-checkin:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(220, 20, 60, 0.4);
        filter: brightness(1.1);
    }
    
    .btn-action-disabled {
        background: rgba(255, 255, 255, 0.03);
        color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.05);
        cursor: not-allowed;
    }
    
    /* Pagination Styles */
    .pagination { gap: 5px; }
    .page-link {
        background: var(--org-card-bg) !important;
        border: 1px solid var(--org-border) !important;
        color: rgba(255,255,255,0.5) !important;
        border-radius: 8px !important;
        padding: 8px 16px;
    }
    .page-item.active .page-link {
        background: var(--org-crimson) !important;
        border-color: var(--org-crimson) !important;
        color: #fff !important;
    }
    .page-item.disabled .page-link { background: #080808 !important; }
</style>

<div class="guestlist-section">
    <div class="container">
        
        <div class="mb-5" data-aos="fade-down">
            <h1 class="guestlist-title">Event <span>Guest List</span></h1>
            <p class="text-muted">Manage attendees and track entry for your exclusively managed concerts.</p>
        </div>

        <!-- Search Box -->
        <div class="guestlist-card mb-5" data-aos="fade-up">
            <div class="guestlist-header">
                <form action="{{ route('organizer.guestlist') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-9">
                            <input type="text" name="search" class="form-control search-input" 
                                   placeholder="Search by Ticket ID, Attendee Name, or Event..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-org-search w-100">
                                <i class="fa fa-search mr-2"></i> SEARCH
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tickets Table -->
        <div class="guestlist-card" data-aos="fade-up" data-aos-delay="100">
            <div class="table-responsive">
                <table class="table org-table">
                    <thead>
                        <tr>
                            <th>Ticket ID</th>
                            <th>Event Title</th>
                            <th>Attendee</th>
                            <th>Tier</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                        <tr id="ticket-row-{{ $ticket->ticket_id }}">
                            <td class="ticket-id">#{{ $ticket->ticket_id }}</td>
                            <td>
                                <div style="color:#fff; font-weight:600;">{{ $ticket->ticketType->event->title ?? 'N/A' }}</div>
                                <div style="font-size:11px; opacity:0.5;">📍 {{ $ticket->ticketType->event->location ?? 'TBA' }}</div>
                            </td>
                            <td>
                                <div style="color:#fff; font-weight:600;">{{ $ticket->order->user->name ?? 'Guest' }}</div>
                                <div style="font-size:11px; opacity:0.5;">{{ $ticket->order->user->email ?? '' }}</div>
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                    <span style="font-size:12px; color:rgba(255,255,255,0.6)">{{ $ticket->ticketType->name ?? 'N/A' }}</span>
                                    @if(isset($ticket->ticketType->batch_number))
                                        <div>
                                            <span class="badge-status" style="background: rgba(220, 20, 60, 0.1); color: var(--org-crimson); border: 1px solid rgba(220, 20, 60, 0.2); font-size: 9px;">
                                                BATCH {{ $ticket->ticketType->batch_number }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="status-col">
                                @if($ticket->ticket_status === 'Active')
                                    <span class="badge-status badge-active">ACTIVE</span>
                                @else
                                    <span class="badge-status badge-used">USED</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-action-group">
                                    <a href="{{ route('admin.tickets.show', $ticket->ticket_id) }}" 
                                       class="btn-action btn-action-view" title="View Details">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    
                                    @if($ticket->ticket_status === 'Active')
                                        <button onclick="activateTicket(this, '{{ $ticket->ticket_id }}', '{{ route('tickets.validate.ajax', $ticket->ticket_id) }}')" 
                                                class="btn-action btn-action-checkin" 
                                                title="Check-in Guest">
                                            <i class="fa fa-check"></i>
                                        </button>
                                    @else
                                        <div class="btn-action btn-action-disabled" title="Already Checked-in">
                                            <i class="fa fa-check"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div style="font-size:40px; margin-bottom:15px; opacity:0.2;">🎟️</div>
                                <div style="color:rgba(255,255,255,0.4)">No tickets found in the guest list.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tickets->hasPages())
            <div class="p-4 d-flex justify-content-center border-top" style="border-color:rgba(255,255,255,0.05) !important;">
                {{ $tickets->appends(request()->input())->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </div>

    </div>
</div>

@endsection

@section('ExtraJS2')
<script>
    function activateTicket(btn, id, url) {
        Swal.fire({
            title: 'Check-in Attendee?',
            text: `Confirm check-in for ticket #${id}?`,
            icon: 'question',
            background: '#111',
            color: '#fff',
            showCancelButton: true,
            confirmButtonColor: '#dc143c',
            cancelButtonColor: '#444',
            confirmButtonText: 'Yes, Mark as Used'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
                
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Verified!',
                            text: data.message,
                            background: '#111',
                            color: '#fff',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        // Update UI
                        const row = document.getElementById(`ticket-row-${id}`);
                        const statusCol = row.querySelector('.status-col');
                        statusCol.innerHTML = '<span class="badge-status badge-used">USED</span>';
                        
                        // Replace button with disabled state for symmetry
                        const btnGroup = btn.parentElement;
                        btn.outerHTML = '<div class="btn-action btn-action-disabled" title="Already Checked-in"><i class="fa fa-check"></i></div>';
                    } else {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa fa-check"></i>';
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(err => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa fa-check"></i>';
                    Swal.fire('Error', 'Something went wrong!', 'error');
                });
            }
        });
    }
</script>
@endsection
