@extends('layouts.master')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
            <h1 class="h3 mb-0">Event Performance Analytics</h1>
            <div>
                <a href="{{ route('admin.export.event-performance') }}" class="btn btn-outline-success">
                    <i class="fas fa-download"></i> Export CSV
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
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

        <!-- Table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Event</th>
                            <th>Kategori</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Tiket Kuota</th>
                            <th>Tiket Terjual</th>
                            <th>Fill Rate</th>
                            <th>Revenue</th>
                            <th>Orders</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.events.show', $event['event_id']) }}">
                                        <strong>{{ $event['name'] }}</strong>
                                    </a>
                                </td>
                                <td>{{ $event['category'] ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($event['date'])->format('d M Y') }}</td>
                                <td>
                                    @if ($event['status'] === 'Tersedia')
                                        <span class="badge bg-success">Tersedia</span>
                                    @elseif($event['status'] === 'Draft')
                                        <span class="badge bg-warning">Draft</span>
                                    @elseif($event['status'] === 'Selesai')
                                        <span class="badge bg-info">Selesai</span>
                                    @else
                                        <span class="badge bg-danger">Batal</span>
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Tidak ada event</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
