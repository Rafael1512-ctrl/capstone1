@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title">Order Details</h4>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <!-- Order Info Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Order #{{ $order->order_number }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <strong>Event:</strong> {{ $order->event->title }}
                                    </p>
                                    <p class="mb-2">
                                        <strong>Event Date:</strong> {{ $order->event->event_date->format('F d, Y') }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <strong>Order Date:</strong> {{ $order->created_at->format('F d, Y H:i') }}
                                    </p>
                                    <p class="mb-2">
                                        <strong>Status:</strong>
                                        <span
                                            class="badge bg-{{ $order->status === 'paid' ? 'success' : ($order->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tickets -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Tickets ({{ $order->tickets->count() }})</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Ticket #</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($order->tickets ?? [] as $ticket)
                                            <tr>
                                                <td><strong>{{ $ticket->ticket_number }}</strong></td>
                                                <td>{{ $ticket->ticketCategory->name }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $ticket->status === 'used' ? 'success' : 'primary' }}">
                                                        {{ ucfirst($ticket->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('tickets.view', $ticket) }}"
                                                        class="btn btn-sm btn-primary">
                                                        View
                                                    </a>
                                                    <a href="{{ route('tickets.download', $ticket) }}"
                                                        class="btn btn-sm btn-success">
                                                        Download
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">No tickets in this order
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-md-4">
                    <!-- Price Summary -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Order Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tickets ({{ $order->quantity }})</span>
                                    <strong>${{ number_format($order->total_price, 2) }}</strong>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <strong>Total:</strong>
                                    <strong class="text-primary">${{ number_format($order->total_price, 2) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Section -->
                    @if ($order->isPending())
                        <div class="card mb-4 border-warning">
                            <div class="card-header bg-warning text-white">
                                <h5 class="card-title mb-0">Payment Pending</h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted small">This order requires payment. Complete payment to confirm your
                                    tickets.</p>
                                <a href="{{ route('payments.show', $order) }}" class="btn btn-warning w-100">
                                    <i class="fas fa-credit-card"></i> Complete Payment
                                </a>
                            </div>
                        </div>
                    @elseif($order->isPaid())
                        <div class="card mb-4 border-success">
                            <div class="card-header bg-success text-white">
                                <h5 class="card-title mb-0">Payment Confirmed</h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted small">Payment has been processed. Your e-tickets are ready!</p>
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Actions</h5>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('tickets.index') }}" class="btn btn-primary btn-sm w-100 mb-2">
                                All Tickets
                            </a>
                            @if ($order->isPending())
                                <form method="POST" action="{{ route('orders.cancel', $order) }}"
                                    style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm w-100"
                                        onclick="return confirm('Cancel this order?')">
                                        Cancel Order
                                    </button>
                                </form>
                            @elseif($order->isPaid())
                                <form method="POST" action="{{ route('payments.refund', $order) }}"
                                    style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm w-100"
                                        onclick="return confirm('Request refund? This cannot be undone.')">
                                        Request Refund
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
