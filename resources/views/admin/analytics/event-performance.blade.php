@extends('layouts.master')

@section('content')
    <div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-5 mt-2">
        <div>
            <h1 class="page-title mb-0">Event Performance</h1>
            <p class="text-muted small mb-0">Evaluate event success and fill rates</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.export.event-performance') }}" class="btn btn-info px-4">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </a>
            <a href="{{ route('admin.export.event-performance-pdf') }}" class="btn btn-primary px-4">
                <i class="fas fa-file-pdf me-1"></i> Export PDF
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary px-4">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

        <!-- Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-6">
                        <select class="form-select" name="category" onchange="this.form.submit()">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->category_id }}" {{ request('category') == $cat->category_id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Event</th>
                            <th>Kategori</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Tiket Kuota</th>
                            <th>Tiket Terjual</th>
                            <th>Fill Rate</th>
                            <th>Revenue</th>
                            <th>Orders</th>
                            <th class="pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                            <tr>
                                <td class="ps-4">
                                    <a href="{{ route('admin.events.show', $event['event_id']) }}" class="text-decoration-none fw-bold text-white">
                                        {{ $event['name'] }}
                                    </a>
                                </td>
                                <td>{{ $event['category'] ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($event['date'])->format('d M Y') }}</td>
                                <td>
                                    @if (in_array(strtolower($event['status']), ['active', 'published', 'tersedia']))
                                        <span class="badge bg-success">Active</span>
                                    @elseif(in_array(strtolower($event['status']), ['draft', 'non-active']))
                                        <span class="badge bg-warning text-dark">Draft / Scheduled</span>
                                    @elseif(strtolower($event['status']) === 'overdue' || strtolower($event['status']) === 'selesai')
                                        <span class="badge bg-info">Selesai (Overdue)</span>
                                    @else
                                        <span class="badge bg-danger">{{ ucfirst($event['status']) }}</span>
                                    @endif
                                </td>
                                <td>{{ $event['total_available'] }}</td>
                                <td>{{ $event['total_sold'] }}</td>
                                <td>
                                    <div class="progress" style="height: 20px; width: 100px;">
                                        <div class="progress-bar" style="width: {{ $event['availability_rate'] }}%">
                                            {{ $event['availability_rate'] }}%
                                        </div>
                                    </div>
                                </td>
                                <td>Rp {{ number_format($event['revenue'], 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $event['orders_count'] }}</span>
                                </td>
                                <td class="pe-4">
                                    <a href="{{ route('admin.analytics.event-detail', $event['event_id']) }}" class="btn btn-sm btn-info text-white">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">Tidak ada event</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
