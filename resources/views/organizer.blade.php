@extends('layouts.landingpageconcert.landingconcert')

@section('contentlandingconcert')

<style>
    /* ── Organizer Dashboard Styles ── */
    :root {
        --org-gold: #c9a84c;
        --org-dark: #0d0d0d;
        --org-card-bg: #1a1a1a;
        --org-border: rgba(201,168,76,0.25);
    }

    /* Hero */
    .org-hero {
        background: linear-gradient(135deg, #0d0d0d 0%, #1a1014 50%, #0d0d0d 100%);
        padding: 80px 0 60px;
        border-bottom: 1px solid var(--org-border);
        position: relative;
        overflow: hidden;
    }
    .org-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(ellipse at center, rgba(201,168,76,0.06) 0%, transparent 65%);
        pointer-events: none;
    }
    .org-hero .badge-role {
        display: inline-block;
        background: linear-gradient(90deg, #c9a84c, #e8c97a);
        color: #000;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        padding: 5px 16px;
        border-radius: 20px;
        margin-bottom: 16px;
    }
    .org-hero h1 {
        font-size: clamp(28px, 4vw, 44px);
        font-weight: 800;
        color: #fff;
        margin-bottom: 10px;
        line-height: 1.2;
    }
    .org-hero h1 span {
        background: linear-gradient(90deg, #c9a84c, #e8c97a);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .org-hero p.subtitle {
        color: rgba(255,255,255,0.55);
        font-size: 15px;
        max-width: 460px;
    }
    .org-avatar {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: linear-gradient(135deg, #c9a84c, #e8c97a);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: 800;
        color: #000;
        flex-shrink: 0;
        box-shadow: 0 0 0 4px rgba(201,168,76,0.2);
    }

    /* Stats */
    .org-stats {
        background: #111;
        padding: 40px 0;
        border-bottom: 1px solid var(--org-border);
    }
    .stat-card {
        background: var(--org-card-bg);
        border: 1px solid var(--org-border);
        border-radius: 14px;
        padding: 24px 20px;
        text-align: center;
        transition: transform 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, #c9a84c, #e8c97a);
        opacity: 0;
        transition: opacity 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        border-color: rgba(201,168,76,0.5);
        box-shadow: 0 12px 40px rgba(201,168,76,0.1);
    }
    .stat-card:hover::before { opacity: 1; }
    .stat-card .stat-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        background: rgba(201,168,76,0.12);
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 14px;
        font-size: 20px;
    }
    .stat-card .stat-value {
        font-size: 28px;
        font-weight: 800;
        color: #c9a84c;
        line-height: 1;
        margin-bottom: 6px;
    }
    .stat-card .stat-label {
        font-size: 12px;
        color: rgba(255,255,255,0.45);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Events Section */
    .org-section {
        background: #0d0d0d;
        padding: 60px 0;
    }
    .org-section-title {
        font-size: 22px;
        font-weight: 800;
        color: #fff;
        margin-bottom: 6px;
    }
    .org-section-title span {
        color: #c9a84c;
    }
    .org-section-sub {
        color: rgba(255,255,255,0.4);
        font-size: 13px;
        margin-bottom: 30px;
    }

    /* Event Card */
    .event-card {
        background: var(--org-card-bg);
        border: 1px solid var(--org-border);
        border-radius: 18px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        height: 100%;
    }
    .event-card:hover {
        transform: translateY(-6px);
        border-color: rgba(201,168,76,0.5);
        box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    }
    .event-card-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        display: block;
    }
    .event-card-img-placeholder {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #1a1a2e, #16213e, #0f3460);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 50px;
    }
    .event-card-body {
        padding: 22px;
    }
    .event-badge {
        display: inline-block;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 4px 12px;
        border-radius: 20px;
        margin-bottom: 12px;
    }
    .event-badge.active {
        background: rgba(34,197,94,0.15);
        color: #22c55e;
        border: 1px solid rgba(34,197,94,0.3);
    }
    .event-badge.upcoming {
        background: rgba(201,168,76,0.15);
        color: #c9a84c;
        border: 1px solid rgba(201,168,76,0.3);
    }
    .event-card-title {
        font-size: 20px;
        font-weight: 800;
        color: #fff;
        margin-bottom: 6px;
    }
    .event-card-meta {
        font-size: 13px;
        color: rgba(255,255,255,0.45);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .event-progress-label {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: rgba(255,255,255,0.5);
        margin-bottom: 6px;
    }
    .event-progress {
        height: 6px;
        background: rgba(255,255,255,0.08);
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 18px;
    }
    .event-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #c9a84c, #e8c97a);
        border-radius: 10px;
        transition: width 1s ease;
    }
    .event-mini-stats {
        display: flex;
        gap: 12px;
        margin-bottom: 18px;
    }
    .event-mini-stat {
        flex: 1;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 10px;
        padding: 10px 8px;
        text-align: center;
    }
    .event-mini-stat .val {
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        line-height: 1;
    }
    .event-mini-stat .lbl {
        font-size: 10px;
        color: rgba(255,255,255,0.4);
        letter-spacing: 0.5px;
        margin-top: 4px;
    }
    .btn-org-primary {
        display: block;
        width: 100%;
        text-align: center;
        background: linear-gradient(90deg, #c9a84c, #e8c97a);
        color: #000 !important;
        font-weight: 700;
        font-size: 13px;
        padding: 10px 0;
        border-radius: 10px;
        text-decoration: none;
        transition: opacity 0.2s, transform 0.2s;
        letter-spacing: 0.5px;
    }
    .btn-org-primary:hover {
        opacity: 0.88;
        transform: scale(0.98);
    }
    .btn-org-outline {
        display: inline-block;
        border: 1px solid var(--org-border);
        color: rgba(255,255,255,0.6) !important;
        font-size: 13px;
        font-weight: 600;
        padding: 8px 20px;
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-org-outline:hover {
        border-color: #c9a84c;
        color: #c9a84c !important;
    }

    /* Orders Table */
    .org-table-wrap {
        background: var(--org-card-bg);
        border: 1px solid var(--org-border);
        border-radius: 18px;
        overflow: hidden;
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
    .org-table tbody tr:hover { background: rgba(201,168,76,0.04); }
    .org-table tbody td {
        padding: 14px 20px;
        color: rgba(255,255,255,0.8);
        font-size: 14px;
        vertical-align: middle;
    }
    .org-table .buyer-name { font-weight: 600; color: #fff; }
    .org-table .buyer-email { font-size: 12px; color: rgba(255,255,255,0.4); }
    .status-badge {
        display: inline-block;
        font-size: 11px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        letter-spacing: 0.5px;
    }
    .status-badge.paid {
        background: rgba(34,197,94,0.15);
        color: #22c55e;
        border: 1px solid rgba(34,197,94,0.25);
    }
    .status-badge.pending {
        background: rgba(251,191,36,0.15);
        color: #fbbf24;
        border: 1px solid rgba(251,191,36,0.25);
    }
    .status-badge.failed, .status-badge.expired {
        background: rgba(239,68,68,0.15);
        color: #ef4444;
        border: 1px solid rgba(239,68,68,0.25);
    }
    .empty-state {
        text-align: center;
        padding: 50px 20px;
        color: rgba(255,255,255,0.3);
    }
    .empty-state .empty-icon { font-size: 40px; margin-bottom: 12px; }
    .empty-state p { font-size: 14px; margin: 0; }

    /* Tab Nav */
    .org-tab-nav {
        display: flex;
        gap: 8px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .org-tab-btn {
        padding: 8px 20px;
        border-radius: 30px;
        border: 1px solid var(--org-border);
        background: transparent;
        color: rgba(255,255,255,0.5);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .org-tab-btn.active, .org-tab-btn:hover {
        background: linear-gradient(90deg, #c9a84c, #e8c97a);
        border-color: transparent;
        color: #000 !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .org-hero { padding: 50px 0 40px; }
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
<div class="org-hero">
    <div class="container">
        <div class="d-flex align-items-center gap-4 mb-4" style="gap:20px">
            <div class="org-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <div class="badge-role">Organizer</div>
                <h1>Welcome back, <span>{{ explode(' ', auth()->user()->name)[0] }}</span> 👋</h1>
                <p class="subtitle mb-0">Manage your concerts, track ticket sales, and monitor buyer activity.</p>
            </div>
        </div>
    </div>
</div>

{{-- ─── STATS ────────────────────────────────────────────── --}}
<div class="org-stats">
    <div class="container">
        <div class="row g-3">
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="0">
                <div class="stat-card">
                    <div class="stat-icon">🎤</div>
                    <div class="stat-value">{{ count($myEvents) }}</div>
                    <div class="stat-label">My Events</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="80">
                <div class="stat-card">
                    <div class="stat-icon">🎟️</div>
                    <div class="stat-value">{{ $totalTicketsSold }}</div>
                    <div class="stat-label">Tickets Sold</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="160">
                <div class="stat-card">
                    <div class="stat-icon">📦</div>
                    <div class="stat-value">{{ $totalOrders }}</div>
                    <div class="stat-label">Total Orders</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="240">
                <div class="stat-card">
                    <div class="stat-icon">💰</div>
                    <div class="stat-value">Rp {{ number_format($totalRevenue / 1000, 0) }}K</div>
                    <div class="stat-label">Revenue</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ─── MY EVENTS ────────────────────────────────────────── --}}
<div class="org-section">
    <div class="container">

        {{-- Section Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="org-section-title">My <span>Events</span></h2>
                <p class="org-section-sub">Concerts you are currently managing</p>
            </div>
        </div>

        {{-- Event Cards --}}
        @if(count($myEvents) > 0)
        <div class="row g-4 mb-5">
            @foreach($myEvents as $event)
            <div class="col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="event-card">
                    {{-- Image --}}
                    <div class="event-card-img-placeholder">
                        {{ $event['emoji'] }}
                    </div>

                    <div class="event-card-body">
                        <span class="event-badge {{ $event['status_class'] }}">{{ $event['status_label'] }}</span>
                        <div class="event-card-title">{{ $event['name'] }}</div>
                        <div class="event-card-meta">
                            <span>📍</span> {{ $event['venue'] }}
                        </div>
                        <div class="event-card-meta">
                            <span>📅</span> {{ $event['date'] }}
                        </div>

                        {{-- Ticket Progress --}}
                        <div class="event-progress-label">
                            <span>Tickets Sold</span>
                            <span>{{ $event['sold'] }} / {{ $event['total'] }}</span>
                        </div>
                        <div class="event-progress">
                            @php $pct = $event['total'] > 0 ? round($event['sold']/$event['total']*100) : 0; @endphp
                            <div class="event-progress-bar" style="width: {{ $pct }}%"></div>
                        </div>

                        {{-- Mini Stats --}}
                        <div class="event-mini-stats">
                            <div class="event-mini-stat">
                                <div class="val">{{ $event['orders'] }}</div>
                                <div class="lbl">Orders</div>
                            </div>
                            <div class="event-mini-stat">
                                <div class="val">{{ $event['sold'] }}</div>
                                <div class="lbl">Sold</div>
                            </div>
                            <div class="event-mini-stat">
                                <div class="val">{{ $pct }}%</div>
                                <div class="lbl">Filled</div>
                            </div>
                        </div>

                        <a href="#orders-{{ $event['id'] }}" class="btn-org-primary">
                            🎫 View Buyer Report
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="org-table-wrap mb-5">
            <div class="empty-state">
                <div class="empty-icon">🎤</div>
                <p>You have no events assigned yet.</p>
            </div>
        </div>
        @endif

        {{-- ─── BUYER HISTORY PER EVENT ───────────────────────── --}}
        @foreach($myEvents as $event)
        <div id="orders-{{ $event['id'] }}" class="mb-5" data-aos="fade-up">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h3 class="org-section-title" style="font-size:18px">
                        {{ $event['emoji'] }} {{ $event['name'] }} — <span>Buyer History</span>
                    </h3>
                    <p class="org-section-sub mb-0">All ticket purchases for this event</p>
                </div>
                <span class="event-badge {{ $event['status_class'] }}">{{ $event['status_label'] }}</span>
            </div>

            <div class="org-table-wrap">
                <div class="org-table-header">
                    <h6>📋 Purchase Records</h6>
                    <span style="color:rgba(255,255,255,0.4);font-size:13px">
                        {{ count($event['orders_list']) }} transaction(s)
                    </span>
                </div>

                @if(count($event['orders_list']) > 0)
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
                            @foreach($event['orders_list'] as $order)
                            <tr>
                                <td data-label="Order #">
                                    <span style="font-family:monospace;color:#c9a84c;font-size:13px">
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
                                    <span style="color:#c9a84c;font-weight:700">
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
        @endforeach

    </div>
</div>

@endsection

@section('ExtraJS2')
<script>
    // Animate progress bars on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.style.width = e.target.dataset.width;
            }
        });
    }, { threshold: 0.3 });

    document.querySelectorAll('.event-progress-bar').forEach(bar => {
        const target = bar.style.width;
        bar.dataset.width = target;
        bar.style.width = '0%';
        observer.observe(bar);
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#orders-"]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                // Highlight briefly
                target.style.transition = 'outline 0.3s';
                target.style.outline = '2px solid rgba(201,168,76,0.4)';
                setTimeout(() => target.style.outline = 'none', 1500);
            }
        });
    });
</script>
@endsection
