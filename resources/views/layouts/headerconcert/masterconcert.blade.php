<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', 'Tixly Concert')</title>
    <meta name="description" content="@yield('meta_description', 'Live Music Event')">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('concert-assets/img/favicon.png') }}">

    <!-- CSS common for all concert pages -->
    <link rel="stylesheet" href="{{ asset('concert-assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('concert-assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('concert-assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('concert-assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('concert-assets/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('concert-assets/css/gijgo.css') }}">
    <link rel="stylesheet" href="{{ asset('concert-assets/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('concert-assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('concert-assets/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('concert-assets/css/slicknav.css') }}">
    <link rel="stylesheet" href="{{ asset('concert-assets/css/style.css') }}">

    @yield('ExtraCSS')

    <style>
        .slider_bg_1 {
            background-image: url("@yield('banner_url')");
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body>
    <!--[if lte IE 9]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
        <![endif]-->

    @include('layouts.headerconcert.headerconcert')

    @yield('content')

    @include('layouts.headerconcert.footerconcert', [
        'footerDate' => $footerDate ?? '20 Nov, 2026',
        'footerLocation' => $footerLocation ?? 'Jakarta International Stadium',
        'footerLocationClass' => $footerLocationClass ?? 'text-warning',
        'footerSlogan' => $footerSlogan ?? 'GET YOUR TICKETS NOW',
        'footerButtonText' => $footerButtonText ?? 'Get Your Ticket',
        'footerButtonLink' => $footerButtonLink ?? '#',
        'footerCopyright' => $footerCopyright ?? 'Tixly © 2026'
    ])

    <!-- JS common for all concert pages -->
    <script src="{{ asset('concert-assets/js/vendor/modernizr-3.5.0.min.js') }}"></script>
    <script src="{{ asset('concert-assets/js/vendor/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('concert-assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('concert-assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('concert-assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('concert-assets/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('concert-assets/js/ajax-form.js') }}"></script>
    <script src="{{ asset('concert-assets/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('concert-assets/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('concert-assets/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('concert-assets/js/scrollIt.js') }}"></script>
    <script src="{{ asset('concert-assets/js/jquery.scrollUp.min.js') }}"></script>
    <script src="{{ asset('concert-assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('concert-assets/js/gijgo.min.js') }}"></script>
    <script src="{{ asset('concert-assets/js/nice-select.min.js') }}"></script>
    <script src="{{ asset('concert-assets/js/jquery.slicknav.min.js') }}"></script>
    <script src="{{ asset('concert-assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('concert-assets/js/plugins.js') }}"></script>
    <script src="{{ asset('concert-assets/js/main.js') }}"></script>

    @yield('ExtraJS')
</body>

</html>
