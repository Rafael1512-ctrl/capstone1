@extends('layouts.master')

@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-dark">Order Details</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ substr($order->transaction_id, 0, 8) }}...</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4 sticky-top" style="top: 20px;">
                <div class="card-header bg-white py-3">
                    <h5 class="m-0 font-weight-bold text-primary">Transaction Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4 text-center">
                        <div class="display-6 font-weight-bold text-primary mb-1">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                        <span class="badge {{ $order->payment_status === 'Verified' ? 'bg-success' : ($order->payment_status === 'Pending' ? 'bg-warning text-dark' : 'bg-danger') }} px-3 py-2">
                            {{ strtoupper($order->payment_status) }}
                        </span>
                    </div>

                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small text-uppercase">Transaction ID</span>
                            <span class="font-weight-bold small"><code>{{ $order->transaction_id }}</code></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small text-uppercase">Payment Method</span>
                            <span class="font-weight-bold">{{ $order->payment_method }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small text-uppercase">Date</span>
                            <span class="font-weight-bold">{{ $order->payment_date ? $order->payment_date->format('d M Y, H:i') : 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small text-uppercase">Total Tickets</span>
                            <span class="font-weight-bold h5 mb-0">{{ $order->total_ticket }}</span>
                        </div>
                    </div>

                    <div class="border-top pt-3 mt-3">
                        <h6 class="font-weight-bold mb-3 small text-uppercase text-muted">Customer Information</h6>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded-circle p-3 mr-3">
                                <i class="fas fa-user text-secondary"></i>
                            </div>
                            <div>
                                <div class="font-weight-bold text-dark">{{ $order->user->name }}</div>
                                <div class="small text-muted">{{ $order->user->email }}</div>
                            </div>
                        </div>
                    </div>
                    
                    @if($order->payment_status === 'Pending')
                        <div class="mt-4">
                            <form action="{{ route('orders.simulate-payment', $order->transaction_id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100 py-2 font-weight-bold">
                                    <i class="fas fa-check-double mr-1"></i> SIMULATE PAYMENT
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Individual Tickets -->
        <div class="col-lg-8">
            <h5 class="mb-4 font-weight-bold text-dark"><i class="fas fa-ticket-alt mr-2 text-primary"></i> Issued Tickets ({{ $order->tickets->count() }})</h5>
            
            <div class="row row-cols-1 g-4">
                @foreach($order->tickets as $ticket)
                    <div class="col mb-4">
                        <div class="card h-100 border-0 shadow-sm ticket-card position-relative overflow-hidden" id="ticket-card-{{ $ticket->ticket_id }}">
                            <div class="ticket-stub-left"></div>
                            <div class="card-body p-0">
                                <div class="row no-gutters">
                                    <div class="col-md-8 p-4">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <span class="badge bg-danger text-white mb-2 px-3 py-1">{{ $ticket->ticketType->name }}</span>
                                                <h4 class="font-weight-bold mb-1 text-dark">{{ $order->event->title ?? 'Event' }}</h4>
                                                <p class="text-muted small mb-0"><i class="fas fa-calendar-alt mr-1"></i> {{ $order->event->schedule_time ? $order->event->schedule_time->format('d M Y, H:i') : 'Date TBD' }}</p>
                                            </div>
                                            <div class="text-right">
                                                <div class="ticket-status-badge">
                                                    @if($ticket->ticket_status === 'Active')
                                                        <span class="badge bg-success px-3 py-1">ACTIVE</span>
                                                    @else
                                                        <span class="badge bg-secondary px-3 py-1">USED</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-4">
                                            <div class="col-6">
                                                <label class="text-muted small text-uppercase font-weight-bold mb-1 d-block font-xs">Ticket Holder</label>
                                                <div class="font-weight-bold text-dark">{{ $order->user->name }}</div>
                                            </div>
                                            <div class="col-6">
                                                <label class="text-muted small text-uppercase font-weight-bold mb-1 d-block font-xs">Ticket ID</label>
                                                <span class="font-weight-bold text-primary"><code>#{{ $ticket->ticket_id }}</code></span>
                                            </div>
                                        </div>

                                        <div class="mt-4 pt-3 border-top d-flex justify-content-start gap-2">
                                            @if($ticket->ticket_status === 'Active')
                                                <button type="button" 
                                                        class="btn btn-success btn-sm px-4 activate-ticket-btn" 
                                                        data-id="{{ $ticket->ticket_id }}"
                                                        data-url="{{ route('tickets.validate.ajax', $ticket->ticket_id) }}">
                                                    <i class="fas fa-check-circle mr-1"></i> MARK AS USED
                                                </button>
                                            @else
                                                <button class="btn btn-light btn-sm disabled px-4" disabled>
                                                    <i class="fas fa-check text-success mr-1"></i> ALREADY USED
                                                </button>
                                            @endif
                                            <a href="{{ route('admin.tickets.show', $ticket->ticket_id) }}" class="btn btn-outline-info btn-sm px-3">
                                                <i class="fas fa-search-plus mr-1"></i> View Detail
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-4 bg-light border-left p-4 d-flex flex-column align-items-center justify-content-center text-center ticket-qr-section">
                                        <div class="bg-white p-2 border rounded shadow-sm mb-2">
                                            @php
                                                $qrData = route('tickets.scan.direct', $ticket->ticket_id);
                                            @endphp
                                            {!! QrCode::size(120)->generate($qrData) !!}
                                        </div>
                                        <p class="text-muted small mb-0 font-italic">Digital Ticket Entry</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    .ticket-card {
        border-radius: 12px;
        transition: all 0.3s ease;
        border: 1.5px dashed #e0e0e0 !important;
        background: #fff;
    }
    .ticket-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        border-color: #dc143c !important;
    }
    .border-left { border-left: 1px dashed #e0e0e0 !important; }
    .ticket-stub-left {
        position: absolute;
        width: 10px;
        height: 100%;
        left: 0;
        top: 0;
        background: #dc143c;
    }
    .font-xs { font-size: 0.65rem; }
    .ticket-qr-section {
        background: #fbfbfb !important;
    }
    .gap-2 { gap: 0.5rem; }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const activateBtns = document.querySelectorAll('.activate-ticket-btn');
        
        activateBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const ticketId = this.dataset.id;
                const url = this.dataset.url;
                const card = document.getElementById(`ticket-card-${ticketId}`);
                
                Swal.fire({
                    title: 'Mark as USED?',
                    text: 'This will verify that the guest has entered the venue.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    confirmButtonText: 'Yes, Mark as Used'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.disabled = true;
                        this.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Updating...';
                        
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
                                this.disabled = false;
                                this.innerHTML = '<i class="fas fa-check-circle mr-1"></i> MARK AS USED';
                                Swal.fire('Error', data.message, 'error');
                            }
                        })
                        .catch(err => {
                            this.disabled = false;
                            this.innerHTML = '<i class="fas fa-check-circle mr-1"></i> MARK AS USED';
                            Swal.fire('Error', 'An unexpected error occurred.', 'error');
                        });
                    }
                });
            });
        });
    });
</script>
@endsection
