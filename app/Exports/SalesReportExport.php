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

class SalesReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithColumnFormatting, WithCustomStartCell, WithTitle
{
    protected $orders;
    protected $totalRevenue;

    public function __construct($orders, $totalRevenue)
    {
        $this->orders = $orders;
        $this->totalRevenue = $totalRevenue;
    }

    public function collection()
    {
        return collect($this->orders);
    }

    public function title(): string
    {
        return 'Sales Report';
    }

    public function startCell(): string
    {
        return 'A3';
    }

    public function headings(): array
    {
        return [
            'Tanggal Transaksi',
            'Nama Event',
            'Nama User',
            'Order ID',
            'Amount (Pendapatan)',
            'Status Pembayaran'
        ];
    }

    public function map($order): array
    {
        return [
            $order->payment_date ? $order->payment_date->format('Y-m-d H:i:s') : '-',
            $order->event->title ?? '-',
            $order->user->name ?? '-',
            $order->transaction_id,
            $order->total_amount,
            strtoupper($order->payment_status)
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => '"Rp "#,##0.00',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        // Title
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'TIXLY - LAPORAN PENJUALAN TIKET');
        $sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Rentang Laporan: ' . date('d M Y'));
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
        $sheet->getStyle('A3:F3')->applyFromArray($headerStyle);

        // Add TOTAL Row
        $totalRow = $lastRow + 1;
        $sheet->mergeCells("A$totalRow:D$totalRow");
        $sheet->setCellValue("A$totalRow", 'TOTAL PENDAPATAN (REVENUE)');
        $sheet->setCellValue("E$totalRow", $this->totalRevenue);
        
        $sheet->getStyle("A$totalRow:F$totalRow")->getFont()->setBold(true);
        $sheet->getStyle("A$totalRow:D$totalRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("E$totalRow")->getNumberFormat()->setFormatCode('"Rp "#,##0.00');

        // Borders
        $sheet->getStyle('A3:F' . $totalRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        // Zebra striping style
        for ($i = 4; $i <= $lastRow; $i++) {
            if ($i % 2 == 0) {
                $sheet->getStyle('A' . $i . ':F' . $i)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F9F9F9');
            }
        }

        return [
            3 => ['font' => ['size' => 12]],
        ];
    }
}
