@extends('layouts.master')

@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-5 mt-2">
        <div>
            <h1 class="page-title mb-1">Ticket Details</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 small mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.tickets.index') }}" class="text-muted">Tickets</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">{{ $ticket->ticket_id }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary py-2 px-4 shadow-sm border-0">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>

    <div class="row">
        <!-- Ticket Information Card -->
        <div class="col-lg-8">
            <div class="card border-0 mb-4 overflow-hidden">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 font-weight-bold">Ticket Specification</h5>
                    <span class="badge bg-secondary px-3 py-1 text-muted3">Viewing 1 of {{ $totalTicketsInOrder }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-5">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small text-uppercase mb-2 d-block">Ticket ID</label>
                            <div class="h4 font-weight-bold text-white mb-0" style="font-family: monospace; letter-spacing: 1px;">{{ $ticket->ticket_id }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small text-uppercase mb-2 d-block">Status</label>
                            <div>
                                @if($ticket->ticket_status === 'Active')
                                    <span class="badge bg-success px-4 py-2">ACTIVE</span>
                                @else
                                    <span class="badge bg-secondary px-4 py-2">USED / REDEEMED</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-muted small text-uppercase mb-2 d-block">Event Name</label>
                            <div class="h3 font-weight-bold text-white mb-0">{{ $ticket->ticketType->event->title ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small text-uppercase mb-2 d-block">Ticket Tier</label>
                            <div class="h5 mb-0"><span class="badge bg-primary text-white px-4 py-2">{{ $ticket->ticketType->name ?? 'N/A' }}</span></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small text-uppercase mb-2 d-block">Purchase Date</label>
                            <div class="h5 text-white mb-0">{{ $ticket->order->payment_date ? $ticket->order->payment_date->format('d M Y, H:i') : 'N/A' }}</div>
                        </div>
                    </div>

                    <div class="border-top pt-4 mt-2">
                        <h6 class="font-weight-bold mb-4 text-white">Buyer Information</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small text-uppercase mb-2 d-block">Name</label>
                                <div class="mb-0 text-white font-weight-600">{{ $ticket->order->user->name ?? 'Guest' }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small text-uppercase mb-2 d-block">Email</label>
                                <div class="mb-0 text-white font-weight-600">{{ $ticket->order->user->email ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer py-4" style="background: rgba(255,255,255,0.02) !important;">
                    <div class="d-flex justify-content-end gap-3">
                        @if($ticket->ticket_status === 'Active')
                            <button type="button" class="btn btn-success px-5 py-2" id="activate-ticket-btn">
                                <i class="fas fa-check-circle me-2"></i> MARK AS USED
                            </button>
                        @endif
                        <button type="button" class="btn btn-outline-danger px-4 py-2" id="delete-ticket-btn">
                            <i class="fas fa-trash me-2"></i> DELETE
                        </button>
                    </div>
                </div>
            </div>

            <!-- ALL TICKETS IN THIS ORDER -->
            <h5 class="mt-5 mb-4 font-weight-bold text-white"><i class="fas fa-layer-group text-primary me-2"></i> All Tickets in Transaction #{{ substr($ticket->order->transaction_id, 0, 8) }}...</h5>
            
            <div class="row">
                @foreach($orderTickets as $t)
                    <div class="col-md-12 mb-4">
                        <div class="card border-0 shadow-sm overflow-hidden {{ $t->ticket_id === $ticket->ticket_id ? 'current-ticket-card' : '' }}" style="border-radius: 16px;">
                            <div class="row no-gutters">
                                <div class="col-md-8 p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <span class="badge bg-danger text-white mb-2 px-3 py-1" style="font-size: 10px;">{{ $t->ticketType->name }}</span>
                                            <h5 class="font-weight-bold mb-0 text-white" style="font-family: monospace;">{{ $t->ticket_id }}</h5>
                                        </div>
                                        <div>
                                            <span class="badge {{ $t->ticket_status === 'Active' ? 'bg-success' : 'bg-secondary' }} px-3 py-1">
                                                {{ strtoupper($t->ticket_status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-muted small mb-4">
                                        <i class="fa fa-clock me-1 opacity-50"></i> Purchased on {{ $t->order->payment_date ? $t->order->payment_date->format('d M, H:i') : '-' }}
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        @if($t->ticket_status === 'Active')
                                            <button type="button" class="btn btn-sm btn-success px-4 py-2 font-weight-700" 
                                                    onclick="quickActivate('{{ $t->ticket_id }}', '{{ route('tickets.validate.ajax', $t->ticket_id) }}')">
                                                <i class="fas fa-check-circle me-1"></i> Mark as Used
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-secondary disabled px-4 py-2 font-weight-700" disabled>Validated</button>
                                        @endif
                                        
                                        @if($t->ticket_id !== $ticket->ticket_id)
                                            <a href="{{ route('admin.tickets.show', $t->ticket_id) }}" class="btn-action btn-action-view" title="View Spec">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif
                                        <button type="button" class="btn-action btn-action-delete" onclick="confirmTicketDeletion('{{ $t->ticket_id }}')" title="Delete Ticket">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-4 p-3 d-flex flex-column align-items-center justify-content-center text-center" style="background: rgba(255,255,255,0.03); border-left: 1px solid var(--tix-border2);">
                                    <div class="bg-white p-2 border-0 rounded-lg shadow-sm mb-2">
                                        @php $qr = route('tickets.scan.direct', $t->ticket_id); @endphp
                                        {!! QrCode::size(100)->generate($qr) !!}
                                    </div>
                                    <p class="text-muted small mb-0 opacity-50 font-monospace" style="font-size: 10px;">ID: {{ $t->ticket_id }}</p>
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

            <div class="card shadow-sm border-0 p-4 mb-4 overflow-hidden position-relative" style="border-radius: 16px; background: linear-gradient(135deg, #dc143c, #8b0000) !important;">
                <div class="position-absolute" style="right: -20px; bottom: -20px; opacity: 0.1; font-size: 120px;">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="d-flex align-items-center position-relative" style="z-index: 2;">
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 54px; height: 54px; flex-shrink: 0;">
                        <i class="fas fa-wallet" style="color: #dc143c; font-size: 22px;"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-white opacity-75 small font-weight-700 text-uppercase letter-spacing-1">Order Total</h6>
                        <h3 class="mb-1 font-weight-800 text-white">Rp {{ number_format($ticket->order->total_amount, 0, ',', '.') }}</h3>
                        <div class="small text-white opacity-50 font-monospace">#{{ $ticket->order->transaction_id }}</div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 p-4" style="border-radius: 16px;">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; background: rgba(220, 20, 60, 0.1) !important;">
                        <i class="fas fa-ticket-alt" style="color: var(--tix-red); font-size: 20px;"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-white font-weight-700">Order Usage Stats</h6>
                        <small class="text-muted small">Real-time validation tracking</small>
                    </div>
                </div>
                
                <div class="text-center mb-4">
                    <div class="h2 font-weight-800 text-white mb-1">{{ $usedTicketsInOrder }} <span class="text-muted" style="font-size: 16px; font-weight: 500;">/ {{ $totalTicketsInOrder }} used</span></div>
                    <div class="progress mt-3" style="height: 10px; background: rgba(255,255,255,0.05); border-radius: 20px; overflow: hidden;">
                        <div class="progress-bar" role="progressbar" 
                             style="width: {{ ($usedTicketsInOrder / $totalTicketsInOrder) * 100 }}%; background: linear-gradient(90deg, #dc143c, #ff4d6d); border-radius: 20px;" 
                             aria-valuenow="{{ $usedTicketsInOrder }}" aria-valuemin="0" aria-valuemax="{{ $totalTicketsInOrder }}"></div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between small px-1">
                    <div class="text-muted">Available</div>
                    <div class="text-white font-weight-700">{{ $totalTicketsInOrder - $usedTicketsInOrder }} Tickets</div>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="{{ route('admin.orders.show', $ticket->transaction_id) }}" class="btn btn-outline-primary w-100 py-3 shadow-sm d-flex align-items-center justify-content-center" style="border-radius: 16px; font-weight: 700; background: rgba(220, 20, 60, 0.05);">
                    <i class="fas fa-receipt me-3"></i> VIEW FULL ORDER HISTORY
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
    .gap-3 { gap: 1rem; }
    .current-ticket-card {
        border: 1px solid var(--tix-red) !important;
        background: rgba(220, 20, 60, 0.03) !important;
        box-shadow: 0 0 20px rgba(220, 20, 60, 0.1) !important;
    }
    .letter-spacing-1 { letter-spacing: 1px; }
    .font-weight-600 { font-weight: 600; }
    .font-weight-700 { font-weight: 700; }
    .font-weight-800 { font-weight: 800; }
</style>
@endsection
