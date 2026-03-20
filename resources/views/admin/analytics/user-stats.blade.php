@extends('layouts.master')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
            <h1 class="h3 mb-0">User Statistics</h1>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- User Stats -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card border-left-primary">
                    <div class="card-body text-center">
                        <h2 class="h4 text-primary">{{ $totalUsers }}</h2>
                        <p class="text-muted small mb-0">Total Users</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-left-success">
                    <div class="card-body text-center">
                        <h2 class="h4 text-success">{{ $totalOrganizers }}</h2>
                        <p class="text-muted small mb-0">Total Organizers</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-left-warning">
                    <div class="card-body text-center">
                        <h2 class="h4 text-warning">{{ $totalAdmins }}</h2>
                        <p class="text-muted small mb-0">Total Admins</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Growth Chart -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">User Growth (30 Hari Terakhir)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="growthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Buyers -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Top 10 Pembeli</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Total Orders</th>
                                    <th>Total Spent</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topBuyers as $key => $buyer)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td><strong>{{ $buyer->name }}</strong></td>
                                        <td>{{ $buyer->email }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $buyer->orders_count }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $spent = \App\Models\Order::where('user_id', $buyer->id)
                                                    ->where('status', 'paid')
                                                    ->sum('total_amount');
                                            @endphp
                                            Rp {{ number_format($spent, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">Belum ada pembeli</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .border-left-primary {
            border-left: 4px solid #007bff !important;
        }

        .border-left-success {
            border-left: 4px solid #28a745 !important;
        }

        .border-left-warning {
            border-left: 4px solid #ffc107 !important;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        const growthCtx = document.getElementById('growthChart').getContext('2d');
        const dates = {!! json_encode($usersGrowth->pluck('date')) !!};
        const counts = {!! json_encode($usersGrowth->pluck('count')) !!};

        new Chart(growthCtx, {
            type: 'bar',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Pengguna Baru',
                    data: counts,
                    borderColor: '#007bff',
                    backgroundColor: '#007bff',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
