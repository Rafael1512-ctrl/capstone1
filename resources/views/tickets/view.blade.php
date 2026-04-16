@extends('layouts.landingpageconcert.landingconcert')

@section('title', 'E-Ticket — ' . (($ticket->ticketType->eventWithTrashed ?? $ticket->ticketType->event)?->title ?? 'TIXLY'))

@section('contentlandingconcert')
<div class="container py-5">
    <!-- Action Buttons (Top) -->
    <div class="text-center mb-5 no-print">
        <h2 class="text-white font-weight-bold mb-3" style="font-family: 'DM Serif Display', serif;">Your Digital Tickets</h2>
        <div class="d-flex justify-content-center gap-3">
             <a href="{{ route('tickets.index') }}" class="btn btn-outline-light px-4 py-2" style="border-radius: 50px;">
                <i class="fa fa-arrow-left mr-2"></i> Back to Tickets
             </a>
             <button onclick="window.print()" class="btn btn-danger px-5 py-2" style="border-radius: 50px; background: #dc143c; border: none; box-shadow: 0 5px 15px rgba(220,20,60,0.3);">
                <i class="fa fa-print mr-2"></i> Print All Tickets
             </button>
        </div>
    </div>

    {{-- SCREEN VIEW: Separated Horizontal Cards --}}
    <div class="tickets-container no-print">
        @foreach($ticketsInOrder as $t)
            <div class="ticket-wrapper position-relative mb-5 mx-auto" style="width: 1000px; max-width: 100%;" id="ticket-{{ $t->ticket_id }}">
                <div class="card bg-dark border-0 overflow-hidden shadow-2xl main-ticket-card" 
                     style="border-radius: 30px; border: 1px solid {{ $t->ticket_id === $ticket->ticket_id ? '#dc143c' : 'rgba(255,255,255,0.08)' }}; background: linear-gradient(145deg, #1a1a1a 0%, #0f0f0f 100%);">
                    
                    <div class="row no-gutters">
                        <!-- Left Section: Poster -->
                        <div class="col-md-5 position-relative overflow-hidden" style="min-height: 400px;">
                            @php
                                // Pakai eventWithTrashed agar event soft-deleted tetap tampil
                                $event = $t->ticketType->eventWithTrashed ?? $t->ticketType->event;
                                $imgUrl = null;
                                if ($event && $event->banner_url) {
                                    $imgUrl = filter_var($event->banner_url, FILTER_VALIDATE_URL)
                                        ? $event->banner_url
                                        : (str_starts_with($event->banner_url, '/storage/')
                                            ? $event->banner_url
                                            : \Illuminate\Support\Facades\Storage::url($event->banner_url));
                                }
                                $transaction = $t->order;
                            @endphp
                            @if($imgUrl)
                                <img src="{{ \App\Models\SiteSetting::forceDirectUrl($imgUrl) }}" alt="{{ $event->title ?? 'Event' }}" class="h-100 w-100" style="object-fit: cover; opacity: 0.8;" >
                            @else
                                <div class="h-100 w-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #1e0a0a, #2a1020); font-size: 4rem;">🎵</div>
                            @endif
                            <div class="position-absolute" style="top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(to right, rgba(0,0,0,0.4), transparent, rgba(0,0,0,0.85));"></div>
                            
                            <div class="position-absolute p-4" style="bottom: 0; left: 0; width: 100%;">
                                <h1 class="text-white" style="font-family: 'DM Serif Display', serif; font-size: 2.2rem; line-height: 1;">{{ $event->title ?? 'Event' }}</h1>
                                <p class="text-white-50 mb-0 small" style="letter-spacing: 2px;">{{ $event->location ?? '' }}</p>
                            </div>
                        </div>

                        <!-- Right Section: Details -->
                        <div class="col-md-7 d-flex flex-column" style="background: #121212; position: relative;">
                            <div class="perforation-top"></div>
                            <div class="perforation-bottom"></div>

                            <div class="card-body p-4 p-lg-5 d-flex flex-row align-items-center">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div class="text-white font-weight-bold" style="letter-spacing: 1px; color: #dc143c !important;">
                                             <i class="fa fa-tag mr-2"></i> {{ strtoupper($t->ticket_id) }}
                                        </div>
                                        <span class="badge {{ $t->ticket_status === 'Active' ? 'bg-success' : 'bg-secondary' }} px-3 py-1 rounded-pill">
                                            {{ strtoupper($t->ticket_status) }}
                                        </span>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-6">
                                            <label class="text-white-50 d-block text-uppercase mb-1 small" style="letter-spacing: 1px;">SECTION/TIER</label>
                                            <span class="text-white h5 font-weight-bold">{{ $t->ticketType->name }}</span>
                                        </div>
                                        <div class="col-6">
                                            <label class="text-white-50 d-block text-uppercase mb-1 small" style="letter-spacing: 1px;">DATE/TIME</label>
                                        <span class="text-white h6 font-weight-bold">{{ $event && $event->schedule_time ? $event->schedule_time->format('d M, H:i') : 'TBA' }} WIB</span>
                                        </div>
                                    </div>
                                    
                                    <div class="p-3 mb-1" style="background: rgba(255,255,255,0.05); border-radius: 12px;">
                                        <label class="text-white-50 d-block text-uppercase mb-0 small" style="font-size: 0.6rem;">TICKET HOLDER</label>
                                        <span class="text-white font-weight-bold">{{ $transaction->user->name }}</span>
                                    </div>
                                </div>

                                <div class="ml-4 text-center">
                                    <div class="bg-white p-2 border rounded shadow-lg mb-2 mx-auto" style="width: 150px; height: 150px;">
                                        @php $qr = route('tickets.scan.direct', $t->ticket_id); @endphp
                                        {!! QrCode::size(134)->generate($qr) !!}
                                    </div>
                                    <small class="text-white-50 font-weight-bold">SCAN GATE</small>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="px-5 pb-4 mt-n2">
                                <div class="row gutter-sm">
                                    <div class="col-sm-6 mb-2">
                                        <button class="btn btn-outline-light btn-block py-2 font-weight-bold" 
                                                style="border-radius: 10px; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.05);"
                                                onclick="printThisTicket('{{ $t->ticket_id }}')">
                                            <i class="fa fa-print mr-2"></i> PRINT TICKET
                                        </button>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <a href="{{ route('tickets.download.pdf', $t->ticket_id) }}" 
                                           target="_blank"
                                           class="btn btn-outline-danger btn-block py-2 font-weight-bold" 
                                           style="border-radius: 10px; border: 1px solid rgba(220,20,60,0.5); background: rgba(220,20,60,0.1); color: #ff6b6b;">
                                            <i class="fa fa-file-pdf-o mr-2"></i> DOWNLOAD PDF
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- PRINT VIEW: Pure High Quality Printable Stubs --}}
    <div class="print-only-container">
        @foreach($ticketsInOrder as $t)
            <div class="print-stub-page print-ticket-{{ $t->ticket_id }}">
                <div class="print-stub">
                    @php
                        // Pakai eventWithTrashed agar event soft-deleted tetap tampil di print view
                        $event = $t->ticketType->eventWithTrashed ?? $t->ticketType->event;
                        $imgUrl = null;
                        if ($event && $event->banner_url) {
                            $imgUrl = filter_var($event->banner_url, FILTER_VALIDATE_URL)
                                ? $event->banner_url
                                : (str_starts_with($event->banner_url, '/storage/')
                                    ? $event->banner_url
                                    : \Illuminate\Support\Facades\Storage::url($event->banner_url));
                        }
                        $transaction = $t->order;
                    @endphp
                    <div class="print-stub-left">
                        @if($imgUrl)
                            <img src="{{ \App\Models\SiteSetting::forceDirectUrl($imgUrl) }}" alt="Poster" >
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#111;font-size:3rem;">🎵</div>
                        @endif
                        <div class="print-overlay"></div>
                        <div class="print-header">
                            <div class="print-badge">OFFICIAL ENTRY</div>
                            <h1 class="print-title">{{ $event->title ?? 'Event' }}</h1>
                        </div>
                    </div>
                    <div class="print-stub-right">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="print-tier">{{ strtoupper($t->ticketType->name) }}</div>
                            <div class="text-right">
                                <div class="print-branding">TIXLY CONCERTS</div>
                                <div style="font-family: monospace; font-size: 8.5pt; color: #dc143c; font-weight: 800; margin-top: -5px;">#{{ $t->ticket_id }}</div>
                            </div>
                        </div>
                        
                        <div class="print-qr">
                            @php $qrPrint = route('tickets.scan.direct', $t->ticket_id); @endphp
                            {!! QrCode::size(140)->generate($qrPrint) !!}
                            <div class="print-hint">SCAN AT THE GATE</div>
                        </div>

                        <div class="print-info">
                            <div class="p-info-item">
                                <small>DATE / TIME</small>
                                <p>{{ $event && $event->schedule_time ? $event->schedule_time->format('d M Y | H:i') : 'TBA' }} WIB</p>
                            </div>
                            <div class="p-info-item">
                                <small>VENUE</small>
                                <p>{{ $event->location ?? 'TBA' }}</p>
                            </div>
                            <div class="p-info-item">
                                <small>ATTENDEE</small>
                                <p>{{ $transaction->user->name }}</p>
                            </div>
                            <div class="p-info-item">
                                <small>TICKET SERIAL ID</small>
                                <p style="font-family: monospace;">#{{ $t->ticket_id }}</p>
                            </div>
                        </div>

                        <div class="print-serial">
                            Generated by TIXLY System - {{ now()->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');

    body {
        background: radial-gradient(circle at top right, #1a0000 0%, #000000 100%) !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
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

    /* Shared Printable Stub Styles (Available for Screen & Print) */
    .print-stub-page {
        display: none;
        width: 100% !important;
        position: relative !important;
        box-sizing: border-box !important;
    }

    .print-stub {
        display: flex !important;
        width: 21cm !important;
        height: 9.5cm !important;
        border: 1px solid #333 !important;
        background: #fff !important;
        margin: 0 auto !important;
        position: relative !important;
        overflow: hidden !important;
        text-align: left !important;
        color: #000 !important;
    }

    .print-stub-left {
        width: 38% !important;
        height: 100% !important;
        position: relative !important;
        background-color: #000 !important;
    }

    .print-stub-left img {
        display: block !important;
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
    }

    .print-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.85), transparent);
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
        width: 62%;
        padding: 25px 35px;
        display: flex;
        flex-direction: column;
        border-left: 2px dashed #eee;
        text-align: left;
        box-sizing: border-box;
    }

    .print-branding { font-size: 11pt; font-weight: 900; color: #dc143c; margin-bottom: 15px; text-align: right;}
    .print-tier { 
        background: #000; 
        color: #fff; 
        padding: 4px 12px; 
        display: inline-block; 
        font-weight: 800; 
        font-size: 10pt;
        margin-bottom: 20px;
        align-self: flex-start;
    }

    .print-qr { text-align: center; margin-bottom: 20px; }
    .print-qr img, .print-qr svg { height: 3.5cm; width: 3.5cm; }
    .print-hint { font-size: 7.5pt; color: #888; font-weight: 800; margin-top: 5px; }

    .print-info { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: auto; }
    .p-info-item { margin-bottom: 5px; }
    .p-info-item small { display: block; font-size: 6.5pt; color: #888; font-weight: 800; text-transform: uppercase; }
    .p-info-item p { font-size: 10pt; font-weight: 700; color: #000; margin: 0; line-height: 1.2; }
    
    .print-serial { 
        margin-top: 10px; 
        font-size: 6pt; 
        font-family: monospace; 
        color: #aaa;
        text-align: right;
    }

    /* Printing Logic */
    @media print {
        @page {
            size: landscape;
            margin: 0;
        }
        html, body {
            height: auto !important;
            overflow: visible !important;
            background: #fff !important;
        }
        .container, .main-content {
            margin: 0 !important;
            padding: 0 !important;
            max-width: 100% !important;
        }
        .print-only-container {
            display: block !important;
            visibility: visible !important;
            width: 100% !important;
        }
        .perforation-top, .perforation-bottom, .no-print, header, footer, .main-header, .navbar {
            display: none !important;
        }
        .print-stub-page {
            display: block !important;
            page-break-after: always !important;
            break-after: page !important;
            padding: 3.5cm 0 !important; /* Increased top margin for better printing */
            visibility: visible !important;
        }
        .print-stub-page:last-child {
            page-break-after: auto !important;
            break-after: auto !important;
        }
        .print-stub {
            border: 1px solid #333 !important;
            background: #fff !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            visibility: visible !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .print-stub-left {
            background-color: #000 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .print-overlay {
            background: linear-gradient(to top, rgba(0,0,0,0.85), transparent) !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }

    /* Logic for printing single ticket */
    @media print {
        header, footer, .main-header, .navbar, .no-print {
            display: none !important;
        }
        body.printing-single .print-stub-page {
            display: none !important;
        }
        body.printing-single .print-stub-page.active-single-print {
            display: block !important;
            page-break-after: avoid !important;
            margin: 0 !important;
            padding: 0 !important;
        }
    }
</style>

<script>
    function printThisTicket(ticketId) {
        // Reset previous single prints
        document.querySelectorAll('.print-stub-page').forEach(page => {
            page.classList.remove('active-single-print');
        });

        // Add active class to target ticket
        const targetPage = document.querySelector(`.print-ticket-${ticketId}`);
        if (targetPage) {
            targetPage.classList.add('active-single-print');
            document.body.classList.add('printing-single');
            window.print();

            window.onafterprint = function() {
                document.body.classList.remove('printing-single');
                targetPage.classList.remove('active-single-print');
            };
        }
    }

    function downloadTicketPDF(ticketId) {
        // Find the print-stub inside the print-only-container for this ticket
        const wrapper = document.querySelector(`.print-ticket-${ticketId}`);
        const originalStub = wrapper ? wrapper.querySelector('.print-stub') : null;

        if (!originalStub) {
            alert('Ticket not found.');
            return;
        }

        // Collect all <style> and <link rel="stylesheet"> from the current page
        let stylesHtml = '';
        document.querySelectorAll('style').forEach(s => {
            stylesHtml += s.outerHTML;
        });
        document.querySelectorAll('link[rel="stylesheet"]').forEach(l => {
            stylesHtml += l.outerHTML;
        });

        // Build full HTML for the iframe: just the stub, fully styled
        const stubHTML = `<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
${stylesHtml}
<style>
  /* Override to make stub visible in iframe */
  html, body { margin: 0; padding: 0; background: #fff !important; }
  .print-stub-page { display: block !important; visibility: visible !important; padding: 0 !important; }
  .print-stub {
    display: flex !important;
    width: 21cm !important;
    height: 9.5cm !important;
    border: 1px solid #333 !important;
    background: #fff !important;
    margin: 0 auto !important;
    overflow: hidden !important;
    color: #000 !important;
  }
  .no-print, header, footer, .navbar, .tickets-container { display: none !important; }
  @page { size: landscape; margin: 0; }
  @media print {
    html, body { background: #fff !important; }
    .print-stub-page { display: block !important; }
    .print-stub { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    .print-stub-left { background-color: #000 !important; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    .print-overlay { background: linear-gradient(to top, rgba(0,0,0,0.85), transparent) !important; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
  }
</style>
</head>
<body>
<div class="print-stub-page print-ticket-${ticketId}">
  ${originalStub.outerHTML}
</div>
</body>
</html>`;

        // Create hidden iframe
        const iframe = document.createElement('iframe');
        iframe.style.cssText = 'position:fixed;top:-9999px;left:-9999px;width:29.7cm;height:21cm;border:0;visibility:hidden;';
        document.body.appendChild(iframe);

        const iframeDoc = iframe.contentWindow.document;
        iframeDoc.open();
        iframeDoc.write(stubHTML);
        iframeDoc.close();

        // Wait for images and fonts to load, then print
        iframe.onload = function() {
            setTimeout(function() {
                try {
                    // Override the default print filename through document title
                    const originalTitle = document.title;
                    document.title = `TIXLY_T-${ticketId}`;
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                    // Restore title after short delay
                    setTimeout(function() {
                        document.title = originalTitle;
                    }, 1000);
                } catch(e) {
                    console.error('Print error:', e);
                }
                // Remove iframe after dialog closes
                iframe.contentWindow.onafterprint = function() {
                    document.body.removeChild(iframe);
                };
                setTimeout(function() {
                    if (document.body.contains(iframe)) {
                        document.body.removeChild(iframe);
                    }
                }, 30000);
            }, 800); // slight delay for fonts/images
        };
    }
</script>
@endsection
