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
            <div class="d-flex gap-3 align-items-center">
                <a href="{{ route('tickets.scanner') }}" class="btn btn-danger py-2 px-4 shadow-lg d-flex align-items-center mr-3" 
                   style="border-radius: 12px; background: linear-gradient(90deg, #dc143c, #8b0000); border: none; font-weight: 700;">
                    <i class="fa fa-camera mr-2"></i> OPEN SCANNER
                </a>
                <span class="event-badge {{ $eventData['status_class'] }}">{{ $eventData['status_label'] }}</span>
            </div>
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

        {{-- Ticket Capacity Control --}}
        <div class="org-table-wrap mb-5">
            <div class="org-table-header">
                <h6>📑 Ticket Capacity Control</h6>
                <span class="badge badge-info" style="font-size: 10px; background: rgba(59, 130, 246, 0.1); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.2);">
                    <i class="fa fa-info-circle mr-1"></i> Request Admin for quota changes
                </span>
            </div>
            
            <div class="table-responsive">
                <table class="org-table">
                    <thead>
                        <tr>
                            <th>Ticket Name</th>
                            <th>Current Capacity</th>
                            <th>Sold</th>
                            <th>Remaining</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eventData['ticket_types'] as $type)
                        @php
                            $isPending = isset($eventData['pending_requests'][$type->id]);
                            $remaining = $type->quantity_total - $type->quantity_sold;
                        @endphp
                        <tr>
                            <td><strong style="color: #fff;">{{ $type->name }}</strong></td>
                            <td>{{ $type->quantity_total }} Slot</td>
                            <td style="color: #22c55e;">{{ $type->quantity_sold }}</td>
                            <td style="color: #fbbf24;">{{ $remaining }}</td>
                            <td>
                                @if($isPending)
                                    <span class="status-badge pending">Awaiting Admin Approval</span>
                                @else
                                    <span class="status-badge verified">Stable</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-light" 
                                        onclick="openCapacityModal({{ $type->id }}, '{{ $type->name }}', {{ $type->quantity_total }})"
                                        style="border-radius: 8px; font-size: 11px; {{ $isPending ? 'opacity: 0.5; pointer-events: none;' : '' }}">
                                    <i class="fa fa-edit mr-1"></i> Request Change
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Purchase Records --}}
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
            @endif
        </div>

        {{-- Attendee List / Scannable Tickets --}}
        <div class="org-table-wrap mt-5">
            <div class="org-table-header" style="background: rgba(255, 255, 255, 0.02);">
                <h6>📋 Guest Check-in List (Detailed Tickets)</h6>
                <span style="color:rgba(255,255,255,0.4);font-size:13px">
                    {{ count($eventData['ticket_list']) }} total ticket(s)
                </span>
            </div>

            @if(count($eventData['ticket_list']) > 0)
            <div class="table-responsive">
                <table class="org-table">
                    <thead>
                        <tr>
                            <th>Ticket ID</th>
                            <th>Tier</th>
                            <th>Buyer Name</th>
                            <th>Check-in Status</th>
                            <th>Date Purchased</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eventData['ticket_list'] as $ticket)
                        <tr>
                            <td data-label="Ticket ID">
                                <span style="font-family:monospace;color:#fff;font-size:13px;font-weight:700">
                                    {{ $ticket['id'] }}
                                </span>
                            </td>
                            <td data-label="Tier">
                                <span class="badge badge-outline-light" style="border: 1px solid rgba(255,255,255,0.2); color: #fff; padding: 4px 10px; border-radius: 5px; font-size: 11px;">
                                    {{ $ticket['type'] }}
                                </span>
                            </td>
                            <td data-label="Buyer Name">
                                <span class="text-white">{{ $ticket['buyer'] }}</span>
                            </td>
                            <td data-label="Check-in Status">
                                @if($ticket['status'] === 'Active')
                                    <span class="status-badge paid" style="background: rgba(0, 255, 127, 0.1); color: #00ff7f; border-color: rgba(0, 255, 127, 0.2);">
                                        <i class="fa fa-circle mr-1" style="font-size: 8px;"></i> READY
                                    </span>
                                @else
                                    <span class="status-badge" style="background: rgba(255, 255, 255, 0.05); color: rgba(255, 255, 255, 0.3); border-color: rgba(255, 255, 255, 0.1);">
                                        <i class="fa fa-check-circle mr-1"></i> USED
                                    </span>
                                @endif
                            </td>
                            <td data-label="Date Purchased">
                                <span style="color:rgba(255,255,255,0.4);font-size:12px">
                                    {{ $ticket['date'] }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <p>No tickets available for check-in list.</p>
            </div>
            @endif
        </div>

    </div>
</div>

@endsection

@section('ExtraJS2')
<!-- Capacity Request Modal -->
<div class="modal fade" id="capacityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: #111; border: 1px solid var(--org-border); border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-white fw-bold">Request Capacity Change</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="capacityForm" action="{{ route('organizer.capacity.request') }}" method="POST">
                @csrf
                <input type="hidden" name="event_id" value="{{ $eventData['id'] }}">
                <input type="hidden" name="ticket_type_id" id="modal_type_id">
                
                <div class="modal-body p-4">
                    <div class="mb-4 text-center">
                        <span id="modal_ticket_name" class="badge badge-outline-light py-2 px-3 mb-2" style="border:1px solid #444; color: #ff3366;">TIER NAME</span>
                        <p class="text-muted small">Update your event slot availability. Requests must be approved by the admin.</p>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label text-muted small">Current Capacity</label>
                            <input type="text" id="modal_current_capacity" class="form-control text-white border-0" 
                                   style="background: rgba(255,255,255,0.05); cursor: not-allowed;" readonly>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-muted small">New Requested Slots</label>
                            <input type="number" name="requested_capacity" class="form-control text-white" 
                                   style="background: rgba(255,255,255,0.1); border: 1px solid #444;" 
                                   min="1" required placeholder="Ex: 500">
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label text-muted small">Reason for Change</label>
                        <textarea name="reason" rows="3" class="form-control text-white" 
                                  style="background: rgba(255,255,255,0.1); border: 1px solid #444;" 
                                  placeholder="Why do you need this change?" required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 p-4">
                    <button type="button" class="btn btn-dark px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger px-5" style="background: #dc143c; border: none; font-weight: 700;">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function openCapacityModal(id, name, current) {
        document.getElementById('modal_type_id').value = id;
        document.getElementById('modal_ticket_name').innerText = name;
        document.getElementById('modal_current_capacity').value = current + ' Slots';
        
        const modal = new bootstrap.Modal(document.getElementById('capacityModal'));
        modal.show();
    }

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
