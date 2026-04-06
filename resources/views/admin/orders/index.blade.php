@extends('layouts.master')

@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-white">Order Management</h1>
            <p class="text-muted small">Monitor and manage all customer transactions.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-white">All Transactions ({{ $orders->total() }})</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="py-3 px-4">Transaction Details</th>
                            <th class="py-3">Customer</th>
                            <th class="py-3 text-center">Tickets</th>
                            <th class="py-3">Amount</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3">Purchase Date & Time</th>
                            <th class="py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td class="px-4 py-3 align-middle">
                                    <div class="font-weight-bold text-white">{{ substr($order->event->title ?? 'N/A', 0, 30) }}...</div>
                                    <code class="small" style="color: var(--tix-red);">#{{ $order->transaction_id }}</code>
                                </td>
                                <td class="py-3 align-middle">
                                    <div class="font-weight-bold">{{ $order->user->name ?? 'Unknown User' }}</div>
                                    <div class="small text-muted">{{ $order->user->email ?? 'N/A' }}</div>
                                </td>
                                <td class="py-3 align-middle text-center">
                                    <div class="h5 mb-0 font-weight-bold" style="color: var(--tix-red);">{{ $order->total_ticket }}</div>
                                    <div class="text-muted small text-uppercase">Tickets</div>
                                </td>
                                <td class="py-3 align-middle">
                                    <div class="font-weight-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                                </td>
                                <td class="py-3 align-middle text-center">
                                    @php
                                        $badgeClass = match($order->payment_status) {
                                            'Verified' => 'bg-success',
                                            'Pending' => 'bg-warning text-dark',
                                            'Failed' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} px-3 py-2" style="font-size: 10px;">{{ strtoupper($order->payment_status) }}</span>
                                </td>
                                <td class="py-3 align-middle">
                                    <div class="font-weight-bold">{{ $order->payment_date ? $order->payment_date->format('d M Y') : '-' }}</div>
                                    <div class="text-muted small">Time: {{ $order->payment_date ? $order->payment_date->format('H:i') : '-' }} WIB</div>
                                </td>
                                <td class="py-3 align-middle text-center">
                                    <a href="{{ route('admin.orders.show', $order->transaction_id) }}" class="btn btn-outline-info btn-lg rounded-circle" title="View Detail (Lihat Tiket)" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">No orders found.</td>
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

<style>
    .table td { vertical-align: middle; }
    .btn-outline-info:hover { color: #fff !important; }
</style>
@endsection
