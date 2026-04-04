@extends('exports.layouts.pdf')

@section('content')
    <div style="margin-bottom: 20px;">
        <table style="width: 100%; border-collapse: separate; border-spacing: 10px;">
            <tr>
                <td width="33%" style="border: 1px solid #ddd; padding: 15px; text-align: center; border-radius: 8px; border-left: 5px solid #007bff;">
                    <h2 style="margin: 0; color: #007bff;">{{ $totalUsers }}</h2>
                    <p style="margin: 5px 0 0; font-size: 10px; color: #666;">TOTAL USERS</p>
                </td>
                <td width="33%" style="border: 1px solid #ddd; padding: 15px; text-align: center; border-radius: 8px; border-left: 5px solid #28a745;">
                    <h2 style="margin: 0; color: #28a745;">{{ $totalOrganizers }}</h2>
                    <p style="margin: 5px 0 0; font-size: 10px; color: #666;">TOTAL ORGANIZERS</p>
                </td>
                <td width="33%" style="border: 1px solid #ddd; padding: 15px; text-align: center; border-radius: 8px; border-left: 5px solid #ffc107;">
                    <h2 style="margin: 0; color: #ffc107;">{{ $totalAdmins }}</h2>
                    <p style="margin: 5px 0 0; font-size: 10px; color: #666;">TOTAL ADMINS</p>
                </td>
            </tr>
        </table>
    </div>

    <h3 style="margin-top: 30px;">Top 10 Pembeli Teraktif</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="30%">Nama</th>
                <th width="35%">Email</th>
                <th class="text-center" width="15%">Total Orders</th>
                <th class="text-right" width="15%">Total Spent</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topBuyers as $key => $buyer)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td class="font-bold">{{ $buyer->name }}</td>
                    <td>{{ $buyer->email }}</td>
                    <td class="text-center">{{ $buyer->orders_count }}</td>
                    <td class="text-right">Rp {{ number_format($buyer->total_spent ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4">Belum ada pembeli</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot style="background-color: #eee; font-weight: bold;">
            <tr>
                <td colspan="3">TOTAL (TOP 10)</td>
                <td class="text-center">{{ $topBuyers->sum('orders_count') }}</td>
                <td class="text-right">Rp {{ number_format($topBuyers->sum('total_spent'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
@endsection
