<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('page_title', 'Admin') — TIXLY</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=DM+Serif+Display&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/css/fonts.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />

    <!-- ══════════════════════════════════════════
         TIXLY ADMIN — GLOBAL DARK CONCERT THEME
         ══════════════════════════════════════════ -->
    <style>
        :root {
            --tix-red: #dc143c;
            --tix-dark-red: #8b0000;
            --tix-bg: #0d0d0d;
            --tix-bg2: #111111;
            --tix-bg3: #1a0a0f;
            --tix-card: #141414;
            --tix-border: rgba(220, 20, 60, 0.15);
            --tix-border2: rgba(255, 255, 255, 0.07);
            --tix-text: #ffffff;
            --tix-text2: rgba(255, 255, 255, 0.55);
            --tix-text3: rgba(255, 255, 255, 0.3);
            --tix-sidebar: 270px;
        }

        /* ── Hide ALL sidebar scrollbars ── */
        .sidebar,
        .sidebar-wrapper,
        .sidebar-content,
        .scrollbar-outer,
        .scrollbar-inner,
        .scroll-content,
        .scroll-element {
            scrollbar-width: none !important;
            -ms-overflow-style: none !important;
        }

        .sidebar::-webkit-scrollbar,
        .sidebar-wrapper::-webkit-scrollbar,
        .sidebar-content::-webkit-scrollbar,
        .scrollbar-outer::-webkit-scrollbar,
        .scrollbar-inner::-webkit-scrollbar,
        .scroll-content::-webkit-scrollbar,
        .scroll-element {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }

        /* Hide the Kaiadmin jQuery Scrollbar plugin elements */
        .scroll-element.scroll-y {
            display: none !important;
        }

        .scroll-element.scroll-x {
            display: none !important;
        }


        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--tix-bg) !important;
            color: var(--tix-text) !important;
            overflow: hidden;
        }

        /* ── Layout ── */
        .wrapper {
            display: flex;
            width: 100%;
            height: 100vh;
        }

        .sidebar {
            width: var(--tix-sidebar);
            height: 100vh;
            flex-shrink: 0;
            overflow-y: auto;
            position: relative;
            z-index: 100;
        }

        .main-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
            width: calc(100% - var(--tix-sidebar));
        }

        .main-header {
            position: sticky;
            top: 0;
            z-index: 1000;
            height: 64px;
            flex-shrink: 0;
        }

        .main-content-area {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 28px 28px;
            background: var(--tix-bg);
        }

        /* ── Scrollbar ── */
        .main-content-area::-webkit-scrollbar {
            width: 6px;
        }

        .main-content-area::-webkit-scrollbar-track {
            background: transparent;
        }

        .main-content-area::-webkit-scrollbar-thumb {
            background: rgba(220, 20, 60, 0.2);
            border-radius: 4px;
        }

        .main-content-area::-webkit-scrollbar-thumb:hover {
            background: rgba(220, 20, 60, 0.4);
        }

        /* ══════════════
           CARDS
           ══════════════ */
        .card {
            background: var(--tix-card) !important;
            border: 1px solid var(--tix-border2) !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.3) !important;
            color: var(--tix-text) !important;
        }

        .card-header {
            background: rgba(255, 255, 255, 0.03) !important;
            border-bottom: 1px solid var(--tix-border2) !important;
            color: var(--tix-text) !important;
            font-weight: 700;
            padding: 16px 20px !important;
        }

        .card-body {
            padding: 20px !important;
            color: var(--tix-text) !important;
        }

        .card-title {
            color: var(--tix-text) !important;
            font-weight: 700;
        }

        .card-text {
            color: var(--tix-text2) !important;
        }

        /* ══════════════
           TABLES
           ══════════════ */
        .table,
        table {
            color: var(--tix-text2) !important;
            border-color: var(--tix-border2) !important;
        }

        .table thead th,
        table thead th {
            background: rgba(220, 20, 60, 0.06) !important;
            color: var(--tix-text3) !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            letter-spacing: 1.2px !important;
            text-transform: uppercase !important;
            border-bottom: 1px solid var(--tix-border) !important;
            padding: 12px 16px !important;
        }

        .table tbody tr,
        table tbody tr {
            border-bottom: 1px solid rgba(255, 255, 255, 0.04) !important;
            transition: background 0.15s;
        }

        .table tbody tr:hover,
        table tbody tr:hover {
            background: rgba(220, 20, 60, 0.04) !important;
        }

        .table tbody td,
        table tbody td {
            border-color: rgba(255, 255, 255, 0.04) !important;
            vertical-align: middle !important;
            padding: 12px 16px !important;
            color: var(--tix-text2) !important;
        }

        .table-striped>tbody>tr:nth-of-type(odd)>* {
            background: rgba(255, 255, 255, 0.02) !important;
        }

        .table-bordered {
            border: 1px solid var(--tix-border2) !important;
        }

        /* ══════════════
           FORMS
           ══════════════ */
        .form-control,
        .form-select {
            background: rgba(255, 255, 255, 0.04) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: var(--tix-text) !important;
            border-radius: 10px !important;
            padding: 10px 14px !important;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--tix-red) !important;
            box-shadow: 0 0 0 3px rgba(220, 20, 60, 0.12) !important;
            background: rgba(255, 255, 255, 0.06) !important;
            color: var(--tix-text) !important;
            outline: none !important;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.25) !important;
        }

        .form-select option {
            background: #1a0a0f;
            color: #fff;
        }

        .form-label,
        label {
            color: rgba(255, 255, 255, 0.7) !important;
            font-weight: 600;
            font-size: 13px;
        }

        .form-text {
            color: var(--tix-text3) !important;
        }

        .invalid-feedback {
            color: #ff6080 !important;
            font-size: 12px;
        }

        .is-invalid {
            border-color: var(--tix-red) !important;
        }

        /* ══════════════
           BUTTONS
           ══════════════ */
        .btn-primary {
            background: linear-gradient(135deg, #dc143c, #8b0000) !important;
            border-color: transparent !important;
            color: #fff !important;
            font-weight: 700 !important;
            border-radius: 10px !important;
            transition: opacity 0.2s, transform 0.2s !important;
        }

        .btn-primary:hover {
            opacity: 0.88 !important;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.08) !important;
            border-color: rgba(255, 255, 255, 0.12) !important;
            color: rgba(255, 255, 255, 0.7) !important;
            border-radius: 10px !important;
            font-weight: 600 !important;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.12) !important;
            color: #fff !important;
        }

        .btn-success {
            background: linear-gradient(135deg, #1a6b3a, #0f4026) !important;
            border-color: transparent !important;
            color: #fff !important;
            font-weight: 700 !important;
            border-radius: 10px !important;
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc143c, #8b0000) !important;
            border-color: transparent !important;
            color: #fff !important;
            font-weight: 700 !important;
            border-radius: 10px !important;
        }

        .btn-warning {
            background: linear-gradient(135deg, #d4890a, #8a5a09) !important;
            border-color: transparent !important;
            color: #fff !important;
            font-weight: 700 !important;
            border-radius: 10px !important;
        }

        .btn-info {
            background: linear-gradient(135deg, #0a70b8, #084d80) !important;
            border-color: transparent !important;
            color: #fff !important;
            font-weight: 700 !important;
            border-radius: 10px !important;
        }

        .btn-outline-danger {
            border-color: var(--tix-red) !important;
            color: var(--tix-red) !important;
            border-radius: 10px !important;
            font-weight: 600 !important;
        }

        .btn-outline-danger:hover {
            background: var(--tix-red) !important;
            color: #fff !important;
        }

        .btn-outline-primary {
            border-color: var(--tix-red) !important;
            color: var(--tix-red) !important;
            border-radius: 10px !important;
            font-weight: 600 !important;
        }

        .btn-outline-primary:hover {
            background: var(--tix-red) !important;
            color: #fff !important;
        }

        .btn-sm {
            font-size: 12px !important;
            padding: 6px 12px !important;
        }

        .btn-xs {
            font-size: 11px !important;
            padding: 4px 8px !important;
        }

        /* ══════════════
           BADGES
           ══════════════ */
        .badge {
            font-weight: 700 !important;
            border-radius: 6px !important;
        }

        .badge-success,
        .bg-success {
            background: rgba(34, 197, 94, 0.15) !important;
            color: #22c55e !important;
            border: 1px solid rgba(34, 197, 94, 0.3) !important;
        }

        .badge-danger,
        .bg-danger {
            background: rgba(220, 20, 60, 0.15) !important;
            color: #ff6080 !important;
            border: 1px solid rgba(220, 20, 60, 0.3) !important;
        }

        .badge-warning,
        .bg-warning {
            background: rgba(251, 191, 36, 0.15) !important;
            color: #fbbf24 !important;
            border: 1px solid rgba(251, 191, 36, 0.3) !important;
        }

        .badge-info,
        .bg-info {
            background: rgba(59, 130, 246, 0.15) !important;
            color: #60a5fa !important;
            border: 1px solid rgba(59, 130, 246, 0.3) !important;
        }

        .badge-primary,
        .bg-primary {
            background: rgba(220, 20, 60, 0.15) !important;
            color: #ff6080 !important;
            border: 1px solid rgba(220, 20, 60, 0.3) !important;
        }

        .badge-secondary {
            background: rgba(255, 255, 255, 0.08) !important;
            color: rgba(255, 255, 255, 0.6) !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
        }

        /* ══════════════
           ALERTS
           ══════════════ */
        .alert {
            border-radius: 12px !important;
            border: none !important;
            font-weight: 600;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1) !important;
            color: #22c55e !important;
            border: 1px solid rgba(34, 197, 94, 0.2) !important;
        }

        .alert-danger {
            background: rgba(220, 20, 60, 0.1) !important;
            color: #ff6080 !important;
            border: 1px solid rgba(220, 20, 60, 0.2) !important;
        }

        .alert-warning {
            background: rgba(251, 191, 36, 0.1) !important;
            color: #fbbf24 !important;
            border: 1px solid rgba(251, 191, 36, 0.2) !important;
        }

        .alert-info {
            background: rgba(59, 130, 246, 0.1) !important;
            color: #60a5fa !important;
            border: 1px solid rgba(59, 130, 246, 0.2) !important;
        }

        /* ══════════════
           MISC ELEMENTS
           ══════════════ */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            color: var(--tix-text) !important;
        }

        p,
        span,
        li,
        td,
        th {
            color: inherit;
        }

        a {
            color: var(--tix-red);
        }

        a:hover {
            color: #ff4d6d;
        }

        hr {
            border-color: var(--tix-border2) !important;
        }

        .text-muted {
            color: var(--tix-text3) !important;
        }

        .page-title {
            color: var(--tix-text) !important;
            font-weight: 800;
            font-size: 22px;
        }

        .page-header {
            margin-bottom: 24px;
        }

        /* Pagination */
        .pagination .page-link {
            background: rgba(255, 255, 255, 0.04) !important;
            border: 1px solid var(--tix-border2) !important;
            color: rgba(255, 255, 255, 0.6) !important;
            border-radius: 8px !important;
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #dc143c, #8b0000) !important;
            border-color: transparent !important;
            color: #fff !important;
        }

        .pagination .page-link:hover {
            background: rgba(220, 20, 60, 0.1) !important;
            color: #fff !important;
        }

        /* Select2 / Bootstrap Select overrides */
        .select2-container--default .select2-selection--single {
            background: rgba(255, 255, 255, 0.04) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #fff !important;
        }

        /* Modal */
        .modal-content {
            background: #1a0a0f !important;
            border: 1px solid var(--tix-border) !important;
            border-radius: 18px !important;
        }

        .modal-header {
            border-bottom: 1px solid var(--tix-border2) !important;
            background: rgba(220, 20, 60, 0.05) !important;
        }

        .modal-footer {
            border-top: 1px solid var(--tix-border2) !important;
        }

        .modal-title {
            color: #fff !important;
            font-weight: 800;
        }

        /* Dropdown */
        .dropdown-menu {
            background: #1a0a0f !important;
            border: 1px solid var(--tix-border) !important;
            border-radius: 12px !important;
        }

        .dropdown-item {
            color: rgba(255, 255, 255, 0.7) !important;
            font-size: 13px;
            border-radius: 8px;
        }

        .dropdown-item:hover {
            background: rgba(220, 20, 60, 0.08) !important;
            color: #fff !important;
        }

        /* Input Group */
        .input-group-text {
            background: rgba(255, 255, 255, 0.06) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: rgba(255, 255, 255, 0.5) !important;
        }

        /* Nav Tabs */
        .nav-tabs {
            border-bottom: 1px solid var(--tix-border2) !important;
        }

        .nav-tabs .nav-link {
            color: rgba(255, 255, 255, 0.45) !important;
            border: transparent !important;
            font-weight: 600;
        }

        .nav-tabs .nav-link.active {
            background: transparent !important;
            border-bottom: 2px solid var(--tix-red) !important;
            color: #fff !important;
        }

        /* Page-level breadcrumb / section headers */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--tix-border2);
        }

        .section-header h4 {
            font-size: 20px;
            font-weight: 800;
            color: #fff;
            font-family: 'DM Serif Display', serif;
        }

        /* Kaiadmin overrides — remove leftover white backgrounds */
        .main-panel,
        .content-wrapper,
        .page-inner {
            background: var(--tix-bg) !important;
        }

        .navbar-header {
            background: transparent !important;
        }

        .logo-header,
        .main-header-logo {
            background: transparent !important;
        }

        /* Remove Kaiadmin default sidebar gradient */
        .sidebar[data-background-color="dark"] {
            background: #0d0d0d !important;
        }

        /* ── Unified Dark Overrides for Legacy Bootstrap Classes ── */
        .wrapper .bg-white, .wrapper .bg-light, .wrapper .table-light, .wrapper .thead-light { background: var(--tix-card) !important; color: var(--tix-text) !important; }
        .wrapper .text-dark, .wrapper .text-black { color: var(--tix-text) !important; }
        .wrapper .text-primary { color: var(--tix-red) !important; }
        .wrapper .text-muted { color: var(--tix-text3) !important; }
        .wrapper .border, .wrapper .border-bottom, .wrapper .border-top, .wrapper .border-start, .wrapper .border-end { border-color: var(--tix-border2) !important; }
        .wrapper .card-header.bg-white { border-bottom: 1px solid var(--tix-border2) !important; }
        .wrapper .table { color: var(--tix-text) !important; background: transparent !important; }
        .wrapper .table-hover tbody tr:hover { background: rgba(255,255,255,0.02) !important; }
        .wrapper .table thead.bg-light th, .wrapper .table thead th { 
            background: rgba(220,20,60,0.05) !important; 
            color: var(--tix-text2) !important; 
            border: none !important;
            font-size: 11px !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
        }
        .wrapper .table td { border-color: var(--tix-border2) !important; }
        
        /* Form Premium Styling */
        .wrapper label { color: var(--tix-text2) !important; font-weight: 600 !important; margin-bottom: 8px !important; }
        .wrapper .form-control, .wrapper .form-select, .wrapper .input-group-text {
            background: rgba(255,255,255,0.04) !important;
            border: 1px solid var(--tix-border) !important;
            color: var(--tix-text) !important;
            border-radius: 10px !important;
            padding: 10px 15px !important;
        }
        .wrapper .form-control:focus, .wrapper .form-select:focus {
            background: rgba(255,255,255,0.08) !important;
            border-color: var(--tix-red) !important;
            box-shadow: 0 0 0 0.25rem rgba(220,20,60,0.15) !important;
        }
        .wrapper .form-control::placeholder { color: var(--tix-text3) !important; opacity: 0.6; }
        
        /* Fix Breadcrumb/Title colors */
        .wrapper h1, .wrapper h2, .wrapper h3, .wrapper h4, .wrapper h5, .wrapper h6 { color: var(--tix-text) !important; font-weight: 700 !important; }
        .wrapper .small, .wrapper small { color: var(--tix-text3) !important; }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                z-index: 1050;
                box-shadow: 4px 0 20px rgba(0, 0, 0, 0.6);
            }

            .main-panel {
                width: 100%;
            }
        }

        /* ══════════════
           SWEETALERT PREMIUM
           ══════════════ */
        .tixly-swal-popup { 
            border-radius: 20px !important; 
            border: 1px solid rgba(220, 20, 60, 0.25) !important; 
            padding: 24px !important; 
            box-shadow: 0 40px 100px rgba(0,0,0,0.85) !important;
            background: linear-gradient(135deg, #1a0a0f 0%, #110710 100%) !important;
        }
        .tixly-swal-actions { 
            gap: 12px !important; 
            width: 100% !important; 
            border-top: 1px solid rgba(255,255,255,0.06) !important; 
            padding-top: 20px !important; 
            margin-top: 24px !important; 
            display: flex !important;
            justify-content: flex-end !important;
        }
        .tixly-swal-confirm { 
            background: linear-gradient(135deg, #dc143c, #8b0000) !important; 
            color: #fff !important; 
            border: none !important; 
            border-radius: 12px !important; 
            padding: 12px 28px !important; 
            font-weight: 800 !important; 
            font-size: 14px !important; 
            cursor: pointer !important; 
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
            font-family: 'Inter', sans-serif !important;
            box-shadow: 0 4px 12px rgba(220, 20, 60, 0.25) !important;
        }
        .tixly-swal-confirm:hover { 
            transform: translateY(-2px) !important; 
            box-shadow: 0 8px 24px rgba(220, 20, 60, 0.45) !important; 
            opacity: 0.9 !important;
        }
        .tixly-swal-cancel { 
            background: rgba(255, 255, 255, 0.05) !important; 
            color: rgba(255, 255, 255, 0.6) !important; 
            border: 1px solid rgba(255, 255, 255, 0.1) !important; 
            border-radius: 12px !important; 
            padding: 12px 28px !important; 
            font-weight: 700 !important; 
            font-size: 14px !important; 
            cursor: pointer !important; 
            transition: all 0.2s !important;
            font-family: 'Inter', sans-serif !important;
        }
        .tixly-swal-cancel:hover { 
            background: rgba(255, 255, 255, 0.1) !important; 
            color: #fff !important; 
            border-color: rgba(255, 255, 255, 0.2) !important;
        }
    </style>

    @yield('ExtraCSS')
</head>

<body>
    <div class="wrapper">
        @include('layouts.sidebar')

        <div class="main-panel">
            @include('layouts.header')

            <div class="main-content-area">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Core JS -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/chart.js/chart.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
    @yield('ExtraJS')
</body>

</html>