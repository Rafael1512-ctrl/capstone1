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
                                        {{ $org->name }} ({{ $org->email }})
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
                        <label for="maps_url" class="form-label">Link Embed Google Maps (Opsional)</label>
                        <textarea class="form-control @error('maps_url') is-invalid @enderror" name="maps_url"
                            id="maps_url" rows="2" placeholder="Contoh: https://www.google.com/maps/embed?pb=...">{{ old('maps_url', $event->maps_url) }}</textarea>
                        @error('maps_url')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="text-muted">
                            Dapatkan di Google Maps: Bagikan > Sematkan peta > Salin URL di dalam src="..."
                        </small>
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
                            <label for="banner" class="form-label">Banner Event (Upload)</label>
                            @if ($event->banner_url && !filter_var($event->banner_url, FILTER_VALIDATE_URL))
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
                            <label for="banner_url_external" class="form-label">Atau URL Banner Luar (Google Drive, dll)</label>
                            @if ($event->banner_url && filter_var($event->banner_url, FILTER_VALIDATE_URL))
                                <div class="mb-2">
                                    <img src="{{ $event->banner_url }}" alt="Banner External"
                                        style="max-width: 200px; max-height: 150px;">
                                </div>
                            @endif
                            <input type="url" class="form-control" name="banner_url_external" id="banner_url_external" 
                                placeholder="https://drive.google.com/file/d/..." 
                                value="{{ old('banner_url_external', filter_var($event->banner_url, FILTER_VALIDATE_URL) ? $event->banner_url : '') }}">
                            <small class="text-muted">Jika diisi, URL ini akan digunakan sebagai banner.</small>
                        </div>

                        <div class="col-md-3">
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

                    <!-- Performers Section (Only for Festival) -->
                    <div id="performers-section" class="card mb-4" style="display: none; border-color: #e83e8c; border-width: 2px;">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-users"></i> Daftar Performer
                            </h5>
                            <small class="text-muted">Tambahkan/edit performer untuk festival ini</small>
                        </div>
                        <div class="card-body">
                            <button type="button" class="btn btn-sm btn-primary mb-3" id="add-performer-btn">
                                <i class="fas fa-plus"></i> Tambah Performer
                            </button>
                            <div id="performers-container">
                                @if($event->performers && count($event->performers) > 0)
                                    @foreach($event->performers as $index => $performer)
                                        <div class="performer-item card mb-3 border-secondary">
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label">Nama Performer *</label>
                                                        <input type="text" class="form-control" name="performers[{{ $index }}][name]"
                                                            placeholder="Nama artis/grup" value="{{ $performer['name'] ?? '' }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Peran/Genre *</label>
                                                        <input type="text" class="form-control" name="performers[{{ $index }}][role]"
                                                            placeholder="Vokalis, DJ, Band, dll" value="{{ $performer['role'] ?? '' }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Foto Performer (Upload)</label>
                                                        @if(isset($performer['photo']) && $performer['photo'] && !filter_var($performer['photo'], FILTER_VALIDATE_URL))
                                                            <div class="mb-2">
                                                                <img src="{{ $performer['photo'] }}" alt="Performer"
                                                                    style="max-width: 100px; max-height: 80px;">
                                                            </div>
                                                        @endif
                                                        <input type="file" class="form-control performer-photo" accept="image/*"
                                                            name="performers[{{ $index }}][photo]" data-index="{{ $index }}">
                                                        <small class="text-muted">Max: 2MB</small>
                                                    </div>
                                                    <div class="col-md-12 mt-2">
                                                        <label class="form-label">Atau URL Foto Luar</label>
                                                        @if(isset($performer['photo']) && $performer['photo'] && filter_var($performer['photo'], FILTER_VALIDATE_URL))
                                                            <div class="mb-2">
                                                                <img src="{{ $performer['photo'] }}" alt="Performer External"
                                                                    style="max-width: 100px; max-height: 80px;">
                                                            </div>
                                                        @endif
                                                        <input type="url" class="form-control" name="performers[{{ $index }}][photo_external]" 
                                                            placeholder="https://..." value="{{ filter_var($performer['photo'] ?? '', FILTER_VALIDATE_URL) ? $performer['photo'] : '' }}">
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <label class="form-label">Deskripsi</label>
                                                    <textarea class="form-control" name="performers[{{ $index }}][description]"
                                                        rows="2" placeholder="Info singkat tentang performer">{{ $performer['description'] ?? '' }}</textarea>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-danger mt-2 remove-performer-btn">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
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

    <script>
        // Get categories for Festival detection
        const FESTIVAL_CATEGORY = "Fes"; // Festival category keyword
        let performerCount = 0;

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            performerCount = document.querySelectorAll('.performer-item').length;

            // Listen to category change
            document.getElementById('category_id').addEventListener('change', function() {
                togglePerformerSection(this.value);
            });

            // Check initial category
            togglePerformerSection(document.getElementById('category_id').value);

            // Add new performer
            document.getElementById('add-performer-btn').addEventListener('click', addPerformer);

            // Remove performer buttons
            document.querySelectorAll('.remove-performer-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    this.closest('.performer-item').remove();
                    reindexPerformers();
                });
            });
        });

        function togglePerformerSection(categoryId) {
            const performersSection = document.getElementById('performers-section');
            
            // Get category name from select option
            const categorySelect = document.getElementById('category_id');
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            const categoryName = selectedOption.text.toLowerCase();

            // Show/hide based on whether category contains "festival"
            if (categoryName.includes('festival')) {
                performersSection.style.display = 'block';
            } else {
                performersSection.style.display = 'none';
            }
        }

        function addPerformer() {
            const container = document.getElementById('performers-container');
            const performerHTML = `
                <div class="performer-item card mb-3 border-secondary">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Nama Performer *</label>
                                <input type="text" class="form-control" name="performers[${performerCount}][name]"
                                    placeholder="Nama artis/grup" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Peran/Genre *</label>
                                <input type="text" class="form-control" name="performers[${performerCount}][role]"
                                    placeholder="Vokalis, DJ, Band, dll" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Foto Performer (Upload)</label>
                                <input type="file" class="form-control performer-photo" accept="image/*"
                                    name="performers[${performerCount}][photo]" data-index="${performerCount}">
                                <small class="text-muted">Max: 2MB</small>
                            </div>
                            <div class="col-md-12 mt-2">
                                <label class="form-label">Atau URL Foto Luar</label>
                                <input type="url" class="form-control" name="performers[${performerCount}][photo_external]" 
                                    placeholder="https://...">
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="performers[${performerCount}][description]"
                                rows="2" placeholder="Info singkat tentang performer"></textarea>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger mt-2 remove-performer-btn">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', performerHTML);
            performerCount++;

            // Add event listener to new remove button
            document.querySelectorAll('.remove-performer-btn').forEach(btn => {
                if (!btn.dataset.listenerAdded) {
                    btn.addEventListener('click', function() {
                        this.closest('.performer-item').remove();
                        reindexPerformers();
                    });
                    btn.dataset.listenerAdded = 'true';
                }
            });
        }

        function reindexPerformers() {
            document.querySelectorAll('.performer-item').forEach((item, index) => {
                item.querySelectorAll('input[type="text"], input[type="file"], textarea').forEach(input => {
                    const oldName = input.getAttribute('name');
                    if (oldName) {
                        const newName = oldName.replace(/\d+\]/, index + ']');
                        input.setAttribute('name', newName);
                    }
                });
            });
        }
    </script>
@endsection
