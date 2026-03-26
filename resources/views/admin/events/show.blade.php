@extends('layouts.master')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
            <div>
                <h1 class="h3 mb-0">{{ $event->title }}</h1>
                <p class="text-muted small">
                    <i class="fas fa-calendar-alt"></i> {{ $event->schedule_time->format('d M Y H:i') }} |
                    <i class="fas fa-map-marker-alt"></i> {{ $event->location }}
                </p>
            </div>
            <div class="btn-group">
                <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Banner & Status -->
        <div class="row mb-4">
            <div class="col-md-8">
                @if ($event->banner_url)
                    <img src="{{ asset($event->banner_url) }}" alt="Banner" class="img-fluid rounded shadow-sm" style="max-height: 400px; width: 100%; object-fit: cover;">
                @else
                    <div class="bg-light rounded p-4 text-center text-muted">
                        <i class="fas fa-image" style="font-size: 3rem;"></i>
                        <p>Banner tidak tersedia</p>
                    </div>
                @endif
            </div>
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Informasi Event</h5>
                        <dl class="row">
                            <dt class="col-sm-5">Status:</dt>
                            <dd class="col-sm-7">
                                @if ($event->status === 'published')
                                    <span class="badge bg-success">Published</span>
                                @elseif($event->status === 'draft')
                                    <span class="badge bg-warning">Draft</span>
                                @else
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </dd>

                            <dt class="col-sm-5">Kategori:</dt>
                            <dd class="col-sm-7">
                                @if ($event->category)
                                    <span class="badge" style="background-color: {{ $event->category->color }}">
                                        {{ $event->category->name }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </dd>

                            <dt class="col-sm-5">Organizer:</dt>
                            <dd class="col-sm-7">{{ $event->organizer->name ?? 'N/A' }}</dd>

                            <dt class="col-sm-5">ID Event:</dt>
                            <dd class="col-sm-7">{{ $event->event_id }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Deskripsi Event</h5>
            </div>
            <div class="card-body">
                {{ $event->description }}
            </div>
        </div>

        <!-- Performers Section (Only for Festival) -->
        @if ($event->category && strtolower($event->category->name) === 'festival' && $event->performers && count($event->performers) > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users"></i> Daftar Performer
                    </h5>
                </div>
                <div class="card-body">
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
            </div>
        @endif

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card border-left-success">
                    <div class="card-body text-center">
                        <h2 class="h4 text-success">{{ $totalTicketsSold }}</h2>
                        <p class="text-muted small mb-0">Tiket Terjual</p>
                        <small class="text-muted">dari {{ $totalTicketsAvailable }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-left-primary">
                    <div class="card-body text-center">
                        <h2 class="h4 text-primary">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                        <p class="text-muted small mb-0">Total Revenue</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-left-info">
                    <div class="card-body text-center">
                        @php
                            $availability =
                                $totalTicketsAvailable > 0
                                    ? round(
                                        (($totalTicketsAvailable - $totalTicketsSold) / $totalTicketsAvailable) * 100,
                                        1,
                                    )
                                    : 0;
                        @endphp
                        <h2 class="h4 text-info">{{ $availability }}%</h2>
                        <p class="text-muted small mb-0">Ketersediaan</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-left-warning">
                    <div class="card-body text-center">
                        <h2 class="h4 text-warning">{{ $event->orders->count() }}</h2>
                        <p class="text-muted small mb-0">Total Orders</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ticket Types -->
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Tiket</th>
                            <th>Harga</th>
                            <th>Tersedia</th>
                            <th>Terjual</th>
                            <th>Persentase</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($event->ticketTypes as $type)
                            <tr>
                                <td><strong>{{ $type->name }}</strong></td>
                                <td>Rp {{ number_format($type->price, 0, ',', '.') }}</td>
                                <td>{{ $type->availableStock() }}</td>
                                <td>{{ $type->quantity_sold }}</td>
                                <td>
                                    @php
                                        $sold_percent =
                                            $type->quantity_total > 0
                                                ? round(($type->quantity_sold / $type->quantity_total) * 100, 1)
                                                : 0;
                                    @endphp
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" style="width: {{ $sold_percent }}%">
                                            {{ $sold_percent }}%
                                        </div>
                                    </div>
                                </td>
                                <td>Rp {{ number_format($type->quantity_sold * $type->price, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">Belum ada jenis tiket</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .border-left-success {
            border-left: 4px solid #28a745 !important;
        }

        .border-left-primary {
            border-left: 4px solid #007bff !important;
        }

        .border-left-info {
            border-left: 4px solid #17a2b8 !important;
        }

        .border-left-warning {
            border-left: 4px solid #ffc107 !important;
        }
    </style>
@endsection
