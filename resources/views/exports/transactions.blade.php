@extends('exports.layouts.pdf')

@section('content')
    <h3>Log Transaksi Per Hari</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th width="30%">Tanggal</th>
                <th width="30%" class="text-center">Status</th>
                <th width="40%" class="text-right">Jumlah Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactionData as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</td>
                    <td class="text-center">
                        @if($item->status == 'Verified' || $item->status == 'Completed')
                            <span style="color: #28a745; font-weight: bold;">Verified</span>
                        @elseif($item->status == 'Pending')
                            <span style="color: #ffc107; font-weight: bold;">Pending</span>
                        @else
                            <span style="color: #dc3545; font-weight: bold;">{{ $item->status }}</span>
                        @endif
                    </td>
                    <td class="text-right">{{ $item->count }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot style="background-color: #eee; font-weight: bold;">
            <tr>
                <td colspan="2">TOTAL</td>
                <td class="text-right">{{ $transactionData->sum('count') }}</td>
            </tr>
        </tfoot>
    </table>

    <h3 style="margin-top: 30px;">Statistik Metode Pembayaran</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Metode Pembayaran</th>
                <th class="text-center">Total Transaksi</th>
                <th class="text-right">Total Amount</th>
                <th class="text-right">Rata-rata</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paymentMethods as $method)
                <tr>
                    <td>{{ $method->payment_method ?? 'Unknown' }}</td>
                    <td class="text-center">{{ $method->count }}</td>
                    <td class="text-right">Rp {{ number_format($method->total, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($method->total / $method->count, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot style="background-color: #eee; font-weight: bold;">
            <tr>
                <td>TOTAL</td>
                <td class="text-center">{{ $paymentMethods->sum('count') }}</td>
                <td class="text-right">Rp {{ number_format($paymentMethods->sum('total'), 0, ',', '.') }}</td>
                <td class="text-right">
                    @if($paymentMethods->sum('count') > 0)
                        Rp {{ number_format($paymentMethods->sum('total') / $paymentMethods->sum('count'), 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
        </tfoot>
    </table>
@endsection
