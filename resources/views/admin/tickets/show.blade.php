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
                <div class="card-header bg-white py-3">
                    <h5 class="m-0 font-weight-bold text-primary">Ticket Specification</h5>
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
                                <p class="mb-0">{{ $ticket->order->user->name ?? 'Guest' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small text-uppercase font-weight-bold">Email</label>
                                <p class="mb-0">{{ $ticket->order->user->email ?? 'N/A' }}</p>
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
                            <i class="fas fa-trash mr-1"></i> DELETE TICKET
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- QR Code & Stats Card -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4 text-center p-4">
                <h6 class="text-muted small text-uppercase font-weight-bold mb-3">QR Identification</h6>
                <div class="bg-white p-3 border rounded-lg mb-3 mx-auto" style="width: fit-content;">
                    {{-- Generate QR for admin view also --}}
                    @php
                        $qrData = route('tickets.scan.direct', $ticket->ticket_id);
                    @endphp
                    {!! QrCode::size(200)->generate($qrData) !!}
                </div>
                <p class="text-muted small mb-0">Scannable manually for check-in</p>
            </div>

            <div class="card shadow-sm border-0 bg-primary text-white p-4">
                <div class="d-flex align-items-center">
                    <div class="icon-big text-center icon-white mr-3" style="font-size: 2rem; opacity: 0.8;">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 opacity-75">Order Total</h6>
                        <h4 class="mb-0 font-weight-bold">Rp {{ number_format($ticket->order->total_amount, 0, ',', '.') }}</h4>
                        <small class="opacity-75">Order ID: #{{ substr($ticket->order->transaction_id, 0, 8) }}</small>
                    </div>
                </div>
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
</script>
@endsection
