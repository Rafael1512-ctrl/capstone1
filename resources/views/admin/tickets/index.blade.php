@extends('layouts.master')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-5 mt-2">
        <div>
            <h1 class="page-title mb-0">Tickets Management</h1>
            <p class="text-muted small mb-0">Manage all issued tickets, activate them upon entry, or delete if necessary.</p>
        </div>

    </div>

    <!-- Search Box -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.tickets.index') }}" method="GET" class="row">
                <div class="col-md-10 mb-2">
                    <input type="text" name="search" class="form-control" placeholder="Search by Ticket ID, Buyer Name, or Event Title..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 mb-2">
                    <button type="submit" class="btn btn-primary w-100 py-2 shadow-sm font-weight-bold">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header border-0 bg-transparent py-4">
            <h5 class="m-0 text-white fw-bold">All Issued Tickets ({{ $tickets->total() }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Ticket ID</th>
                            <th>Event Name</th>
                            <th>Buyer</th>
                            <th>Type/Tier</th>
                            <th>Status</th>
                            <th class="text-center pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                        <tr id="ticket-row-{{ $ticket->ticket_id }}">
                            <td class="px-4">
                                <a href="{{ route('admin.tickets.show', $ticket->ticket_id) }}" target="_blank" style="text-decoration: none;">
                                    <code>{{ $ticket->ticket_id }}</code>
                                </a>
                            </td>
                            <td>{{ $ticket->ticketType->event->title ?? 'N/A' }}</td>
                            <td>{{ $ticket->order->user->name ?? 'N/A' }}</td>
                            <td>
                                <div>{{ $ticket->ticketType->name ?? 'N/A' }}</div>
                                @if(isset($ticket->ticketType->batch_number))
                                    <span class="badge bg-dark text-crimson border-crimson mt-1" style="font-size: 9px; color: #dc143c; border: 1px solid rgba(220,20,60,0.3);">
                                        BATCH {{ $ticket->ticketType->batch_number }}
                                    </span>
                                @endif
                            </td>
                            <td class="ticket-status-col">
                                @if($ticket->ticket_status === 'Active')
                                    <span class="badge bg-success">ACTIVE</span>
                                @else
                                    <span class="badge bg-secondary">USED</span>
                                @endif
                            </td>
                            <td class="pe-4">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <a href="{{ route('admin.tickets.show', $ticket->ticket_id) }}" class="btn-action btn-action-view" target="_blank" title="View Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($ticket->ticket_status === 'Active')
                                        <button type="button" 
                                                class="btn-action btn-action-success validate-btn" 
                                                data-id="{{ $ticket->ticket_id }}"
                                                data-url="{{ route('tickets.validate.ajax', $ticket->ticket_id) }}" 
                                                title="Activate Ticket">
                                            <i class="fas fa-check-circle"></i> ACTIVATE
                                        </button>
                                    @else
                                        <button class="btn-action btn-action-disabled" disabled>
                                            <i class="fas fa-check-circle"></i> ACTIVATED
                                        </button>
                                    @endif

                                    <button type="button"
                                            class="btn-action btn-action-delete delete-ticket-btn"
                                            data-id="{{ $ticket->ticket_id }}"
                                            data-url="{{ route('admin.tickets.destroy', $ticket->ticket_id) }}"
                                            title="Delete Ticket">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-ticket-alt d-block h1 mb-3"></i>
                                No tickets found matching your search.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 border-top">
                {{ $tickets->appends(request()->input())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Activation Logic
        const validateBtns = document.querySelectorAll('.validate-btn');
        
        validateBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const ticketId = this.dataset.id;
                const url = this.dataset.url;
                const row = document.getElementById(`ticket-row-${ticketId}`);
                
                Swal.fire({
                    title: 'Activate Ticket?',
                    text: `Mark ticket #${ticketId} as activated? This cannot be undone.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#adadad',
                    confirmButtonText: 'Yes, Activate'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.disabled = true;
                        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                        
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
                                    title: 'Activation Success!',
                                    text: data.message,
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                
                                // Update UI
                                const statusCol = row.querySelector('.ticket-status-col');
                                statusCol.innerHTML = '<span class="badge bg-secondary status-badge" style="font-size: 10px;">USED</span>';
                                
                                // Reset the button group to remove ACTIVATE and keep VIEW/DELETE
                                const btnGroup = this.parentElement;
                                btnGroup.innerHTML = `
                                    <a href="{{ url('admin/tickets') }}/${ticketId}" class="btn-action btn-action-view" target="_blank" title="View Ticket Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn-action btn-action-disabled" disabled>
                                        <i class="fas fa-check-circle"></i> ACTIVATED
                                    </button>
                                    <button type="button"
                                            class="btn-action btn-action-delete delete-ticket-btn"
                                            data-id="${ticketId}"
                                            data-url="{{ url('admin/tickets') }}/${ticketId}"
                                            title="Delete Ticket">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                `;
                                // Re-attach delete listener to new button
                                attachDeleteListener(btnGroup.querySelector('.delete-ticket-btn'));
                            } else {
                                this.disabled = false;
                                this.innerHTML = '<i class="fas fa-power-off"></i> ACTIVATE';
                                Swal.fire('Error', data.message, 'error');
                            }
                        })
                        .catch(err => {
                            this.disabled = false;
                            this.innerHTML = '<i class="fas fa-power-off"></i> ACTIVATE';
                             Swal.fire('Error', 'Something went wrong!', 'error');
                        });
                    }
                });
            });
        });

        // Delete Logic
        function attachDeleteListener(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const ticketId = this.dataset.id;
                const url = this.dataset.url;
                const row = document.getElementById(`ticket-row-${ticketId}`);
                
                Swal.fire({
                    title: 'Delete Ticket?',
                    text: `Are you sure you want to delete ticket #${ticketId}? This will remove it permanently.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#adadad',
                    confirmButtonText: 'Yes, Delete'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ _method: 'DELETE' })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: data.message,
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                row.classList.add('fade-out');
                                setTimeout(() => row.remove(), 500);
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        })
                        .catch(err => {
                             Swal.fire('Error', 'Something went wrong!', 'error');
                        });
                    }
                });
            });
        }

        document.querySelectorAll('.delete-ticket-btn').forEach(btn => attachDeleteListener(btn));
    });
</script>

<style>
    .card { border-radius: 12px; }
    .table thead th { border-top: none; }
    code { color: #dc143c; }
    .validate-btn, .delete-ticket-btn { font-weight: 700; }
    .fade-out { opacity: 0; transform: translateX(-20px); transition: all 0.5s; }
</style>
@endsection
