<!DOCTYPE html>
<html lang="en">

<head>
  <title>@yield('title', 'TIXLY — Concert Tickets')</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <meta name="description" content="" />
  <meta name="keywords" content="" />
  <meta name="author" content="Free-Template.co" />

  <link rel="shortcut icon" href="{{ asset('cardboard-assets/img/ftco-32x32.png') }}">

  <link href="https://fonts.googleapis.com/css?family=DM+Serif+Display:400,400i|Roboto+Mono&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('cardboard-assets/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('cardboard-assets/css/animate.css') }}">
  <link rel="stylesheet" href="{{ asset('cardboard-assets/css/owl.carousel.min.css') }}">
  <link rel="stylesheet" href="{{ asset('cardboard-assets/css/jquery.fancybox.min.css') }}">


  <link rel="stylesheet" href="{{ asset('cardboard-assets/fonts/ionicons/css/ionicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('cardboard-assets/fonts/fontawesome/css/font-awesome.min.css') }}">
  <link rel="stylesheet" href="{{ asset('cardboard-assets/fonts/flaticon/font/flaticon.css') }}">
  <link rel="stylesheet" href="{{ asset('cardboard-assets/css/aos.css') }}">
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.css">

  <!-- Theme Style -->
  <link rel="stylesheet" href="{{ asset('cardboard-assets/css/style.css') }}">

  <style>
    .work-thumb {
      display: block;
      height: 550px;
      overflow: hidden;
      position: relative;
      background-color: #000;
    }
    .work-thumb img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
      position: relative;
      z-index: 1;
    }
    .work-thumb .work-text {
      z-index: 10;
    }
    .work-thumb:before {
      z-index: 5;
    }
    .work-thumb:hover img {
      transform: scale(1.08);
    }
    /* Mobile adjustments */
    @media (max-width: 576px) {
      .work-thumb {
        height: 400px;
      }
    }
  </style>

</head>

<body style="background: #0d0d0d; color: #fff;">

  
  @include('layouts.landingpageconcert.header')

  @yield('contentlandingconcert')

  @include('layouts.landingpageconcert.footer')



  <!-- loader -->
  <div id="loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
      <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
      <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10"
        stroke="#ffc107" /></svg></div>

  <script src="{{ asset('cardboard-assets/js/jquery-3.2.1.min.js') }}"></script>
  <script src="{{ asset('cardboard-assets/js/jquery-migrate-3.0.1.min.js') }}"></script>
  <script src="{{ asset('cardboard-assets/js/popper.min.js') }}"></script>
  <script src="{{ asset('cardboard-assets/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('cardboard-assets/js/owl.carousel.min.js') }}"></script>
  <script src="{{ asset('cardboard-assets/js/jquery.waypoints.min.js') }}"></script>
  <script src="{{ asset('cardboard-assets/js/jquery.fancybox.min.js') }}"></script>
  <script src="{{ asset('cardboard-assets/js/jquery.stellar.min.js') }}"></script>
  <script src="{{ asset('cardboard-assets/js/aos.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="{{ asset('cardboard-assets/js/main.js') }}"></script>
  @yield('ExtraJS2')
</body>

</html>
