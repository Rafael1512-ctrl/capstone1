@extends('layouts.master')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
            <div>
                <h1 class="h3 mb-0">{{ $event->title }}</h1>
                <p class="text-muted small">
                    <i class="fas fa-calendar-alt"></i> {{ $event->date->format('d M Y H:i') }} |
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
                    <img src="{{ $event->banner_url }}" alt="Banner" class="img-fluid rounded">
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
                            <dd class="col-sm-7">{{ $event->organizer->name }}</dd>

                            <dt class="col-sm-5">Dibuat:</dt>
                            <dd class="col-sm-7">{{ $event->created_at->format('d M Y') }}</dd>
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
            <div class="card-header d-flex justify-content-between">
                <h5 class="card-title mb-0">Jenis Tiket</h5>
                <a href="{{ route('admin.events.manage-tickets', $event) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-edit"></i> Kelola
                </a>
            </div>
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
