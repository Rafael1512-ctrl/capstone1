@extends('layouts.master')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
            <h1 class="h3 mb-0">Edit Event</h1>
            <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

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

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="organizer_id" class="form-label">Organizer *</label>
                            <select class="form-select @error('organizer_id') is-invalid @enderror" name="organizer_id"
                                id="organizer_id" required>
                                @foreach ($organizers as $org)
                                    <option value="{{ $org->user_id }}"
                                        {{ $event->organizer_id == $org->user_id ? 'selected' : '' }}>
                                        {{ $org->nama_lengkap }} ({{ $org->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('organizer_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="category_id" class="form-label">Kategori *</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" name="category_id"
                                id="category_id" required>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->category_id }}"
                                        {{ $event->category_id == $cat->category_id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="title" class="form-label">Judul Event *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title"
                                id="title" value="{{ $event->title }}" required>
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="schedule_time" class="form-label">Tanggal & Waktu *</label>
                            <input type="datetime-local" class="form-control @error('schedule_time') is-invalid @enderror"
                                name="schedule_time" id="schedule_time"
                                value="{{ $event->schedule_time->format('Y-m-d\TH:i') }}" required>
                            @error('schedule_time')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">Lokasi *</label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror" name="location"
                            id="location" value="{{ $event->location }}" required>
                        @error('location')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description"
                            rows="4" required>{{ $event->description }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="banner" class="form-label">Banner Event</label>
                            @if ($event->banner_url)
                                <div class="mb-2">
                                    <img src="{{ $event->banner_url }}" alt="Banner"
                                        style="max-width: 200px; max-height: 150px;">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('banner') is-invalid @enderror" name="banner"
                                id="banner" accept="image/*">
                            <small class="text-muted">Ganti banner atau biarkan kosong</small>
                            @error('banner')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-9">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select @error('status') is-invalid @enderror" name="status" id="status"
                                required>
                                <option value="draft" {{ $event->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ $event->status === 'published' ? 'selected' : '' }}>Published
                                </option>
                                <option value="cancelled" {{ $event->status === 'cancelled' ? 'selected' : '' }}>Cancelled
                                </option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Ticket Types Section -->
                    <hr class="my-4">
                    <h5 class="mb-3">Jenis Tiket & Kuota</h5>
                    <p class="text-muted small mb-4">Atur kuota dan harga untuk setiap jenis tiket</p>

                    @php
                        // Get existing ticket types by name
                        $regularTicket = $event->ticketTypes->where('name', 'Regular')->first();
                        $vipTicket = $event->ticketTypes->where('name', 'VIP')->first();
                        $vvipTicket = $event->ticketTypes->where('name', 'VVIP')->first();
                    @endphp

                    <!-- Regular Ticket Type -->
                    <div class="card mb-3 border-start border-4" style="border-color: #6c757d !important;">
                        <div class="card-body">
                            <h6 class="card-title mb-3">
                                <i class="fas fa-tag"></i> Tiket Regular
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="regular_quota" class="form-label">Kuota Tiket *</label>
                                    <input type="number"
                                        class="form-control @error('regular_quota') is-invalid @enderror"
                                        name="regular_quota" id="regular_quota" placeholder="Jumlah tiket"
                                        value="{{ old('regular_quota', $regularTicket?->quantity_total ?? 0) }}"
                                        min="0" required>
                                    @error('regular_quota')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="regular_price" class="form-label">Harga (Rp) *</label>
                                    <input type="number"
                                        class="form-control @error('regular_price') is-invalid @enderror"
                                        name="regular_price" id="regular_price" placeholder="0"
                                        value="{{ old('regular_price', $regularTicket?->price ?? 0) }}" min="0"
                                        step="1000" required>
                                    @error('regular_price')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- VIP Ticket Type -->
                    <div class="card mb-3 border-start border-4" style="border-color: #ffc107 !important;">
                        <div class="card-body">
                            <h6 class="card-title mb-3">
                                <i class="fas fa-crown"></i> Tiket VIP
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="vip_quota" class="form-label">Kuota Tiket *</label>
                                    <input type="number" class="form-control @error('vip_quota') is-invalid @enderror"
                                        name="vip_quota" id="vip_quota" placeholder="Jumlah tiket"
                                        value="{{ old('vip_quota', $vipTicket?->quantity_total ?? 0) }}" min="0"
                                        required>
                                    @error('vip_quota')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="vip_price" class="form-label">Harga (Rp) *</label>
                                    <input type="number" class="form-control @error('vip_price') is-invalid @enderror"
                                        name="vip_price" id="vip_price" placeholder="0"
                                        value="{{ old('vip_price', $vipTicket?->price ?? 0) }}" min="0"
                                        step="1000" required>
                                    @error('vip_price')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- VVIP Ticket Type -->
                    <div class="card mb-3 border-start border-4" style="border-color: #dc3545 !important;">
                        <div class="card-body">
                            <h6 class="card-title mb-3">
                                <i class="fas fa-gem"></i> Tiket VVIP
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="vvip_quota" class="form-label">Kuota Tiket *</label>
                                    <input type="number" class="form-control @error('vvip_quota') is-invalid @enderror"
                                        name="vvip_quota" id="vvip_quota" placeholder="Jumlah tiket"
                                        value="{{ old('vvip_quota', $vvipTicket?->quantity_total ?? 0) }}" min="0"
                                        required>
                                    @error('vvip_quota')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="vvip_price" class="form-label">Harga (Rp) *</label>
                                    <input type="number" class="form-control @error('vvip_price') is-invalid @enderror"
                                        name="vvip_price" id="vvip_price" placeholder="0"
                                        value="{{ old('vvip_price', $vvipTicket?->price ?? 0) }}" min="0"
                                        step="1000" required>
                                    @error('vvip_price')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Event
                        </button>
                        <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
