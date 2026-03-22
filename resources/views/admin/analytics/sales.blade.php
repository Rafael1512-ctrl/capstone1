@extends('layouts.master')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
            <h1 class="h3 mb-0">Sales Analytics</h1>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <select class="form-select" name="period" onchange="this.form.submit()">
                            <option value="daily" {{ request('period', 'monthly') === 'daily' ? 'selected' : '' }}>Harian
                                (7 Hari)</option>
                            <option value="weekly" {{ request('period') === 'weekly' ? 'selected' : '' }}>Mingguan (30 Hari)
                            </option>
                            <option value="monthly" {{ request('period', 'monthly') === 'monthly' ? 'selected' : '' }}>
                                Bulanan (90 Hari)</option>
                            <option value="yearly" {{ request('period') === 'yearly' ? 'selected' : '' }}>Tahunan</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('admin.export.sales', ['period' => $period]) }}"
                            class="btn btn-outline-success w-100">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Chart -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Revenue & Orders Trend</h5>
            </div>
            <div class="card-body">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Periode</th>
                            <th>Total Orders</th>
                            <th>Total Revenue</th>
                            <th>Rata-rata per Order</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salasData as $data)
                            <tr>
                                <td><strong>{{ $data->period }}</strong></td>
                                <td>{{ $data->total_orders }}</td>
                                <td>Rp {{ number_format($data->total_revenue, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($data->total_revenue / $data->total_orders, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const periods = {!! json_encode($salasData->pluck('period')) !!};
        const orders = {!! json_encode($salasData->pluck('total_orders')) !!};
        const revenues = {!! json_encode($salasData->pluck('total_revenue')) !!};

        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: periods,
                datasets: [{
                        label: 'Revenue (Rp)',
                        data: revenues,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Orders',
                        data: orders,
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Revenue (Rp)'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Orders'
                        },
                        grid: {
                            drawOnChartArea: false,
                        }
                    }
                }
            }
        });
    </script>
@endsection
