<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.5;
        }

        /* Letterhead / Kop Perusahaan */
        .header {
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header table {
            width: 100%;
        }

        .logo {
            width: 150px;
        }

        .company-info {
            text-align: right;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #1a1a2e;
            margin-bottom: 5px;
        }

        .company-details {
            font-size: 10px;
            color: #666;
        }

        /* Report Title */
        .report-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .report-title h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }

        .report-title p {
            margin: 5px 0 0;
            color: #666;
        }

        /* Table Styles */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.data-table th, table.data-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table.data-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
        }

        table.data-table tr:nth-child(even) {
            background-color: #fafafa;
        }

        /* Badge Styles (similar to Bootstrap) */
        .badge {
            padding: 3px 7px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            color: white;
            display: inline-block;
        }

        .badge-success { background-color: #28a745; }
        .badge-warning { background-color: #ffc107; color: #000; }
        .badge-danger { background-color: #dc3545; }
        .badge-info { background-color: #17a2b8; }
        .badge-primary { background-color: #007bff; }

        /* Footer */
        .footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }

        .page-number:before {
            content: "Halaman " counter(page);
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <table>
            <tr>
                <td width="50%">
                    {{-- Logo --}}
                    <div class="company-name">TIXLY</div>
                    <div class="company-details">TICKET MANAGEMENT SYSTEM</div>
                </td>
                <td width="50%" class="company-info">
                    <div class="company-details">
                        Jl. Gatot Subroto Kav. 22-23<br>
                        Jakarta Pusat, DKI Jakarta 12930<br>
                        Telp: (021) 1234 5678 | Email: admin@tixly.com<br>
                        Website: www.tixly.com
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="report-title">
        <h2>{{ $title }}</h2>
        <p>Dicetak pada: {{ now()->format('d M Y H:i:s') }} oleh {{ auth()->user()->name }}</p>
        @if(isset($period_label))
            <p>Periode: {{ $period_label }}</p>
        @endif
    </div>

    @yield('content')

    <div class="footer">
        <span class="page-number"></span> | Laporan ini dihasilkan secara otomatis oleh Tixly System.
    </div>
</body>
</html>
