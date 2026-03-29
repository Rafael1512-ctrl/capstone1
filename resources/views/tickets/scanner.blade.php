@extends('layouts.landingpageconcert.landingconcert')

@section('title', 'Ticket QR Scanner — TIXLY')

@section('contentlandingconcert')
<!-- Include HTML5-QRCode Library -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<div class="org-hero py-5">
    <div class="container text-center">
        <h1 class="text-white mb-3">CONCERT <span>SCANNER</span> 📸</h1>
        <p class="text-white-50">Point your camera at a guest's Ticket QR code to validate entry.</p>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Scanner Card -->
            <div class="scanner-card p-2 p-md-4 rounded-xl shadow-2xl" 
                 style="background: #111; border: 1px solid rgba(220, 20, 60, 0.2); overflow: hidden;">
                
                <div id="reader-wrapper" class="position-relative">
                    <div id="reader" style="width: 100%; border: none; border-radius: 15px; overflow: hidden; background: #000;"></div>
                    
                    <!-- Scanner Decoration / Overlay -->
                    <div class="scanner-overlay d-none" id="scan-feedback">
                        <div class="spinner-border text-danger" role="status"></div>
                        <p class="text-white mt-2 small">Processing Ticket...</p>
                    </div>
                </div>

                <div class="scanner-controls mt-4 text-center">
                    <div id="scanner-status" class="badge badge-pill badge-outline-light mb-3 p-2 px-3" style="color: rgba(255,255,255,0.5); border: 1px solid rgba(255,255,255,0.1);">
                        <i class="fa fa-video mr-2"></i> Initializing Camera...
                    </div>
                </div>
            </div>

            <!-- Manual Entry Option -->
            <div class="mt-5 text-center">
                <hr style="border-top: 1px dashed rgba(255,255,255,0.1);">
                <p class="text-white-50 mb-3 small">OR ENTER TICKET ID MANUALLY</p>
                <form action="{{ route('tickets.validate') }}" method="POST" id="manual-form">
                    @csrf
                    <div class="input-group input-group-lg bg-dark rounded-xl" style="padding: 5px; border: 1px solid rgba(255,255,255,0.1);">
                        <input type="text" name="unique_code" class="form-control bg-transparent text-white border-0 shadow-none" placeholder="T-XXXXXX-XXXX" required>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-danger px-4" style="border-radius: 10px; background: #dc143c;">VALIDATE</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    #reader {
        border: none !important;
    }
    #reader__scan_region {
        background: #000;
    }
    #reader video {
        border-radius: 15px !important;
        object-fit: cover !important;
    }
    #reader__dashboard {
        background: #111 !important;
        color: white !important;
        padding: 20px !important;
        border-radius: 0 0 15px 15px;
    }
    button[id^="html5-qrcode-button"] {
        background: #dc143c !important;
        color: white !important;
        border: none !important;
        padding: 10px 20px !important;
        border-radius: 8px !important;
        font-weight: bold !important;
        cursor: pointer !important;
        margin-top: 10px !important;
    }
    select[id^="html5-qrcode-select"] {
        padding: 8px !important;
        border-radius: 8px !important;
        background: #222 !important;
        color: white !important;
        border: 1px solid #333 !important;
    }
    .scanner-overlay {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.8);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 15px;
        z-index: 100;
    }
</style>

@section('ExtraJS2')
<script>
    function onScanSuccess(decodedText, decodedResult) {
        // Since decodedText is the direct URL (because we encoded it in the QR)
        // We can just redirect to it if it belongs to our domain
        const scanStatus = document.getElementById('scanner-status');
        const feedback = document.getElementById('scan-feedback');
        
        scanStatus.innerHTML = "<i class='fa fa-check-circle text-success mr-2'></i> QR Detected! Redirecting...";
        feedback.classList.remove('d-none');
        
        // Safety check: ensure it's a URL and points to our validation endpoint
        if (decodedText.includes('/scan-ticket/')) {
            window.location.href = decodedText;
        } else {
            // If it's just the ID, try to construct the URL or handle manually
            // But with our new system, it SHOULD be the URL
            alert("This QR code is not a valid TIXLY Ticket URL.");
            feedback.classList.add('d-none');
        }
    }

    function onScanFailure(error) {
        // Just keep scanning
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", 
        { 
            fps: 10, 
            qrbox: {width: 250, height: 250},
            experimentalFeatures: {
                useBarCodeDetectorIfSupported: true
            },
            rememberLastUsedCamera: true,
            aspectRatio: 1.0
        },
        /* verbose= */ false
    );

    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    
    // Manual form handler (AJAX optionally, but redirect is fine for this task)
    document.getElementById('manual-form').addEventListener('submit', function(e) {
        // Regular submit is fine
    });
</script>
@endsection

@endsection
