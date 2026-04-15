@extends('layouts.master')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-5 mt-2">
        <div>
            <h1 class="page-title mb-0">Export Center</h1>
            <p class="text-muted small mb-0">Download comprehensive reports in PDF or Excel format</p>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <!-- Global Reports -->
        <div class="col-md-4">
            <div class="card h-100 overflow-hidden border-0 shadow-sm" style="background: linear-gradient(135deg, #1a0a0f 0%, #110710 100%) !important;">
                <div class="card-body p-4 text-center">
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center" style="width:60px; height:60px; background: rgba(220, 20, 60, 0.1); border-radius:15px; border:1px solid rgba(220, 20, 60, 0.2);">
                            <i class="fas fa-calendar-alt text-danger fs-3"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold text-white mb-2">Event List</h5>
                    <p class="text-muted small mb-4">Export all created events and their status</p>
                    <div class="d-grid">
                        <a href="{{ route('admin.export.events') }}" class="btn btn-danger btn-sm fw-bold">
                            <i class="fas fa-file-excel me-2"></i>Download Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 overflow-hidden border-0 shadow-sm" style="background: linear-gradient(135deg, #1a0a0f 0%, #110710 100%) !important;">
                <div class="card-body p-4 text-center">
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center" style="width:60px; height:60px; background: rgba(59, 130, 246, 0.1); border-radius:15px; border:1px solid rgba(59, 130, 246, 0.2);">
                            <i class="fas fa-shopping-cart text-info fs-3"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold text-white mb-2">Order History</h5>
                    <p class="text-muted small mb-4">Export complete transaction history</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.export.orders') }}" class="btn btn-info btn-sm fw-bold text-white">
                            <i class="fas fa-file-excel me-2"></i>Download Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 overflow-hidden border-0 shadow-sm" style="background: linear-gradient(135deg, #1a0a0f 0%, #110710 100%) !important;">
                <div class="card-body p-4 text-center">
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center" style="width:60px; height:60px; background: rgba(34, 197, 94, 0.1); border-radius:15px; border:1px solid rgba(34, 197, 94, 0.2);">
                            <i class="fas fa-chart-line text-success fs-3"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold text-white mb-2">Sales Analytics</h5>
                    <p class="text-muted small mb-4">Laporan pendapatan rutin dan performa event lengkap</p>
                    <div class="d-grid gap-2 mb-2">
                        <a href="{{ route('admin.export.comprehensive-sales-pdf') }}" class="btn btn-primary btn-sm fw-bold">
                            <i class="fas fa-file-pdf me-2"></i>Export Comprehensive PDF
                        </a>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.export.sales') }}" class="btn btn-success btn-sm fw-bold">
                            <i class="fas fa-file-excel me-2"></i>Export Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Per Event Section -->
    <div class="card border-0 shadow-sm" style="background: rgba(255,255,255,0.03) !important;">
        <div class="card-header border-0 bg-transparent py-4 px-4">
            <div class="d-flex align-items-center gap-3">
                <div style="width:4px; height:24px; background: var(--tix-red); border-radius:2px;"></div>
                <h4 class="mb-0 fw-bold text-white" style="font-family: 'Inter', sans-serif;">Export per Event</h4>
            </div>
            <p class="text-muted small mt-1 mb-0 ps-4">Select an event to generate its dedicated sales report</p>
        </div>
        <div class="card-body p-4 pt-0">
            <div class="table-responsive">
                <table id="eventExportTable" class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4">Event Name</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th class="text-center">Export Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-white">{{ $event->title }}</div>
                                <div class="small text-muted">ID: #{{ $event->event_id }}</div>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $event->category->name ?? 'Uncategorized' }}</span>
                            </td>
                            <td>
                                <div class="small fw-semibold text-white">{{ \Carbon\Carbon::parse($event->schedule_time)->format('d M Y') }}</div>
                            </td>
                            <td class="text-center ps-4">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.export.event.excel', $event->event_id) }}" 
                                       class="btn btn-sm btn-outline-info d-flex align-items-center gap-2 px-3 py-2" 
                                       style="border-color: rgba(59, 130, 246, 0.4) !important; color: #60a5fa !important;">
                                        <i class="fas fa-file-excel"></i>
                                        <span>Excel</span>
                                    </a>
                                    <a href="{{ route('admin.export.event.pdf', $event->event_id) }}" 
                                       class="btn btn-sm btn-outline-danger d-flex align-items-center gap-2 px-3 py-2"
                                       style="border-color: rgba(220, 20, 60, 0.4) !important; color: #ff6080 !important;">
                                        <i class="fas fa-file-pdf"></i>
                                        <span>PDF</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@section('ExtraJS')
<script>
    $(document).ready(function() {
        $('#eventExportTable').DataTable({
            "pageLength": 10,
            "dom": '<"d-flex justify-content-between align-items-center mb-3"f>t<"d-flex justify-content-between align-items-center mt-3"ip>',
            "language": {
                "search": "",
                "searchPlaceholder": "Cari event...",
                "paginate": {
                    "previous": "<i class='fas fa-chevron-left'></i>",
                    "next": "<i class='fas fa-chevron-right'></i>"
                }
            }
        });
    });
</script>
@endsection

<style>
    .dataTables_filter input {
        width: 300px !important;
        margin-left: 0 !important;
    }
    .table thead th {
        border-top: none !important;
    }
    .btn-outline-info:hover, .btn-outline-danger:hover {
        color: #fff !important;
    }
</style>
@endsection
