@extends('layouts.landingpageconcert.landingconcert')

@section('title', 'My Tickets — TIXLY')

@section('contentlandingconcert')

    <style>
        .my-tickets-hero {
            background: linear-gradient(135deg, #0d0d0d 0%, #1a0a0f 50%, #0d0d0d 100%);
            padding: 70px 0 40px;
            border-bottom: 1px solid rgba(220, 20, 60, 0.15);
            position: relative;
            overflow: hidden;
        }

        .my-tickets-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(ellipse at center, rgba(220, 20, 60, 0.07) 0%, transparent 65%);
            pointer-events: none;
        }

        /* Stats */
        .stat-pill {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 22px 28px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: border-color 0.3s;
        }

        .stat-pill:hover {
            border-color: rgba(220, 20, 60, 0.35);
        }

        .stat-pill .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .stat-pill .stat-val {
            font-size: 28px;
            font-weight: 800;
            color: #fff;
            line-height: 1;
        }

        .stat-pill .stat-lbl {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.4);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 4px;
        }

        /* Order Card */
        .order-card {
            background: #111;
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 20px;
            overflow: hidden;
            display: flex;
            min-height: 160px;
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }

        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            border-color: rgba(220, 20, 60, 0.35);
        }

        .order-card-img {
            width: 160px;
            min-width: 160px;
            height: 100%;
            object-fit: cover;
            display: block;
            flex-shrink: 0;
            align-self: stretch;
        }

        .order-card-img-placeholder {
            width: 160px;
            min-width: 160px;
            background: linear-gradient(135deg, #1e0a0a, #2a1020);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            flex-shrink: 0;
        }

        .order-card-body {
            padding: 24px 28px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .ticket-type-pill {
            display: inline-block;
            background: rgba(220, 20, 60, 0.1);
            color: #ff6080;
            border: 1px solid rgba(220, 20, 60, 0.2);
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            margin-right: 5px;
            margin-bottom: 5px;
        }

        .btn-detail {
            background: linear-gradient(135deg, #dc143c, #8b0000);
            color: #fff !important;
            border: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.78rem;
            padding: 9px 22px;
            transition: opacity 0.2s, transform 0.2s;
            box-shadow: 0 4px 14px rgba(220, 20, 60, 0.3);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-detail:hover {
            opacity: 0.88;
            transform: translateY(-1px);
            color: #fff !important;
        }

        .dot-sep {
            color: rgba(255, 255, 255, 0.2);
            margin: 0 6px;
        }

        /* Empty */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
        }

        .empty-icon {
            font-size: 60px;
            opacity: 0.12;
            margin-bottom: 20px;
        }

        @media (max-width: 576px) {
            .order-card {
                flex-direction: column;
            }

            .order-card-img,
            .order-card-img-placeholder {
                width: 100%;
                min-width: 100%;
                height: 200px;
            }
        }
    </style>

    {{-- Hero --}}
    <div class="my-tickets-hero">
        <div class="container">
            <div class="mb-2"
                style="font-size:11px;font-weight:700;letter-spacing:2px;color:#dc143c;text-transform:uppercase;">TIXLY
            </div>
            <h1
                style="font-family:'DM Serif Display',serif;font-size:clamp(28px,4vw,44px);font-weight:800;color:#fff;margin-bottom:8px;">
                My <span
                    style="background:linear-gradient(90deg,#dc143c,#ff4d6d);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Tickets</span>
            </h1>
            <p style="color:rgba(255,255,255,0.45);font-size:15px;margin-bottom:0;">Semua pesanan konser yang kamu miliki.
            </p>
        </div>
    </div>

    <div class="container py-5">

        {{-- Stats --}}
        <div class="row g-3 mb-5">
            <div class="col-md-4">
                <div class="stat-pill">
                    <div class="stat-icon" style="background:rgba(220,20,60,0.1);color:#dc143c;">🎟️</div>
                    <div>
                        <div class="stat-val">{{ $totalCount }}</div>
                        <div class="stat-lbl">Total Tiket</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-pill">
                    <div class="stat-icon" style="background:rgba(34,197,94,0.1);color:#22c55e;">✅</div>
                    <div>
                        <div class="stat-val">{{ $activeCount }}</div>
                        <div class="stat-lbl">Aktif</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-pill">
                    <div class="stat-icon" style="background:rgba(255,255,255,0.05);color:#888;">📋</div>
                    <div>
                        <div class="stat-val">{{ $usedCount }}</div>
                        <div class="stat-lbl">Terpakai</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section Title --}}
        @if($orders->count() > 0)
            <div class="mb-4">
                <h5 style="color:rgba(255,255,255,0.6);font-size:12px;letter-spacing:2px;text-transform:uppercase;">
                    {{ $orders->count() }} Pesanan</h5>
            </div>
        @endif

        {{-- Orders --}}
        @if($orders->count() > 0)
            <div class="d-flex flex-column" style="gap: 20px;">
                @foreach($orders as $ord)
                    @php
                        $event = $ord['event'];
                        $order = $ord['order'];
                        $isEventDeleted = $ord['event_deleted'] ?? false;
                        $imgSrc = null;
                        if ($event && $event->banner_url) {
                            if (filter_var($event->banner_url, FILTER_VALIDATE_URL)) {
                                $imgSrc = $event->banner_url;
                            } else {
                                $imgSrc = str_starts_with($event->banner_url, '/') ? asset($event->banner_url) : \Illuminate\Support\Facades\Storage::url($event->banner_url);
                            }
                        }
                    @endphp

                    <div class="order-card">
                        {{-- Image --}}
                        @if($imgSrc)
                            <div style="position:relative;flex-shrink:0;width:160px;min-width:160px;align-self:stretch;">
                                <img src="{{ \App\Models\SiteSetting::forceDirectUrl($imgSrc) }}" alt="{{ $event->title }}"
                                    style="width:100%;height:100%;object-fit:cover;display:block;"
                                    onerror="this.parentElement.innerHTML='<div style=\'width:160px;min-width:160px;background:linear-gradient(135deg,#1e0a0a,#2a1020);display:flex;align-items:center;justify-content:center;font-size:3rem;align-self:stretch;\'>🎵</div>'">
                                @if($isEventDeleted)
                                    <span style="position:absolute;top:8px;left:8px;background:rgba(0,0,0,0.75);color:#ff6080;font-size:9px;font-weight:800;letter-spacing:1px;padding:3px 7px;border-radius:4px;border:1px solid rgba(220,20,60,0.4);text-transform:uppercase;">Event Dihapus</span>
                                @endif
                            </div>
                        @else
                            <div class="order-card-img-placeholder">🎵</div>
                        @endif

                        {{-- Body --}}
                        <div class="order-card-body">
                            {{-- Top --}}
                            <div>
                                <h5 style="color:#fff;font-weight:800;font-size:1.1rem;margin-bottom:6px;">
                                    {{ $event->title ?? 'Event' }}
                                    @if($isEventDeleted)
                                        <span style="font-size:10px;font-weight:700;color:#888;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);border-radius:4px;padding:2px 7px;margin-left:6px;vertical-align:middle;">Tidak Tersedia</span>
                                    @endif
                                </h5>
                                <div style="font-size:0.8rem;color:rgba(255,255,255,0.38);margin-bottom:12px;">
                                    <i class="fa fa-calendar" style="color:#dc143c;"></i>
                                    {{ $event && $event->schedule_time ? $event->schedule_time->format('d M Y') : 'TBA' }}
                                    <span class="dot-sep">•</span>
                                    <i class="fa fa-map-marker" style="color:#dc143c;"></i>
                                    {{ \Illuminate\Support\Str::limit($event->location ?? 'TBA', 35) }}
                                </div>

                                {{-- Ticket type pills --}}
                                <div>
                                    @foreach($ord['ticket_types'] as $type)
                                        <span class="ticket-type-pill">{{ $type['count'] }}x {{ $type['name'] }}</span>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Bottom --}}
                            <div class="d-flex align-items-center justify-content-between flex-wrap mt-3"
                                style="gap:10px;padding-top:14px;border-top:1px solid rgba(255,255,255,0.05);">
                                <span style="font-size:0.73rem;color:rgba(255,255,255,0.3);">
                                    <i class="fa fa-shopping-cart"></i>
                                    {{ $order && $order->payment_date ? $order->payment_date->format('d M Y, H:i') . ' WIB' : 'Unknown' }}
                                    
                                    @if($order && $order->payment_status === 'Pending')
                                        <span class="badge badge-warning ml-2" style="background: rgba(255,193,7,0.1); color: #ffc107; border: 1px solid rgba(255,193,7,0.3); font-size: 10px;">🕒 PAYMENT PENDING</span>
                                        @if($order->expires_at && $order->expires_at->isFuture())
                                            <span class="timer-display ml-1" data-expires="{{ $order->expires_at->toIso8601String() }}" style="font-size: 10px; color: #ffc107; font-weight: bold;">--:--</span>
                                        @endif
                                    @elseif($order && $order->payment_status === 'Verified')
                                        <span class="badge badge-success ml-2" style="background: rgba(40,167,69,0.1); color: #28a745; border: 1px solid rgba(40,167,69,0.3); font-size: 10px;">✅ PAID</span>
                                    @elseif($order && $order->payment_status === 'Expired')
                                        <span class="badge badge-danger ml-2" style="background: rgba(220,53,69,0.1); color: #dc3545; border: 1px solid rgba(220,53,69,0.3); font-size: 10px;">❌ EXPIRED</span>
                                    @endif
                                </span>

                                <div class="d-flex gap-2">
                                    @if($order && $order->payment_status === 'Pending' && !$order->isExpired() && !$isEventDeleted)
                                        <a href="{{ route('public.checkout.show', [$event->event_id, $ord['ticket_types']->first()['id'] ?? 0]) }}?resume=1&order_id={{ $order->transaction_id }}" class="btn-detail" style="background: linear-gradient(135deg, #ffc107, #d39e00); box-shadow: 0 4px 15px rgba(255,193,7,0.3);">
                                            <i class="fa fa-credit-card"></i> Pay Now
                                        </a>
                                    @endif

                                    @if($order && $order->payment_status === 'Verified')
                                        <a href="{{ route('tickets.view', $ord['representative_id']) }}" class="btn-detail">
                                            <i class="fa fa-qrcode"></i> Lihat {{ $ord['ticket_count'] }} QR Code
                                        </a>
                                    @else
                                        <button class="btn-detail" style="background: #333; cursor: not-allowed; opacity: 0.6;" disabled>
                                            <i class="fa fa-lock"></i> Locked
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">🎟️</div>
                <h3 style="color:#fff;font-weight:800;margin-bottom:10px;">Belum Ada Tiket</h3>
                <p style="color:rgba(255,255,255,0.4);margin-bottom:30px;">Tiket yang kamu beli akan muncul di sini.</p>
                <a href="{{ route('landing') }}" class="btn-detail" style="font-size:0.9rem;padding:12px 36px;">
                    🎵 Cari Konser
                </a>
        @endif

    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const timers = document.querySelectorAll('.timer-display');
        
        function updateTimers() {
            timers.forEach(timer => {
                const expiresAt = new Date(timer.dataset.expires).getTime();
                const now = new Date().getTime();
                const distance = expiresAt - now;
                
                if (distance < 0) {
                    timer.innerHTML = "EXPIRED";
                    timer.style.color = "#dc3545";
                    return;
                }
                
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                timer.innerHTML = (minutes < 10 ? "0" : "") + minutes + ":" + (seconds < 10 ? "0" : "") + seconds;
            });
        }
        
        if (timers.length > 0) {
            updateTimers();
            setInterval(updateTimers, 1000);
        }
    });
    </script>
@endsection