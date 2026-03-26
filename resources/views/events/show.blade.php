@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="page-inner">
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="page-title">{{ $event->title }}</h4>
                        @if ($event->category)
                            <span class="badge badge-primary">{{ $event->category->name }}</span>
                        @endif
                    </div>
                    @if (auth()->user() && (auth()->user()->user_id === $event->organizer_id || auth()->user()->isAdmin()))
                        <div class="btn-group">
                            <a href="{{ route('events.edit', $event->event_id) }}" class="btn btn-warning">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('ticket-categories.index', $event->event_id) }}" class="btn btn-info">
                                <i class="fa fa-ticket"></i> Kelola Tiket
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <!-- Main Content -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <!-- Banner -->
                        @if ($event->banner_url)
                            @php
                                if (str_starts_with($event->banner_url, '/storage/')) {
                                    $bannerSrc = $event->banner_url;
                                } elseif (str_starts_with($event->banner_url, '/')) {
                                    $bannerSrc = asset($event->banner_url);
                                } else {
                                    $bannerSrc = Storage::url($event->banner_url);
                                }
                            @endphp
                            <img src="{{ $bannerSrc }}" class="card-img-top" alt="{{ $event->title }}"
                                style="height: 400px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                style="height: 400px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="fas fa-ticket fa-5x text-white"></i>
                            </div>
                        @endif

                        <div class="card-body">
                            <!-- Event Information Grid -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-muted small">Tanggal & Waktu</label>
                                        <p class="mb-0">
                                            <i class="fas fa-calendar-alt"></i>
                                            <strong>
                                                @if ($event->schedule_time)
                                                    {{ $event->schedule_time->format('d F Y - H:i') }}
                                                @else
                                                    TBA
                                                @endif
                                            </strong>
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="text-muted small">Lokasi</label>
                                        <p class="mb-0">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <strong>{{ $event->location }}</strong>
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="text-muted small">Kuota Tiket</label>
                                        <p class="mb-0">
                                            <i class="fas fa-ticket-alt"></i>
                                            <strong>{{ $event->ticket_quota }} tiket</strong>
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-muted small">Status</label>
                                        <p class="mb-0">
                                            @if ($event->status === 'published')
                                                <span class="badge badge-success">
                                                    <i class="fa fa-check"></i> Dipublikasikan
                                                </span>
                                            @elseif($event->status === 'draft')
                                                <span class="badge badge-warning">
                                                    <i class="fa fa-file"></i> Draft
                                                </span>
                                            @else
                                                <span class="badge badge-danger">
                                                    <i class="fa fa-ban"></i> Dibatalkan
                                                </span>
                                            @endif
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="text-muted small">Penyelenggara</label>
                                        <p class="mb-0">
                                            <strong>{{ $event->organizer?->nama_lengkap ?? 'N/A' }}</strong>
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="text-muted small">Jumlah Tipe Tiket</label>
                                        <p class="mb-0">
                                            <strong>{{ $event->ticketTypes->count() }} tipe</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Performers Section (Only for Festival) -->
                            @if ($event->category && strtolower($event->category->name) === 'festival' && $event->performers && count($event->performers) > 0)
                                <div class="performers-section mb-4">
                                    <h5 class="mb-3">
                                        <i class="fas fa-users"></i> Daftar Performer
                                    </h5>
                                    <div class="row">
                                        @foreach ($event->performers as $performer)
                                            <div class="col-md-6 mb-3">
                                                <div class="card border-0 shadow-sm h-100">
                                                    @if (isset($performer['photo']) && $performer['photo'])
                                                        <img src="{{ $performer['photo'] }}" class="card-img-top"
                                                            alt="{{ $performer['name'] }}"
                                                            style="height: 200px; object-fit: cover;">
                                                    @else
                                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                                            style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                            <i class="fas fa-music fa-3x text-white opacity-50"></i>
                                                        </div>
                                                    @endif
                                                    <div class="card-body">
                                                        <h6 class="card-title mb-1">{{ $performer['name'] }}</h6>
                                                        <p class="text-muted small mb-2">
                                                            <i class="fas fa-microphone-alt"></i>
                                                            {{ $performer['role'] }}
                                                        </p>
                                                        @if (isset($performer['description']) && $performer['description'])
                                                            <p class="card-text small">{{ $performer['description'] }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <hr class="my-4">
                            @endif

                        <!-- Description & Map -->
                        <div class="row mt-4">
                            <div class="col-md-7">
                                <h5>Deskripsi Event</h5>
                                <p>{{ $event->description }}</p>
                            </div>
                            <div class="col-md-5">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-map-marked-alt text-primary"></i> Venue Map
                                        </h6>
                                    </div>
                                    <div class="card-body p-0">
                                        @if ($event->maps_url)
                                            <iframe src="{{ $event->maps_url }}" width="100%" height="250"
                                                style="border:0;" allowfullscreen="" loading="lazy">
                                            </iframe>
                                        @else
                                            <div class="p-4 text-center text-muted">
                                                <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
                                                <p class="small">Link Google Maps belum diset.</p>
                                                <a href="{{ route('events.edit', $event->event_id) }}"
                                                    class="btn btn-xs btn-outline-primary">Set Map</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Sidebar -->
                <div class="col-md-4">
                    <!-- Ticket Types Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Tipe & Harga Tiket</h5>
                        </div>
                        <div class="card-body">
                            @if ($event->ticketTypes->count() > 0)
                                @foreach ($event->ticketTypes as $type)
                                    <div class="ticket-type-item mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="mb-0">{{ $type->name }}</h6>
                                            <span
                                                class="badge badge-info">Rp{{ number_format($type->price, 0, ',', '.') }}</span>
                                        </div>
                                        <p class="text-muted small mb-2">
                                            Stok:
                                            <strong>{{ $type->quantity_total - $type->quantity_sold }}/{{ $type->quantity_total }}</strong>
                                        </p>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-primary"
                                                style="width: {{ ($type->quantity_sold / $type->quantity_total) * 100 }}%">
                                            </div>
                                        </div>
                                    </div>
                                    @if (!$loop->last)
                                        <hr class="my-2">
                                    @endif
                                @endforeach

                                @if ($event->status === 'published' && auth()->check())
                                    <a href="{{ route('orders.create', $event->event_id) }}"
                                        class="btn btn-primary btn-block mt-3">
                                        <i class="fa fa-shopping-cart"></i> Beli Tiket
                                    </a>
                                @elseif(!auth()->check())
                                    <a href="{{ route('login') }}" class="btn btn-primary btn-block mt-3">
                                        Login untuk Beli Tiket
                                    </a>
                                @else
                                    <button class="btn btn-secondary btn-block mt-3" disabled>
                                        Event tidak tersedia
                                    </button>
                                @endif
                            @else
                                <p class="text-muted text-center">
                                    <i class="fa fa-info-circle"></i> Belum ada tipe tiket
                                </p>
                                @if (auth()->user() && auth()->user()->user_id === $event->organizer_id)
                                    <a href="{{ route('ticket-categories.create', $event->event_id) }}"
                                        class="btn btn-sm btn-primary btn-block">
                                        Tambah Tipe Tiket
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- Event Stats -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Statistik</h5>
                        </div>
                        <div class="card-body">
                            <div class="stat-item mb-3">
                                <label class="text-muted small">Total Tiket Terjual</label>
                                <p class="mb-0">
                                    <strong class="text-primary text-lg">
                                        {{ $event->orders()->where('status', 'completed')->count() }}
                                    </strong>
                                </p>
                            </div>

                            <div class="stat-item mb-3">
                                <label class="text-muted small">Tiket Tersisa</label>
                                <p class="mb-0">
                                    <strong class="text-success text-lg">
                                        {{ $event->ticketTypes->sum('quantity_total') - $event->ticketTypes->sum('quantity_sold') }}
                                    </strong>
                                </p>
                            </div>

                            <div class="stat-item">
                                <label class="text-muted small">Total Revenue</label>
                                <p class="mb-0">
                                    <strong class="text-info text-lg">
                                        Rp{{ number_format($event->orders()->where('status', 'completed')->sum('total_amount') ?? 0, 0, ',', '.') }}
                                    </strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .ticket-type-item {
            padding: 10px;
            border-radius: 4px;
            background-color: #f8f9fa;
        }

        .stat-item {
            padding: 10px 0;
        }

        .btn-block {
            display: block;
            width: 100%;
        }
    </style>
@endsection
