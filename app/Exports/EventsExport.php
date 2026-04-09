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

class EventsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithColumnFormatting, WithCustomStartCell, WithTitle
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
        return 'Events Report';
    }

    public function startCell(): string
    {
        return 'A3';
    }

    public function headings(): array
    {
        return [
            'ID',
            'Judul Event',
            'Organizer',
            'Kategori',
            'Tanggal',
            'Lokasi',
            'Status',
            'Tiket Terjual',
            'Total Tiket',
            'Revenue'
        ];
    }

    public function map($event): array
    {
        $totalTickets = $event->ticketTypes->sum('quantity_total');
        $soldTickets = $event->ticketTypes->sum('quantity_sold');
        
        $revenue = $event->tickets->sum(function($ticket) {
            return $ticket->order && $ticket->order->payment_status === 'Verified' ? $ticket->price : 0;
        });

        return [
            $event->event_id,
            $event->title,
            $event->organizer->name ?? '-',
            $event->category?->name ?? '-',
            $event->schedule_time ? $event->schedule_time->format('Y-m-d') : '-',
            $event->location,
            ucfirst($event->status),
            $soldTickets,
            $totalTickets,
            $revenue
        ];
    }

    public function columnFormats(): array
    {
        return [
            'J' => '"Rp "#,##0.00',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Add Title at the Top
        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', 'TIXLY - LAPORAN DATA EVENT');
        $sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Tanggal Cetak: ' . date('d M Y H:i:s'));
        $sheet->getStyle('A2')->getFont()->setItalic(true);

        // Header Styling (Row 3)
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'DC143C'], // Tixly Red
            ],
        ];
        $sheet->getStyle('A3:J3')->applyFromArray($headerStyle);

        // Data Borders and Alignment
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A3:J' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('A4:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G4:I' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        return [
            // Extra row heights
            3 => ['font' => ['size' => 12]],
        ];
    }
}
