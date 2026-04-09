@extends('layouts.master')
@section('page_title', 'Dashboard')

@section('content')
    <div class="container-fluid px-2">

        {{-- ── HERO HEADER ── --}}
        <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap" style="gap:16px;">
            <div>
                <div
                    style="font-size:11px;font-weight:700;letter-spacing:2px;color:#dc143c;text-transform:uppercase;margin-bottom:6px;">
                    Overview</div>
                <h2
                    style="font-family:'DM Serif Display',serif;font-size:28px;font-weight:800;color:#fff;margin-bottom:4px;">
                    Admin <span
                        style="background:linear-gradient(90deg,#dc143c,#ff4d6d);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Dashboard</span>
                </h2>
                <p style="color:rgba(255,255,255,0.35);font-size:13px;margin:0;">Selamat datang kembali, <strong
                        style="color:rgba(255,255,255,0.6);">{{ Auth::user()->name }}</strong></p>
            </div>
            <div class="d-flex gap-2 flex-wrap" style="gap:10px;">
                <a href="{{ route('admin.events.create') }}" class="btn btn-primary px-4 py-2"
                    style="border-radius:10px;font-weight:700;">
                    <i class="fas fa-plus me-2"></i>Event Baru
                </a>
                <a href="{{ route('admin.events.index') }}" class="btn btn-secondary px-4 py-2"
                    style="border-radius:10px;font-weight:700;">
                    <i class="fas fa-list me-2"></i>Kelola Event
                </a>
            </div>
        </div>

        {{-- ── STAT CARDS ── --}}
        <div class="row g-3 mb-4">
            @php
                $stats = [
                    ['icon' => '💰', 'label' => 'Total Revenue', 'val' => 'Rp ' . number_format($totalRevenue / 1000, 0) . 'K', 'sub' => 'Hari ini: Rp ' . number_format($todayRevenue, 0, ',', '.'), 'color' => 'rgba(220,20,60,0.12)', 'accent' => '#dc143c'],
                    ['icon' => '🎤', 'label' => 'Total Events', 'val' => $totalEvents, 'sub' => 'Dari ' . $totalOrganizers . ' organizer', 'color' => 'rgba(59,130,246,0.1)', 'accent' => '#60a5fa'],
                    ['icon' => '📦', 'label' => 'Total Orders', 'val' => $totalOrders, 'sub' => 'Paid: ' . $paidOrders . ' · Pending: ' . $pendingOrders, 'color' => 'rgba(34,197,94,0.1)', 'accent' => '#22c55e'],
                    ['icon' => '👥', 'label' => 'Total Users', 'val' => $totalUsers, 'sub' => 'Organizer: ' . $totalOrganizers, 'color' => 'rgba(251,191,36,0.1)', 'accent' => '#fbbf24'],
                ];
            @endphp
            @foreach($stats as $s)
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100"
                        style="border-radius:18px;padding:4px;background:linear-gradient(135deg,rgba(220,20,60,0.05),rgba(0,0,0,0));border:1px solid rgba(255,255,255,0.06);">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div
                                    style="width:52px;height:52px;border-radius:14px;background:{{ $s['color'] }};display:flex;align-items:center;justify-content:center;font-size:22px;">
                                    {{ $s['icon'] }}</div>
                                <div>
                                    <div
                                        style="font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:rgba(255,255,255,0.3);">
                                        {{ $s['label'] }}</div>
                                    <div style="font-size:26px;font-weight:800;color:#fff;line-height:1.1;">{{ $s['val'] }}
                                    </div>
                                </div>
                            </div>
                            <div
                                style="font-size:11px;color:rgba(255,255,255,0.3);padding-top:10px;border-top:1px solid rgba(255,255,255,0.05);">
                                {{ $s['sub'] }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ── REVENUE MONTH + CHART ── --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-3">
                <div class="card h-100" style="border-radius:18px;">
                    <div class="card-body p-4 d-flex flex-column justify-content-center text-center">
                        <div
                            style="font-size:12px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:rgba(255,255,255,0.3);margin-bottom:12px;">
                            Revenue Bulan Ini</div>
                        <div style="font-size:30px;font-weight:800;color:#22c55e;line-height:1;">IDR
                            {{ number_format($monthRevenue / 1000000, 1) }}M</div>
                        <div style="font-size:11px;color:rgba(255,255,255,0.25);margin-top:8px;">Rp
                            {{ number_format($monthRevenue, 0, ',', '.') }}</div>
                        <div
                            style="height:4px;background:rgba(34,197,94,0.1);border-radius:4px;margin-top:16px;overflow:hidden;">
                            <div
                                style="height:100%;width:{{ min(100, round($monthRevenue / max(1, $totalRevenue) * 100)) }}%;background:linear-gradient(90deg,#22c55e,#16a34a);border-radius:4px;">
                            </div>
                        </div>
                        <div style="font-size:10px;color:rgba(255,255,255,0.2);margin-top:4px;">
                            {{ round($monthRevenue / max(1, $totalRevenue) * 100) }}% dari total</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="card" style="border-radius:18px;">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div style="font-weight:800;font-size:15px;color:#fff;">📈 Penjualan 7 Hari Terakhir</div>
                        <a href="{{ route('admin.analytics.sales') }}"
                            style="font-size:12px;color:#dc143c;font-weight:700;text-decoration:none;">Lihat detail →</a>
                    </div>
                    <div class="card-body">
                        <canvas id="salesChart" style="height: 280px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── TOP EVENTS + ORGANIZERS ── --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-6">
                <div class="card" style="border-radius:18px;">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div style="font-weight:800;font-size:15px;color:#fff;">🔥 Top 5 Events</div>
                        <a href="{{ route('admin.events.index') }}"
                            style="font-size:12px;color:#dc143c;font-weight:700;text-decoration:none;">Lihat semua →</a>
                    </div>
                    <div class="card-body p-0">
                        @forelse($topEvents as $i => $event)
                            <a href="{{ route('admin.events.show', $event) }}"
                                class="d-flex align-items-center gap-3 px-4 py-3 text-decoration-none"
                                style="border-bottom:1px solid rgba(255,255,255,0.04);transition:background 0.15s;"
                                onmouseover="this.style.background='rgba(220,20,60,0.04)'"
                                onmouseout="this.style.background='transparent'">
                                <div
                                    style="width:28px;height:28px;border-radius:8px;background:rgba(220,20,60,0.1);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:#dc143c;flex-shrink:0;">
                                    {{ $i + 1 }}
                                </div>
                                <div class="flex-grow-1" style="overflow:hidden;">
                                    <div
                                        style="color:#fff;font-weight:700;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        {{ $event->title }}</div>
                                    <div style="color:rgba(255,255,255,0.3);font-size:11px;">
                                        {{ $event->category?->name ?? 'Uncategorized' }}</div>
                                </div>
                                <span
                                    style="background:rgba(34,197,94,0.12);color:#22c55e;border:1px solid rgba(34,197,94,0.25);border-radius:6px;padding:3px 10px;font-size:11px;font-weight:700;flex-shrink:0;">
                                    {{ $event->orders_count }} orders
                                </span>
                            </a>
                        @empty
                            <div class="text-center py-4" style="color:rgba(255,255,255,0.2);font-size:13px;">Belum ada event
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card" style="border-radius:18px;">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div style="font-weight:800;font-size:15px;color:#fff;">🎭 Top 5 Organizers</div>
                        <a href="{{ route('admin.users.organizers') }}"
                            style="font-size:12px;color:#dc143c;font-weight:700;text-decoration:none;">Lihat semua →</a>
                    </div>
                    <div class="card-body p-0">
                        @forelse($topOrganizers as $i => $organizer)
                            <div class="d-flex align-items-center gap-3 px-4 py-3"
                                style="border-bottom:1px solid rgba(255,255,255,0.04);">
                                <div
                                    style="width:32px;height:32px;border-radius:10px;background:linear-gradient(135deg,#dc143c,#8b0000);display:flex;align-items:center;justify-content:center;font-weight:800;color:#fff;font-size:13px;flex-shrink:0;">
                                    {{ strtoupper(substr($organizer->name, 0, 1)) }}
                                </div>
                                <div class="flex-grow-1" style="overflow:hidden;">
                                    <div
                                        style="color:#fff;font-weight:700;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        {{ $organizer->name }}</div>
                                    <div
                                        style="color:rgba(255,255,255,0.3);font-size:11px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        {{ $organizer->email }}</div>
                                </div>
                                <span
                                    style="background:rgba(59,130,246,0.12);color:#60a5fa;border:1px solid rgba(59,130,246,0.25);border-radius:6px;padding:3px 10px;font-size:11px;font-weight:700;flex-shrink:0;">
                                    {{ $organizer->events_count }} events
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-4" style="color:rgba(255,255,255,0.2);font-size:13px;">Belum ada
                                organizer</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- ── QUICK ACTIONS ── --}}
        <div class="card mb-4" style="border-radius:18px;">
            <div class="card-header">
                <div style="font-weight:800;font-size:15px;color:#fff;">⚡ Quick Actions</div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @php
                        $actions = [
                            ['href' => route('admin.analytics.sales'), 'icon' => 'fas fa-chart-bar', 'label' => 'Sales Analytics', 'color' => '#dc143c'],
                            ['href' => route('admin.analytics.transactions'), 'icon' => 'fas fa-exchange-alt', 'label' => 'Transactions', 'color' => '#60a5fa'],
                            ['href' => route('admin.analytics.event-performance'), 'icon' => 'fas fa-star', 'label' => 'Event Performance', 'color' => '#22c55e'],
                            ['href' => route('admin.analytics.user-stats'), 'icon' => 'fas fa-user-chart', 'label' => 'User Stats', 'color' => '#fbbf24'],
                        ];
                    @endphp
                    @foreach($actions as $a)
                        <div class="col-6 col-md-3">
                            <a href="{{ $a['href'] }}" class="d-flex align-items-center gap-3 p-3 text-decoration-none"
                                style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);border-radius:12px;transition:all 0.2s;"
                                onmouseover="this.style.background='rgba(220,20,60,0.07)';this.style.borderColor='rgba(220,20,60,0.3)';"
                                onmouseout="this.style.background='rgba(255,255,255,0.03)';this.style.borderColor='rgba(255,255,255,0.07)';">
                                <i class="{{ $a['icon'] }}" style="color:{{ $a['color'] }};font-size:18px;width:20px;"></i>
                                <span
                                    style="color:rgba(255,255,255,0.7);font-size:13px;font-weight:600;">{{ $a['label'] }}</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
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
                    borderColor: '#dc143c',
                    backgroundColor: 'rgba(220,20,60,0.08)',
                    tension: 0.4, fill: true, yAxisID: 'y',
                    pointBackgroundColor: '#dc143c', pointRadius: 4,
                }, {
                    label: 'Orders',
                    data: orders,
                    borderColor: '#60a5fa',
                    backgroundColor: 'rgba(96,165,250,0.06)',
                    tension: 0.4, fill: true, yAxisID: 'y1',
                    pointBackgroundColor: '#60a5fa', pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { labels: { color: 'rgba(255,255,255,0.5)', font: { size: 12, weight: '700' } } },
                },
                scales: {
                    x: { ticks: { color: 'rgba(255,255,255,0.3)' }, grid: { color: 'rgba(255,255,255,0.04)' } },
                    y: {
                        type: 'linear', position: 'left',
                        ticks: { color: 'rgba(255,255,255,0.3)' },
                        grid: { color: 'rgba(255,255,255,0.04)' },
                        title: { display: true, text: 'Revenue (Rp)', color: 'rgba(255,255,255,0.3)' }
                    },
                    y1: {
                        type: 'linear', position: 'right',
                        ticks: { color: 'rgba(255,255,255,0.3)' },
                        grid: { drawOnChartArea: false },
                        title: { display: true, text: 'Orders', color: 'rgba(255,255,255,0.3)' }
                    }
                }
            }
        });
    </script>
@endsection