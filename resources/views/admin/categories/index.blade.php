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
                                        <div class="text-muted small mt-1"><i class="{{ $category->icon }} me-1"></i>
                                            {{ $category->icon }}</div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info px-3">{{ $category->events()->count() }} Event</span>
                                </td>
                                <td class="pe-4">
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <a href="{{ route('admin.categories.edit', $category->category_id) }}"
                                            class="btn-action btn-action-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form id="delete-form-{{ $category->category_id }}"
                                            action="{{ route('admin.categories.destroy', $category->category_id) }}"
                                            method="POST" style="display:inline; margin:0;">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn-action btn-action-delete" title="Hapus"
                                                onclick="confirmDelete({{ $category->category_id }}, '{{ addslashes($category->name) }}')">
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

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content" style="
                    background: linear-gradient(135deg, #1a0a0f 0%, #110710 100%) !important;
                    border: 1px solid rgba(220,20,60,0.35) !important;
                    border-radius: 20px !important;
                    padding: 8px;
                    box-shadow: 0 40px 80px rgba(0,0,0,0.8) !important;
                ">
                <div class="modal-body" style="padding: 32px 28px 8px; text-align:center;">
                    <!-- Icon -->
                    <div style="
                            width: 76px; height: 76px; border-radius: 50%;
                            background: rgba(220,20,60,0.12);
                            border: 1.5px solid rgba(220,20,60,0.35);
                            display: flex; align-items: center; justify-content: center;
                            margin: 0 auto 20px;
                            box-shadow: 0 0 40px rgba(220,20,60,0.2);
                        ">
                        <i class="fas fa-trash" style="font-size:30px; color:#dc143c;"></i>
                    </div>

                    <!-- Title -->
                    <h5 style="
                            color:#fff; font-weight:800; font-size:20px;
                            margin-bottom: 12px; font-family:'Inter',sans-serif;
                        ">Hapus Kategori?</h5>

                    <!-- Body text -->
                    <p style="color:rgba(255,255,255,0.6); font-size:14px; line-height:1.7; margin-bottom:8px;">
                        Kamu akan menghapus kategori
                    </p>
                    <p id="deleteModalCategoryName" style="
                            color:#fff; font-size:17px; font-weight:700;
                            margin-bottom:16px;
                        "></p>

                    
                </div>
                <div class="modal-footer" style="
                        border-top: 1px solid rgba(255,255,255,0.06) !important;
                        padding: 20px 28px 24px;
                        display: flex; gap: 12px; justify-content: center;
                        background: transparent !important;
                    ">
                    <button type="button" class="btn" data-bs-dismiss="modal" style="
                            background: rgba(255,255,255,0.08);
                            border: 1px solid rgba(255,255,255,0.15);
                            color: rgba(255,255,255,0.8);
                            border-radius: 12px;
                            padding: 12px 32px;
                            font-weight: 700;
                            font-size: 14px;
                            min-width: 120px;
                            transition: all 0.2s;
                        " onmouseover="this.style.background='rgba(255,255,255,0.14)'; this.style.color='#fff';"
                        onmouseout="this.style.background='rgba(255,255,255,0.08)'; this.style.color='rgba(255,255,255,0.8)';">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="button" id="deleteModalConfirm" style="
                            background: linear-gradient(135deg, #dc143c, #8b0000);
                            border: none;
                            color: #fff;
                            border-radius: 12px;
                            padding: 12px 32px;
                            font-weight: 800;
                            font-size: 14px;
                            min-width: 120px;
                            cursor: pointer;
                            transition: all 0.25s;
                            box-shadow: 0 4px 15px rgba(220,20,60,0.3);
                        "
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(220,20,60,0.5)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(220,20,60,0.3)';">
                        <i class="fas fa-trash me-2"></i>Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('ExtraJS')
    <script>
        var deleteTargetId = null;

        function confirmDelete(id, name) {
            deleteTargetId = id;
            document.getElementById('deleteModalCategoryName').textContent = '"' + name + '"';
            var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        document.getElementById('deleteModalConfirm').addEventListener('click', function () {
            if (deleteTargetId) {
                document.getElementById('delete-form-' + deleteTargetId).submit();
            }
        });
    </script>
@endsection