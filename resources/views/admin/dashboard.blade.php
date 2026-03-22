@extends('layouts.master')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
            <div>
                <h1 class="h3 mb-0 text-dark">Admin</h1>
                <p class="text-muted small">Selamat datang, {{ Auth::user()->name }}</p>
            </div>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Event Baru
                </a>
                <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-list"></i> Kelola Event
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card border-left-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-2">Total Revenue</p>
                                <h2 class="h4 mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                            </div>
                            <div class="text-primary" style="font-size: 2rem;">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                        <small class="text-success">Hari ini: Rp {{ number_format($todayRevenue, 0, ',', '.') }}</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card border-left-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-2">Total Events</p>
                                <h2 class="h4 mb-0">{{ $totalEvents }}</h2>
                            </div>
                            <div class="text-success" style="font-size: 2rem;">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                        <small class="text-muted">Dari {{ $totalOrganizers }} organizer</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card border-left-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-2">Total Orders</p>
                                <h2 class="h4 mb-0">{{ $totalOrders }}</h2>
                            </div>
                            <div class="text-info" style="font-size: 2rem;">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                        <small class="text-success">Dibayar: {{ $paidOrders }}</small>
                        <small class="d-block text-warning">Pending: {{ $pendingOrders }}</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card border-left-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-2">Total Users</p>
                                <h2 class="h4 mb-0">{{ $totalUsers }}</h2>
                            </div>
                            <div class="text-warning" style="font-size: 2rem;">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <small class="text-muted">Organizer: {{ $totalOrganizers }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Month Revenue -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <p class="text-muted small mb-2">Revenue Bulan Ini</p>
                        <h2 class="h3 text-success mb-0">Rp {{ number_format($monthRevenue, 0, ',', '.') }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-8 mb-3">
                <div class="card">
                    <div class="card-header border-bottom d-flex justify-content-between">
                        <h5 class="card-title mb-0">Penjualan 7 Hari Terakhir</h5>
                        <a href="{{ route('admin.analytics.sales') }}" class="small">Lihat detail</a>
                    </div>
                    <div class="card-body">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events & Organizers -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-trending-up text-success"></i> Top 5 Events
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @forelse($topEvents as $event)
                                <a href="{{ route('admin.events.show', $event) }}"
                                    class="list-group-item list-group-item-action d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1">{{ $event->title }}</h6>
                                        <small class="text-muted">
                                            {{ $event->category?->name ?? 'Uncategorized' }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <div class="badge bg-success">{{ $event->orders_count }}</div>
                                        <small class="d-block text-muted">orders</small>
                                    </div>
                                </a>
                            @empty
                                <p class="text-muted text-center py-3">Belum ada event</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user-tie text-info"></i> Top 5 Organizers
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @forelse($topOrganizers as $organizer)
                                <div class="list-group-item d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1">{{ $organizer->name }}</h6>
                                        <small class="text-muted">{{ $organizer->email }}</small>
                                    </div>
                                    <div class="text-end">
                                        <div class="badge bg-primary">{{ $organizer->events_count }}</div>
                                        <small class="d-block text-muted">events</small>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted text-center py-3">Belum ada organizer</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Links -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Analytics & Reports</h5>
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('admin.analytics.sales') }}"
                                    class="btn btn-outline-primary btn-block w-100">
                                    <i class="fas fa-chart-bar"></i> Sales Analytics
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('admin.analytics.transactions') }}"
                                    class="btn btn-outline-info btn-block w-100">
                                    <i class="fas fa-exchange-alt"></i> Transactions
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('admin.analytics.event-performance') }}"
                                    class="btn btn-outline-success btn-block w-100">
                                    <i class="fas fa-star"></i> Event Performance
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('admin.analytics.user-stats') }}"
                                    class="btn btn-outline-warning btn-block w-100">
                                    <i class="fas fa-user-chart"></i> User Stats
                                </a>
                            </div>
                        </div>
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

        .border-left-info {
            border-left: 4px solid #17a2b8 !important;
        }

        .border-left-warning {
            border-left: 4px solid #ffc107 !important;
        }

        .btn-block {
            display: block !important;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        // Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const dates = {!! json_encode($dailySales->pluck('date')) !!};
        const revenues = {!! json_encode($dailySales->pluck('revenue')) !!};
        const orders = {!! json_encode($dailySales->pluck('orders')) !!};

        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: dates,
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