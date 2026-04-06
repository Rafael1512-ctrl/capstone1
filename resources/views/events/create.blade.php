@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title">Buat Event Baru</h4>
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
                        <a href="#">Buat Event Baru</a>
                    </li>
                </ul>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Informasi Event</div>
                        </div>

                        <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data"
                            id="eventForm">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        @if (auth()->user()->isAdmin())
                                            <!-- Organizer Select (Admin only) -->
                                            <div class="form-group">
                                                <label for="organizer_id">Organizer <span class="text-danger">*</span></label>
                                                <select class="form-control @error('organizer_id') is-invalid @enderror"
                                                    id="organizer_id" name="organizer_id" required>
                                                    <option value="">-- Pilih Organizer --</option>
                                                    @foreach ($organizers as $org)
                                                        <option value="{{ $org->user_id }}"
                                                            {{ old('organizer_id') == $org->user_id ? 'selected' : '' }}>
                                                            {{ $org->name }} ({{ $org->email }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('organizer_id')
                                                    <small class="text-danger d-block mt-2">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        @endif
                                        
                                        <!-- Judul Event -->
                                        <div class="form-group">
                                            <label for="title">Judul Event <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                                id="title" name="title" placeholder="Contoh: Tulus Concert 2026"
                                                required maxlength="200" value="{{ old('title') }}">
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
                                                rows="6" placeholder="Jelaskan detail event Anda..." required maxlength="1000">{{ old('description') }}</textarea>
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
                                            <div class="custom-file-upload">
                                                <input type="file"
                                                    class="form-control-file @error('banner_url') is-invalid @enderror"
                                                    id="banner_url" name="banner_url"
                                                    accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                                <small class="form-text text-muted d-block mt-2">
                                                    Format: JPG, PNG, GIF, WebP | Max 5MB
                                                </small>
                                            </div>
                                            @error('banner_url')
                                                <small class="text-danger d-block mt-2">{{ $message }}</small>
                                            @enderror

                                            <!-- Preview Banner -->
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
                                                        {{ old('category_id') == $category->category_id ? 'selected' : '' }}>
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
                                                required min="1" max="999999" value="{{ old('ticket_quota') }}">
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
                                                value="{{ old('schedule_time') }}">
                                            @error('schedule_time')
                                                <small class="text-danger d-block mt-2">{{ $message }}</small>
                                            @enderror
                                            <small class="form-text text-muted">Harus waktu yang akan datang</small>
                                        </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="location">Lokasi Event <span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('location') is-invalid @enderror"
                                                id="location" name="location"
                                                placeholder="Contoh: Jakarta International Expo, Jakarta" required
                                                maxlength="255" value="{{ old('location') }}">
                                            @error('location')
                                                <small class="text-danger d-block mt-2">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- Google Maps URL -->
                                        <div class="form-group">
                                            <label for="maps_url">Link Embed Google Maps <span class="text-muted">(Opsional)</span></label>
                                            <textarea class="form-control @error('maps_url') is-invalid @enderror"
                                                id="maps_url" name="maps_url"
                                                placeholder="Contoh: https://www.google.com/maps/embed?pb=..."
                                                rows="2">{{ old('maps_url') }}</textarea>
                                            @error('maps_url')
                                                <small class="text-danger d-block mt-2">{{ $message }}</small>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Dapatkan di Google Maps: Bagikan > Sematkan peta > Salin URL di dalam src="..."
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">
                                <h4 class="fw-bold mb-3">Pengaturan Batch Tiket (Maksimal 2 Batch)</h4>
                                <p class="text-muted mb-4">Tentukan kuota dan harga untuk kategori Regular, VIP, dan VVIP di setiap batch.</p>
                                
                                <div class="row">
                                    <!-- Batch 1 -->
                                    <div class="col-md-12 mb-4">
                                        <div class="card bg-dark text-white border-primary">
                                            <div class="card-header border-primary d-flex justify-content-between align-items-center">
                                                <div class="card-title text-primary">Batch 1 - Pengaturan Waktu</div>
                                                <div class="form-group mb-0">
                                                    <label class="mb-0">Mulai Penjualan <span class="text-danger">*</span></label>
                                                    <input type="datetime-local" name="batch1_start_at" class="form-control form-control-sm @error('batch1_start_at') is-invalid @enderror" 
                                                        required value="{{ old('batch1_start_at') }}">
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-dark">
                                                        <thead>
                                                            <tr>
                                                                <th>Kategori Tiket</th>
                                                                <th>Kuota <span class="text-danger">*</span></th>
                                                                <th>Harga (IDR) <span class="text-danger">*</span></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><strong>Regular</strong></td>
                                                                <td><input type="number" name="batch1_regular_quota" class="form-control" required min="0" value="{{ old('batch1_regular_quota', 0) }}"></td>
                                                                <td><input type="number" name="batch1_regular_price" class="form-control" required min="0" value="{{ old('batch1_regular_price', 0) }}"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>VIP</strong></td>
                                                                <td><input type="number" name="batch1_vip_quota" class="form-control" required min="0" value="{{ old('batch1_vip_quota', 0) }}"></td>
                                                                <td><input type="number" name="batch1_vip_price" class="form-control" required min="0" value="{{ old('batch1_vip_price', 0) }}"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>VVIP</strong></td>
                                                                <td><input type="number" name="batch1_vvip_quota" class="form-control" required min="0" value="{{ old('batch1_vvip_quota', 0) }}"></td>
                                                                <td><input type="number" name="batch1_vvip_price" class="form-control" required min="0" value="{{ old('batch1_vvip_price', 0) }}"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Batch 2 -->
                                    <div class="col-md-12 mb-4">
                                        <div class="card bg-dark text-white border-info">
                                            <div class="card-header border-info d-flex justify-content-between align-items-center">
                                                <div class="card-title text-info">Batch 2 - Pengaturan Waktu</div>
                                                <div class="form-group mb-0">
                                                    <label class="mb-0">Mulai Penjualan <span class="text-danger">*</span></label>
                                                    <input type="datetime-local" name="batch2_start_at" class="form-control form-control-sm @error('batch2_start_at') is-invalid @enderror" 
                                                        required value="{{ old('batch2_start_at') }}">
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-dark">
                                                        <thead>
                                                            <tr>
                                                                <th>Kategori Tiket</th>
                                                                <th>Kuota <span class="text-danger">*</span></th>
                                                                <th>Harga (IDR) <span class="text-danger">*</span></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><strong>Regular</strong></td>
                                                                <td><input type="number" name="batch2_regular_quota" class="form-control" required min="0" value="{{ old('batch2_regular_quota', 0) }}"></td>
                                                                <td><input type="number" name="batch2_regular_price" class="form-control" required min="0" value="{{ old('batch2_regular_price', 0) }}"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>VIP</strong></td>
                                                                <td><input type="number" name="batch2_vip_quota" class="form-control" required min="0" value="{{ old('batch2_vip_quota', 0) }}"></td>
                                                                <td><input type="number" name="batch2_vip_price" class="form-control" required min="0" value="{{ old('batch2_vip_price', 0) }}"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>VVIP</strong></td>
                                                                <td><input type="number" name="batch2_vvip_quota" class="form-control" required min="0" value="{{ old('batch2_vvip_quota', 0) }}"></td>
                                                                <td><input type="number" name="batch2_vvip_price" class="form-control" required min="0" value="{{ old('batch2_vvip_price', 0) }}"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-action">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-save"></i> Buat Event
                                </button>
                                <a href="{{ route('organizer.dashboard') }}" class="btn btn-secondary">
                                    <i class="fa fa-times"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
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

        // Set minimum date to today
        const todayDate = new Date().toISOString().slice(0, 16);
        document.getElementById('schedule_time').min = todayDate;
    </script>
@endsection
