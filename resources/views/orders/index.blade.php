@extends('layouts.master')

@section('ExtraCSS')
<style>
    .order-card { border-radius: 12px; transition: 0.3s; }
    .order-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    .badge-paid { background: rgba(40, 167, 69, 0.1); color: #28a745; border: 1px solid #28a745; }
    .badge-pending { background: rgba(255, 193, 7, 0.1); color: #ffc107; border: 1px solid #ffc107; }
    .table td { vertical-align: middle; }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-dark">Riwayat Pesanan Saya</h1>
            <p class="text-muted small">Kelola semua tiket konser yang telah Anda beli.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4 order-card">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light text-muted small text-uppercase font-weight-bold">
                        <tr>
                            <th class="py-3 px-4">Detail Transaksi</th>
                            <th class="py-3 text-center">Jumlah Tiket</th>
                            <th class="py-3">Total Harga</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3">Waktu Beli</th>
                            <th class="py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-weight-bold text-dark">{{ substr($order->event->title ?? 'N/A', 0, 40) }}...</div>
                                    <code class="text-primary small">#{{ $order->transaction_id }}</code>
                                </td>
                                <td class="py-3 text-center">
                                    <div class="h5 mb-0 font-weight-bold text-primary">{{ $order->total_ticket }}</div>
                                    <div class="text-muted small text-uppercase">Tiket</div>
                                </td>
                                <td class="py-3 font-weight-bold">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="py-3 text-center">
                                    @php
                                        $statusClass = $order->payment_status === 'Verified' ? 'badge-success' : ($order->payment_status === 'Pending' ? 'badge-warning' : 'badge-danger');
                                    @endphp
                                    <span class="badge {{ $statusClass }} px-3 py-2" style="font-size: 10px;">{{ strtoupper($order->payment_status) }}</span>
                                </td>
                                <td class="py-3">
                                    <div class="font-weight-bold">{{ $order->payment_date ? $order->payment_date->format('d M Y') : '-' }}</div>
                                    <div class="text-muted small">Jam: {{ $order->payment_date ? $order->payment_date->format('H:i') : '-' }} WIB</div>
                                </td>
                                <td class="py-3 text-center text-nowrap">
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <a href="{{ route('orders.show', $order->transaction_id) }}" class="btn btn-info rounded-circle d-inline-flex align-items-center justify-content-center" title="Lihat Tiket" style="width: 38px; height: 38px;">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($order->payment_status === 'Pending')
                                            <form action="{{ route('orders.simulate-payment', $order->transaction_id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success rounded-circle d-inline-flex align-items-center justify-content-center ml-2" title="Bayar Sekarang" style="width: 38px; height: 38px;">
                                                    <i class="fas fa-credit-card"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-receipt d-block h1 mb-3"></i>
                                    Anda belum memiliki riwayat pesanan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($orders->fields ?? false && $orders->hasPages())
                <div class="p-4 border-top">
                    {{ $orders->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
