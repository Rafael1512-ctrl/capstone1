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
            
            <div class="row g-4 mb-5 justify-content-center">
                <div class="col-md-4">
                    <div class="card h-100 shadow" style="background-color: #262635; border: 1px solid #3b3b4d; border-radius: 24px; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)';" onmouseout="this.style.transform='none';">
                        <div class="card-body p-4">
                            <h5 class="card-title font-weight-bold" style="color: #fff; margin-bottom: 15px;">👤 Pembeli Tiket</h5>
                            <p class="card-text" style="color: #ccc; margin-bottom: 20px;">Jelajahi dan beli tiket untuk event favorit Anda</p>
                            <a href="{{ route('register') }}?role=user" class="btn btn-primary" style="border-radius: 50px; font-weight: 600; padding: 10px 25px;">Daftar Sekarang</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow" style="background-color: #262635; border: 1px solid #3b3b4d; border-radius: 24px; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)';" onmouseout="this.style.transform='none';">
                        <div class="card-body p-4">
                            <h5 class="card-title font-weight-bold" style="color: #fff; margin-bottom: 15px;">🎤 Organizer</h5>
                            <p class="card-text" style="color: #ccc; margin-bottom: 20px;">Buat dan kelola event Anda sendiri</p>
                            <a href="{{ route('register') }}?role=organizer" class="btn" style="background: linear-gradient(to right, #9d50bb, #6e48aa); color: white; border-radius: 50px; font-weight: 600; padding: 10px 25px;">Daftar Organizer</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow" style="background-color: #262635; border: 1px solid #3b3b4d; border-radius: 24px; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)';" onmouseout="this.style.transform='none';">
                        <div class="card-body p-4">
                            <h5 class="card-title font-weight-bold" style="color: #fff; margin-bottom: 15px;">⚙️ Admin</h5>
                            <p class="card-text" style="color: #ccc; margin-bottom: 20px;">Kelola sistem dan analytics platform</p>
                            <a href="{{ route('login') }}" class="btn btn-outline-light" style="border-radius: 50px; font-weight: 600; padding: 10px 25px;">Login Admin</a>
                        </div>
                    </div>
                </div>
            </div>

            <h3 class="mb-4">Events Populer</h3>
            <div class="row justify-content-center flex-wrap">
                @forelse(\App\Models\Event::published()->limit(8)->get() as $event)
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4 px-3 d-flex align-items-stretch">
                        <div class="card w-100 shadow" style="min-width: 220px; background-color: #262635; border: 1px solid #3b3b4d; border-radius: 24px; transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 20px rgba(157, 80, 187, 0.2)';" onmouseout="this.style.transform='none'; this.style.boxShadow='none';">
                            <div class="card-body d-flex flex-column p-4">
                                <h5 class="card-title font-weight-bold" style="color: #fff; margin-bottom: 15px;">{{ $event->title }}</h5>
                                <p class="small mb-3" style="color: #aaa;">
                                    <i class="fa fa-calendar mr-2" style="color: #9d50bb;"></i> {{ $event->event_date->format('d M Y') }}
                                </p>
                                <p class="small flex-grow-1" style="color: #ccc; line-height: 1.6;">{{ Str::limit($event->brief_description, 100) }}</p>
                                <a href="{{ route('login') }}" class="btn btn-sm mt-auto" style="background: linear-gradient(to right, #9d50bb, #6e48aa); color: white; border-radius: 50px; border: none; padding: 8px 20px; font-weight: 600;">Lihat Detail</a>
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