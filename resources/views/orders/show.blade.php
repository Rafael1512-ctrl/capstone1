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
                                                <h6 class="mb-0">{{ $ticket->name ?? 'Tiket Acara' }}</h6>
                                                <small class="text-muted">{{ $order->event->name }}</small>
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
                        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center p-4">
                            <i class="fas fa-check-circle fa-3x me-4 text-success"></i>
                            <div>
                                <h5 class="alert-heading mb-1">Pembayaran Berhasil!</h5>
                                <p class="mb-0">Tiket Anda Anda sudah aktif. Silakan tunjukkan QR Code saat masuk ke lokasi acara.</p>
                            </div>
                        </div>
                        
                        <div class="text-center mt-5">
                            <h5 class="mb-4">Digital Ticket & QR Code</h5>
                            <div class="d-inline-block bg-white p-4 rounded shadow-sm border">
                                <i class="fas fa-qrcode fa-10x"></i>
                                <p class="mt-3 mb-0 font-monospace small text-uppercase">Code: {{ $ticket->ticket_id ?? 'N/A' }}</p>
                            </div>
                            <div class="mt-4">
                                <a href="#" class="btn btn-outline-primary me-2"><i class="fas fa-download me-1"></i> Simpan Ticket</a>
                                <a href="{{ route('events.index') }}" class="btn btn-primary">Kembali ke Beranda</a>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center p-4">
                            <i class="fas fa-exclamation-triangle fa-3x me-4 text-warning"></i>
                            <div>
                                <h5 class="alert-heading mb-1">Menunggu Pembayaran</h5>
                                <p class="mb-0">Pesanan telah dibuat. Silakan selesaikan pembayaran melalui <strong>{{ $order->payment_method }}</strong> Anda.</p>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-6">
                                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary w-100">Cek Status Pesanan</a>
                            </div>
                            <div class="col-md-6">
                                <form action="{{ route('orders.simulate-payment', $order->transaction_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100">Simulasi Bayar Sekarang</button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
