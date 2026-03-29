@extends('layouts.landingpageconcert.landingconcert')

@section('contentlandingconcert')

<style>
    :root {
        --org-gold: #dc143c; /* Crimson Red matching User Theme */
        --org-dark: #0d0d0d;
        --org-card-bg: #111111;
        --org-border: rgba(220, 20, 60, 0.2);
    }

    .report-hero {
        background: linear-gradient(135deg, #0d0d0d 0%, #1a1014 50%, #0d0d0d 100%);
        padding: 50px 0 40px;
        border-bottom: 1px solid var(--org-border);
        position: relative;
    }
    .report-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(ellipse at center, rgba(220, 20, 60, 0.1) 0%, transparent 65%);
        pointer-events: none;
    }
    
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: rgba(255,255,255,0.6);
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 24px;
        transition: color 0.2s;
    }
    .btn-back:hover {
        color: #ff3366;
    }

    .org-section-title {
        font-size: 28px;
        font-weight: 800;
        color: #fff;
        margin-bottom: 6px;
    }
    .org-section-title span {
        color: #ff3366;
    }
    .org-section-sub {
        color: rgba(255,255,255,0.4);
        font-size: 14px;
    }

    .org-section {
        background: #0d0d0d;
        padding: 60px 0;
    }

    .org-table-wrap {
        background: var(--org-card-bg);
        border: 1px solid var(--org-border);
        border-radius: 18px;
        overflow: hidden;
        margin-bottom: 30px;
    }
    .org-table-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--org-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .org-table-header h6 {
        color: #fff;
        font-weight: 700;
        font-size: 15px;
        margin: 0;
    }
    .org-table {
        width: 100%;
        border-collapse: collapse;
    }
    .org-table thead th {
        background: rgba(255,255,255,0.03);
        color: rgba(255,255,255,0.4);
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 12px 20px;
        font-weight: 600;
        border-bottom: 1px solid var(--org-border);
    }
    .org-table tbody tr {
        border-bottom: 1px solid rgba(255,255,255,0.05);
        transition: background 0.2s;
    }
    .org-table tbody tr:last-child { border-bottom: none; }
    .org-table tbody tr:hover { background: rgba(255, 51, 102, 0.04); }
    .org-table tbody td {
        padding: 14px 20px;
        color: rgba(255,255,255,0.8);
        font-size: 14px;
        vertical-align: middle;
    }
    .buyer-name { font-weight: 600; color: #fff; }
    .buyer-email { font-size: 12px; color: rgba(255,255,255,0.4); }
    
    .status-badge {
        display: inline-block;
        font-size: 11px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        letter-spacing: 0.5px;
    }
    .status-badge.paid, .status-badge.verified {
        background: rgba(34,197,94,0.15);
        color: #22c55e;
        border: 1px solid rgba(34,197,94,0.25);
    }
    .status-badge.pending {
        background: rgba(251,191,36,0.15);
        color: #fbbf24;
        border: 1px solid rgba(251,191,36,0.25);
    }
    .status-badge.failed, .status-badge.expired, .status-badge.canceled {
        background: rgba(239,68,68,0.15);
        color: #ef4444;
        border: 1px solid rgba(239,68,68,0.25);
    }
    .empty-state {
        text-align: center;
        padding: 50px 20px;
        color: rgba(255,255,255,0.3);
    }
    
    .event-badge {
        display: inline-block;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 4px 12px;
        border-radius: 20px;
    }
    .event-badge.active {
        background: rgba(34,197,94,0.15);
        color: #22c55e;
        border: 1px solid rgba(34,197,94,0.3);
    }
    .event-badge.upcoming {
        background: rgba(255, 51, 102, 0.15);
        color: #ff3366;
        border: 1px solid rgba(255, 51, 102, 0.3);
    }

    @media (max-width: 768px) {
        .org-table thead { display: none; }
        .org-table tbody td { display: block; padding: 8px 16px; }
        .org-table tbody td::before {
            content: attr(data-label);
            font-size: 10px;
            color: rgba(255,255,255,0.35);
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 2px;
        }
    }
</style>

{{-- ─── HERO ─────────────────────────────────────────────── --}}
<div class="report-hero">
    <div class="container">
        <a href="{{ route('organizer.dashboard') }}" class="btn-back">
            <span>&larr;</span> Back to Dashboard
        </a>
        
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="org-section-title">
                    {{ $eventData['emoji'] }} {{ $eventData['name'] }} — <span>Analytics & Buyers</span>
                </h1>
                <p class="org-section-sub mb-0">Sales chart and detailed purchase list for this event</p>
            </div>
            <span class="event-badge {{ $eventData['status_class'] }}">{{ $eventData['status_label'] }}</span>
        </div>
    </div>
</div>

{{-- ─── DETAILS ────────────────────────────────────────── --}}
<div class="org-section">
    <div class="container">
        
        {{-- Graphics / Chart --}}
        <div class="org-table-wrap" style="padding: 20px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 style="color: #fff; font-weight: 700; font-size: 15px; margin: 0;">📊 Daily Sales Transactions</h6>
                <div style="font-size:12px; color:rgba(255,255,255,0.5);">
                    Total Revenue: <strong style="color:#ff3366;">Rp {{ number_format($eventData['revenue'], 0, ',', '.') }}</strong>
                </div>
            </div>
            
            @if(count($eventData['chart_data']) > 0)
            <canvas id="chart-report" height="80"></canvas>
            @else
            <div class="empty-state" style="padding: 20px;">
                <p style="margin: 0;">No transaction data available yet.</p>
            </div>
            @endif
        </div>

        {{-- Users list / Orders --}}
        <div class="org-table-wrap">
            <div class="org-table-header">
                <h6>👥 Purchase Records (User List)</h6>
                <span style="color:rgba(255,255,255,0.4);font-size:13px">
                    {{ count($eventData['orders_list']) }} transaction(s)
                </span>
            </div>

            @if(count($eventData['orders_list']) > 0)
            <div class="table-responsive">
                <table class="org-table">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Buyer</th>
                            <th>Ticket Type</th>
                            <th>Qty</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eventData['orders_list'] as $order)
                        <tr>
                            <td data-label="Order #">
                                <span style="font-family:monospace;color:#ff3366;font-size:13px">
                                    {{ $order['order_number'] }}
                                </span>
                            </td>
                            <td data-label="Buyer">
                                <div class="buyer-name">{{ $order['buyer_name'] }}</div>
                                <div class="buyer-email">{{ $order['buyer_email'] }}</div>
                            </td>
                            <td data-label="Ticket Type">
                                <span style="color:rgba(255,255,255,0.7);font-size:13px">
                                    {{ $order['ticket_type'] }}
                                </span>
                            </td>
                            <td data-label="Qty">
                                <span style="color:#fff;font-weight:700">{{ $order['qty'] }}</span>
                            </td>
                            <td data-label="Amount">
                                <span style="color:#ff3366;font-weight:700">
                                    Rp {{ number_format($order['amount'], 0, ',', '.') }}
                                </span>
                            </td>
                            <td data-label="Status">
                                <span class="status-badge {{ $order['status'] }}">
                                    {{ ucfirst($order['status']) }}
                                </span>
                            </td>
                            <td data-label="Date">
                                <span style="color:rgba(255,255,255,0.45);font-size:12px">
                                    {{ $order['date'] }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <p>No purchases recorded for this event yet.</p>
            </div>
            @endif
        </div>

    </div>
</div>

@endsection

@section('ExtraJS2')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const eventData = @json($eventData);
        
        if (eventData.chart_data && eventData.chart_data.length > 0) {
            const ctx = document.getElementById('chart-report');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: eventData.chart_dates,
                        datasets: [{
                            label: 'Tickets Sold',
                            data: eventData.chart_data,
                            borderColor: '#dc143c',
                            backgroundColor: 'rgba(220, 20, 60, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#111111',
                            pointBorderColor: '#dc143c',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#111111',
                                titleColor: '#dc143c',
                                bodyColor: '#fff',
                                borderColor: 'rgba(220, 20, 60, 0.3)',
                                borderWidth: 1,
                                padding: 10,
                                displayColors: false
                            }
                        },
                        scales: {
                            x: {
                                grid: { color: 'rgba(255,255,255,0.05)', drawBorder: false },
                                ticks: { color: 'rgba(255,255,255,0.5)', font: { size: 11 } }
                            },
                            y: {
                                grid: { color: 'rgba(255,255,255,0.05)', drawBorder: false },
                                ticks: { 
                                    color: 'rgba(255,255,255,0.5)', 
                                    font: { size: 11 },
                                    stepSize: 1
                                },
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        }
    });
</script>
@endsection
