@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title">My Dashboard</h4>
            </div>

            <!-- Statistics Row -->
            <div class="row">
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-primary card-round shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-3">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-ticket-alt"></i>
                                    </div>
                                </div>
                                <div class="col-9 col-stats">
                                    <div class="numbers text-end">
                                        <p class="card-category">Tiket Saya</p>
                                        <h4 class="card-title">{{ $stats['total_tickets'] ?? 0 }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-success card-round shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-3">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </div>
                                <div class="col-9 col-stats">
                                    <div class="numbers text-end">
                                        <p class="card-category">Sudah Dipakai</p>
                                        <h4 class="card-title">{{ $stats['used_tickets'] ?? 0 }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-info card-round shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-3">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-hourglass-half"></i>
                                    </div>
                                </div>
                                <div class="col-9 col-stats">
                                    <div class="numbers text-end">
                                        <p class="card-category">Tiket Aktif</p>
                                        <h4 class="card-title">{{ $stats['active_tickets'] ?? 0 }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-warning card-round shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-3">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                </div>
                                <div class="col-9 col-stats">
                                    <div class="numbers text-end">
                                        <p class="card-category">Pesanan</p>
                                        <h4 class="card-title">{{ $stats['total_orders'] ?? 0 }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <!-- Recent Tickets -->
                <div class="col-md-7">
                    <div class="card card-round border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Tiket Terbaru</h5>
                                <a href="{{ route('tickets.index') }}" class="btn btn-primary btn-xs px-3">Lihat Semua</a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4">Tiket #</th>
                                            <th>Event</th>
                                            <th>Tipe</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($tickets->take(5) ?? [] as $ticket)
                                            <tr>
                                                <td class="ps-4">
                                                    <a href="{{ route('tickets.view', $ticket->ticket_id) }}" class="fw-bold">
                                                        {{ substr($ticket->ticket_id, -8) }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <div class="text-truncate" style="max-width: 151px;">
                                                        {{ $ticket->event->name ?? 'N/A' }}
                                                    </div>
                                                </td>
                                                <td><span class="small">{{ $ticket->ticketType->name ?? 'N/A' }}</span></td>
                                                <td>
                                                    <span class="badge rounded-pill bg-{{ $ticket->ticket_status === 'Active' ? 'success' : 'secondary' }}">
                                                        {{ $ticket->ticket_status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-5 text-muted">Belum ada tiket terjual</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="col-md-5">
                    <div class="card card-round border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Pesanan Terakhir</h5>
                                <a href="{{ route('orders.index') }}" class="btn btn-outline-primary btn-xs px-3">Riwayat</a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4">ID Transaksi</th>
                                            <th class="text-end pe-4">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($orders->take(5) ?? [] as $order)
                                            <tr>
                                                <td class="ps-4">
                                                    <a href="{{ route('orders.show', $order->transaction_id) }}" class="small">
                                                        #{{ $order->transaction_id }}
                                                    </a>
                                                    <br>
                                                    <small class="text-muted smaller">
                                                        {{ $order->payment_date->format('d M, H:i') }}
                                                    </small>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <span class="badge {{ $order->payment_status === 'Verified' ? 'bg-success' : 'bg-warning text-dark' }} px-3">
                                                        {{ $order->payment_status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="text-center py-5 text-muted">Belum ada pesanan</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Action -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card card-round bg-primary d-flex align-items-center py-4 border-0 shadow-sm">
                        <div class="card-body text-center text-white">
                            <h4 class="mb-3">Ingin nonton konser lagi?</h4>
                            <p class="mb-4 opacity-75">Temukan event-event menarik lainnya dan pesan tiketnya sekarang!</p>
                            <a href="{{ route('events.index') }}" class="btn btn-white btn-round px-4 text-primary fw-bold">Jelajahi Event</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
