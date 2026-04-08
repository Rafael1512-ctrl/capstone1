@extends('layouts.master')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
            <h1 class="h3 mb-0">Buat Event Baru</h1>
            <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
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

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="organizer_id" class="form-label">Organizer *</label>
                            <select class="form-select @error('organizer_id') is-invalid @enderror" name="organizer_id"
                                id="organizer_id" required>
                                <option value="">-- Pilih Organizer --</option>
                                @foreach ($organizers as $org)
                                    <option value="{{ $org->user_id }}"
                                        {{ old('organizer_id') == $org->user_id ? 'selected' : '' }}>
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
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->category_id }}"
                                        {{ old('category_id') == $cat->category_id ? 'selected' : '' }}>
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
                                id="title" placeholder="Nama event" value="{{ old('title') }}" required>
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="schedule_time" class="form-label">Tanggal & Waktu *</label>
                            <input type="datetime-local" class="form-control @error('schedule_time') is-invalid @enderror"
                                name="schedule_time" id="schedule_time" value="{{ old('schedule_time') }}" required>
                            @error('schedule_time')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">Lokasi *</label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror" name="location"
                            id="location" placeholder="Alamat/Lokasi lengkap" value="{{ old('location') }}" required>
                        @error('location')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="maps_url" class="form-label">Link Embed Google Maps (Opsional)</label>
                        <textarea class="form-control @error('maps_url') is-invalid @enderror" name="maps_url"
                            id="maps_url" rows="2" placeholder="Contoh: https://www.google.com/maps/embed?pb=...">{{ old('maps_url') }}</textarea>
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
                            rows="4" placeholder="Deskripsi lengkap event" required>{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                        <div class="col-md-3">
                            <label for="banner" class="form-label">Banner Event (Upload)</label>
                            <input type="file" class="form-control @error('banner') is-invalid @enderror" name="banner"
                                id="banner" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG, GIF. Max: 2MB</small>
                            @error('banner')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="banner_url_external" class="form-label">Atau URL Banner Luar (Google Drive, dll)</label>
                            <input type="url" class="form-control" name="banner_url_external" id="banner_url_external" 
                                placeholder="https://drive.google.com/file/d/..." value="{{ old('banner_url_external') }}">
                            <small class="text-muted">Jika diisi, URL ini akan digunakan sebagai banner.</small>
                        </div>

                        <div class="col-md-3">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select @error('status') is-invalid @enderror" name="status" id="status"
                                required>
                                <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft
                                </option>
                                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published
                                </option>
                                <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Cancelled
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
                            <div class="alert alert-info mt-2 mb-0 py-2 small border-0 shadow-none">
                                <i class="fas fa-sync-alt me-1"></i> 
                                <strong>Tips Kolaborasi:</strong> Gunakan <strong>URL Foto Luar</strong> (Google Drive/Imgur) agar foto performer muncul di laptop teman Anda tanpa perlu kirim file gambar.
                            </div>
                        </div>
                        <div class="card-body">
                            <button type="button" class="btn btn-sm btn-primary mb-3" id="add-performer-btn">
                                <i class="fas fa-plus"></i> Tambah Performer
                            </button>
                            <div id="performers-container">
                                @if(old('performers'))
                                    @foreach(old('performers') as $index => $performer)
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
                                                        <input type="file" class="form-control performer-photo" accept="image/*"
                                                            name="performers[{{ $index }}][photo]" data-index="{{ $index }}">
                                                        <small class="text-muted">Max: 2MB</small>
                                                    </div>
                                                    <div class="col-md-12 mt-2">
                                                        <label class="form-label">Atau URL Foto Luar</label>
                                                        <input type="url" class="form-control" name="performers[{{ $index }}][photo_external]" 
                                                            placeholder="Contoh: https://images.unsplash.com/..." value="{{ $performer['photo_external'] ?? '' }}">
                                                        <small class="text-muted text-primary">Jika diisi, URL ini akan digunakan sebagai foto performer.</small>

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

                    <!-- Batch Configuration Section -->
                    <hr class="my-4">
                    <h5 class="mb-3 text-primary"><i class="fas fa-layer-group me-2"></i>Pengaturan Batch Tiket (Maksimal 2 Batch)</h5>
                    <p class="text-muted small mb-4">Wajib mengisi semua kategori di kedua batch.</p>

                    <div class="row g-4">
                        <!-- Batch 1 -->
                        <div class="col-md-12">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white py-2 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Batch 1 - Waktu & Kategori</h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <label class="small mb-0">Mulai:</label>
                                        <input type="datetime-local" name="batch1_start_at" class="form-control form-control-sm" 
                                            required style="width: auto;">
                                        <label class="small mb-0 ms-2">Selesai:</label>
                                        <input type="datetime-local" name="batch1_ended_at" class="form-control form-control-sm" 
                                            style="width: auto;">
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Kategori Tiket</th>
                                                    <th>Kuota *</th>
                                                    <th>Harga (Rp) *</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><strong>Regular</strong></td>
                                                    <td><input type="number" name="batch1_regular_quota" class="form-control" required min="0" value="0"></td>
                                                    <td><input type="number" name="batch1_regular_price" class="form-control" required min="0" value="0"></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>VIP</strong></td>
                                                    <td><input type="number" name="batch1_vip_quota" class="form-control" required min="0" value="0"></td>
                                                    <td><input type="number" name="batch1_vip_price" class="form-control" required min="0" value="0"></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>VVIP</strong></td>
                                                    <td><input type="number" name="batch1_vvip_quota" class="form-control" required min="0" value="0"></td>
                                                    <td><input type="number" name="batch1_vvip_price" class="form-control" required min="0" value="0"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Waiting List Section -->
                        <div class="col-md-12">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark py-2 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Waiting List (Batch 1) - Kuota</h6>
                                </div>
                                <div class="card-body">
                                    <p class="small text-muted mb-3">Tentukan kuota anggota waiting list per-kategori yang dapat mendaftar. Waiting list akan otomatis terbuka 5 menit setelah tiket reguler Batch 1 kategori terkait terjual habis, dan akan berlangsung selama 10 menit (atau sampai kuota habis).</p>
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Kategori Tiket</th>
                                                    <th>Kuota Waiting List *</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><strong>Regular</strong></td>
                                                    <td><input type="number" name="batch1_regular_waiting_quota" class="form-control" required min="0" value="0"></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>VIP</strong></td>
                                                    <td><input type="number" name="batch1_vip_waiting_quota" class="form-control" required min="0" value="0"></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>VVIP</strong></td>
                                                    <td><input type="number" name="batch1_vvip_waiting_quota" class="form-control" required min="0" value="0"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Batch 2 -->
                        <div class="col-md-12">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white py-2 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Batch 2 - Waktu & Kategori</h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <label class="small mb-0">Mulai:</label>
                                        <input type="datetime-local" name="batch2_start_at" class="form-control form-control-sm" 
                                            required style="width: auto;">
                                        <label class="small mb-0 ms-2">Selesai:</label>
                                        <input type="datetime-local" name="batch2_ended_at" class="form-control form-control-sm" 
                                            style="width: auto;">
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Kategori Tiket</th>
                                                    <th>Kuota *</th>
                                                    <th>Harga (Rp) *</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><strong>Regular</strong></td>
                                                    <td><input type="number" name="batch2_regular_quota" class="form-control" required min="0" value="0"></td>
                                                    <td><input type="number" name="batch2_regular_price" class="form-control" required min="0" value="0"></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>VIP</strong></td>
                                                    <td><input type="number" name="batch2_vip_quota" class="form-control" required min="0" value="0"></td>
                                                    <td><input type="number" name="batch2_vip_price" class="form-control" required min="0" value="0"></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>VVIP</strong></td>
                                                    <td><input type="number" name="batch2_vvip_quota" class="form-control" required min="0" value="0"></td>
                                                    <td><input type="number" name="batch2_vvip_price" class="form-control" required min="0" value="0"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Event
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
            // Set count based on current items (from old input)
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
                                    placeholder="Contoh: https://images.unsplash.com/...">
                                <small class="text-muted">Jika diisi, URL ini akan digunakan sebagai foto performer.</small>

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
            const items = document.querySelectorAll('.performer-item');
            items.forEach((item, index) => {
                item.querySelectorAll('input, textarea').forEach(input => {
                    const name = input.getAttribute('name');
                    if (name && name.includes('performers[')) {
                        // Replace the numeric index in name like performers[0][name] -> performers[1][name]
                        const newName = name.replace(/performers\[\d+\]/, `performers[${index}]`);
                        input.setAttribute('name', newName);
                    }
                });
            });
            // Update global count to match current number of items
            performerCount = items.length;
        }
    </script>
@endsection
