@extends('layouts.master')

@section('content')
<div class="container-fluid px-4">
    <h1 class="h3 mb-4 mt-4">Tambah Banner Baru</h1>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('admin.banners.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label font-weight-bold">Judul Banner (Opsional - Untuk Catatan Admin)</label>
                                <input type="text" class="form-control" name="title" value="{{ old('title') }}" placeholder="Contoh: Promo Akhir Tahun (Kosongkan jika hanya ingin tampilkan foto)">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label font-weight-bold">Sub-judul / Deskripsi (Opsional)</label>
                                <textarea class="form-control" name="subtitle" rows="3" placeholder="Kosongkan jika banner Anda sudah ada tulisannya di dalam foto">{{ old('subtitle') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">Teks Badge (Opsional)</label>
                                <input type="text" class="form-control" name="badge_text" value="{{ old('badge_text') }}" placeholder="Contoh: HOT">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">Teks Tombol (Opsional)</label>
                                <input type="text" class="form-control" name="button_text" value="{{ old('button_text') }}" placeholder="Bawaan: Learn More">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label font-weight-bold">Link URL (Wajib agar bisa diklik)</label>
                                <input type="text" class="form-control" name="link_url" value="{{ old('link_url', '#concert') }}" placeholder="https://...">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label font-weight-bold">URL Foto Banner (Gdrive Link) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="background_url" value="{{ old('background_url') }}" required placeholder="Masukkan link foto banner Anda di sini">
                                <small class="text-muted">Jika Anda mengosongkan semua tulisan di atas, banner akan tampil **Full Photo** saja.</small>
                            </div>
                            <div class="col-md-12 d-none">
                                <label class="form-label font-weight-bold">URL Foto Samping (Hanya jika pakai template lama)</label>
                                <input type="text" class="form-control" name="image_url" value="{{ old('image_url') }}" placeholder="Loncati jika hanya pakai 1 foto banner">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">Urutan Tampil (Sort Order)</label>
                                <input type="number" class="form-control" name="sort_order" value="{{ old('sort_order', 0) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">Status Aktif</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                                    <label class="form-check-label">Tampilkan di Landing Page</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i> Simpan Banner
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
                    <h6 class="mb-0 font-weight-bold">Panduan Visual</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted">Bannner akan tampil dengan desain premium dark red (seperti di landing page saat ini).</p>
                    <div style="background: #1a0a0a; border-radius: 12px; padding: 20px; border: 1px solid rgba(220, 20, 60, 0.2);">
                        <div class="badge bg-danger rounded-pill mb-2 px-3" style="font-size: 8px;">BADGE TEXT</div>
                        <h6 class="text-white mb-2" style="font-size: 1rem;">Judul Banner Akan Tampil Disini</h6>
                        <p class="text-muted mb-3" style="font-size: 0.75rem;">Deskripsi banner akant tampil di bawah judul untuk memberikan informasi lebih lanjut.</p>
                        <div class="bg-white text-dark py-1 px-3 d-inline-block rounded-pill font-weight-bold" style="font-size: 8px;">BUTTON TEXT</div>
                    </div>
                    <ul class="small text-muted mt-3 mb-0 ps-3">
                        <li>Gunakan foto samping dengan resolusi minimal 500x500px.</li>
                        <li>Background akan digabung dengan gradient gelap bawaan.</li>
                        <li>Urutan angka terkecil akan tampil paling pertama.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
