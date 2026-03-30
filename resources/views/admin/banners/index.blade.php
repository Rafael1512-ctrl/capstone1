@extends('layouts.master')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
        <h1 class="h3 mb-0">Kelola Hero Banners</h1>
        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus"></i> Tambah Banner
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Preview</th>
                        <th>Judul & Subtitle</th>
                        <th>Badge</th>
                        <th>Status</th>
                        <th>Urutan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($banners as $banner)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div style="width: 120px; height: 60px; overflow: hidden; border-radius: 8px; border: 1px solid #eee; background-image: url('{{ $banner->background_url ?: 'https://via.placeholder.com/120x60/1a0a0a/ffffff?text=Promo' }}'); background-size: cover; background-position: center;">
                                        @if($banner->image_url)
                                            <img src="{{ $banner->image_url }}" alt="Image" style="height: 100%; float: right;">
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong>{{ $banner->title }}</strong>
                                <br>
                                <small class="text-muted">{{ Str::limit($banner->subtitle, 50) }}</small>
                            </td>
                            <td>
                                @if($banner->badge_text)
                                    <span class="badge bg-danger rounded-pill">{{ $banner->badge_text }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.banners.toggle-active', $banner->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="border-0 bg-transparent">
                                        @if($banner->is_active)
                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i> Aktif</span>
                                        @else
                                            <span class="badge bg-secondary"><i class="fas fa-times me-1"></i> Non-Aktif</span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td>{{ $banner->sort_order }}</td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm">
                                    <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-white btn-sm text-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-white btn-sm text-danger" title="Delete" onclick="return confirm('Hapus banner ini?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fas fa-image mb-3 fa-3x d-block opacity-25"></i>
                                Belum ada banner yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            {{ $banners->links() }}
        </div>
    </div>
</div>
@endsection
