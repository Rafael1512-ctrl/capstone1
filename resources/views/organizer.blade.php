@extends('layouts.landingpageconcert.landingconcert')

@section('contentlandingconcert')

<style>
    /* ── Organizer Dashboard Styles ── */
    :root {
        --org-gold: #dc143c; /* Crimson Red matching User Theme */
        --org-dark: #0d0d0d;
        --org-card-bg: #111111;
        --org-border: rgba(220, 20, 60, 0.2);
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
        background: radial-gradient(ellipse at center, rgba(255, 51, 102, 0.08) 0%, transparent 65%);
        pointer-events: none;
    }
    .org-hero .badge-role {
        display: inline-block;
        background: linear-gradient(90deg, #dc143c, #8b0000);
        color: #fff;
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
        background: linear-gradient(90deg, #dc143c, #ff4d6d);
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
        background: linear-gradient(135deg, #dc143c, #8b0000);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: 800;
        color: #fff;
        flex-shrink: 0;
        box-shadow: 0 0 0 4px rgba(220, 20, 60, 0.15);
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
        background: linear-gradient(90deg, #dc143c, #8b0000);
        opacity: 0;
        transition: opacity 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        border-color: rgba(255, 51, 102, 0.5);
        box-shadow: 0 12px 40px rgba(220, 20, 60, 0.12);
    }
    .stat-card:hover::before { opacity: 1; }
    .stat-card .stat-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        background: rgba(220, 20, 60, 0.1);
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 14px;
        font-size: 20px;
    }
    .stat-card .stat-value {
        font-size: 28px;
        font-weight: 800;
        color: #ff3366;
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
        color: #ff3366;
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
        border-color: rgba(255, 51, 102, 0.5);
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
        background: rgba(255, 51, 102, 0.15);
        color: #ff3366;
        border: 1px solid rgba(255, 51, 102, 0.3);
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
        background: linear-gradient(90deg, #dc143c, #8b0000);
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
        background: linear-gradient(90deg, #dc143c, #8b0000);
        color: #fff !important;
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
        border-color: #ff3366;
        color: #ff3366 !important;
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
    .org-table tbody tr:hover { background: rgba(255, 51, 102, 0.04); }
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
        background: linear-gradient(90deg, #dc143c, #8b0000);
        border-color: transparent;
        color: #fff !important;
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
                    @if($event['banner_url'])
                        @php
                            if (str_starts_with($event['banner_url'], '/storage/')) {
                                $imgSrc = $event['banner_url'];
                            } elseif (str_starts_with($event['banner_url'], '/')) {
                                $imgSrc = asset($event['banner_url']);
                            } else {
                                $imgSrc = Storage::url($event['banner_url']);
                            }
                        @endphp
                        <img src="{{ $imgSrc }}" alt="{{ $event['name'] }}" class="event-card-img">
                    @else
                        <div class="event-card-img-placeholder">
                            {{ $event['emoji'] }}
                        </div>
                    @endif

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

                        {{-- Revenue & Analysis --}}
                        <div class="event-mini-stats" style="margin-top: -10px;">
                            <div class="event-mini-stat">
                                <div class="val" style="color:#ff3366; font-size: 16px;">Rp {{ number_format($event['revenue'] / 1000, 0) }}K</div>
                                <div class="lbl">Revenue</div>
                            </div>
                            <div class="event-mini-stat">
                                <div class="val" style="font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $event['performance'] }}">
                                    @if($pct >= 90) 🔥 @elseif($pct >= 50) 🚀 @else 📈 @endif {{ $event['performance'] }}
                                </div>
                                <div class="lbl">Analysis</div>
                            </div>
                        </div>

                        <a href="{{ route('organizer.report', $event['id']) }}" class="btn-org-primary">
                            🎫 View Buyer Report & Chart
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
</script>
@endsection
