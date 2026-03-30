@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title">Daftar Event</h4>
                <ul class="breadcrumbs">
                    <li class="nav-home">
                        <a href="{{ route('organizer.dashboard') }}">
                            <span class="icon-breadcrumb">/</span>
                            Dashboard
                        </a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Event Terpublikasi</a>
                    </li>
                </ul>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Event Terpublikasi</h5>
                                @if (auth()->user()?->isOrganizer() || auth()->user()?->isAdmin())
                                    <a href="{{ route('events.create') }}" class="btn btn-primary btn-rounded">
                                        <i class="fa fa-plus"></i> Buat Event Baru
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="card-body">
                            @if ($events->count() > 0)
                                <div class="row">
                                    @foreach ($events as $event)
                                        <div class="col-md-6 col-lg-4 mb-4">
                                            <div class="card h-100 event-card">
                                                <!-- Banner Event -->
                                                @php
                                                    $banner = $event->banner_url;
                                                    if ($banner) {
                                                        if (filter_var($banner, FILTER_VALIDATE_URL)) {
                                                            $imgSrc = $banner;
                                                        } else {
                                                            $imgSrc = str_starts_with($banner, '/') ? asset($banner) : Storage::url($banner);
                                                        }
                                                    } else {
                                                        $imgSrc = null;
                                                    }
                                                @endphp

                                                @if ($imgSrc)
                                                    <img src="{{ $imgSrc }}" class="card-img-top"
                                                        alt="{{ $event->title }}" style="height: 200px; object-fit: cover;"
                                                        onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&q=80&w=800';">
                                                @else
                                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                                        style="height: 200px; background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);">
                                                        <i class="fas fa-ticket fa-3x text-danger opacity-50"></i>
                                                    </div>
                                                @endif

                                                <div class="card-body">
                                                    <!-- Kategori -->
                                                    @if ($event->category)
                                                        <div class="mb-2">
                                                            <span
                                                                class="badge badge-primary">{{ $event->category->name }}</span>
                                                        </div>
                                                    @endif

                                                    <!-- Judul -->
                                                    <h5 class="card-title">{{ $event->title }}</h5>

                                                    <!-- Deskripsi -->
                                                    <p class="card-text text-muted small">
                                                        {{ Str::limit($event->description, 80) }}
                                                    </p>

                                                    <!-- Info Event -->
                                                    <div class="event-info small">
                                                        <p class="mb-2 text-muted">
                                                            <i class="fas fa-calendar-alt"></i>
                                                            {{ $event->schedule_time ? $event->schedule_time->format('d M Y') : 'TBA' }}
                                                        </p>
                                                        <p class="mb-2 text-muted">
                                                            <i class="fas fa-map-marker-alt"></i>
                                                            {{ Str::limit($event->location, 40) }}
                                                        </p>
                                                        @php
                                                            $isFestival = $event->category && (
                                                                strtolower($event->category->name) === 'festival' || 
                                                                str_contains(strtolower($event->category->name), 'festival')
                                                            );
                                                        @endphp
                                                        @if ($isFestival && $event->performers && count($event->performers) > 0)
                                                            <p class="mb-2 text-info">
                                                                <i class="fas fa-users"></i>
                                                                <strong>{{ count($event->performers) }} Performer</strong>
                                                            </p>
                                                        @endif
                                                        <p class="mb-0 text-muted">
                                                            <i class="fas fa-ticket-alt"></i>
                                                            @if ($event->ticketTypes->count() > 0)
                                                                <strong>Mulai dari:
                                                                    Rp{{ number_format($event->ticketTypes->min('price'), 0, ',', '.') }}</strong>
                                                            @else
                                                                <span class="text-warning">Belum ada tipe tiket</span>
                                                            @endif
                                                        </p>
                                                    </div>

                                                    <!-- Status Kuota -->
                                                    @if ($event->ticket_quota)
                                                        <div class="mt-3">
                                                            <small class="text-muted">Kuota: {{ $event->ticket_quota }}
                                                                tiket</small>
                                                            <div class="progress mt-1" style="height: 6px;">
                                                                <div class="progress-bar bg-success" style="width: 100%">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="card-footer bg-white border-top-0">
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('events.show', $event->event_id) }}"
                                                            class="btn btn-primary btn-sm flex-fill">
                                                            <i class="fa fa-eye"></i> Lihat Detail
                                                        </a>
                                                        @if ($event->organizer_id === auth()->id() || auth()->user()->isAdmin())
                                                            <a href="{{ route('events.edit', $event->event_id) }}"
                                                                class="btn btn-info btn-sm">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Pagination -->
                                @if ($events->hasPages())
                                    <div class="d-flex justify-content-center mt-4">
                                        {{ $events->links('pagination::bootstrap-4') }}
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i> Belum ada event.
                                    <a href="{{ route('events.create') }}">Buat event sekarang</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .event-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .event-info p {
            margin-bottom: 8px;
        }
    </style>
@endsection
