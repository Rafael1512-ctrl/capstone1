@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title">Edit Event</h4>
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
                        <a href="{{ route('events.index') }}">Events</a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Edit Event</a>
                    </li>
                </ul>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Ubah Informasi Event</div>
                        </div>

                        <form action="{{ route('events.update', $event->event_id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <!-- Judul Event -->
                                        <div class="form-group">
                                            <label for="title">Judul Event <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                                id="title" name="title" placeholder="Contoh: Tulus Concert 2026"
                                                required maxlength="200" value="{{ old('title', $event->title) }}">
                                            @error('title')
                                                <small class="text-danger d-block mt-2">{{ $message }}</small>
                                            @enderror
                                            <small class="form-text text-muted">Max 200 karakter</small>
                                        </div>

                                        <!-- Deskripsi Event -->
                                        <div class="form-group">
                                            <label for="description">Deskripsi Event <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                                rows="6" placeholder="Jelaskan detail event Anda..." required maxlength="1000">{{ old('description', $event->description) }}</textarea>
                                            @error('description')
                                                <small class="text-danger d-block mt-2">{{ $message }}</small>
                                            @enderror
                                            <small class="form-text text-muted">Max 1000 karakter</small>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <!-- Banner Upload -->
                                        <div class="form-group">
                                            <label for="banner_url">Banner Event <span
                                                    class="text-muted">(Opsional)</span></label>

                                            <!-- Current Banner Preview -->
                                            @if ($event->banner_url)
                                                <div class="mb-3">
                                                    <img src="{{ Storage::url($event->banner_url) }}" alt="Banner Event"
                                                        style="max-height: 160px; max-width: 100%; border-radius: 4px;">
                                                    <small class="form-text text-muted d-block mt-2">Banner saat ini</small>
                                                </div>
                                            @endif

                                            <!-- File Input -->
                                            <div class="custom-file-upload">
                                                <input type="file"
                                                    class="form-control-file @error('banner_url') is-invalid @enderror"
                                                    id="banner_url" name="banner_url"
                                                    accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                                <small class="form-text text-muted d-block mt-2">
                                                    Format: JPG, PNG, GIF, WebP | Max 5MB<br>
                                                    <em>Kosongkan jika ingin tetap gunakan banner lama</em>
                                                </small>
                                            </div>
                                            @error('banner_url')
                                                <small class="text-danger d-block mt-2">{{ $message }}</small>
                                            @enderror

                                            <!-- New Banner Preview -->
                                            <div id="bannerPreview" style="margin-top: 15px;">
                                                <img id="previewImg"
                                                    style="max-height: 160px; max-width: 100%; border-radius: 4px; display: none;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Kategori Event -->
                                        <div class="form-group">
                                            <label for="category_id">Kategori Event <span
                                                    class="text-muted">(Opsional)</span></label>
                                            <select class="form-control @error('category_id') is-invalid @enderror"
                                                id="category_id" name="category_id">
                                                <option value="">-- Pilih Kategori --</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->category_id }}"
                                                        {{ old('category_id', $event->category_id) == $category->category_id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <small class="text-danger d-block mt-2">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Kuota Tiket -->
                                        <div class="form-group">
                                            <label for="ticket_quota">Kuota Tiket <span class="text-danger">*</span></label>
                                            <input type="number"
                                                class="form-control @error('ticket_quota') is-invalid @enderror"
                                                id="ticket_quota" name="ticket_quota" placeholder="Jumlah tiket tersedia"
                                                required min="1" max="999999"
                                                value="{{ old('ticket_quota', $event->ticket_quota) }}">
                                            @error('ticket_quota')
                                                <small class="text-danger d-block mt-2">{{ $message }}</small>
                                            @enderror
                                            <small class="form-text text-muted">Total tiket yang akan dijual</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Tanggal & Waktu -->
                                        <div class="form-group">
                                            <label for="schedule_time">Tanggal & Waktu Event <span
                                                    class="text-danger">*</span></label>
                                            <input type="datetime-local"
                                                class="form-control @error('schedule_time') is-invalid @enderror"
                                                id="schedule_time" name="schedule_time" required
                                                value="{{ old('schedule_time', $event->schedule_time ? $event->schedule_time->format('Y-m-d\TH:i') : '') }}">
                                            @error('schedule_time')
                                                <small class="text-danger d-block mt-2">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                        <div class="form-group">
                                            <label for="location">Lokasi Event <span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('location') is-invalid @enderror"
                                                id="location" name="location"
                                                placeholder="Contoh: Jakarta International Expo, Jakarta" required
                                                maxlength="255" value="{{ old('location', $event->location) }}">
                                            @error('location')
                                                <small class="text-danger d-block mt-2">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <!-- Google Maps URL -->
                                        <div class="form-group">
                                            <label for="maps_url">Link Embed Google Maps <span class="text-muted">(Opsional)</span></label>
                                            <textarea class="form-control @error('maps_url') is-invalid @enderror"
                                                id="maps_url" name="maps_url"
                                                placeholder="Contoh: https://www.google.com/maps/embed?pb=..."
                                                rows="2">{{ old('maps_url', $event->maps_url) }}</textarea>
                                            @error('maps_url')
                                                <small class="text-danger d-block mt-2">{{ $message }}</small>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Dapatkan di Google Maps: Bagikan > Sematkan peta > Salin URL di dalam src="..."
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Status -->
                                        <div class="form-group">
                                            <label for="status">Status Event <span class="text-danger">*</span></label>
                                            <select class="form-control @error('status') is-invalid @enderror"
                                                id="status" name="status" required>
                                                <option value="draft"
                                                    {{ old('status', $event->status) === 'draft' ? 'selected' : '' }}>
                                                    <i class="fa fa-file-o"></i> Draft
                                                </option>
                                                <option value="published"
                                                    {{ old('status', $event->status) === 'published' ? 'selected' : '' }}>
                                                    <i class="fa fa-check"></i> Dipublikasikan
                                                </option>
                                                <option value="cancelled"
                                                    {{ old('status', $event->status) === 'cancelled' ? 'selected' : '' }}>
                                                    <i class="fa fa-ban"></i> Dibatalkan
                                                </option>
                                            </select>
                                            @error('status')
                                                <small class="text-danger d-block mt-2">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-action">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-save"></i> Simpan Perubahan
                                </button>
                                <a href="{{ route('events.show', $event->event_id) }}" class="btn btn-secondary">
                                    <i class="fa fa-times"></i> Batal
                                </a>
                                <button type="button" class="btn btn-danger" data-toggle="modal"
                                    data-target="#deleteModal">
                                    <i class="fa fa-trash"></i> Hapus Event
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Penghapusan</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus event <strong>{{ $event->title }}</strong>?</p>
                    <p class="text-danger"><small>⚠ Tindakan ini tidak dapat dikembalikan dan akan menghapus semua data
                            terkait.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <form action="{{ route('events.destroy', $event->event_id) }}" method="POST"
                        style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus Event</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-file-upload {
            position: relative;
            display: inline-block;
        }

        .custom-file-upload input[type="file"] {
            position: relative;
            width: 100%;
            padding: 10px;
            border: 2px dashed #ccc;
            border-radius: 4px;
            cursor: pointer;
            transition: border-color 0.3s;
        }

        .custom-file-upload input[type="file"]:hover {
            border-color: #3B82F6;
        }

        .custom-file-upload input[type="file"]:focus {
            border-color: #3B82F6;
            outline: none;
        }
    </style>

    <script>
        // Preview banner image
        document.getElementById('banner_url').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.getElementById('previewImg');
                    preview.src = event.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
@endsection
