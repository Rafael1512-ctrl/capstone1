@extends('layouts.landingpageconcert.landingconcert')

@section('title', 'My Tickets — TIXLY')

@section('contentlandingconcert')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-md-12">
            <h1 class="font-weight-bold text-white mb-1" style="font-family: 'DM Serif Display', serif;">My Tickets</h1>
            <p class="text-white-50">Manage and view your collection of concert tickets.</p>
        </div>
    </div>

    <!-- Ticket Stats -->
    <div class="row mb-5">
        <div class="col-md-4 mb-3">
            <div class="card p-4" style="border-radius: 20px; background: linear-gradient(145deg, #1c1c1c, #161616); border: 1px solid rgba(255,255,255,0.05);">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: rgba(220, 20, 60, 0.1); color: #dc143c;">
                        <i class="fa fa-ticket fa-lg"></i>
                    </div>
                    <div class="ml-3">
                        <small class="text-white-50 text-uppercase font-weight-bold" style="letter-spacing: 1px; font-size: 0.7rem;">Total Tickets</small>
                        <h3 class="text-white mb-0 font-weight-bold">{{ $totalCount }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card p-4" style="border-radius: 20px; background: linear-gradient(145deg, #1c1c1c, #161616); border: 1px solid rgba(255,255,255,0.05);">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: rgba(0, 255, 127, 0.1); color: #00ff7f;">
                        <i class="fa fa-check-circle fa-lg"></i>
                    </div>
                    <div class="ml-3">
                        <small class="text-white-50 text-uppercase font-weight-bold" style="letter-spacing: 1px; font-size: 0.7rem;">Active</small>
                        <h3 class="text-white mb-0 font-weight-bold">{{ $activeCount }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card p-4" style="border-radius: 20px; background: linear-gradient(145deg, #1c1c1c, #161616); border: 1px solid rgba(255,255,255,0.05);">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: rgba(255, 255, 255, 0.05); color: #888;">
                        <i class="fa fa-history fa-lg"></i>
                    </div>
                    <div class="ml-3">
                        <small class="text-white-50 text-uppercase font-weight-bold" style="letter-spacing: 1px; font-size: 0.7rem;">Used</small>
                        <h3 class="text-white mb-0 font-weight-bold">{{ $usedCount }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tickets List -->
    <div class="row">
        @forelse($tickets as $ticket)
            @php
                $event = $ticket->ticketType->event ?? null;
                $order = $ticket->order ?? null;
                $statusColor = $ticket->ticket_status == 'Active' ? '#00ff7f' : '#888';
            @endphp
            <div class="col-md-6 mb-4">
                <div class="ticket-card d-flex" style="background: #161616; border-radius: 24px; overflow: hidden; height: 205px; border: 1px solid rgba(255,255,255,0.08); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                    <!-- Image -->
                    <div style="width: 140px; min-width: 140px; height: 100%; position: relative;">
                        @if($event && $event->banner_url)
                            @php
                                $imgUrl = str_starts_with($event->banner_url, '/storage/') ? $event->banner_url : \Illuminate\Support\Facades\Storage::url($event->banner_url);
                            @endphp
                            <img src="{{ \App\Models\SiteSetting::forceDirectUrl($imgUrl) }}" alt="{{ $event->title }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.onerror=null;this.src='https://via.placeholder.com/140x180/1a0a0a/dc143c?text=Concert';" referrerpolicy="no-referrer">
                        @else
                            <div style="width: 100%; height: 100%; background: #222; display: flex; align-items: center; justify-content: center;">
                                <i class="fa fa-music text-muted"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Details -->
                    <div class="p-4 flex-grow-1 border-right" style="border-right: 2px dashed rgba(255,255,255,0.1) !important; position: relative;">
                        <span class="badge position-absolute" style="top: 15px; right: 15px; background: {{ $statusColor }}; color: #000; font-weight: 800; font-size: 0.65rem; text-transform: uppercase; padding: 4px 8px; border-radius: 5px;">
                            {{ $ticket->ticket_status }}
                        </span>
                        <h5 class="text-white font-weight-bold mb-1" style="font-size: 1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $event->title ?? 'Event' }}</h5>
                        <p class="text-white-50 small mb-1"><i class="fa fa-calendar mr-1"></i> {{ $event && $event->schedule_time ? $event->schedule_time->format('d M Y') : 'TBA' }}</p>
                        <p class="text-white-50 small mb-2" style="font-size: 0.7rem;"><i class="fa fa-shopping-cart mr-1"></i> Dibeli: {{ $order && $order->payment_date ? $order->payment_date->format('d M Y, H:i') : 'Unknown' }} WIB</p>
                        
                        <div class="mt-2">
                            <span class="text-white-50 d-block mb-1" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">Ticket Type</span>
                            <span class="text-white font-weight-bold" style="font-size: 0.9rem;">{{ $ticket->ticketType->name ?? 'Standard' }}</span>
                        </div>

                        <div class="mt-2 d-flex justify-content-between align-items-end">
                            <div>
                                <span class="badge px-2 py-1" style="background: rgba(220, 20, 60, 0.15); color: #dc143c; border: 1px solid rgba(220, 20, 60, 0.2); border-radius: 5px; font-size: 0.75rem;">
                                    {{ $order->total_ticket ?? 1 }}x Tiket
                                </span>
                            </div>
                            <a href="{{ route('tickets.view', $ticket->ticket_id) }}" class="btn btn-sm btn-outline-danger" style="border-radius: 50px; font-weight: bold; font-size: 0.7rem; border-color: #dc143c; color: #dc143c;">Details</a>
                        </div>
                    </div>

                    <!-- QR Area -->
                    <div class="d-flex align-items-center justify-content-center p-3" style="width: 100px; min-width: 100px; background: #1a1a1a;">
                        <div class="text-center">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ urlencode($ticket->ticket_id) }}" alt="QR" style="width: 60px; height: 60px; filter: grayscale(1) invert(1); opacity: 0.9;">
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-md-12 text-center py-5">
                <div class="mb-4">
                    <i class="fa fa-ticket fa-5x text-white-50 opacity-2" style="opacity: 0.1;"></i>
                </div>
                <h3 class="text-white font-weight-bold">Belum Ada Tiket</h3>
                <p class="text-white-50">Tiket yang kamu beli akan muncul di sini.</p>
                <a href="{{ route('landing') }}" class="btn mt-3" style="background: #dc143c; color: #fff; border-radius: 50px; font-weight: bold; padding: 12px 40px;">Cari Konser</a>
            </div>
        @endforelse
    </div>

    @if($tickets->hasPages())
    <div class="d-flex justify-content-center mt-5 mb-5 pb-5 custom-pagination">
        {{ $tickets->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>

<style>
    .ticket-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(220, 20, 60, 0.15);
        border-color: rgba(220, 20, 60, 0.3) !important;
    }
    
    /* Pagination Styling */
    .custom-pagination .pagination {
        gap: 8px;
    }
    .custom-pagination .page-link {
        background: #1a1a1a;
        color: rgba(255,255,255,0.6);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 12px !important;
        padding: 10px 18px;
        font-weight: 700;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        min-width: 45px;
        text-align: center;
    }
    .custom-pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #dc143c 0%, #a30f2d 100%);
        border-color: #dc143c;
        color: #fff;
        box-shadow: 0 5px 15px rgba(220,20,60,0.4);
    }
    .custom-pagination .page-link:hover {
        background: rgba(255,255,255,0.05);
        color: #fff;
        border-color: rgba(220,20,60,0.5);
    }
    .custom-pagination .page-item.disabled .page-link {
        background: #111;
        color: #444;
        border-color: #222;
    }
</style>
@endsection
