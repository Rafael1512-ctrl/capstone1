@extends('layouts.master')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
            <h1 class="h3 mb-0">Kelola Events</h1>
            <a href="{{ route('admin.events.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus"></i> Event Baru
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filters -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" class="form-control border-start-0" name="search" placeholder="Cari judul atau lokasi..."
                                value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="status">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>🟢 Active (Published)</option>
                            <option value="non-active" {{ request('status') == 'non-active' ? 'selected' : '' }}>🔴 Non-Active (Draft/Cancel)</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            Apply Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Events Table -->
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Status</th>
                            <th>Judul Event</th>
                            <th>Kategori</th>
                            <th>Organizer</th>
                            <th>Jadwal</th>
                            <th>Lokasi</th>
                            <th>Tiket Terjual</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                            <tr>
                                <td class="ps-4">
                                    @if ($event->status === 'published')
                                        <i class="fas fa-circle text-success" title="Active"></i>
                                        <span class="ms-1 small text-muted">Active</span>
                                    @else
                                        <i class="fas fa-circle text-danger" title="Non-Active"></i>
                                        <span class="ms-1 small text-muted">Non-Active</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $event->title }}</strong>
                                    <br>
                                    <small class="text-muted small">ID: {{ $event->event_id }}</small>
                                </td>
                                <td>
                                    @if ($event->category)
                                        <span class="badge bg-info text-white">{{ $event->category->name }}</span>
                                        @if (strtolower($event->category->name) === 'festival' && $event->performers && count($event->performers) > 0)
                                            <br>
                                            <small class="text-success mt-1 d-inline-block">
                                                <i class="fas fa-users"></i> {{ count($event->performers) }} Performer
                                            </small>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $event->organizer->name ?? '-' }}</td>
                                <td>
                                    {{ $event->schedule_time->format('d M Y') }}<br>
                                    <small class="text-muted">{{ $event->schedule_time->format('H:i') }}</small>
                                </td>
                                <td>
                                    <span title="{{ $event->location }}">{{ Str::limit($event->location, 20) }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">{{ $event->ticketTypes->sum('quantity_sold') }}</span>
                                        <div class="progress flex-grow-1" style="height: 5px; min-width: 50px;">
                                            @php
                                                $total = $event->ticketTypes->sum('quantity_total');
                                                $sold = $event->ticketTypes->sum('quantity_sold');
                                                $percent = $total > 0 ? ($sold / $total) * 100 : 0;
                                            @endphp
                                            <div class="progress-bar" style="width: {{ $percent }}%"></div>
                                        </div>
                                    </div>
                                    <small class="text-muted smaller">dari {{ $total }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group shadow-sm">
                                        <a href="{{ route('admin.events.show', $event->event_id) }}" class="btn btn-white btn-sm text-info"
                                            title="View Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.events.edit', $event->event_id) }}" class="btn btn-white btn-sm text-warning"
                                            title="Edit Event">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.events.destroy', $event->event_id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-white btn-sm text-danger" title="Delete"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus event ini?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="fas fa-calendar-times mb-3 fa-3x d-block"></i>
                                    Tidak ada event yang ditemukan sesuai kriteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-footer bg-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="small text-muted">
                        Menampilkan {{ $events->firstItem() ?? 0 }} s/d {{ $events->lastItem() ?? 0 }} dari {{ $events->total() }} event
                    </div>
                    {{ $events->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
