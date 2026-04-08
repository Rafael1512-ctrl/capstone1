@extends('layouts.master')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('admin.analytics.event-performance') }}">Event Performance</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail Report</li>
                    </ol>
                </nav>
                <h1 class="h3 mb-0">{{ $event->title }}</h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.export.event-performance-pdf', ['event' => $event->event_id]) }}" class="btn btn-outline-primary">
                    <i class="fas fa-file-pdf"></i> Export Detail PDF
                </a>
                <a href="{{ route('admin.analytics.event-performance') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Left Stats -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h5 class="card-title mb-0 text-muted small text-uppercase fw-bold">Ticket Consumption</h5>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="flex-grow-1">
                                <h2 class="mb-0 fw-bold">{{ $ticketStats['used'] }} / {{ $event->tickets()->count() }}</h2>
                                <p class="text-muted mb-0 small">Tiket yang sudah di-scan (Used)</p>
                            </div>
                            <div class="ms-3">
                                <div class="p-3 bg-success-light rounded-circle text-success">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1 small text-muted">
                                <span>Used (Sudah Digunakan)</span>
                                <span>{{ $ticketStats['used'] }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $event->tickets()->count() > 0 ? ($ticketStats['used'] / $event->tickets()->count()) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1 small text-muted">
                                <span>Active (Belum Digunakan)</span>
                                <span>{{ $ticketStats['active'] }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-info" role="progressbar" style="width: {{ $event->tickets()->count() > 0 ? ($ticketStats['active'] / $event->tickets()->count()) * 100 : 0 }}%"></div>
                            </div>
                        </div>

                        <div class="mb-0">
                            <div class="d-flex justify-content-between mb-1 small text-muted">
                                <span>Expired (Kadaluwarsa)</span>
                                <span>{{ $ticketStats['expired'] }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $event->tickets()->count() > 0 ? ($ticketStats['expired'] / $event->tickets()->count()) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Revenue Stats -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h5 class="card-title mb-0 text-muted small text-uppercase fw-bold">Revenue & Efficiency</h5>
                    </div>
                    <div class="card-body px-4 pb-4 text-center d-flex flex-column justify-content-center">
                        <h1 class="display-6 fw-bold text-success mb-1">Rp {{ number_format($ticketTypeSales->sum('revenue'), 0, ',', '.') }}</h1>
                        <p class="text-muted mb-4">Total Pendapatan Terverifikasi</p>
                        
                        <div class="row text-center mt-3">
                            <div class="col-6 border-end border-light">
                                <h4 class="mb-0 fw-bold">{{ $ticketTypeSales->sum('sold') }}</h4>
                                <p class="text-muted small mb-0">Tiket Terjual</p>
                            </div>
                            <div class="col-6">
                                <h4 class="mb-0 fw-bold">{{ $event->total_available ?? $event->ticket_quota }}</h4>
                                <p class="text-muted small mb-0">Total Kuota</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Distribution Chart -->
            <div class="col-xl-4 col-md-12 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h5 class="card-title mb-0 text-muted small text-uppercase fw-bold">Sales per Category</h5>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <canvas id="categoryChart" style="max-height: 250px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h5 class="card-title mb-0 fw-bold text-dark">Detail Penggunaan per Tipe Tiket</h5>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div class="row">
                            @foreach($ticketTypeSales as $type)
                                @php
                                    $tUsed = $type['used'];
                                    $tActive = $type['active'];
                                    $tTotal = max(1, $tUsed + $tActive);
                                    $percentUsed = ($tUsed / $tTotal) * 100;
                                @endphp
                                <div class="col-md-4 mb-3">
                                    <div class="p-3 border rounded bg-light">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="fw-bold mb-0 text-primary">{{ $type['name'] }}</h6>
                                            <span class="badge bg-primary-light text-primary">{{ $type['sold'] }} Terjual</span>
                                        </div>
                                        <div class="row text-center mb-3">
                                            <div class="col-6">
                                                <div class="text-success fw-bold h5 mb-0">{{ $tUsed }}</div>
                                                <div class="small text-muted">Used</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-info fw-bold h5 mb-0">{{ $tActive }}</div>
                                                <div class="small text-muted">Active</div>
                                            </div>
                                        </div>
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentUsed }}%" title="Used"></div>
                                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ 100 - $percentUsed }}%" title="Active"></div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-1">
                                            <small class="text-muted">{{ round($percentUsed) }}% Terpakai</small>
                                            <small class="text-muted">Total: {{ $tUsed + $tActive }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sales per Ticket Type Table -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h5 class="card-title mb-0 fw-bold text-dark">Data Penjualan Per Tipe Tiket</h5>
                    </div>
                    <div class="card-body px-0 pb-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Tipe Tiket</th>
                                        <th>Harga</th>
                                        <th>Kapasitas</th>
                                        <th>Terjual</th>
                                        <th>Used</th>
                                        <th>Active</th>
                                        <th>Revenue</th>
                                        <th class="pe-4">Tingkat Penjualan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ticketTypeSales as $type)
                                        @php
                                            $typeUsed = $type['used'];
                                            $typeActive = $type['active'];
                                        @endphp
                                        <tr>
                                            <td class="ps-4"><strong>{{ $type['name'] }}</strong></td>
                                            <td>Rp {{ number_format($type['price'], 0, ',', '.') }}</td>
                                            <td>{{ $type['total'] }}</td>
                                            <td><span class="badge bg-light text-dark border">{{ $type['sold'] }}</span></td>
                                            <td><span class="badge bg-success">{{ $typeUsed }}</span></td>
                                            <td><span class="badge bg-info">{{ $typeActive }}</span></td>
                                            <td class="text-success fw-bold">Rp {{ number_format($type['revenue'], 0, ',', '.') }}</td>
                                            <td class="pe-4">
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2 small">{{ round(($type['sold'] / max(1, $type['total'])) * 100, 1) }}%</span>
                                                    <div class="progress flex-grow-1" style="height: 6px;">
                                                        <div class="progress-bar bg-primary" style="width: {{ ($type['sold'] / max(1, $type['total'])) * 100 }}%"></div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h5 class="card-title mb-0 fw-bold text-dark">Tren Penjualan (Harian)</h5>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <canvas id="salesTrendChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <!-- Event Info Card -->
                <div class="card shadow-sm border-0 bg-primary text-white">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Informasi Event</h5>
                        <div class="mb-2 d-flex">
                            <i class="fas fa-calendar-alt mt-1 me-2 opacity-75"></i>
                            <div>
                                <div class="small opacity-75">Waktu Pelaksanaan</div>
                                <div>{{ \Carbon\Carbon::parse($event->schedule_time)->format('l, d F Y H:i') }}</div>
                            </div>
                        </div>
                        <div class="mb-2 d-flex">
                            <i class="fas fa-map-marker-alt mt-1 me-2 opacity-75"></i>
                            <div>
                                <div class="small opacity-75">Lokasi</div>
                                <div>{{ $event->location }}</div>
                            </div>
                        </div>
                        <div class="mb-0 d-flex">
                            <i class="fas fa-user-tie mt-1 me-2 opacity-75"></i>
                            <div>
                                <div class="small opacity-75">Organizer</div>
                                <div>{{ $event->organizer->name ?? 'Unknown' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        // Category Distribution Chart
        const catCtx = document.getElementById('categoryChart').getContext('2d');
        const catLabels = {!! json_encode($ticketTypeSales->pluck('name')) !!};
        const catData = {!! json_encode($ticketTypeSales->pluck('sold')) !!};
        
        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: catLabels,
                datasets: [{
                    data: catData,
                    backgroundColor: [
                        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12
                        }
                    }
                },
                maintainAspectRatio: false
            }
        });

        // Sales Trend Chart
        const trendCtx = document.getElementById('salesTrendChart').getContext('2d');
        const trendLabels = {!! json_encode($salesTrend->pluck('date')) !!};
        const trendTickets = {!! json_encode($salesTrend->pluck('tickets')) !!};
        const trendRevenue = {!! json_encode($salesTrend->pluck('revenue')) !!};

        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [
                    {
                        label: 'Revenue (Rp)',
                        data: trendRevenue,
                        borderColor: '#1cc88a',
                        backgroundColor: 'rgba(28, 200, 138, 0.05)',
                        fill: true,
                        tension: 0.3,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Tiket Terjual',
                        data: trendTickets,
                        borderColor: '#4e73df',
                        backgroundColor: 'transparent',
                        tension: 0.3,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        position: 'left',
                        title: { display: true, text: 'Revenue (Rp)' }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        grid: { drawOnChartArea: false },
                        title: { display: true, text: 'Tiket' }
                    }
                }
            }
        });
    </script>

    <style>
        .bg-success-light { background-color: rgba(28, 200, 138, 0.1); }
        .bg-primary-light { background-color: rgba(78, 115, 223, 0.1); }
        .card { transition: transform 0.2s ease; }
        .card:hover { transform: translateY(-2px); }
    </style>
@endsection
