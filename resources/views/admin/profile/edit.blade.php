@extends('layouts.master')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
        <h1 class="h3 mb-0">Ubah Profil Admin</h1>
        <a href="{{ route('admin.profile.show') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-4 mb-4">
                <!-- Profile Picture Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 py-3 mt-2">
                        <h5 class="card-title mb-0 fw-bold">Foto Profil</h5>
                    </div>
                    <div class="card-body text-center p-4">
                        <div class="position-relative d-inline-block mb-4 overflow-hidden">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Avatar" 
                                     id="profile-preview" class="rounded-circle shadow" style="width: 150px; height: 150px; object-fit: cover; border: 5px solid #fff;">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=1e293b&color=fff&size=150" alt="Avatar" 
                                     id="profile-preview" class="rounded-circle shadow" style="width: 150px; height: 150px; object-fit: cover; border: 5px solid #fff;">
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <label for="profile_photo" class="form-label btn btn-outline-primary btn-sm rounded-pill px-4">
                                Pilih Foto Baru
                            </label>
                            <input type="file" name="profile_photo" id="profile_photo" class="d-none" accept="image/*">
                            @error('profile_photo')
                                <small class="text-danger d-block mt-2">{{ $message }}</small>
                            @enderror
                        </div>
                        <p class="text-muted small mb-0 px-3">Maksimal 2MB (JPG, PNG, GIF). Gunakan foto formal terbaik Anda.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <!-- Personal Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom-0 py-3 mt-2">
                        <h5 class="card-title d-flex align-items-center mb-0 fw-bold">
                            <i class="fas fa-user-edit text-primary me-2"></i> Informasi Personal
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-bold small text-muted">Nama Lengkap Admin <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                    value="{{ old('name', $user->name) }}" placeholder="Contoh: Admin Utama" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-bold small text-muted">Email Admin <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                                    value="{{ old('email', $user->email) }}" placeholder="admin@example.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label fw-bold small text-muted">Nomor WhatsApp/Telepon</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-phone-alt"></i></span>
                                    <input type="text" name="phone" id="phone" class="form-control border-start-0 @error('phone') is-invalid @enderror" 
                                        value="{{ old('phone', $user->phone) }}" placeholder="+62 812xxxxxx">
                                </div>
                                @error('phone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small text-muted">Member Sejak</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="far fa-calendar-alt"></i></span>
                                    <input type="text" class="form-control border-start-0" value="{{ $user->member_since ? $user->member_since->format('d M Y') : 'N/A' }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="mb-0">
                            <label for="bio" class="form-label fw-bold small text-muted">Biodata Singkat</label>
                            <textarea name="bio" id="bio" rows="4" class="form-control @error('bio') is-invalid @enderror" 
                                placeholder="Tuliskan sedikit tentang peran Anda atau informasi lainnya...">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted small d-block mt-2">Maksimal 500 karakter.</small>
                        </div>
                    </div>
                </div>

                <!-- Password Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom-0 py-3 mt-2">
                        <h5 class="card-title d-flex align-items-center mb-0 fw-bold">
                            <i class="fas fa-key text-warning me-2"></i> Ganti Kata Sandi (Opsional)
                        </h5>
                    </div>
                    <div class="card-body p-4 bg-light p-4 rounded-bottom">
                        <div class="alert alert-info border-0 shadow-none py-2 small mb-4">
                            <i class="fas fa-info-circle me-2"></i> Kosongkan bagian ini jika Anda tidak ingin mengganti kata sandi.
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pass" class="form-label fw-bold small text-muted">Kata Sandi Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="pass" id="pass" class="form-control border-start-0 @error('pass') is-invalid @enderror" 
                                        autocomplete="new-password" placeholder="Minimal 8 karakter">
                                </div>
                                @error('pass')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pass_confirmation" class="form-label fw-bold small text-muted">Konfirmasi Kata Sandi</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-check-double"></i></span>
                                    <input type="password" name="pass_confirmation" id="pass_confirmation" class="form-control border-start-0" 
                                        placeholder="Ketik ulang kata sandi baru">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="card border-0 shadow-sm mb-5 overflow-hidden">
                    <div class="card-body p-4 bg-white d-flex justify-content-between align-items-center">
                        <p class="text-muted mb-0 small">Terakhir diperbarui: {{ $user->updated_at ? $user->updated_at->diffForHumans() : 'N/A' }}</p>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.profile.show') }}" class="btn btn-outline-secondary px-4 rounded-pill fw-bold">Batal</a>
                            <button type="submit" class="btn btn-primary px-5 rounded-pill shadow fw-bold">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Preview Image Helper
    document.getElementById('profile_photo').addEventListener('change', function(){
        const file = this.files[0];
        if (file){
            const reader = new FileReader();
            reader.onload = function(){
                document.getElementById('profile-preview').setAttribute('src', reader.result);
            }
            reader.readAsDataURL(file);
        }
    });

    // Auto resize textarea
    const bioTextarea = document.getElementById('bio');
    bioTextarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
</script>
@endsection
