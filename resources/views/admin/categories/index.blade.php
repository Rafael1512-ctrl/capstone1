@extends('layouts.master')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5 mt-2">
            <div>
                <h1 class="page-title mb-0">Kelola Kategori Event</h1>
                <p class="text-muted small mb-0">Daftar kategori untuk pengelompokan event</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="fas fa-plus"></i>
                <span>Kategori Baru</span>
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Categories List -->
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Nama Kategori</th>
                            <th class="text-center">Jumlah Event</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                             <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-white">{{ $category->name }}</div>
                                    @if ($category->icon)
                                        <div class="text-muted small mt-1"><i class="{{ $category->icon }} me-1"></i> {{ $category->icon }}</div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info px-3">{{ $category->events()->count() }} Event</span>
                                </td>
                                <td class="pe-4">
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                            class="btn-action btn-action-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                            class="m-0">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-action btn-action-delete" title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Belum ada kategori
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($categories->links())
                <div class="card-footer">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
