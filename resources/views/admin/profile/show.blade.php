@extends('layouts.master')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
        <h1 class="h3 mb-0">Profil Admin</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Dashboard
        </a>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-4">
            <!-- Profile Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center p-5">
                    <div class="position-relative d-inline-block mb-3">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Avatar" 
                                 class="rounded-circle shadow" style="width: 150px; height: 150px; object-fit: cover; border: 5px solid #fff;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=1e293b&color=fff&size=150" alt="Avatar" 
                                 class="rounded-circle shadow" style="width: 150px; height: 150px; object-fit: cover; border: 5px solid #fff;">
                        @endif
                    </div>
                    <h4 class="mb-1 text-dark fw-bold">{{ $user->name }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    <span class="badge bg-primary px-3 py-2 rounded-pill mb-4">System Administrator</span>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary d-flex align-items-center justify-content-center">
                            <i class="fas fa-edit me-2"></i> Edit Profil
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats/Info Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">Account Status</h6>
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-success rounded-circle me-3" style="width: 10px; height: 10px;"></div>
                        <div class="flex-grow-1 fw-bold text-dark">Active Account</div>
                    </div>
                    <hr class="my-3 opacity-25">
                    <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">Member Since</h6>
                    <div class="d-flex align-items-center">
                        <i class="far fa-calendar-alt me-3 text-muted"></i>
                        <div class="text-dark">{{ $user->member_since ? $user->member_since->format('d M Y') : 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Account Details -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom-0 py-3 mt-2">
                    <h5 class="card-title mb-0 fw-bold">Informasi Akun</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-sm-3 text-muted">Nama Lengkap</div>
                        <div class="col-sm-9 fw-bold text-dark">{{ $user->name }}</div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-sm-3 text-muted">Alamat Email</div>
                        <div class="col-sm-9 fw-bold text-dark">{{ $user->email }}</div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-sm-3 text-muted">Nomor Telepon</div>
                        <div class="col-sm-9 fw-bold text-dark">{{ $user->phone ?? 'Belum ditambahkan' }}</div>
                    </div>
                    <div class="row mb-0">
                        <div class="col-sm-3 text-muted">Bio / Tentang Admin</div>
                        <div class="col-sm-9 text-dark" style="line-height: 1.6;">
                            {{ $user->bio ?? 'Admin tidak memberikan biodata.' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom-0 py-3 mt-2">
                    <h5 class="card-title d-flex align-items-center mb-0 fw-bold">
                        <i class="fas fa-shield-alt text-primary me-2"></i> Keamanan Akun
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-3 border">
                        <div class="flex-shrink-0 bg-white shadow-sm rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                            <i class="fas fa-lock text-warning"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 text-dark fw-bold">Kata Sandi</h6>
                            <p class="mb-0 text-muted small">Ubah kata sandi Anda secara berkala untuk menjaga keamanan akun admin.</p>
                        </div>
                        <div class="ms-auto">
                            <a href="{{ route('admin.profile.edit') }}" class="btn btn-sm btn-outline-secondary px-3 rounded-pill">Ubah</a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center p-3 bg-light rounded-3 border">
                        <div class="flex-shrink-0 bg-white shadow-sm rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                            <i class="fas fa-envelope-open-text text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-dark fw-bold">Verifikasi Email</h6>
                            <p class="mb-0 text-muted small">Status: <span class="text-success fw-bold">Terverifikasi</span> pada {{ $user->email_verified_at ? $user->email_verified_at->format('d M Y') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
