<?php

namespace App\Services;

use App\Models\Event;
use Carbon\Carbon;

class GoogleCalendarService
{
    /**
     * Generate a Google Calendar "Add Event" URL with pre-filled data.
     *
     * @param Event $event
     * @param string|null $ticketTypeName  Optional ticket type name to include in description
     * @param int $durationHours  Default event duration in hours
     * @return string
     */
    public static function generateCalendarLink(Event $event, ?string $ticketTypeName = null, int $durationHours = 3): string
    {
        $title = '🎵 ' . $event->title . ' — TIXLY Concert';

        // Format dates for Google Calendar (YYYYMMDDTHHmmssZ format, UTC)
        $startTime = $event->schedule_time;
        if (!$startTime) {
            $startTime = Carbon::now()->addMonth(); // fallback
        }

        $endTime = (clone $startTime)->addHours($durationHours);

        // Convert to UTC for Google Calendar
        $startFormatted = $startTime->copy()->utc()->format('Ymd\THis\Z');
        $endFormatted = $endTime->copy()->utc()->format('Ymd\THis\Z');

        $location = $event->location ?? '';

        // Build description
        $description = "🎶 Konser: {$event->title}\n";
        $description .= "📍 Lokasi: {$location}\n";
        $description .= "📅 Tanggal: " . $startTime->format('d M Y, H:i') . " WIB\n";
        
        if ($ticketTypeName) {
            $description .= "🎫 Kategori Tiket: {$ticketTypeName}\n";
        }

        $description .= "\n🎟️ Tiket dibeli melalui TIXLY";
        $description .= "\n⚠️ Pastikan membawa e-ticket/QR Code saat memasuki venue.";

        // Build Google Calendar URL
        $params = [
            'action'   => 'TEMPLATE',
            'text'     => $title,
            'dates'    => $startFormatted . '/' . $endFormatted,
            'details'  => $description,
            'location' => $location,
            'sf'       => 'true',
            'output'   => 'xml',
        ];

        return 'https://calendar.google.com/calendar/render?' . http_build_query($params);
    }
}
