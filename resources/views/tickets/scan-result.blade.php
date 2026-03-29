@extends('layouts.landingpageconcert.landingconcert')

@section('title', 'Ticket Validation — ' . $ticket->ticket_id)

@section('contentlandingconcert')
<!-- Sound Effects for Demo -->
<audio id="sound-success" src="https://assets.mixkit.co/active_storage/sfx/2000/2000-preview.mp3" preload="auto"></audio>
<audio id="sound-error" src="https://assets.mixkit.co/active_storage/sfx/2180/2180-preview.mp3" preload="auto"></audio>

<div class="container py-5 d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="scan-result-card p-0 text-center rounded-xl shadow-3xl overflow-hidden" 
         style="background: #111; max-width: 550px; width: 100%; border: 1px solid {{ $success ? '#00ff7f33' : '#dc143c33' }};">
        
        <!-- Header Status -->
        <div class="py-5" style="background: {{ $success ? 'linear-gradient(to bottom, #00ff7f22, transparent)' : 'linear-gradient(to bottom, #dc143c22, transparent)' }};">
            <div class="mb-4">
                @if($success)
                    <div class="status-icon-glow mx-auto" style="width: 120px; height: 120px; border-radius: 50%; background: #00ff7f; color: #000; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 50px rgba(0, 255, 127, 0.4);">
                        <i class="fa fa-check fa-4x animate__animated animate__bounceIn"></i>
                    </div>
                @else
                    <div class="status-icon-glow mx-auto" style="width: 120px; height: 120px; border-radius: 50%; background: #dc143c; color: #fff; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 50px rgba(220, 20, 60, 0.4);">
                        <i class="fa fa-times fa-4x animate__animated animate__shakeX"></i>
                    </div>
                @endif
            </div>
            <h1 class="text-white font-weight-bold mb-1" style="letter-spacing: 2px;">{{ $success ? 'VALID ENTRY' : 'ACCESS DENIED' }}</h1>
            <p class="{{ $success ? 'text-success' : 'text-danger' }} font-weight-bold mb-0 text-uppercase small tracking-widest">
                {{ $success ? 'Ticket Verified' : 'Validation Error' }}
            </p>
        </div>

        <div class="px-4 pb-5">
            <!-- Message -->
            <div class="alert {{ $success ? 'alert-soft-success' : 'alert-soft-danger' }} mb-5 py-3 border-0" 
                 style="background: rgba(255,255,255,0.03); border-radius: 15px;">
                <p class="text-white mb-0 h5">{{ $message }}</p>
            </div>

            <!-- Details Card -->
            <div class="row text-left mb-5 g-3">
                <div class="col-12 py-3 border-bottom border-dark">
                    <small class="text-white-50 d-block text-uppercase mb-1 tracking-widest font-weight-bold" style="font-size: 10px;">Event</small>
                    <span class="text-white h5 mb-0 font-weight-bold">{{ $ticket->ticketType->event->title ?? 'N/A' }}</span>
                </div>
                <div class="col-6 py-3 border-right border-dark">
                    <small class="text-white-50 d-block text-uppercase mb-1 tracking-widest font-weight-bold" style="font-size: 10px;">Guest Name</small>
                    <span class="text-white h6 mb-0">{{ $ticket->order->user->name ?? 'N/A' }}</span>
                </div>
                <div class="col-6 py-3">
                    <small class="text-white-50 d-block text-uppercase mb-1 tracking-widest font-weight-bold" style="font-size: 10px;">Ticket Tier</small>
                    <span class="text-white h6 mb-0"><span class="badge badge-danger">{{ $ticket->ticketType->name }}</span></span>
                </div>
                <div class="col-12 py-3">
                    <small class="text-white-50 d-block text-uppercase mb-1 tracking-widest font-weight-bold" style="font-size: 10px;">Unique Ticket ID</small>
                    <code class="text-danger">#{{ $ticket->ticket_id }}</code>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="row gutter-sm">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <a href="{{ route('tickets.scanner') }}" class="btn btn-block py-3 font-weight-bold btn-glow" 
                       style="background: #dc143c; color: #fff; border-radius: 14px; border: none;">
                       <i class="fa fa-qrcode mr-2"></i> SCAN NEXT
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('organizer.dashboard') }}" class="btn btn-outline-light btn-block py-3 font-weight-bold" 
                       style="border-radius: 14px; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.05);">
                       DASHBOARD
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @import url('https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css');
    
    body {
        background: radial-gradient(circle at center, #1a0000 0%, #000000 100%) !important;
    }
    .scan-result-card {
        animation: slideUp 0.6s cubic-bezier(0.23, 1, 0.32, 1);
    }
    .status-icon-glow {
        animation: pulse 2s infinite;
    }
    .btn-glow:hover {
        box-shadow: 0 0 20px rgba(220, 20, 60, 0.4);
        transform: translateY(-2px);
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
</style>

@section('ExtraJS2')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const success = {{ $success ? 'true' : 'false' }};
        const sound = document.getElementById(success ? 'sound-success' : 'sound-error');
        if (sound) {
            sound.play().catch(e => console.log("Sound play failed:", e));
        }
    });
</script>
@endsection
@endsection
