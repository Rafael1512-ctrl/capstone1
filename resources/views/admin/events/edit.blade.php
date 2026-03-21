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
                            <label for="ticket_quota" class="form-label">Kuota Tiket *</label>
                            <input type="number" class="form-control @error('ticket_quota') is-invalid @enderror" name="ticket_quota"
                                id="ticket_quota" value="{{ $event->ticket_quota }}" min="1" required>
                            @error('ticket_quota')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

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

                        <div class="col-md-6">
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
