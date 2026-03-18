@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="page-inner">
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="page-title">{{ $event->title ?? 'Event Details' }}</h4>
                    @if (auth()->user()?->id === ($event->organizer_id ?? null) || auth()->user()?->isAdmin())
                        <div>
                            <a href="{{ route('events.edit', $event) }}" class="btn btn-primary btn-sm">Edit</a>
                            <a href="{{ route('ticket-categories.index', $event) }}" class="btn btn-info btn-sm">Manage
                                Tickets</a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <!-- Event Image & Basic Info -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        @if ($event->image ?? false)
                            <img src="{{ asset('storage/' . $event->image) }}" class="card-img-top"
                                alt="{{ $event->title }}" style="height: 400px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                style="height: 400px;">
                                <i class="fas fa-image fa-5x text-muted"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h3 class="card-title">{{ $event->title }}</h3>
                            <p class="card-text text-muted">{{ $event->brief_description }}</p>

                            <!-- Event Info Grid -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <strong><i class="fas fa-calendar"></i> Event Date:</strong><br>
                                        {{ $event->event_date->format('F d, Y') }}
                                    </p>
                                    <p class="mb-2">
                                        <strong><i class="fas fa-clock"></i> Time:</strong><br>
                                        {{ $event->start_time->format('H:i') }} @if ($event->end_time)
                                            - {{ $event->end_time->format('H:i') }}
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <strong><i class="fas fa-map-marker-alt"></i> Location:</strong><br>
                                        {{ $event->venue_name ?? $event->location }}
                                    </p>
                                    <p class="mb-2">
                                        <strong><i class="fas fa-users"></i> Capacity:</strong><br>
                                        {{ $event->total_capacity }} attendees
                                    </p>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <h5>About This Event</h5>
                                <p>{{ $event->description }}</p>
                            </div>

                            <!-- Organizer Info -->
                            <div class="alert alert-info">
                                <strong>Organized by:</strong> {{ $event->organizer->name }}<br>
                                @if ($event->organizer->phone)
                                    <strong>Contact:</strong> {{ $event->organizer->phone }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Ticket Categories -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Ticket Prices</h5>
                        </div>
                        <div class="card-body">
                            @forelse($event->ticketCategories ?? [] as $category)
                                <div class="mb-3">
                                    <h6>{{ $category->name }}</h6>
                                    <p class="mb-1">
                                        <strong>${{ number_format($category->price, 2) }}</strong>
                                    </p>
                                    <p class="mb-2 text-muted small">
                                        @if ($category->status === 'sold_out')
                                            <span class="badge bg-danger">Sold Out</span>
                                        @elseif($category->available_tickets > 0)
                                            <span class="badge bg-success">{{ $category->available_tickets }}
                                                available</span>
                                        @else
                                            <span class="badge bg-secondary">No tickets</span>
                                        @endif
                                    </p>
                                    @if ($category->description)
                                        <small class="text-muted">{{ $category->description }}</small>
                                    @endif
                                </div>
                                <hr>
                            @empty
                                <p class="text-muted">No ticket categories available yet</p>
                            @endforelse

                            <!-- Buy Button -->
                            @if (auth()->check() && $event->status === 'published')
                                @if ($event->ticketCategories->where('status', 'active')->sum('available_tickets') > 0)
                                    <a href="{{ route('orders.create', $event) }}" class="btn btn-primary w-100 mt-3">
                                        <i class="fas fa-shopping-cart"></i> Buy Tickets
                                    </a>
                                @else
                                    <button class="btn btn-secondary w-100 mt-3" disabled>
                                        <i class="fas fa-hourglass-end"></i> Sold Out
                                    </button>
                                    <a href="{{ route('orders.create', $event) }}?waiting_list=true"
                                        class="btn btn-outline-primary w-100 mt-2">
                                        Join Waiting List
                                    </a>
                                @endif
                            @elseif(!auth()->check())
                                <a href="{{ route('login') }}" class="btn btn-primary w-100 mt-3">
                                    Login to Buy Tickets
                                </a>
                            @else
                                <button class="btn btn-secondary w-100 mt-3" disabled>
                                    Event Not Available
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Stats -->
                    @if ($event->tickets_sold ?? false)
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title">Sales Info</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-2">
                                    <strong>Tickets Sold:</strong> {{ $event->tickets_sold }}
                                </p>
                                <p class="mb-2">
                                    <strong>Available:</strong> {{ $event->available_tickets }}
                                </p>
                                @if ($event->total_revenue ?? false)
                                    <p>
                                        <strong>Revenue:</strong> ${{ number_format($event->total_revenue, 2) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
