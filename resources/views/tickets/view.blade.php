@extends('layouts.landingpageconcert.landingconcert')

@section('title', 'E-Ticket — ' . ($ticket->ticketType->event->title ?? 'TIXLY'))

@section('contentlandingconcert')
<div class="container py-5 d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="ticket-wrapper position-relative" style="width: 480px; max-width: 100%;">
        <!-- Main Ticket Card -->
        <div class="card bg-dark border-0 overflow-hidden" style="border-radius: 24px; box-shadow: 0 30px 60px rgba(0,0,0,0.6); border: 1px solid rgba(255,255,255,0.05);">
            <!-- Event Hero Banner -->
            <div class="position-relative" style="height: 180px;">
                @php
                    $event = $ticket->ticketType->event;
                    $imgUrl = str_starts_with($event->banner_url, '/storage/') ? $event->banner_url : \Illuminate\Support\Facades\Storage::url($event->banner_url);
                @endphp
                <img src="{{ $imgUrl }}" alt="{{ $event->title }}" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.6;">
                <div class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to bottom, rgba(0,0,0,0.2), #1a1a1a);"></div>
                
                <!-- Status Badge Overlay -->
                <div class="position-absolute" style="top: 20px; right: 20px;">
                    <span class="badge" style="background: {{ $ticket->ticket_status == 'Active' ? '#00ff7f' : '#666' }}; color: #000; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; padding: 6px 15px; border-radius: 50px;">
                        {{ $ticket->ticket_status }}
                    </span>
                </div>
            </div>

            <!-- Content Area -->
            <div class="card-body p-5 text-center" style="background: #1a1a1a;">
                <h5 class="text-white-50 mb-1" style="font-weight: bold; font-family: 'DM Serif Display', serif; letter-spacing: 2px;">TICKET E-ENTRY</h5>
                <h2 class="text-white font-weight-bold mb-4" style="font-size: 1.8rem; font-family: 'DM Serif Display', serif;">{{ $event->title }}</h2>

                <hr style="border-top: 2px dashed rgba(255,255,255,0.1); margin: 30px 0;">

                <!-- QR CODE AREA -->
                <div class="qr-container mb-4 d-inline-block p-4" style="background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(220, 20, 60, 0.2);">
                    @if($qrCodeUrl)
                        <img src="{{ $qrCodeUrl }}" alt="Ticket QR" style="width: 220px; min-width: 220px; height: 220px;">
                    @else
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode($ticket->ticket_id) }}" alt="Ticket QR" style="width: 220px; min-width: 220px; height: 220px;">
                    @endif
                </div>
                <p class="text-white-50 small mb-4" style="letter-spacing: 1px;">SHOW THIS CODE AT THE ENTRANCE</p>

                <div class="row text-left mt-5">
                    <div class="col-6 mb-3">
                        <small class="text-white-50 d-block text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">DATE</small>
                        <span class="text-white" style="font-weight: 600;">{{ $event->schedule_time ? $event->schedule_time->format('d M Y') : 'TBA' }}</span>
                    </div>
                    <div class="col-6 mb-3 text-right">
                        <small class="text-white-50 d-block text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">TIME</small>
                        <span class="text-white" style="font-weight: 600;">{{ $event->schedule_time ? $event->schedule_time->format('H:i') : 'TBA' }} WIB</span>
                    </div>
                    <div class="col-6">
                        <small class="text-white-50 d-block text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">TYPE</small>
                        <span class="text-white" style="font-weight: 600;">{{ $ticket->ticketType->name }}</span>
                    </div>
                    <div class="col-6 text-right">
                        <small class="text-white-50 d-block text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">QUANTITY</small>
                        <span class="text-white" style="font-weight: 600;">{{ $order->total_ticket ?? 1 }}x Tiket</span>
                    </div>
                </div>

                <div class="mt-5 pb-2">
                    <a href="{{ route('tickets.index') }}" class="btn btn-block btn-lg" style="background: #dc143c; color: #fff; border-radius: 12px; font-weight: bold; border: none; padding: 15px;">
                        <i class="fa fa-history mr-2"></i> Kembali ke My Tickets
                    </a>
                    <a href="#" class="btn btn-link btn-block text-muted mt-2" onclick="window.print()">
                        <i class="fa fa-print mr-1"></i> Print / Save as PDF
                    </a>
                </div>
            </div>

            <!-- Bottom Perforation Detail -->
            <div class="position-relative" style="height: 10px; background: #1a1a1a;">
                <div style="position: absolute; bottom: -5px; left: -10px; width: 20px; height: 20px; background:rgb(18, 18, 18); border-radius: 50%;"></div>
                <div style="position: absolute; bottom: -5px; right: -10px; width: 20px; height: 20px; background:rgb(18, 18, 18); border-radius: 50%;"></div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background:rgb(18, 18, 18) !important;
    }
    @media print {
        .btn, .main-footer, .main-header { display: none !important; }
        body { background: #fff !important; }
        .ticket-wrapper { transform: scale(1) !important; box-shadow: none !important; }
        .card { border: 1px solid #eee !important; }
        .qr-container { box-shadow: none !important; }
    }
</style>
@endsection
