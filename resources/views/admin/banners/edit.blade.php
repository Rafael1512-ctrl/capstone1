@extends('layouts.master')

@section('content')
<div class="container-fluid px-4">
    <h1 class="h3 mb-4 mt-4">Edit Banner: {{ $banner->title }}</h1>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label font-weight-bold">Judul Banner (Opsional - Untuk Catatan Admin)</label>
                                <input type="text" class="form-control" name="title" value="{{ old('title', $banner->title) }}" placeholder="Contoh: Promo Akhir Tahun">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label font-weight-bold">Sub-judul / Deskripsi (Opsional)</label>
                                <textarea class="form-control" name="subtitle" rows="3" placeholder="Kosongkan jika hanya ingin foto">{{ old('subtitle', $banner->subtitle) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">Teks Badge (Opsional)</label>
                                <input type="text" class="form-control" name="badge_text" value="{{ old('badge_text', $banner->badge_text) }}" placeholder="HOT">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">Teks Tombol (Opsional)</label>
                                <input type="text" class="form-control" name="button_text" value="{{ old('button_text', $banner->button_text) }}">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label font-weight-bold">Link URL (Wajib agar bisa diklik)</label>
                                <input type="text" class="form-control" name="link_url" value="{{ old('link_url', $banner->link_url) }}" placeholder="https://...">
                            </div>
                            
                            <div class="col-md-12">
                                <label class="form-label font-weight-bold d-block">Preview Foto Banner</label>
                                <div class="mb-2">
                                    <img src="{{ $banner->background_url }}" alt="BG" style="width: 200px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                                </div>
                                <label class="form-label font-weight-bold">URL Foto Banner <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="background_url" value="{{ old('background_url', $banner->background_url) }}" required placeholder="Masukkan link foto banner baru">
                                <small class="text-muted">Masukkan link Gdrive atau link gambar langsung lainnya.</small>
                            </div>

                            <div class="col-md-12 d-none">
                                <label class="form-label font-weight-bold">URL Foto Samping (Template Lama)</label>
                                <input type="text" class="form-control" name="image_url" value="{{ old('image_url', $banner->image_url) }}" placeholder="Hanya jika pakai template">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">Urutan Tampil (Sort Order)</label>
                                <input type="number" class="form-control" name="sort_order" value="{{ old('sort_order', $banner->sort_order) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">Status Aktif</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $banner->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label">Tampilkan di Landing Page</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i> Update Banner
                            </button>
                            <a href="{{ route('admin.banners.index') }}" class="btn btn-light px-4">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 font-weight-bold">Informasi Edit</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted">Jika Anda mengganti foto atau background, file lama akan otomatis terhapus dari server untuk menghemat ruang.</p>
                    <ul class="small text-muted mb-0 ps-3">
                        <li>Banner ID: <code>#{{ $banner->id }}</code></li>
                        <li>Dibuat pada: {{ $banner->created_at->format('d/m/Y H:i') }}</li>
                        <li>Update terakhir: {{ $banner->updated_at->format('d/m/Y H:i') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
