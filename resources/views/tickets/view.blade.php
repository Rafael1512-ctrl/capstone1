@extends('layouts.landingpageconcert.landingconcert')

@section('contentlandingconcert')
<div class="container py-5" style="margin-top: 100px; min-height: 70vh;">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <h1 class="font-weight-bold mb-4" style="font-family: 'DM Serif Display', serif; color: #333;">Your Ticket Detail</h1>
            
            <div class="ticket-view-card p-5 rounded-lg bg-white mb-5" style="box-shadow: 0 20px 60px rgba(0,0,0,0.1); border: 2px solid #f0f0f0;">
                <div class="mb-4 d-flex justify-content-center">
                    <div class="qris-box p-3 bg-white" style="border: 2px solid #eee;">
                        <img src="{{ $qrCodeUrl }}" alt="Ticket QR Code" style="width: 250px; height: 250px;">
                    </div>
                </div>
                
                <h2 class="font-weight-bold mb-1" style="color: #333;">{{ $ticket->ticketType->event->title ?? 'Concert Event' }}</h2>
                <div class="d-flex justify-content-center align-items-center mb-4 text-muted">
                    <span class="mr-3"><i class="fa fa-calendar mr-1"></i> {{ $ticket->ticketType->event->date ?? 'TBA' }}</span>
                    <span><i class="fa fa-map-marker mr-1"></i> {{ $ticket->ticketType->event->location ?? 'Venue' }}</span>
                </div>
                
                <div class="bg-light p-4 rounded mb-5" style="border-radius: 20px !important;">
                    <div class="row">
                        <div class="col-6 text-left border-right">
                            <p class="small text-muted mb-0 font-weight-bold text-uppercase">Ticket Category</p>
                            <p class="mb-0 font-weight-bold" style="color: #9d50bb;">{{ $ticket->ticketType->name }}</p>
                        </div>
                        <div class="col-6 text-right">
                            <p class="small text-muted mb-0 font-weight-bold text-uppercase">Ticket ID</p>
                            <p class="mb-0 font-weight-bold" style="color: #333;">#{{ $ticket->ticket_id }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex flex-column align-items-center">
                    <a href="{{ route('tickets.download', $ticket->ticket_id) }}" class="btn btn-primary w-100 py-3 mb-3" style="border-radius: 50px; background: linear-gradient(to right, #9d50bb, #6e48aa); border: none;">Download Ticket Image</a>
                    <a href="{{ route('tickets.index') }}" class="text-muted font-weight-bold">Back to Ticket History</a>
                </div>
            </div>
            
            <p class="small text-muted">Please show this QR Code at the entrance of the venue during the concert day. Don't share this ticket with anyone else.</p>
        </div>
    </div>
</div>
@endsection
