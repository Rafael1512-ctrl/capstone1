<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>E-Ticket TIXLY</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .header { background-color: #dc143c; color: #ffffff; padding: 30px; text-align: center; }
        .content { padding: 30px; }
        .ticket-item { border: 2px dashed #eeeeee; border-radius: 12px; margin-bottom: 20px; padding: 20px; background-color: #fafafa; }
        .event-title { font-size: 24px; font-weight: bold; color: #333333; margin-bottom: 5px; }
        .event-info { color: #666666; font-size: 14px; margin-bottom: 20px; }
        .qr-section { text-align: center; margin: 20px 0; }
        .qr-code { width: 180px; height: 180px; }
        .footer { background-color: #333333; color: #ffffff; padding: 20px; text-align: center; font-size: 12px; }
        .btn { display: inline-block; padding: 12px 25px; background-color: #dc143c; color: #ffffff; text-decoration: none; border-radius: 50px; font-weight: bold; margin-top: 20px; }
        .ticket-id { font-family: monospace; color: #dc143c; font-weight: bold; font-size: 16px; display: block; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0;">Terima Kasih, {{ $user->name }}!</h1>
            <p style="margin: 5px 0 0 0;">Pembelian tiket Anda telah berhasil dikonfirmasi.</p>
        </div>
        <div class="content">
            <div style="margin-bottom: 30px; text-align: center;">
                @php
                    $event = $order->event ?? ($tickets->first() ? $tickets->first()->event : null);
                @endphp
                <h2 style="color: #333; margin-bottom: 10px;">Ringkasan Pesanan</h2>
                <p>Order ID: <strong>#{{ $order->transaction_id }}</strong></p>
                <p>Waktu Beli: <strong>{{ $order->payment_date ? $order->payment_date->format('d M Y, H:i') : '-' }} WIB</strong></p>
                <p>Event: <strong>{{ $event->title ?? 'Event' }}</strong></p>
            </div>

            @foreach($tickets as $ticket)
                <div class="ticket-item">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <div class="event-title">{{ $event->title ?? 'Event' }}</div>
                            <div class="event-info">
                                @if($event && $event->schedule_time)
                                    <div><i class="fa fa-calendar"></i> {{ $event->schedule_time->format('d M Y') }}</div>
                                    <div><i class="fa fa-clock-o"></i> {{ $event->schedule_time->format('H:i') }} WIB</div>
                                @endif
                                <div><i class="fa fa-map-marker"></i> {{ $event->location ?? 'Venue' }}</div>
                            </div>
                            <div style="margin-top: 10px;">
                                <span style="background: #eee; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; color: #555;">{{ $ticket->ticketType->name }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="qr-section">
                        @php
                            $scanUrl = route('tickets.scan.direct', $ticket->ticket_id);
                            // Use external API for maximum email compatibility (Gmail etc)
                            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($scanUrl);
                        @endphp
                        <img src="{{ $qrUrl }}" alt="Ticket QR Code" style="width: 200px; height: 200px; border: 1px solid #eee; padding: 10px; background: white;">
                        <span class="ticket-id">{{ $ticket->ticket_id }}</span>
                        <p style="font-size: 11px; color: #888; margin-top: 5px;">Tunjukkan QR Code ini kepada petugas pintu masuk.</p>
                    </div>
                </div>
            @endforeach

            <div style="text-align: center;">
                <p style="color: #666; font-size: 14px;">Anda juga dapat melihat tiket Anda kapan saja melalui aplikasi TIXLY.</p>
                <a href="{{ route('tickets.index') }}" class="btn" style="color: #ffffff;">Lihat di Aplikasi</a>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} TIXLY. All rights reserved.</p>
            <p>Hormat kami, Tim TIXLY</p>
        </div>
    </div>
</body>
</html>
