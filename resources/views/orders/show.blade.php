@extends('layouts.master')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center p-4">
                    <h4 class="mb-0"><i class="fas fa-receipt me-2"></i> Detail Pesanan #{{ $order->transaction_id }}</h4>
                    <span class="badge {{ $order->payment_status === 'Verified' ? 'bg-success' : ($order->payment_status === 'Pending' ? 'bg-warning text-dark' : 'bg-danger') }} px-3 py-2">
                        {{ $order->payment_status }}
                    </span>
                </div>
                <div class="card-body p-5">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <h5 class="text-muted text-uppercase small mb-3">Informasi Pembeli</h5>
                            <p class="mb-1"><strong>Nama:</strong> {{ $order->user->name }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $order->user->email }}</p>
                            <p class="mb-1"><strong>Metode:</strong> {{ $order->payment_method }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h5 class="text-muted text-uppercase small mb-3">Tanggal Transaksi</h5>
                            <p class="h5">{{ $order->payment_date->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    <div class="table-responsive mb-5">
                        <table class="table table-borderless">
                            <thead class="border-bottom">
                                <tr>
                                    <th class="ps-0">Deskripsi Item</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end pe-0">Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-0 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded p-3 me-3">
                                                <i class="fas fa-ticket-alt fa-2x text-primary"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $ticket->ticket_type_name ?? 'Tiket Acara' }}</h6>
                                                <small class="text-muted">{{ $order->event->title ?? 'Event' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center py-3 align-middle">{{ $order->total_ticket }}</td>
                                    <td class="text-end pe-0 py-3 align-middle"><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tbody>
                            <tfoot class="border-top">
                                <tr>
                                    <td colspan="2" class="text-end h5 py-4">Total Bayar:</td>
                                    <td class="text-end h5 py-4 pe-0 text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if ($order->payment_status === 'Verified')
                        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center p-4 mb-4">
                            <i class="fas fa-check-circle fa-3x me-4 text-success"></i>
                            <div>
                                <h5 class="alert-heading mb-1">Pembayaran Berhasil!</h5>
                                <p class="mb-0">Tiket Anda sudah aktif. Silakan tunjukkan QR Code di bawah ini saat masuk ke lokasi acara.</p>
                            </div>
                        </div>

                        {{-- Google Calendar Reminder Button --}}
                        @php
                            $calendarLink = session('calendar_link');
                            if (!$calendarLink && $order->event) {
                                $ticketTypeName = $order->tickets->first()?->ticketType?->name ?? null;
                                $calendarLink = \App\Services\GoogleCalendarService::generateCalendarLink($order->event, $ticketTypeName);
                            }
                        @endphp
                        @if($calendarLink)
                        <div class="alert border-0 shadow-sm p-3 mb-5" style="background: linear-gradient(135deg, #e8f0fe 0%, #d2e3fc 100%); border-radius: 12px;">
                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                <div class="d-flex align-items-center mb-2 mb-md-0">
                                    <div class="me-3" style="font-size: 2rem;">📅</div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark">Jangan Lupa Konsernya!</h6>
                                        <small class="text-muted">Tambahkan jadwal konser ke Google Calendar sebagai pengingat</small>
                                    </div>
                                </div>
                                <a href="{{ $calendarLink }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary px-4 py-2 rounded-pill fw-bold" style="background: linear-gradient(135deg, #4285F4 0%, #1a73e8 100%); border: none; box-shadow: 0 4px 12px rgba(66,133,244,0.3); transition: all 0.3s;">
                                    <i class="fas fa-calendar-plus me-2"></i> Tambah ke Google Calendar
                                </a>
                            </div>
                        </div>
                        @endif
                        
                        <div class="mt-5">
                            <h5 class="mb-4 text-center font-weight-bold">Digital Tickets & QR Codes</h5>
                            <div class="row row-cols-1 row-cols-lg-2 g-4">
                                @foreach($tickets as $t)
                                    <div class="col mb-4">
                                        <div class="card h-100 border shadow-sm ticket-card-style position-relative overflow-hidden" id="ticket-card-{{ $t->ticket_id }}">
                                            <div class="ticket-accent bg-primary"></div>
                                            <div class="card-body p-0">
                                                <div class="row no-gutters">
                                                    <div class="col-md-8 p-4">
                                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                                            <div>
                                                                <span class="badge bg-danger text-white mb-2 px-3 py-1">{{ $t->ticket_type_name }}</span>
                                                                <h5 class="font-weight-bold mb-1 text-dark">{{ $order->event->title ?? 'Event' }}</h5>
                                                                <p class="text-muted small mb-0"><i class="fas fa-calendar-alt mr-1"></i> {{ $order->event->schedule_time ? $order->event->schedule_time->format('d M Y, H:i') : 'Date TBD' }}</p>
                                                            </div>
                                                            <div class="text-end">
                                                                @if($t->ticket_status === 'Active')
                                                                    <span class="badge bg-success px-2 py-1 small">ACTIVE</span>
                                                                @else
                                                                    <span class="badge bg-secondary px-2 py-1 small">USED</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="row mt-4">
                                                            <div class="col-6">
                                                                <label class="text-muted small text-uppercase font-weight-bold mb-0 d-block" style="font-size: 0.6rem;">Ticket ID</label>
                                                                <span class="font-weight-bold text-dark font-monospace small">#{{ $t->ticket_id }}</span>
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="text-muted small text-uppercase font-weight-bold mb-0 d-block" style="font-size: 0.6rem;">Owner</label>
                                                                <span class="font-weight-bold text-dark small text-truncate d-block">{{ $order->user->name }}</span>
                                                            </div>
                                                        </div>

                                                        {{-- Admin/Staff Only: Activate Button inside individual ticket --}}
                                                        @if(Auth::user()->isAdmin() || Auth::user()->role === 'organizer')
                                                            <div class="mt-4 pt-3 border-top d-flex gap-2">
                                                                @if($t->ticket_status === 'Active')
                                                                    <button type="button" 
                                                                            class="btn btn-success btn-sm activate-ticket-btn" 
                                                                            data-id="{{ $t->ticket_id }}"
                                                                            data-url="{{ route('tickets.validate.ajax', $t->ticket_id) }}">
                                                                        <i class="fas fa-check-circle mr-1"></i> Activate Now
                                                                    </button>
                                                                @else
                                                                    <button class="btn btn-light btn-sm disabled" disabled>
                                                                        <i class="fas fa-check text-success mr-1"></i> Validated
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-4 bg-light border-start p-4 d-flex flex-column align-items-center justify-content-center text-center">
                                                        <div class="bg-white p-2 border rounded shadow-sm mb-2">
                                                            @php
                                                                $qrData = route('tickets.scan.direct', $t->ticket_id);
                                                            @endphp
                                                            {!! QrCode::size(110)->generate($qrData) !!}
                                                        </div>
                                                        <p class="text-muted small mb-0 font-italic" style="font-size: 0.6rem;">Scan for Entry</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="text-center mt-5">
                                <a href="{{ route('tickets.index') }}" class="btn btn-outline-primary me-2 px-4 rounded-pill"><i class="fas fa-ticket-alt me-1"></i> My Tickets</a>
                                <a href="{{ route('landing') }}" class="btn btn-primary px-4 rounded-pill">Cari Konser Lainnya</a>
                            </div>
                        </div>
                    @elseif ($order->payment_status === 'Pending')
                        <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center p-4">
                            <i class="fas fa-clock fa-3x me-4 text-warning"></i>
                            <div>
                                <h5 class="alert-heading mb-1">Menunggu Pembayaran</h5>
                                <p class="mb-0">Pesanan telah dibuat dan kuota tiket telah diamankan. Silakan selesaikan pembayaran melalui <strong>{{ $order->payment_method }}</strong> Anda.</p>
                                <p class="mt-2 mb-0 fw-bold fs-5 text-danger" id="countdown-timer">Sisa Waktu: --:--</p>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-6">
                                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary w-100 rounded-pill py-3 font-weight-bold">Kembali ke Daftar Pesanan</a>
                            </div>
                            <div class="col-md-6">
                                <form action="{{ route('orders.simulate-payment', $order->transaction_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 font-weight-bold">Confirm & Pay Now (Simulasi)</button>
                                </form>
                            </div>
                        </div>

                        <script>
                            @if(isset($order->expires_at))
                                const escapesAt = new Date("{{ $order->expires_at->format('Y-m-d\TH:i:s') }}").getTime();
                                const timer = setInterval(function() {
                                    const now = new Date().getTime();
                                    const distance = escapesAt - now;

                                    if (distance < 0) {
                                        clearInterval(timer);
                                        document.getElementById('countdown-timer').innerHTML = "Waktu Habis!";
                                        location.reload(); 
                                        return;
                                    }

                                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                    
                                    document.getElementById('countdown-timer').innerHTML = "Sisa Waktu: " + 
                                        (minutes < 10 ? "0" + minutes : minutes) + ":" + 
                                        (seconds < 10 ? "0" + seconds : seconds);
                                }, 1000);
                            @endif
                        </script>
                    @else
                        {{-- Handle Cancelled / Expired status --}}
                        <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center p-4">
                            <i class="fas fa-times-circle fa-3x me-4 text-danger"></i>
                            <div>
                                <h5 class="alert-heading mb-1">Pesanan Dibatalkan</h5>
                                <p class="mb-0">Waktu pembayaran Anda telah habis atau pesanan dibatalkan. Kuota tiket ini telah dikembalikan ke sistem.</p>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-6 text-md-end text-center mb-3 mb-md-0">
                                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary w-100 rounded-pill py-3">Lihat Riwayat Pesanan</a>
                            </div>
                            <div class="col-md-6 text-center">
                                <a href="{{ route('public.event.show', $order->event->event_id) }}" class="btn btn-primary w-100 rounded-pill py-3 font-weight-bold">Pesan Ulang Tiket</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .ticket-card-style { border-radius: 12px; transition: 0.3s; border: 1.5px dashed #ddd !important; }
    .ticket-card-style:hover { transform: translateY(-5px); border-color: #dc143c !important; }
    .ticket-accent { position: absolute; left: 0; top: 0; width: 6px; height: 100%; }
    .border-start { border-left: 1.5px dashed #ddd !important; }
    .font-monospace { font-family: monospace; }
</style>

@if(Auth::check() && (Auth::user()->isAdmin() || Auth::user()->role === 'organizer'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.activate-ticket-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const ticketId = this.dataset.id;
                    const url = this.dataset.url;
                    
                    Swal.fire({
                        title: 'Verify Entry?',
                        text: 'This will mark the ticket as USED.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        confirmButtonText: 'Yes, Verify'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(url, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('Success', data.message, 'success').then(() => location.reload());
                                } else {
                                    Swal.fire('Error', data.message, 'error');
                                }
                            });
                        }
                    });
                });
            });
        });
    </script>
@endif
@endsection
