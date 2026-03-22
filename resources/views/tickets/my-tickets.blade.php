@extends('layouts.landingpageconcert.landingconcert')

@section('title', 'My Tickets — TIXLY')

@section('contentlandingconcert')
<div class="container py-5" style="margin-top: 40px; min-height: 70vh;">

    {{-- Page Header --}}
    <div class="row mb-5">
        <div class="col-md-8">
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 12px;">
                <div style="width: 4px; height: 36px; background: linear-gradient(#dc143c, #8b0000); border-radius: 4px;"></div>
                <h1 style="font-family: Georgia, serif; color: #fff; font-size: 2rem; font-weight: 800; margin: 0;">
                    My Ticket History
                </h1>
            </div>
            <p style="color: rgba(255,255,255,0.45); font-size: 0.95rem; margin-left: 18px;">
                Review and download your concert tickets here.
            </p>
        </div>
        <div class="col-md-4 text-md-right d-flex align-items-center justify-content-md-end">
            <a href="{{ route('home') }}"
                style="display: inline-flex; align-items: center; gap: 8px;
                       padding: 9px 22px; border-radius: 25px;
                       background: linear-gradient(135deg, #dc143c, #8b0000);
                       color: #fff; font-size: 0.88rem; font-weight: 600;
                       text-decoration: none; box-shadow: 0 4px 15px rgba(220,20,60,0.3);">
                <i class="fa fa-search"></i> Explore Events
            </a>
        </div>
    </div>

    @if($tickets->isEmpty())
        <div class="text-center py-5 mt-4">
            <div style="width: 100px; height: 100px; border-radius: 50%;
                        background: rgba(220,20,60,0.08); border: 1.5px solid rgba(220,20,60,0.2);
                        display: flex; align-items: center; justify-content: center;
                        margin: 0 auto 28px;">
                <i class="fa fa-ticket" style="font-size: 2.5rem; color: rgba(220,20,60,0.4);"></i>
            </div>
            <h3 style="color: rgba(255,255,255,0.6); font-size: 1.3rem; font-weight: 600; margin-bottom: 12px;">
                No Tickets Found
            </h3>
            <p style="color: rgba(255,255,255,0.35); font-size: 0.95rem;">
                You haven't purchased any tickets yet. Go explore our
                <a href="{{ route('home') }}" style="color: #dc143c; text-decoration: none; font-weight: 600;">latest concerts!</a>
            </p>
        </div>
    @else
        <div class="row">
            @foreach($tickets as $ticket)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="ticket-card"
                        style="background: #161616; border: 1px solid rgba(255,255,255,0.07);
                               border-radius: 16px; padding: 24px; transition: all 0.3s ease;
                               box-shadow: 0 4px 20px rgba(0,0,0,0.3);">

                        {{-- Status Badge --}}
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                            <span style="display: inline-flex; align-items: center; gap: 6px;
                                         padding: 5px 14px; border-radius: 20px; font-size: 0.78rem; font-weight: 600;
                                         {{ $ticket->isActive()
                                             ? 'background: rgba(34,197,94,0.12); color: #4ade80; border: 1px solid rgba(34,197,94,0.3);'
                                             : 'background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.4); border: 1px solid rgba(255,255,255,0.1);'
                                         }}">
                                <span style="width: 6px; height: 6px; border-radius: 50; display: inline-block;
                                             background: {{ $ticket->isActive() ? '#4ade80' : 'rgba(255,255,255,0.3)' }};"></span>
                                {{ $ticket->ticket_status }}
                            </span>
                            <small style="color: rgba(255,255,255,0.3); font-size: 0.78rem;">
                                {{ $ticket->created_at ? $ticket->created_at->format('d M Y') : 'N/A' }}
                            </small>
                        </div>

                        {{-- Event Title --}}
                        <h4 style="color: #fff; font-weight: 700; font-size: 1.05rem; margin-bottom: 8px; line-height: 1.4;">
                            {{ $ticket->ticketType->event->title ?? 'Concert Event' }}
                        </h4>

                        {{-- Location --}}
                        <p style="color: rgba(255,255,255,0.4); font-size: 0.85rem; margin-bottom: 20px; display: flex; align-items: center; gap: 6px;">
                            <i class="fa fa-map-marker" style="color: #dc143c;"></i>
                            {{ $ticket->ticketType->event->location ?? 'Venue Location' }}
                        </p>

                        {{-- Divider --}}
                        <div style="border-top: 1px solid rgba(255,255,255,0.07); padding-top: 16px;
                                    display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <p style="margin: 0; font-size: 0.72rem; text-transform: uppercase;
                                          letter-spacing: 0.8px; color: rgba(255,255,255,0.3); font-weight: 600;">
                                    Ticket Category
                                </p>
                                <p style="margin: 0; font-weight: 700; color: #ff6b6b; font-size: 0.92rem;">
                                    {{ $ticket->ticketType->name ?? '-' }}
                                </p>
                            </div>
                            <a href="{{ route('tickets.view', $ticket->ticket_id) }}"
                                style="display: inline-flex; align-items: center; gap: 6px;
                                       padding: 7px 16px; border-radius: 20px;
                                       border: 1.5px solid rgba(220,20,60,0.5);
                                       color: #ff6b6b; font-size: 0.82rem; font-weight: 600;
                                       text-decoration: none; transition: all 0.2s;"
                                onmouseover="this.style.background='rgba(220,20,60,0.12)'; this.style.borderColor='#dc143c'; this.style.color='#fff';"
                                onmouseout="this.style.background='transparent'; this.style.borderColor='rgba(220,20,60,0.5)'; this.style.color='#ff6b6b';">
                                <i class="fa fa-eye"></i> View
                            </a>
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
    .ticket-card:hover {
        transform: translateY(-4px);
        border-color: rgba(220,20,60,0.3) !important;
        box-shadow: 0 12px 35px rgba(0,0,0,0.5) !important;
    }
</style>
@endsection
