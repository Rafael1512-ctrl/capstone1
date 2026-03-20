@extends('layouts.master')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
            <h1 class="h3 mb-0">Transaction Analytics</h1>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <select class="form-select" name="days" onchange="this.form.submit()">
                            <option value="7" {{ request('days', 30) == 7 ? 'selected' : '' }}>7 Hari Terakhir</option>
                            <option value="30" {{ request('days', 30) == 30 ? 'selected' : '' }}>30 Hari Terakhir
                            </option>
                            <option value="90" {{ request('days') == 90 ? 'selected' : '' }}>90 Hari Terakhir</option>
                            <option value="365" {{ request('days') == 365 ? 'selected' : '' }}>1 Tahun Terakhir</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- Payment Methods Stats -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Metode Pembayaran</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Metode</th>
                                    <th>Total Transaksi</th>
                                    <th>Total Amount</th>
                                    <th>Rata-rata</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paymentMethods as $method)
                                    <tr>
                                        <td><strong>{{ $method->payment_method ?? 'Unknown' }}</strong></td>
                                        <td>{{ $method->count }}</td>
                                        <td>Rp {{ number_format($method->total, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($method->total / $method->count, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction Status -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Status Transaksi</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="transactionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        const ctx = document.getElementById('transactionChart').getContext('2d');

        // Group data by status
        const data = {!! json_encode($transactionData) !!};
        const groupedData = {};

        data.forEach(item => {
            if (!groupedData[item.status]) {
                groupedData[item.status] = {};
            }
            groupedData[item.status][item.date] = item.count;
        });

        const dates = [...new Set(data.map(d => d.date))];
        const statuses = [...new Set(data.map(d => d.status))];
        const colors = ['#28a745', '#ffc107', '#dc3545'];

        const datasets = statuses.map((status, index) => ({
            label: status.charAt(0).toUpperCase() + status.slice(1),
            data: dates.map(date => groupedData[status]?.[date] ?? 0),
            borderColor: colors[index] || '#999',
            backgroundColor: colors[index] + '33',
            tension: 0.4,
            fill: true
        }));

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: datasets
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
