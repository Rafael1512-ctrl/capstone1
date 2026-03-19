@extends('admin-layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Daftar Event</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('admin') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Event Management</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Daftar Event</a>
                </li>
            </ul>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>Sukses!</strong> {{ $message }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Event List</h4>
                        <a href="{{ route('event.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Event
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Lokasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($events as $event)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $event->title }}</td>
                                            <td>{{ $event->date->format('d-m-Y') }}</td>
                                            <td>{{ $event->time }}</td>
                                            <td>{{ $event->location }}</td>
                                            <td>
                                                <a href="{{ route('event.edit', $event) }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('event.destroy', $event) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada event</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($events->hasPages())
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center">
                                    {{ $events->links() }}
                                </ul>
                            </nav>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('ExtraCSS')
@endsection

@section('ExtraJS')
@endsection
