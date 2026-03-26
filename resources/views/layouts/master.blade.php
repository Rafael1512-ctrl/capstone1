<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Tixly - Admin Dashboard</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["{{ asset('assets/css/fonts.min.css') }}"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />
    <style>
        /* Wrapper & Layout - Sidebar Fixed */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            overflow: hidden;
        }

        .wrapper {
            display: flex;
            width: 100%;
            height: 100vh;
            gap: 0;
        }

        /* Sidebar - Fixed Left */
        .sidebar {
            width: 270px;
            height: 100vh;
            flex-shrink: 0;
            overflow-y: auto;
            position: relative;
            z-index: 100;
        }

        /* Main Panel */
        .main-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            width: calc(100% - 270px);
            height: 100vh;
            overflow: hidden;
        }

        /* Header */
        .main-header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            flex-shrink: 0;
        }

        /* Content Area - Scrollable */
        .main-content-area {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 20px;
        }

        /* Logo Header in main-header */
        .main-header-logo {
            width: 100%;
            background-color: #1a1a2e;
            padding: 0;
            margin: 0;
        }

        .logo-header {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0.75rem 1rem;
            width: 100%;
        }

        .logo-header .logo {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .logo-header .nav-toggle,
        .logo-header .topbar-toggler {
            display: none;
        }

        /* Hide hamburger menus */
        .navbar-header .nav-toggle,
        .sidenav-toggler {
            display: none !important;
        }

        /* Responsive for smaller screens */
        @media (max-width: 1024px) {
            .sidebar {
                width: 250px;
            }

            .main-panel {
                width: calc(100% - 250px);
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                width: 250px;
                height: 100vh;
                z-index: 1050;
                box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
            }

            .main-panel {
                width: 100%;
            }

            .wrapper.sidebar-hidden .sidebar {
                transform: translateX(-100%);
            }
        }
    </style>
    @yield('ExtraCSS')

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
</head>

<body>
    <div class="wrapper">
        @include('layouts.sidebar')

        <div class="main-panel">
            @include('layouts.header')

            <div class="main-content-area">
                @yield('content')

                @include('layouts.footer')
            </div>


        </div>
    </div>
    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

    <!-- Chart JS -->
    <script src="{{ asset('assets/js/plugin/chart.js/chart.min.js') }}"></script>

    <!-- jQuery Sparkline -->
    <script src="{{ asset('assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>

    <!-- Chart Circle -->
    <script src="{{ asset('assets/js/plugin/chart-circle/circles.min.js') }}"></script>

    <!-- Datatables -->
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>

    <!-- Bootstrap Notify -->
    <script src="{{ asset('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>

    <!-- jQuery Vector Maps -->
    <script src="{{ asset('assets/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jsvectormap/world.js') }}"></script>

    <!-- Google Maps Plugin -->
    <script src="{{ asset('assets/js/plugin/gmaps/gmaps.js') }}"></script>

    <!-- Sweet Alert -->
    <script src="{{ asset('assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>

    <!-- Kaiadmin JS -->
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
    @yield('ExtraJS')
</body>

</html>
