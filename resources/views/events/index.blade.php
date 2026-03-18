@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title">Events</h4>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Upcoming Events</h5>
                                @if (auth()->user()?->isOrganizer())
                                    <a href="{{ route('events.create') }}" class="btn btn-primary btn-sm">Create Event</a>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @forelse($events ?? [] as $event)
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card h-100">
                                            @if ($event->image)
                                                <img src="{{ asset('storage/' . $event->image) }}" class="card-img-top"
                                                    alt="{{ $event->title }}" style="height: 200px; object-fit: cover;">
                                            @else
                                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                                    style="height: 200px;">
                                                    <i class="fas fa-image fa-3x text-muted"></i>
                                                </div>
                                            @endif
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $event->title }}</h5>
                                                <p class="card-text text-muted">
                                                    {{ Str::limit($event->brief_description, 100) }}</p>
                                                <p class="mb-1">
                                                    <small><i class="fas fa-calendar"></i>
                                                        {{ $event->event_date->format('M d, Y') }}</small>
                                                </p>
                                                <p class="mb-2">
                                                    <small><i class="fas fa-map-marker-alt"></i>
                                                        {{ $event->location }}</small>
                                                </p>
                                                <p class="text-primary fw-bold">
                                                    ${{ number_format($event->base_price, 2) }}
                                                </p>
                                            </div>
                                            <div class="card-footer bg-white">
                                                <a href="{{ route('events.show', $event) }}"
                                                    class="btn btn-primary btn-sm w-100">
                                                    View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-md-12">
                                        <div class="alert alert-info">No events available</div>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Pagination -->
                            {{ $events->links() ?? '' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
