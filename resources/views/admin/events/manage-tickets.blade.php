@extends('layouts.master')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
            <div>
                <h1 class="h3 mb-0">Kelola Tiket: {{ $event->title }}</h1>
                <p class="text-muted small">Atur jenis dan kuota tiket untuk event ini</p>
            </div>
            <a href="{{ route('admin.events.show', $event) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Add New Ticket Type -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Tambah Jenis Tiket Baru</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.events.store-ticket', $event) }}" method="POST" class="row g-3">
                    @csrf

                    <div class="col-md-3">
                        <label class="form-label">Nama Tiket *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                            placeholder="misal: VIP, Regular" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Harga *</label>
                        <input type="number" class="form-control @error('price') is-invalid @enderror" name="price"
                            placeholder="0" step="1000" min="0" value="{{ old('price', 0) }}" required>
                        @error('price')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Kuota Tiket *</label>
                        <input type="number" class="form-control @error('quantity_total') is-invalid @enderror"
                            name="quantity_total" placeholder="0" min="1" value="{{ old('quantity_total', 100) }}"
                            required>
                        @error('quantity_total')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-plus"></i> Tambah
                        </button>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Deskripsi (Optional)</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="2"
                            placeholder="Deskripsi jenis tiket">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </form>
            </div>
        </div>

        <!-- Ticket Types List -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Daftar Jenis Tiket</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Harga</th>
                            <th>Kuota</th>
                            <th>Terjual</th>
                            <th>Tersedia</th>
                            <th>Persentase</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($event->ticketTypes as $ticket)
                            <tr>
                                <td><strong>{{ $ticket->name }}</strong></td>
                                <td><small class="text-muted">{{ $ticket->description ?? '-' }}</small></td>
                                <td>Rp {{ number_format($ticket->price, 0, ',', '.') }}</td>
                                <td>{{ $ticket->quantity_total }}</td>
                                <td>{{ $ticket->quantity_sold }}</td>
                                <td>{{ $ticket->availableStock() }}</td>
                                <td>
                                    @php
                                        $sold_percent =
                                            $ticket->quantity_total > 0
                                                ? round(($ticket->quantity_sold / $ticket->quantity_total) * 100, 1)
                                                : 0;
                                    @endphp
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" style="width: {{ $sold_percent }}%">
                                            {{ $sold_percent }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $ticket->id }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @if ($ticket->quantity_sold == 0)
                                            <form action="{{ route('admin.events.delete-ticket', [$event, $ticket->id]) }}"
                                                method="POST" style="display:inline;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Hapus"
                                                    onclick="return confirm('Yakin ingin menghapus?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal{{ $ticket->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.events.update-ticket', $event) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Tiket: {{ $ticket->name }}</h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Tiket</label>
                                                    <input type="text" class="form-control" name="name"
                                                        value="{{ $ticket->name }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Harga</label>
                                                    <input type="number" class="form-control" name="price"
                                                        step="1000" min="0" value="{{ $ticket->price }}"
                                                        required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Kuota Total</label>
                                                    <input type="number" class="form-control" name="quantity_total"
                                                        min="{{ $ticket->quantity_sold }}"
                                                        value="{{ $ticket->quantity_total }}" required>
                                                    <small class="text-muted">Minimum: {{ $ticket->quantity_sold }} (tiket
                                                        yang sudah terjual)</small>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    Belum ada jenis tiket. Tambahkan jenis tiket di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
