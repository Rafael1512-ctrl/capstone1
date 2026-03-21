@extends('layouts.landingpageconcert.landingconcert')

@section('contentlandingconcert')
<div class="container py-5" style="margin-top: 100px; min-height: 70vh;">
    <div class="row mb-5">
        <div class="col-md-8">
            <h1 class="font-weight-bold" style="font-family: 'DM Serif Display', serif; color: #333;">My Ticket History</h1>
            <p class="text-muted">Review and download your concert tickets here.</p>
        </div>
    </div>

    @if($tickets->isEmpty())
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fa fa-ticket text-muted" style="font-size: 80px; opacity: 0.2;"></i>
            </div>
            <h3 class="text-muted">No tickets found.</h3>
            <p>You haven't purchased any tickets yet. Go explore our <a href="{{ route('home') }}" style="color: #9d50bb;">latest concerts!</a></p>
        </div>
    @else
        <div class="row">
            @foreach($tickets as $ticket)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="ticket-history-card p-4 rounded-lg bg-white" style="box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #eee; transition: all 0.3s ease;">
                        <div class="d-flex justify-content-between mb-3 align-items-center">
                            <span class="badge {{ $ticket->isActive() ? 'badge-success' : 'badge-secondary' }} px-3 py-1" style="border-radius: 20px;">
                                {{ $ticket->ticket_status }}
                            </span>
                            <small class="text-muted">{{ $ticket->created_at ? $ticket->created_at->format('d M Y') : 'N/A' }}</small>
                        </div>
                        
                        <h4 class="font-weight-bold mb-1" style="color: #333;">{{ $ticket->ticketType->event->title ?? 'Concert Event' }}</h4>
                        <p class="small text-muted mb-3"><i class="fa fa-map-marker mr-1"></i> {{ $ticket->ticketType->event->location ?? 'Venue Location' }}</p>
                        
                        <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0 small text-uppercase font-weight-bold text-muted">Ticket Category</p>
                                <p class="mb-0 font-weight-bold" style="color: #9d50bb;">{{ $ticket->ticketType->name }}</p>
                            </div>
                            <a href="{{ route('tickets.view', $ticket->ticket_id) }}" class="btn btn-outline-primary btn-sm" style="border-radius: 20px; border-color: #9d50bb; color: #9d50bb;">View Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="d-flex justify-content-center mt-5">
            {{ $tickets->links() }}
        </div>
    @endif
</div>

<style>
    .ticket-history-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 45px rgba(0,0,0,0.1) !important;
        border-color: #9d50bb !important;
    }
</style>
@endsection
