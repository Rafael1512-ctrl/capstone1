<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>TIXLY Ticket</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #fff; font-family: Arial, Helvetica, sans-serif; color: #000; }
        .ticket-page { page-break-after: always; padding: 50px 25px; }
        .ticket-page:last-child { page-break-after: auto; }
    </style>
</head>
<body>

@foreach($ticketData as $data)
@php
    $t            = $data['ticket'];
    $event        = $data['event'];
    $transaction  = $data['transaction'];
    $qrHtml       = $data['qrHtml'] ?? null;
    $imgBase64    = $data['imgBase64'];
    $scheduleText = $data['scheduleText'];
@endphp

<div class="ticket-page">

    <table style="width:100%; border-collapse:collapse; border:1.5px solid #555;">
        <tr>

            {{-- ===== LEFT: DARK IMAGE PANEL ===== --}}
            <td style="width:37%; padding:0; background:#0a0a0a; vertical-align:top; border-right:2px dashed #bbb;">
                <div style="position:relative; height:255px; overflow:hidden; background:#111; width:100%;">
                    @if($imgBase64)
                        <img src="{{ $imgBase64 }}" style="width:100%; height:255px; display:block;">
                    @endif
                    <div style="position:absolute; bottom:0; left:0; right:0; height:140px; background:linear-gradient(to top, rgba(0,0,0,0.97) 0%, rgba(0,0,0,0.5) 55%, transparent 100%);"></div>
                    <div style="position:absolute; bottom:12px; left:14px; right:14px;">
                        <div style="font-size:6pt; font-weight:bold; letter-spacing:2pt; color:#dc143c; text-transform:uppercase; margin-bottom:5px;">OFFICIAL ENTRY</div>
                        <div style="font-size:17pt; font-weight:bold; color:#ffffff; font-family:Georgia,serif; line-height:1.15; word-wrap:break-word;">{{ $event->title }}</div>
                    </div>
                </div>
            </td>

            {{-- ===== RIGHT: DETAILS PANEL ===== --}}
            <td style="width:63%; padding:14px 16px 12px 16px; background:#ffffff; vertical-align:top;">

                {{-- Inner vertical-stack table --}}
                <table style="width:100%; border-collapse:collapse;">

                    {{-- ROW 1: Tier badge + Branding --}}
                    <tr>
                        <td style="width:45%; vertical-align:middle; padding-bottom:10px;">
                            <span style="background:#000; color:#fff; padding:4px 12px; font-size:8pt; font-weight:bold; letter-spacing:1.5pt; display:inline-block;">{{ strtoupper($t->ticketType->name) }}</span>
                        </td>
                        <td style="width:55%; text-align:right; vertical-align:middle; padding-bottom:10px;">
                            <span style="display:block; font-size:9.5pt; font-weight:bold; color:#dc143c;">TIXLY CONCERTS</span>
                            <span style="display:block; font-size:7pt; font-weight:bold; color:#dc143c; font-family:Courier,monospace;">#{{ $t->ticket_id }}</span>
                        </td>
                    </tr>

                    {{-- ROW 2: QR Code as HTML table (DomPDF-native, no Imagick needed) --}}
                    <tr>
                        <td colspan="2" style="text-align:center; padding-bottom:5px;">
                            @if($qrHtml)
                                {!! $qrHtml !!}
                            @endif
                        </td>
                    </tr>

                    {{-- ROW 3: SCAN AT THE GATE --}}
                    <tr>
                        <td colspan="2" style="text-align:center; font-size:6pt; color:#777; font-weight:bold; letter-spacing:0.8pt; padding-bottom:12px;">
                            SCAN AT THE GATE
                        </td>
                    </tr>

                    {{-- ROW 4: DATE/TIME + VENUE --}}
                    <tr>
                        <td style="width:50%; vertical-align:top; padding:0 10px 8px 0;">
                            <span style="display:block; font-size:5.5pt; font-weight:bold; color:#888; text-transform:uppercase; letter-spacing:0.5pt; margin-bottom:3px;">DATE / TIME</span>
                            <span style="display:block; font-size:9pt; font-weight:bold; color:#000; line-height:1.3;">{{ $scheduleText }}</span>
                        </td>
                        <td style="width:50%; vertical-align:top; padding:0 0 8px 0;">
                            <span style="display:block; font-size:5.5pt; font-weight:bold; color:#888; text-transform:uppercase; letter-spacing:0.5pt; margin-bottom:3px;">VENUE</span>
                            <span style="display:block; font-size:9pt; font-weight:bold; color:#000; line-height:1.3;">{{ $event->location }}</span>
                        </td>
                    </tr>

                    {{-- ROW 5: ATTENDEE + TICKET SERIAL ID --}}
                    <tr>
                        <td style="width:50%; vertical-align:top; padding:0 10px 0 0;">
                            <span style="display:block; font-size:5.5pt; font-weight:bold; color:#888; text-transform:uppercase; letter-spacing:0.5pt; margin-bottom:3px;">ATTENDEE</span>
                            <span style="display:block; font-size:9pt; font-weight:bold; color:#000; line-height:1.3;">{{ $transaction->user->name }}</span>
                        </td>
                        <td style="width:50%; vertical-align:top; padding:0;">
                            <span style="display:block; font-size:5.5pt; font-weight:bold; color:#888; text-transform:uppercase; letter-spacing:0.5pt; margin-bottom:3px;">TICKET SERIAL ID</span>
                            <span style="display:block; font-size:8.5pt; font-weight:bold; color:#000; font-family:Courier,monospace; line-height:1.3;">#{{ $t->ticket_id }}</span>
                        </td>
                    </tr>



                </table>

            </td>
        </tr>
    </table>

</div>
@endforeach

</body>
</html>
