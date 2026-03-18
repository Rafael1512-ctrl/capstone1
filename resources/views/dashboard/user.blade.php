@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title">My Dashboard</h4>
            </div>

            <!-- Statistics Row -->
            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <div class="card card-stats card-primary card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-ticket-alt"></i>
                                    </div>
                                </div>
                                <div class="col-9 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">My Tickets</p>
                                        <h4 class="card-title">{{ $stats['total_tickets'] ?? 0 }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4">
                    <div class="card card-stats card-success card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </div>
                                <div class="col-9 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">Used Tickets</p>
                                        <h4 class="card-title">{{ $stats['used_tickets'] ?? 0 }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4">
                    <div class="card card-stats card-info card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                </div>
                                <div class="col-9 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">Upcoming Events</p>
                                        <h4 class="card-title">{{ $stats['upcoming_events'] ?? 0 }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tickets and Orders -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">My Tickets</h5>
                                <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-link">View All</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Ticket #</th>
                                            <th>Event</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($tickets->take(5) ?? [] as $ticket)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('tickets.view', $ticket) }}">
                                                        {{ substr($ticket->ticket_number, -6) }}
                                                    </a>
                                                </td>
                                                <td>{{ $ticket->event->title }}</td>
                                                <td>{{ $ticket->ticketCategory->name }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $ticket->status === 'used' ? 'success' : 'primary' }}">
                                                        {{ ucfirst($ticket->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">No tickets yet</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">My Orders</h5>
                                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-link">View All</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Event</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($orders->take(5) ?? [] as $order)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('orders.show', $order) }}">
                                                        {{ substr($order->order_number, -6) }}
                                                    </a>
                                                </td>
                                                <td>{{ $order->event->title }}</td>
                                                <td>${{ number_format($order->total_price, 2) }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $order->status === 'paid' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">No orders yet</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Explore Events -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Explore Events</h5>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('events.index') }}" class="btn btn-primary">Browse All Events</a>
                            <a href="{{ route('landingconcert') }}" class="btn btn-secondary">Back to Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
