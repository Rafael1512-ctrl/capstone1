@extends('layouts.landingpageconcert.landingconcert')

@section('title', 'E-Ticket — ' . ($ticket->ticketType->event->title ?? 'TIXLY'))

@section('contentlandingconcert')
<div class="container py-5 d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="ticket-wrapper position-relative" style="width: 1000px; max-width: 100%;">
        
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
                    <img src="{{ $imgUrl }}" alt="{{ $event->title }}" class="h-100 w-100" style="object-fit: cover; opacity: 0.7;">
                    
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
                            
                            {{-- Admin/Organizer View: Buyer Info --}}
                            @if(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isOrganizer()))
                            <div class="col-12 mb-4">
                                <div class="p-3 shadow-inner" style="background: rgba(220, 20, 60, 0.08); border: 1px dashed rgba(220, 20, 60, 0.3); border-radius: 18px;">
                                    <label class="text-danger d-block text-uppercase mb-2 font-weight-bold" style="font-size: 0.6rem; letter-spacing: 2px;">
                                        <i class="fa fa-shield mr-1"></i> Staff Only: Buyer Info
                                    </label>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-danger text-white font-weight-bold" style="width: 42px; height: 42px; font-size: 1.1rem; box-shadow: 0 4px 10px rgba(220,20,60,0.3);">
                                                {{ strtoupper(substr($transaction->user->name ?? 'U', 0, 1)) }}
                                            </div>
                                        </div>
                                        <div style="line-height: 1.2;">
                                            <span class="text-white font-weight-bold d-block mb-1" style="font-size: 0.95rem;">{{ $transaction->user->name ?? 'Unknown User' }}</span>
                                            <span class="text-white-50 small">{{ $transaction->user->email ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- QR Code Section -->
                        <div class="flex-grow-1 d-flex flex-column align-items-center justify-content-center p-3 mb-4 rounded-xl shadow-inner" 
                             style="background: #ffffff; border-radius: 20px; border: 8px solid #f8f9fa;">
                            @php
                                // Ensure we use the SVG if it exists, or fallback to the URL-encoded direct scan link
                                $qrSource = $qrCodeUrl ? $qrCodeUrl : "https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=" . urlencode(route('tickets.scan.direct', $ticket->ticket_id));
                            @endphp
                            <img src="{{ $qrSource }}" alt="Ticket QR" class="img-fluid mb-3" style="max-height: 180px; width: auto;">
                            
                            <p class="text-muted small mb-0 font-weight-bold" style="letter-spacing: 1px;">SCAN ME TO VALIDATE</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-auto">
                            <div class="row gutter-sm">
                                <div class="col-sm-8 mb-2">
                                    <a href="{{ route('tickets.index') }}" class="btn btn-danger btn-block py-3 font-weight-bold" 
                                       style="border-radius: 12px; background: #dc143c; border: none; box-shadow: 0 10px 20px rgba(220,20,60,0.3);">
                                       <i class="fa fa-arrow-left mr-2"></i> KEMBALI KE MY TICKETS
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

        <!-- Order Additional Details (Optional but adds value) -->
        <div class="mt-4 text-center">
            <p class="text-white-50 small" style="opacity: 0.6;">
                Need help? <a href="/contact" class="text-danger">Contact Support</a> with Transaction ID: #{{ $transaction->transaction_id }}
            </p>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Inter:wght@400;600;700;800&display=swap');

    body {
        background: radial-gradient(circle at top right, #1a0000 0%, #000000 100%) !important;
        font-family: 'Inter', sans-serif;
    }

    .main-ticket-card {
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .perforation-top, .perforation-bottom {
        position: absolute;
        left: -15px;
        width: 30px;
        height: 30px;
        background:rgb(15, 0, 0);
        border-radius: 50%;
        z-index: 10;
        box-shadow: inset 0 0 10px rgba(0,0,0,0.3);
    }

    .perforation-top { top: -15px; }
    .perforation-bottom { bottom: -15px; }

    .no-gutters {
        margin-right: 0;
        margin-left: 0;
    }
    .no-gutters > .col,
    .no-gutters > [class*="col-"] {
        padding-right: 0;
        padding-left: 0;
    }

    @media (max-width: 768px) {
        .ticket-wrapper {
            padding: 15px;
        }
        .main-ticket-card .row {
            flex-direction: column;
        }
        .col-md-5 {
            min-height: 250px !important;
        }
        .perforation-top, .perforation-bottom {
            display: none;
        }
        .card-body {
            padding: 30px !important;
        }
    }

    @media print {
        @page {
            size: landscape;
            margin: 0;
        }
        body {
            background: #fff !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .main-header, .main-footer, .btn, .text-center.mt-4 {
            display: none !important;
        }
        .container {
            width: 100% !important;
            max-width: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .ticket-wrapper {
            width: 100% !important;
            max-width: none !important;
            transform: none !important;
        }
        .main-ticket-card {
            border: none !important;
            box-shadow: none !important;
            width: 100% !important;
            break-inside: avoid;
            background: #1a1a1a !important; /* Keep dark background in print */
            color: white !important;
        }
        .col-md-5 {
            width: 40% !important;
            float: left !important;
            height: 100% !important;
            min-height: 480px !important;
        }
        .col-md-7 {
            width: 60% !important;
            float: left !important;
            background: #121212 !important;
        }
        
        /* Ensure images and colors show up */
        img {
            max-width: 100% !important;
            -webkit-print-color-adjust: exact !important;
        }
        .badge {
            background-color: #dc143c !important;
            color: white !important;
            -webkit-print-color-adjust: exact !important;
        }
        .text-white { color: #fff !important; }
        .text-white-50 { color: rgba(255,255,255,0.5) !important; }
        .text-muted { color: #666 !important; }
        .bg-dark-soft { background: rgba(255,255,255,0.05) !important; }
        
        .perforation-top, .perforation-bottom {
            background: #fff !important; /* Make holes white in print */
        }
    }
</style>
@endsection

