@extends('exports.layouts.pdf')

@section('content')
    <div style="margin-bottom: 25px; border: 1px solid #ddd; padding: 15px; border-radius: 8px; background-color: #f9f9f9;">
        <h3 style="margin-top: 0; color: #333;">Event: {{ $event->title }}</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 25%; font-weight: bold;">Date:</td>
                <td style="width: 25%;">{{ \Carbon\Carbon::parse($event->schedule_time)->format('d M Y, H:i') }}</td>
                <td style="width: 25%; font-weight: bold;">Total Revenue:</td>
                <td style="width: 25%;">Rp {{ number_format($total_revenue, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Location:</td>
                <td>{{ $event->location }}</td>
                <td style="font-weight: bold;">Tickets Sold:</td>
                <td>{{ $total_tickets }}</td>
            </tr>
        </table>
    </div>

    <h4 style="border-bottom: 2px solid #dc143c; padding-bottom: 5px; color: #dc143c;">Order Details</h4>
    <table class="data-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th class="text-center">Tickets</th>
                <th class="text-right">Amount</th>
                <th class="text-right">Payment Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td class="font-bold">#{{ $order->transaction_id }}</td>
                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                    <td class="text-center">{{ $order->total_ticket }}</td>
                    <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $order->payment_date ? \Carbon\Carbon::parse($order->payment_date)->format('d/m/Y H:i') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No verified orders found for this event.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
