@extends('exports.layouts.pdf')

@section('content')
    <div style="background-color: #1a1a2e; color: white; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
        <h3 style="margin: 0; text-transform: uppercase; letter-spacing: 2px;">Grand Summary</h3>
        <table style="width: 100%; margin-top: 15px; color: white;">
            <tr>
                <td style="width: 25%; font-size: 14px;">Total Revenue:</td>
                <td style="width: 25%; font-size: 18px; font-weight: bold;">Rp {{ number_format($grand_total_revenue, 0, ',', '.') }}</td>
                <td style="width: 25%; font-size: 14px;">Total Tickets Sold:</td>
                <td style="width: 25%; font-size: 18px; font-weight: bold;">{{ $grand_total_tickets }}</td>
            </tr>
        </table>
    </div>

    @foreach($events as $data)
        @php $event = $data['event']; @endphp
        <div style="page-break-inside: avoid; margin-bottom: 40px;">
            <div style="border-left: 5px solid #dc143c; padding-left: 15px; margin-bottom: 15px;">
                <h3 style="margin: 0; color: #333;">{{ $event->title }}</h3>
                <p style="margin: 5px 0; color: #666; font-size: 10px;">
                    Category: {{ $event->category->name ?? 'N/A' }} | Date: {{ \Carbon\Carbon::parse($event->schedule_time)->format('d M Y') }}
                </p>
            </div>

            <table style="width: 100%; margin-bottom: 10px; background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                <tr>
                    <td><strong>Event Revenue:</strong> Rp {{ number_format($data['total_revenue'], 0, ',', '.') }}</td>
                    <td><strong>Sold:</strong> {{ $data['total_tickets'] }} Tickets</td>
                </tr>
            </table>

            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 20%;">Order ID</th>
                        <th style="width: 30%;">Customer</th>
                        <th style="width: 15%;" class="text-center">Tickets</th>
                        <th style="width: 20%;" class="text-right">Amount</th>
                        <th style="width: 15%;" class="text-right">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['orders'] as $order)
                        <tr>
                            <td class="font-bold">#{{ $order->transaction_id }}</td>
                            <td>{{ $order->user->name ?? 'N/A' }}</td>
                            <td class="text-center">{{ $order->total_ticket }}</td>
                            <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="text-right">{{ $order->payment_date ? $order->payment_date->format('d/m/y') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center" style="color: #999; font-style: italic;">No verified orders for this event.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(!$loop->last)
            <hr style="border: 1px dashed #ddd; margin: 30px 0;">
        @endif
    @endforeach
@endsection
