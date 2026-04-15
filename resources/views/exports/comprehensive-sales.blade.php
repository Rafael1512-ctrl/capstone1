@extends('exports.layouts.pdf')

@section('content')
    <!-- 1. GRAND SUMMARY CARD -->
    <div style="background-color: #1a1a2e; color: white; padding: 25px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        <h3 style="margin: 0; text-transform: uppercase; letter-spacing: 2px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px;">Ringkasan Pendapatan</h3>
        <table style="width: 100%; margin-top: 15px; color: white;">
            <tr>
                <td style="width: 25%; font-size: 14px; opacity: 0.8;">Total Revenue:</td>
                <td style="width: 25%; font-size: 18px; font-weight: bold; color: #ff6080;">Rp {{ number_format($grand_total_revenue, 0, ',', '.') }}</td>
                <td style="width: 25%; font-size: 14px; opacity: 0.8;">Total Tiket Terjual:</td>
                <td style="width: 25%; font-size: 18px; font-weight: bold; color: #60a5fa;">{{ $grand_total_tickets }}</td>
            </tr>
        </table>
    </div>

    <!-- 2. PERIODIC OVERVIEW TABLE -->
    <h4 style="color: #333; border-left: 4px solid #dc143c; padding-left: 10px; margin-bottom: 15px; text-transform: uppercase;">Overview Penjualan Berbayar ({{ $period_label }})</h4>
    <table class="data-table">
        <thead>
            <tr>
                <th>Periode</th>
                <th class="text-center">Total Orders</th>
                <th class="text-right">Total Revenue</th>
                <th class="text-right">Avg / Order</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salesOverview as $item)
                <tr>
                    <td class="font-bold">{{ $item->period }}</td>
                    <td class="text-center">{{ $item->total_orders }}</td>
                    <td class="text-right">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->total_revenue / $item->total_orders, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="page-break-after: always;"></div>

    <!-- 3. DETAILED EVENT BREAKDOWN -->
    <h4 style="color: #333; border-left: 4px solid #dc143c; padding-left: 10px; margin-bottom: 25px; text-transform: uppercase;">Rincian Penjualan Per Event</h4>

    @foreach($eventsData as $data)
        @php $event = $data['event']; @endphp
        <div style="page-break-inside: avoid; margin-bottom: 40px; border: 1px solid #eee; border-radius: 8px; overflow: hidden;">
            <div style="background-color: #f8f9fa; padding: 15px; border-bottom: 1px solid #eee;">
                <h3 style="margin: 0; color: #dc143c;">{{ $event->title }}</h3>
                <p style="margin: 5px 0 0; color: #666; font-size: 10px;">
                    <strong>Kategori:</strong> {{ $event->category->name ?? 'N/A' }} | 
                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($event->schedule_time)->format('d M Y') }} |
                    <strong>Revenue:</strong> Rp {{ number_format($data['total_revenue'], 0, ',', '.') }}
                </p>
            </div>

            <table class="data-table" style="margin: 0; border: none;">
                <thead style="background-color: #fff;">
                    <tr>
                        <th style="width: 20%; border-left: none;">ID Transaksi</th>
                        <th style="width: 30%;">User</th>
                        <th style="width: 15%; text-align: center;">Qty</th>
                        <th style="width: 20%; text-align: right;">Total</th>
                        <th style="width: 15%; text-align: right; border-right: none;">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['orders'] as $order)
                        <tr>
                            <td class="font-bold" style="border-left: none;">#{{ $order->transaction_id }}</td>
                            <td>{{ $order->user->name ?? 'N/A' }}</td>
                            <td class="text-center">{{ $order->total_ticket }}</td>
                            <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="text-right" style="border-right: none;">{{ $order->payment_date ? $order->payment_date->format('H:i') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center" style="color: #999; font-style: italic; border-left: none; border-right: none;">Tidak ada transaksi terverifikasi untuk event ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endforeach
@endsection
