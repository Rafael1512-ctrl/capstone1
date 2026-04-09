<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class EventPerformanceExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithColumnFormatting, WithCustomStartCell, WithTitle
{
    protected $events;

    public function __construct($events)
    {
        $this->events = $events;
    }

    public function collection()
    {
        return $this->events;
    }

    public function title(): string
    {
        return 'Event Performance Report';
    }

    public function startCell(): string
    {
        return 'A3';
    }

    public function headings(): array
    {
        return [
            'ID Event',
            'Judul Event',
            'Kategori',
            'Status',
            'Kapasitas',
            'Tiket Terjual',
            'Total Order',
            'Fill Rate (%)',
            'Total Revenue'
        ];
    }

    public function map($event): array
    {
        $totalTicketsAvailable = $event->ticket_quota ?? 0;
        
        // Filter tickets that have a verified order (Sesuai Logika PDF)
        $verifiedTickets = $event->tickets->filter(function($ticket) {
            return $ticket->order && $ticket->order->payment_status === 'Verified';
        });
        
        $totalTicketsSold = $verifiedTickets->count();
        $soldPercentage = $totalTicketsAvailable > 0 ? ($totalTicketsSold / $totalTicketsAvailable) : 0;

        // Revenue & Order Count from Unique Verified Orders
        $uniqueOrders = $verifiedTickets->pluck('order')->unique('transaction_id');
        $revenue = $uniqueOrders->sum('total_amount');
        $ordersCount = $uniqueOrders->count();

        return [
            $event->event_id,
            $event->title,
            $event->category?->name ?? '-',
            ucfirst($event->status),
            $totalTicketsAvailable,
            $totalTicketsSold,
            $ordersCount,
            $soldPercentage,
            $revenue
        ];
    }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_PERCENTAGE_00,
            'I' => '"Rp "#,##0.00',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        // Title
        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A1', 'TIXLY - LAPORAN PERFORMA EVENT');
        $sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Dibuat pada: ' . date('d M Y H:i:s'));
        $sheet->getStyle('A2')->getFont()->setItalic(true);

        // Header Style
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'DC143C'],
            ],
        ];
        $sheet->getStyle('A3:I3')->applyFromArray($headerStyle);

        // Borders
        $sheet->getStyle('A3:I' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        // Alignment
        $sheet->getStyle('A4:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E4:H' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [
            3 => ['font' => ['size' => 12]],
        ];
    }
}
