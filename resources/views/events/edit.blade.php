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
                            enctype="multipart/form-data" id="eventForm">
                            @csrf
                            @method('PUT')

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        @if (auth()->user()->isAdmin())
                                            <!-- Organizer Select (Admin only) -->
                                            <div class="form-group">
                                                <label for="organizer_id">Organizer <span class="text-danger">*</span></label>
                                                <select class="form-control @error('organizer_id') is-invalid @enderror"
                                                    id="organizer_id" name="organizer_id" required>
                                                    @foreach ($organizers as $org)
                                                        <option value="{{ $org->user_id }}"
                                                            {{ old('organizer_id', $event->organizer_id) == $org->user_id ? 'selected' : '' }}>
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
                                                    @php
                                                        $imgSrc = filter_var($event->banner_url, FILTER_VALIDATE_URL) 
                                                            ? $event->banner_url 
                                                            : (str_starts_with($event->banner_url, '/') ? asset($event->banner_url) : Storage::url($event->banner_url));
                                                    @endphp
                                                    <img src="{{ $imgSrc }}" alt="Banner Event"
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

                                            <div class="mt-3">
                                                <label for="banner_url_link">Atau Gunakan Link Banner (Google Drive, dll)</label>
                                                <input type="text" class="form-control @error('banner_url_link') is-invalid @enderror"
                                                    id="banner_url_link" name="banner_url_link"
                                                    placeholder="https://drive.google.com/..."
                                                    value="{{ old('banner_url_link', filter_var($event->banner_url, FILTER_VALIDATE_URL) ? $event->banner_url : '') }}">
                                                @error('banner_url_link')
                                                    <small class="text-danger d-block mt-2">{{ $message }}</small>
                                                @enderror
                                                <small class="form-text text-muted">Akan menimpa file upload jika keduanya diisi.</small>
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

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="location">Lokasi Event <span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('location') is-invalid @enderror"
                                                id="location" name="location"
                                                placeholder="Contoh: Jakarta International Expo, Jakarta" required
                                                maxlength="500" value="{{ old('location', $event->location) }}">
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
                                                rows="2">{{ old('maps_url', $event->maps_url) }}</textarea>
                                            @error('maps_url')
                                                <small class="text-danger d-block mt-2">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">
                                <h4 class="fw-bold mb-3">Pengaturan Batch Tiket (Maksimal 2 Batch)</h4>
                                <p class="text-muted mb-4 small">Tentukan kuota dan harga untuk kategori Regular, VIP, dan VVIP di setiap batch.</p>
                                
                                <div class="row">
                                    <!-- Batch 1 -->
                                    <div class="col-md-12 mb-4">
                                        <div class="card bg-dark text-white border-primary">
                                            <div class="card-header border-primary d-flex justify-content-between align-items-center">
                                                 <div class="card-title text-primary">Batch 1 - Waktu Penjualan</div>
                                                 <div class="d-flex align-items-center gap-3">
                                                     <div class="form-group mb-0">
                                                         <label class="mb-0 text-white">Mulai Penjualan <span class="text-danger">*</span></label>
                                                         <input type="datetime-local" id="batch1_start_at" name="batch1_start_at" class="form-control form-control-sm @error('batch1_start_at') is-invalid @enderror" 
                                                             required value="{{ old('batch1_start_at', $event->batch1_start_at ? $event->batch1_start_at->format('Y-m-d\TH:i') : '') }}">
                                                     </div>
                                                     <div class="form-group mb-0">
                                                         <label class="mb-0 text-white">Selesai Penjualan</label>
                                                         <input type="datetime-local" id="batch1_ended_at" name="batch1_ended_at" class="form-control form-control-sm @error('batch1_ended_at') is-invalid @enderror" 
                                                             value="{{ old('batch1_ended_at', $event->batch1_ended_at ? $event->batch1_ended_at->format('Y-m-d\TH:i') : '') }}">
                                                     </div>
                                                 </div>
                                            </div>
                                            @error('batch1_start_at') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                                            @error('batch1_ended_at') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-dark table-sm table-borderless align-middle">
                                                        <thead>
                                                            <tr>
                                                                <th>Kategori Tiket</th>
                                                                <th>Kuota <span class="text-danger">*</span></th>
                                                                <th>Harga (IDR) <span class="text-danger">*</span></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><strong>Regular</strong> @if($event->batch1_regular_sold > 0) <span class="badge bg-success">Terjual: {{ $event->batch1_regular_sold }}</span> @endif</td>
                                                                <td>
                                                                    <input type="number" name="batch1_regular_quota" class="form-control form-control-sm @error('batch1_regular_quota') is-invalid @enderror" required min="0" value="{{ old('batch1_regular_quota', $event->batch1_regular_quota) }}">
                                                                    @error('batch1_regular_quota') <small class="text-danger">{{ $message }}</small> @enderror
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="batch1_regular_price" class="form-control form-control-sm @error('batch1_regular_price') is-invalid @enderror" required min="0" value="{{ old('batch1_regular_price', floor($event->batch1_regular_price)) }}">
                                                                    @error('batch1_regular_price') <small class="text-danger">{{ $message }}</small> @enderror
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>VIP</strong> @if($event->batch1_vip_sold > 0) <span class="badge bg-success">Terjual: {{ $event->batch1_vip_sold }}</span> @endif</td>
                                                                <td>
                                                                    <input type="number" name="batch1_vip_quota" class="form-control form-control-sm @error('batch1_vip_quota') is-invalid @enderror" required min="0" value="{{ old('batch1_vip_quota', $event->batch1_vip_quota) }}">
                                                                    @error('batch1_vip_quota') <small class="text-danger">{{ $message }}</small> @enderror
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="batch1_vip_price" class="form-control form-control-sm @error('batch1_vip_price') is-invalid @enderror" required min="0" value="{{ old('batch1_vip_price', floor($event->batch1_vip_price)) }}">
                                                                    @error('batch1_vip_price') <small class="text-danger">{{ $message }}</small> @enderror
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>VVIP</strong> @if($event->batch1_vvip_sold > 0) <span class="badge bg-success">Terjual: {{ $event->batch1_vvip_sold }}</span> @endif</td>
                                                                <td>
                                                                    <input type="number" name="batch1_vvip_quota" class="form-control form-control-sm @error('batch1_vvip_quota') is-invalid @enderror" required min="0" value="{{ old('batch1_vvip_quota', $event->batch1_vvip_quota) }}">
                                                                    @error('batch1_vvip_quota') <small class="text-danger">{{ $message }}</small> @enderror
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="batch1_vvip_price" class="form-control form-control-sm @error('batch1_vvip_price') is-invalid @enderror" required min="0" value="{{ old('batch1_vvip_price', floor($event->batch1_vvip_price)) }}">
                                                                    @error('batch1_vvip_price') <small class="text-danger">{{ $message }}</small> @enderror
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Waiting List Section -->
                                    <div class="col-md-12 mb-4 text-white">
                                        <div class="card bg-dark text-white border-warning">
                                            <div class="card-header border-warning d-flex justify-content-between align-items-center">
                                                <div class="card-title text-warning">Waiting List (Batch 1) - Kuota</div>
                                            </div>
                                            <div class="card-body">
                                                <p class="small text-muted mb-3">Tentukan kuota anggota waiting list per-kategori yang dapat mendaftar. Waiting list akan otomatis terbuka 5 menit setelah tiket reguler Batch 1 kategori terkait terjual habis, dan akan berlangsung selama 10 menit (atau sampai kuota habis).</p>
                                                <div class="table-responsive">
                                                    <table class="table table-dark table-sm table-borderless align-middle">
                                                        <thead>
                                                            <tr>
                                                                <th>Kategori Tiket</th>
                                                                <th>Kuota Waiting List <span class="text-danger">*</span></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><strong>Regular</strong></td>
                                                                <td>
                                                                    <input type="number" name="batch1_regular_waiting_quota" class="form-control form-control-sm @error('batch1_regular_waiting_quota') is-invalid @enderror" required min="0" value="{{ old('batch1_regular_waiting_quota', $event->batch1_regular_waiting_quota) }}">
                                                                    @error('batch1_regular_waiting_quota') <small class="text-danger">{{ $message }}</small> @enderror
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>VIP</strong></td>
                                                                <td>
                                                                    <input type="number" name="batch1_vip_waiting_quota" class="form-control form-control-sm @error('batch1_vip_waiting_quota') is-invalid @enderror" required min="0" value="{{ old('batch1_vip_waiting_quota', $event->batch1_vip_waiting_quota) }}">
                                                                    @error('batch1_vip_waiting_quota') <small class="text-danger">{{ $message }}</small> @enderror
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>VVIP</strong></td>
                                                                <td>
                                                                    <input type="number" name="batch1_vvip_waiting_quota" class="form-control form-control-sm @error('batch1_vvip_waiting_quota') is-invalid @enderror" required min="0" value="{{ old('batch1_vvip_waiting_quota', $event->batch1_vvip_waiting_quota) }}">
                                                                    @error('batch1_vvip_waiting_quota') <small class="text-danger">{{ $message }}</small> @enderror
                                                                </td>
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
                                                 <div class="card-title text-info">Batch 2 - Waktu Penjualan</div>
                                                 <div class="d-flex align-items-center gap-3">
                                                     <div class="form-group mb-0">
                                                         <label class="mb-0 text-white">Mulai Penjualan <span class="text-danger">*</span></label>
                                                         <input type="datetime-local" id="batch2_start_at" name="batch2_start_at" class="form-control form-control-sm @error('batch2_start_at') is-invalid @enderror" 
                                                             required value="{{ old('batch2_start_at', $event->batch2_start_at ? $event->batch2_start_at->format('Y-m-d\TH:i') : '') }}">
                                                     </div>
                                                     <div class="form-group mb-0">
                                                         <label class="mb-0 text-white">Selesai Penjualan</label>
                                                         <input type="datetime-local" id="batch2_ended_at" name="batch2_ended_at" class="form-control form-control-sm @error('batch2_ended_at') is-invalid @enderror" 
                                                             value="{{ old('batch2_ended_at', $event->batch2_ended_at ? $event->batch2_ended_at->format('Y-m-d\TH:i') : '') }}">
                                                     </div>
                                                 </div>
                                            </div>
                                            @error('batch2_start_at') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                                            @error('batch2_ended_at') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-dark table-sm table-borderless align-middle">
                                                        <thead>
                                                            <tr>
                                                                <th>Kategori Tiket</th>
                                                                <th>Kuota <span class="text-danger">*</span></th>
                                                                <th>Harga (IDR) <span class="text-danger">*</span></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><strong>Regular</strong> @if($event->batch2_regular_sold > 0) <span class="badge bg-success">Terjual: {{ $event->batch2_regular_sold }}</span> @endif</td>
                                                                <td>
                                                                    <input type="number" name="batch2_regular_quota" class="form-control form-control-sm @error('batch2_regular_quota') is-invalid @enderror" required min="0" value="{{ old('batch2_regular_quota', $event->batch2_regular_quota) }}">
                                                                    @error('batch2_regular_quota') <small class="text-danger">{{ $message }}</small> @enderror
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="batch2_regular_price" class="form-control form-control-sm @error('batch2_regular_price') is-invalid @enderror" required min="0" value="{{ old('batch2_regular_price', floor($event->batch2_regular_price)) }}">
                                                                    @error('batch2_regular_price') <small class="text-danger">{{ $message }}</small> @enderror
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>VIP</strong> @if($event->batch2_vip_sold > 0) <span class="badge bg-success">Terjual: {{ $event->batch2_vip_sold }}</span> @endif</td>
                                                                <td>
                                                                    <input type="number" name="batch2_vip_quota" class="form-control form-control-sm @error('batch2_vip_quota') is-invalid @enderror" required min="0" value="{{ old('batch2_vip_quota', $event->batch2_vip_quota) }}">
                                                                    @error('batch2_vip_quota') <small class="text-danger">{{ $message }}</small> @enderror
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="batch2_vip_price" class="form-control form-control-sm @error('batch2_vip_price') is-invalid @enderror" required min="0" value="{{ old('batch2_vip_price', floor($event->batch2_vip_price)) }}">
                                                                    @error('batch2_vip_price') <small class="text-danger">{{ $message }}</small> @enderror
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>VVIP</strong> @if($event->batch2_vvip_sold > 0) <span class="badge bg-success">Terjual: {{ $event->batch2_vvip_sold }}</span> @endif</td>
                                                                <td>
                                                                    <input type="number" name="batch2_vvip_quota" class="form-control form-control-sm @error('batch2_vvip_quota') is-invalid @enderror" required min="0" value="{{ old('batch2_vvip_quota', $event->batch2_vvip_quota) }}">
                                                                    @error('batch2_vvip_quota') <small class="text-danger">{{ $message }}</small> @enderror
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="batch2_vvip_price" class="form-control form-control-sm @error('batch2_vvip_price') is-invalid @enderror" required min="0" value="{{ old('batch2_vvip_price', floor($event->batch2_vvip_price)) }}">
                                                                    @error('batch2_vvip_price') <small class="text-danger">{{ $message }}</small> @enderror
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
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
                                                <option value="Non-Active"
                                                    {{ old('status', $event->status) === 'Non-Active' ? 'selected' : '' }}>
                                                    Non-Active
                                                </option>
                                                <option value="Active"
                                                    {{ old('status', $event->status) === 'Active' ? 'selected' : '' }}>
                                                    Active
                                                </option>
                                                <option value="draft"
                                                    {{ old('status', $event->status) === 'draft' ? 'selected' : '' }}>
                                                    Draft
                                                </option>
                                                <option value="published"
                                                    {{ old('status', $event->status) === 'published' ? 'selected' : '' }}>
                                                    Published
                                                </option>
                                                <option value="cancelled"
                                                    {{ old('status', $event->status) === 'cancelled' ? 'selected' : '' }}>
                                                    Cancelled
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

        /* Styling agar icon kalender lebih terlihat di mode dark */
        input[type="datetime-local"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            background-color: #3B82F6;
            padding: 5px;
            border-radius: 3px;
            cursor: pointer;
        }

        .card-header .form-group label {
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 600;
        }
    </style>

    <script>
        // Preview banner image (File Upload)
        const bannerInput = document.getElementById('banner_url');
        const previewImg = document.getElementById('previewImg');
        const bannerLinkInput = document.getElementById('banner_url_link');

        function updatePreview(src) {
            if (src) {
                previewImg.src = src;
                previewImg.style.display = 'block';
            } else {
                previewImg.style.display = 'none';
            }
        }

        bannerInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    updatePreview(event.target.result);
                };
                reader.readAsDataURL(file);
            }
        });

        // Preview banner from link
        bannerLinkInput.addEventListener('input', function() {
            if (!bannerInput.files.length) {
                updatePreview(this.value);
            }
        });

        // Set minimum dan maximum date berdasarkan jadwal event
        const scheduleInput = document.getElementById('schedule_time');
        const batchInputs = [
            'batch1_start_at', 'batch1_ended_at', 
            'batch2_start_at', 'batch2_ended_at'
        ];

        function checkBatchDate(input) {
            const eventDate = scheduleInput.value;
            if (eventDate && input.value && input.value > eventDate) {
                input.value = eventDate;
                // Visible feedback
                input.classList.add('is-invalid');
                setTimeout(() => input.classList.remove('is-invalid'), 2000);
            }
        }

        function updateBatchDateConstraints() {
            const eventDate = scheduleInput.value;
            if (eventDate) {
                batchInputs.forEach(id => {
                    const input = document.getElementById(id);
                    if (input) {
                        input.max = eventDate;
                        checkBatchDate(input);
                    }
                });
            }
        }

        scheduleInput.addEventListener('change', updateBatchDateConstraints);

        // Add listeners to batch inputs
        batchInputs.forEach(id => {
            const input = document.getElementById(id);
            if (input) {
                input.addEventListener('change', () => checkBatchDate(input));
                input.addEventListener('input', () => checkBatchDate(input));
            }
        });

        // Block submission if dates are invalid
        const eventForm = document.getElementById('eventForm'); // Make sure ID matches
        // Block submission if dates are invalid
        const eventForm = document.getElementById('eventForm');
        eventForm.addEventListener('submit', function(e) {
            const eventDateStr = scheduleInput.value;
            if (!eventDateStr) return;
            
            const eventDate = new Date(eventDateStr);
            let hasError = false;
            let errorMsg = '';
            
            batchInputs.forEach(id => {
                const input = document.getElementById(id);
                if (input && input.value) {
                    const batchDate = new Date(input.value);
                    if (batchDate > eventDate) {
                        hasError = true;
                        errorMsg = 'Waktu Batch tidak boleh melebihi jadwal Event!';
                        input.classList.add('is-invalid');
                    }
                }
            });

            if (hasError) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Waktu Tidak Valid',
                    text: errorMsg,
                    background: '#1a0a0f',
                    color: '#fff',
                    confirmButtonColor: '#dc143c',
                    confirmButtonText: 'Perbaiki Sekarang'
                });
                return false;
            }
        });
        
        // Initial setup
        updateBatchDateConstraints();
    </script>
@endsection
@endsection
