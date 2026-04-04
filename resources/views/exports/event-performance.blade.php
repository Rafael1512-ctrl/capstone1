@extends('exports.layouts.pdf')

@section('content')
    <table class="data-table">
        <thead>
            <tr>
                <th width="20%">Event</th>
                <th width="12%">Kategori</th>
                <th width="12%">Tanggal</th>
                <th width="10%">Status</th>
                <th class="text-center" width="8%">Kuota</th>
                <th class="text-center" width="8%">Terjual</th>
                <th class="text-center" width="10%">Fill Rate</th>
                <th class="text-right" width="12%">Revenue</th>
                <th class="text-center" width="8%">Orders</th>
            </tr>
        </thead>
        <tbody>
            @forelse($events as $event)
                <tr>
                    <td class="font-bold">{{ $event['name'] }}</td>
                    <td>{{ $event['category'] ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($event['date'])->format('d M Y') }}</td>
                    <td class="text-center">
                        @if ($event['status'] === 'Tersedia')
                            <span style="color: #28a745; font-weight: bold;">Tersedia</span>
                        @elseif($event['status'] === 'Draft')
                            <span style="color: #ffc107; font-weight: bold;">Draft</span>
                        @elseif($event['status'] === 'Selesai')
                            <span style="color: #17a2b8; font-weight: bold;">Selesai</span>
                        @else
                            <span style="color: #dc3545; font-weight: bold;">{{ $event['status'] }}</span>
                        @endif
                    </td>
                    <td class="text-center">{{ $event['total_available'] }}</td>
                    <td class="text-center">{{ $event['total_sold'] }}</td>
                    <td class="text-center">{{ $event['availability_rate'] }}%</td>
                    <td class="text-right">Rp {{ number_format($event['revenue'] ?? 0, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $event['orders_count'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center py-4">Tidak ada data event</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot style="background-color: #eee; font-weight: bold;">
            <tr>
                <td colspan="4">TOTAL</td>
                <td class="text-center">{{ collect($events)->sum('total_available') }}</td>
                <td class="text-center">{{ collect($events)->sum('total_sold') }}</td>
                <td class="text-center">
                    @if(collect($events)->sum('total_available') > 0)
                        {{ round((collect($events)->sum('total_sold') / collect($events)->sum('total_available')) * 100, 2) }}%
                    @else
                        -
                    @endif
                </td>
                <td class="text-right">Rp {{ number_format(collect($events)->sum('revenue'), 0, ',', '.') }}</td>
                <td class="text-center">{{ collect($events)->sum('orders_count') }}</td>
            </tr>
        </tfoot>
    </table>
@endsection
