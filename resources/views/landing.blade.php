@extends('layouts.master')

@section('content')
<div class="container-fluid py-5">
    @if(Auth::check())
        {{-- User sudah login, redirect berdasarkan role --}}
        <div class="alert alert-info">
            <p>Selamat datang kembali, {{ Auth::user()->name }}!</p>
            <p>Anda akan diarahkan ke dashboard Anda...</p>
        </div>
        <script>
            // Redirect otomatis ke dashboard
            window.location.href = "{{ route('dashboard') }}";
        </script>
    @else
        {{-- User belum login, tampilkan landing page --}}
        <div class="text-center py-5">
            <h1 class="display-4 mb-4">🎫 Welcome to LuxTix</h1>
            <p class="lead mb-5">Platform Penjualan Tiket Konser & Event Terbaik</p>
            
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="card h-100 shadow">
                        <div class="card-body">
                            <h5 class="card-title">👤 Pembeli Tiket</h5>
                            <p class="card-text">Jelajahi dan beli tiket untuk event favorit Anda</p>
                            <a href="{{ route('register') }}?role=user" class="btn btn-primary">Daftar Sekarang</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow">
                        <div class="card-body">
                            <h5 class="card-title">🎤 Organizer</h5>
                            <p class="card-text">Buat dan kelola event Anda sendiri</p>
                            <a href="{{ route('register') }}?role=organizer" class="btn btn-success">Daftar Organizer</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow">
                        <div class="card-body">
                            <h5 class="card-title">⚙️ Admin</h5>
                            <p class="card-text">Kelola sistem dan analytics platform</p>
                            <a href="{{ route('login') }}" class="btn btn-dark">Login Admin</a>
                        </div>
                    </div>
                </div>
            </div>

            <h3 class="mb-4">Events Populer</h3>
            <div class="row g-3">
                @forelse(\App\Models\Event::published()->limit(6)->get() as $event)
                    <div class="col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">{{ $event->title }}</h6>
                                <p class="small text-muted">
                                    📅 {{ $event->event_date->format('d M Y') }}
                                </p>
                                <p class="small">{{ Str::limit($event->brief_description, 100) }}</p>
                                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Belum ada event tersedia</p>
                @endforelse
            </div>

            <hr class="my-5">

            <div class="text-center">
                <p class="mb-3">Sudah punya akun?</p>
                <a href="{{ route('login') }}" class="btn btn-link">Login di sini</a>
            </div>
        </div>
    @endif
</div>
@endsection