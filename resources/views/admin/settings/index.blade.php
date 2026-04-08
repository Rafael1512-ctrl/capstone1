@extends('layouts.master')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h1 class="h3 mb-0">Pengaturan Website</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 font-weight-bold text-primary">Landing Page Content</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="form-label font-weight-bold">About Us Section Image URL (External Link)</label>
                            <input type="text" class="form-control" name="about_us_image_url" 
                                value="{{ \App\Models\SiteSetting::getValue('about_us_image_url', asset('cardboard-assets/img/hero_1.jpg')) }}" 
                                placeholder="https://drive.google.com/uc?id=...">
                            <small class="text-muted">Masukkan URL gambar luar (Gdrive, Cloudinary, dll) untuk mengganti gambar di bagian 'About Us'.</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label font-weight-bold">About Us Title (Opsional)</label>
                            <input type="text" class="form-control" name="about_us_title" 
                                value="{{ \App\Models\SiteSetting::getValue('about_us_title', 'Your Gateway to Exclusive Live Music Experiences') }}">
                        </div>

                        <div class="mt-4 pt-3 border-top d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i> Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 font-weight-bold">Preview</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted">Gambar saat ini:</p>
                    <img src="{{ \App\Models\SiteSetting::getValue('about_us_image_url', asset('cardboard-assets/img/hero_1.jpg')) }}" 
                         class="img-fluid rounded shadow-sm mb-3" alt="Preview" >
                    <div class="alert alert-info py-2 px-3 small border-0">
                        <i class="fas fa-info-circle me-1"></i> Perubahan akan langsung tampil di halaman depan website untuk pengunjung.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
