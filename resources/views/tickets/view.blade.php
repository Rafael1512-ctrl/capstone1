@extends('layouts.landingpageconcert.landingconcert')

@section('title', 'E-Ticket — ' . ($ticket->ticketType->event->title ?? 'TIXLY'))

@section('contentlandingconcert')
<div class="container py-5 d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    {{-- SCREEN VIEW: The Horizontal Dark Card the user likes --}}
    <div class="ticket-wrapper position-relative no-print" style="width: 1000px; max-width: 100%;">
        
        <!-- Main E-Ticket Horizontal Card -->
        <div class="card bg-dark border-0 overflow-hidden shadow-2xl main-ticket-card" 
             style="border-radius: 30px; border: 1px solid rgba(255,255,255,0.08); background: linear-gradient(145deg, #1a1a1a 0%, #0f0f0f 100%);">
            
            <div class="row no-gutters h-100">
                <!-- Left Section: Event Poster & Banner -->
                <div class="col-md-5 position-relative overflow-hidden" style="min-height: 480px;">
                    @php
                        $event = $ticket->ticketType->event;
                        $imgUrl = str_starts_with($event->banner_url, '/storage/') ? $event->banner_url : \Illuminate\Support\Facades\Storage::url($event->banner_url);
                        $transaction = $ticket->order;
                    @endphp
                    <img src="{{ \App\Models\SiteSetting::forceDirectUrl($imgUrl) }}" alt="{{ $event->title }}" class="h-100 w-100" style="object-fit: cover; opacity: 0.7;" referrerpolicy="no-referrer">
                    
                    <!-- Glass Overlay -->
                    <div class="position-absolute" style="top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(to right, rgba(0,0,0,0.4), transparent, rgba(0,0,0,0.8));"></div>
                    
                    <!-- Event Status & Badge -->
                    <div class="position-absolute p-4" style="top: 0; left: 0; width: 100%;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge" style="background: rgba(220, 20, 60, 0.9); color: white; padding: 10px 20px; border-radius: 30px; font-weight: 700; letter-spacing: 1px; box-shadow: 0 4px 15px rgba(220,20,60,0.4);">
                                {{ strtoupper($ticket->ticket_status) }}
                            </span>
                            <div class="text-white-50 small " style="font-weight: 600; letter-spacing: 2px;">
                                ORDER #{{ substr($transaction->transaction_id, 0, 8) }}
                            </div>
                        </div>
                    </div>

                    <!-- Event Name on Image (Floating) -->
                    <div class="position-absolute p-4" style="bottom: 20px; left: 20px; right: 20px;">
                        <h5 class="text-white-50 mb-0" style="font-weight: 700; font-family: 'DM Serif Display', serif; letter-spacing: 3px; font-size: 0.8rem;">OFFICIAL ENTRY</h5>
                        <h1 class="text-white" style="font-family: 'DM Serif Display', serif; font-size: 2.8rem; line-height: 1; text-shadow: 0 4px 10px rgba(0,0,0,0.5);">{{ $event->title }}</h1>
                    </div>
                </div>

                <!-- Right Section: Content Area -->
                <div class="col-md-7 d-flex flex-column" style="background: #121212; position: relative;">
                    <!-- Perforation Circles (The "Tear Off" Look) -->
                    <div class="perforation-top"></div>
                    <div class="perforation-bottom"></div>

                    <div class="card-body p-4 p-lg-5 d-flex flex-column h-100">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <h6 class="text-white-50 mb-1" style="letter-spacing: 3px; font-weight: 800;">TICKET E-ENTRY</h6>
                                <p class="text-muted small">Show this QR code at the entrance gate</p>
                            </div>
                            <div class="text-right">
                                <h3 class="text-white font-weight-bold mb-0" style="color: #dc143c !important;">{{ $ticket->ticketType->name }}</h3>
                                <div class="badge badge-outline-danger mt-1" style="border: 1px solid #dc143c; color: #dc143c; border-radius: 5px; padding: 2px 8px; font-size: 0.7rem;">Verified Purchase</div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-6 mb-4">
                                <label class="text-white-50 d-block text-uppercase mb-1" style="font-size: 0.65rem; letter-spacing: 2px; font-weight: 700;">DATE</label>
                                <span class="text-white h5 font-weight-bold">{{ $event->schedule_time ? $event->schedule_time->format('d M Y') : 'TBA' }}</span>
                            </div>
                            <div class="col-6 mb-4">
                                <label class="text-white-50 d-block text-uppercase mb-1" style="font-size: 0.65rem; letter-spacing: 2px; font-weight: 700;">TIME</label>
                                <span class="text-white h5 font-weight-bold">{{ $event->schedule_time ? $event->schedule_time->format('H:i') : 'TBA' }} WIB</span>
                            </div>
                            <div class="col-12 mb-4">
                                <div class="p-3 bg-dark-soft rounded-xl" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 15px;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <label class="text-white-50 d-block text-uppercase mb-0" style="font-size: 0.6rem; letter-spacing: 2px; font-weight: 700;">QUANTITY PURCHASED</label>
                                            <span class="text-white font-weight-bold" style="font-size: 1.1rem;">{{ $transaction->total_ticket ?? 1 }}x Tickets</span>
                                        </div>
                                        <div class="icon-circle bg-danger-transparent p-2 rounded-circle" style="background: rgba(220, 20, 108, 0.1);">
                                            <i class="fa fa-ticket text-danger" style="font-size: 1.2rem;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- QR Code Section -->
                        <div class="flex-grow-1 d-flex flex-column align-items-center justify-content-center p-3 mb-4 rounded-xl shadow-inner" 
                             style="background: #ffffff; border-radius: 20px; border: 8px solid #f8f9fa;">
                            @php
                                $qrSource = $qrCodeUrl ? $qrCodeUrl : "https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=" . urlencode(route('tickets.scan.direct', $ticket->ticket_id));
                            @endphp
                            <img src="{{ $qrSource }}" alt="Ticket QR" class="img-fluid mb-3" style="max-height: 180px; width: auto;">
                            <p class="text-dark small mb-0 font-weight-bold" style="letter-spacing: 1px;">SCAN ME TO VALIDATE</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-auto">
                            <div class="row gutter-sm">
                                <div class="col-sm-8 mb-2">
                                    <a href="{{ route('tickets.index') }}" class="btn btn-danger btn-block py-3 font-weight-bold" 
                                       style="border-radius: 12px; background: #dc143c; border: none; box-shadow: 0 10px 20px rgba(220,20,60,0.3);">
                                       <i class="fa fa-arrow-left mr-2"></i> MY TICKETS
                                    </a>
                                </div>
                                <div class="col-sm-4 mb-2">
                                    <button onclick="window.print()" class="btn btn-outline-light btn-block py-3 font-weight-bold" 
                                            style="border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.05);">
                                        <i class="fa fa-print mr-2"></i> PRINT
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- PRINT VIEW: Pure High Quality Printable Stub (Hidden on Screen) --}}
    <div class="print-only-container">
        <div class="print-stub">
            <div class="print-stub-left">
                <img src="{{ \App\Models\SiteSetting::forceDirectUrl($imgUrl) }}" alt="Poster" referrerpolicy="no-referrer">
                <div class="print-overlay"></div>
                <div class="print-header">
                    <div class="print-badge">OFFICIAL ENTRY</div>
                    <h1 class="print-title">{{ $event->title }}</h1>
                </div>
            </div>
            <div class="print-stub-right">
                <div class="print-branding">TIXLY CONCERTS</div>
                <div class="print-tier">{{ strtoupper($ticket->ticketType->name) }}</div>
                
                <div class="print-qr">
                    <img src="{{ $qrSource }}" alt="QR">
                    <div class="print-hint">SCAN AT THE GATE</div>
                </div>

                <div class="print-info">
                    <div class="p-info-item">
                        <small>DATE / TIME</small>
                        <p>{{ $event->schedule_time ? $event->schedule_time->format('d M Y | H:i') : 'TBA' }} WIB</p>
                    </div>
                    <div class="p-info-item">
                        <small>VENUE</small>
                        <p>{{ $event->location }}</p>
                    </div>
                    <div class="p-info-item">
                        <small>ATTENDEE</small>
                        <p>{{ $transaction->user->name }}</p>
                    </div>
                </div>

                <div class="print-serial">
                    SERIAL: #{{ $ticket->ticket_id }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Inter:wght@400;600;700;800&display=swap');

    body {
        background: radial-gradient(circle at top right, #1a0000 0%, #000000 100%) !important;
        font-family: 'Inter', sans-serif;
    }

    /* Screen Styles */
    .perforation-top, .perforation-bottom {
        position: absolute;
        left: -15px;
        width: 30px;
        height: 30px;
        background: #0d0d0d;
        border-radius: 50%;
        z-index: 10;
    }
    .perforation-top { top: -15px; }
    .perforation-bottom { bottom: -15px; }

    /* Print Only Container - Hidden on Screen */
    .print-only-container {
        display: none;
    }

    @media print {
        @page {
            size: landscape;
            margin: 0.5cm;
        }
        body {
            background: #fff !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .no-print, header, footer, .main-header, .navbar {
            display: none !important;
        }
        .print-only-container {
            display: block !important;
            width: 100%;
        }
        .print-stub {
            display: flex;
            width: 25cm;
            height: 12cm;
            border: 2px solid #000;
            background: #fff;
            margin: 0 auto;
            position: relative;
            overflow: hidden;
        }
        .print-stub-left {
            width: 40%;
            height: 100%;
            position: relative;
            background-color: #000 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .print-stub-left img {
            display: block !important;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 1 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .print-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.85), transparent) !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .print-header {
            position: absolute;
            bottom: 30px;
            left: 30px;
            color: #fff;
        }
        .print-badge { font-size: 10pt; font-weight: 800; letter-spacing: 2pt; color: #dc143c; }
        .print-title { font-size: 28pt; margin: 0; font-family: 'DM Serif Display', serif; }

        .print-stub-right {
            width: 60%;
            padding: 30px 50px;
            display: flex;
            flex-direction: column;
            border-left: 2px dashed #ccc;
            text-align: left;
        }
        .print-branding { font-size: 12pt; font-weight: 900; color: #dc143c; margin-bottom: 20px; text-align: right;}
        .print-tier { 
            background: #000; 
            color: #fff; 
            padding: 5px 15px; 
            display: inline-block; 
            font-weight: 800; 
            font-size: 11pt;
            margin-bottom: 30px;
            align-self: flex-start;
        }
        .print-qr { text-align: center; margin-bottom: 30px; }
        .print-qr img { height: 4cm; }
        .print-hint { font-size: 8pt; color: #888; font-weight: 800; margin-top: 5px; }

        .print-info { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .p-info-item small { display: block; font-size: 7pt; color: #888; font-weight: 800; }
        .p-info-item p { font-size: 11pt; font-weight: 700; color: #000; margin: 0; }
        
        .print-serial { 
            margin-top: auto; 
            font-size: 7pt; 
            font-family: monospace; 
            color: #888;
            text-align: right;
        }
    }
</style>
@endsection
