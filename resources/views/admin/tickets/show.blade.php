@extends('layouts.master')

@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-dark">Ticket Details</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.tickets.index') }}">Tickets</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $ticket->ticket_id }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Ticket Information Card -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 font-weight-bold text-primary">Ticket Specification</h5>
                    <span class="badge bg-light text-primary border px-3 py-1">Viewing 1 of {{ $totalTicketsInOrder }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase font-weight-bold">Ticket ID</label>
                            <p class="h5 font-weight-bold"><code>{{ $ticket->ticket_id }}</code></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase font-weight-bold">Status</label>
                            <div>
                                @if($ticket->ticket_status === 'Active')
                                    <span class="badge bg-success px-3 py-2">ACTIVE</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-2">USED / REDEEMED</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="text-muted small text-uppercase font-weight-bold">Event Name</label>
                            <p class="h5">{{ $ticket->ticketType->event->title ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase font-weight-bold">Ticket Tier</label>
                            <p class="h5"><span class="badge bg-info text-white">{{ $ticket->ticketType->name ?? 'N/A' }}</span></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase font-weight-bold">Purchase Date</label>
                            <p class="h5 text-dark">{{ $ticket->order->payment_date ? $ticket->order->payment_date->format('d M Y, H:i') : 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="border-top pt-4">
                        <h6 class="font-weight-bold mb-3 text-dark">Buyer Information</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small text-uppercase font-weight-bold">Name</label>
                                <p class="mb-0 text-dark">{{ $ticket->order->user->name ?? 'Guest' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small text-uppercase font-weight-bold">Email</label>
                                <p class="mb-0 text-dark">{{ $ticket->order->user->email ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light py-3">
                    <div class="d-flex justify-content-end gap-2">
                        @if($ticket->ticket_status === 'Active')
                            <button type="button" class="btn btn-success" id="activate-ticket-btn">
                                <i class="fas fa-check-circle mr-1"></i> MARK AS USED
                            </button>
                        @endif
                        <button type="button" class="btn btn-outline-danger" id="delete-ticket-btn">
                            <i class="fas fa-trash mr-1"></i> DELETE
                        </button>
                    </div>
                </div>
            </div>

            <!-- ALL TICKETS IN THIS ORDER -->
            <h5 class="mt-5 mb-4 font-weight-bold text-dark"><i class="fas fa-layer-group text-primary mr-2"></i> All Tickets in Transaction #{{ substr($ticket->order->transaction_id, 0, 8) }}...</h5>
            
            <div class="row">
                @foreach($orderTickets as $t)
                    <div class="col-md-12 mb-4">
                        <div class="card border-0 shadow-sm overflow-hidden {{ $t->ticket_id === $ticket->ticket_id ? 'current-ticket-card' : '' }}" style="border-radius: 15px;">
                            <div class="row no-gutters">
                                <div class="col-md-8 p-4">
                                    <div class="d-flex justify-content-between mb-3">
                                        <div>
                                            <span class="badge bg-danger text-white mb-2 px-3 py-1">{{ $t->ticketType->name }}</span>
                                            <h5 class="font-weight-bold mb-0 text-dark">{{ $t->ticket_id }}</h5>
                                        </div>
                                        <div>
                                            <span class="badge {{ $t->ticket_status === 'Active' ? 'bg-success' : 'bg-secondary' }} px-3 py-1">
                                                {{ strtoupper($t->ticket_status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-muted small mb-3">
                                        <i class="fa fa-clock mr-1"></i> Purchased on {{ $t->order->payment_date ? $t->order->payment_date->format('d M, H:i') : '-' }}
                                    </div>
                                    <div class="d-flex gap-2 mt-4">
                                        @if($t->ticket_status === 'Active')
                                            <button type="button" class="btn btn-sm btn-success px-4" 
                                                    onclick="quickActivate('{{ $t->ticket_id }}', '{{ route('tickets.validate.ajax', $t->ticket_id) }}')">
                                                <i class="fas fa-check-circle mr-1"></i> Mark as Used
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-light border disabled px-4" disabled>Already Validated</button>
                                        @endif
                                        
                                        <div class="btn-group btn-group-sm">
                                            @if($t->ticket_id !== $ticket->ticket_id)
                                                <a href="{{ route('admin.tickets.show', $t->ticket_id) }}" class="btn btn-outline-info">View Spec</a>
                                            @endif
                                            <button type="button" class="btn btn-outline-danger" onclick="confirmTicketDeletion('{{ $t->ticket_id }}')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 bg-light border-left p-3 d-flex flex-column align-items-center justify-content-center text-center">
                                    <div class="bg-white p-2 border rounded shadow-sm mb-2">
                                        @php $qr = route('tickets.scan.direct', $t->ticket_id); @endphp
                                        {!! QrCode::size(100)->generate($qr) !!}
                                    </div>
                                    <p class="text-muted font-xs mb-0">ID: {{ $t->ticket_id }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Sidebar Section -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4 text-center p-4">
                <h6 class="text-muted small text-uppercase font-weight-bold mb-3">Viewing QR Identification</h6>
                <div class="bg-white p-3 border rounded-lg mb-3 mx-auto" style="width: fit-content;">
                    @php
                        $qrData = route('tickets.scan.direct', $ticket->ticket_id);
                    @endphp
                    {!! QrCode::size(200)->generate($qrData) !!}
                </div>
                <p class="text-muted small mb-0 font-monospace">{{ $ticket->ticket_id }}</p>
                <div class="mt-3">
                    <p class="text-muted small mb-0 italic">Scannable manually for individual check-in.</p>
                </div>
            </div>

            <div class="card shadow-sm border-0 bg-primary text-white p-4 mb-4" style="border-radius: 12px; background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%) !important;">
                <div class="d-flex align-items-center">
                    <div class="icon-big text-center icon-white mr-3" style="font-size: 2rem; opacity: 0.8;">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 opacity-75">Order Total</h6>
                        <h4 class="mb-0 font-weight-bold">Rp {{ number_format($ticket->order->total_amount, 0, ',', '.') }}</h4>
                        <small class="opacity-75">Order ID: #{{ $ticket->order->transaction_id }}</small>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 bg-dark text-white p-4" style="border-radius: 12px;">
                <div class="d-flex align-items-center">
                    <div class="icon-big text-center icon-white mr-3" style="font-size: 2rem; color: #ffc107;">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 opacity-75 text-white">Order Usage Stats</h6>
                        <h4 class="mb-0 font-weight-bold text-white">{{ $usedTicketsInOrder }} / {{ $totalTicketsInOrder }} <span class="small font-weight-normal">used</span></h4>
                        <div class="progress mt-2" style="height: 10px; background: rgba(255,255,255,0.1); border-radius: 5px; overflow: hidden;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ ($usedTicketsInOrder / $totalTicketsInOrder) * 100 }}%" aria-valuenow="{{ $usedTicketsInOrder }}" aria-valuemin="0" aria-valuemax="{{ $totalTicketsInOrder }}"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="{{ route('admin.orders.show', $ticket->transaction_id) }}" class="btn btn-outline-primary w-100 py-3" style="border-radius: 12px; font-weight: 700;">
                    <i class="fas fa-receipt mr-2"></i> VIEW FULL ORDER HISTORY
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Activate Ticket
        const activateBtn = document.getElementById('activate-ticket-btn');
        if (activateBtn) {
            activateBtn.addEventListener('click', function() {
                Swal.fire({
                    title: 'Verify Ticket Entry?',
                    text: 'This will mark the ticket as USED in the system.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    confirmButtonText: 'Yes, Verify Entry'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("{{ route('tickets.validate.ajax', $ticket->ticket_id) }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Success', data.message, 'success').then(() => location.reload());
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        });
                    }
                });
            });
        }

        // Delete Ticket
        const deleteBtn = document.getElementById('delete-ticket-btn');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function() {
                Swal.fire({
                    title: 'Permanent Delete?',
                    text: 'Are you sure you want to delete this ticket? All data will be lost.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes, Delete Permanent'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("{{ route('admin.tickets.destroy', $ticket->ticket_id) }}", {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Deleted!', data.message, 'success').then(() => {
                                    window.location.href = "{{ route('admin.tickets.index') }}";
                                });
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        });
                    }
                });
            });
        }
    });

    function quickActivate(ticketId, url) {
        Swal.fire({
            title: 'Verify This Ticket?',
            text: `Mark ticket #${ticketId} as USED?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Yes, Verify'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Success', data.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                });
            }
        });
    }

    function confirmTicketDeletion(ticketId) {
        Swal.fire({
            title: 'Delete Ticket?',
            text: `You are about to delete ticket #${ticketId} forever.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Yes, Delete It!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Determine target URL (it's the same for all tickets in this order)
                const baseUrl = "{{ route('admin.tickets.destroy', ':id') }}".replace(':id', ticketId);
                
                fetch(baseUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Deleted', data.message, 'success').then(() => {
                            // If we deleted the "main" ticket we were viewing, go back to list
                            if (ticketId === "{{ $ticket->ticket_id }}") {
                                window.location.href = "{{ route('admin.tickets.index') }}";
                            } else {
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                });
            }
        });
    }
</script>
<style>
    .gap-2 { gap: 0.5rem; }
    .current-ticket-card {
        border: 2px solid #0d6efd !important;
        background: #f0f7ff !important;
    }
    .current-ticket-card .bg-light {
        background: #e1efff !important;
    }
</style>
@endsection
