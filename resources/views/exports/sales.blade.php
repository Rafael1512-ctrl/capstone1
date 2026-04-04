@extends('exports.layouts.pdf')

@section('content')
    <table class="data-table">
        <thead>
            <tr>
                <th>Periode</th>
                <th class="text-center">Total Orders</th>
                <th class="text-right">Total Revenue</th>
                <th class="text-right">Rata-rata per Order</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salesData as $item)
                <tr>
                    <td class="font-bold">{{ $item->period }}</td>
                    <td class="text-center">{{ $item->total_orders }}</td>
                    <td class="text-right">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->total_revenue / $item->total_orders, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot style="background-color: #eee; font-weight: bold;">
            <tr>
                <td>TOTAL</td>
                <td class="text-center">{{ $salesData->sum('total_orders') }}</td>
                <td class="text-right">Rp {{ number_format($salesData->sum('total_revenue'), 0, ',', '.') }}</td>
                <td class="text-right">
                    @if($salesData->sum('total_orders') > 0)
                        Rp {{ number_format($salesData->sum('total_revenue') / $salesData->sum('total_orders'), 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
        </tfoot>
    </table>
@endsection
