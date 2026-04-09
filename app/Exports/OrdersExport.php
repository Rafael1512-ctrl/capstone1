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

class OrdersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithColumnFormatting, WithCustomStartCell, WithTitle
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        return $this->orders;
    }

    public function title(): string
    {
        return 'Orders Report';
    }

    public function startCell(): string
    {
        return 'A3';
    }

    public function headings(): array
    {
        return [
            'ID Transaksi',
            'Customer',
            'Event',
            'Amount',
            'Method',
            'Status',
            'Created At'
        ];
    }

    public function map($order): array
    {
        return [
            $order->transaction_id,
            $order->user->name ?? '-',
            $order->event->title ?? '-',
            $order->total_amount,
            strtoupper($order->payment_method ?? '-'),
            strtoupper($order->payment_status),
            $order->payment_date ? $order->payment_date->format('Y-m-d H:i:s') : '-'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => '"Rp "#,##0.00',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Add Title
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'TIXLY - RIWAYAT TRANSAKSI CUSTOMER');
        $sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Dicetak pada: ' . date('d M Y H:i:s'));
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
        $sheet->getStyle('A3:G3')->applyFromArray($headerStyle);

        // Borders
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A3:G' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        // Perataan data Status
        $sheet->getStyle('F4:F' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [
            3 => ['font' => ['size' => 12]],
        ];
    }
}
