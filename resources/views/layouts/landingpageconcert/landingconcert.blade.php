<!DOCTYPE html>
<html lang="en">

<head>
  <title>@yield('title', 'TIXLY — Concert Tickets')</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <meta name="description" content="" />
  <meta name="keywords" content="" />
  <meta name="author" content="Free-Template.co" />

  <link rel="icon" type="image/svg+xml" href="{{ asset('tixly-favicon.svg') }}">

  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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

    /* ===== THEME TOGGLER & GLOBAL STYLES ===== */
    :root {
      --bg-color: #0d0d0d;
      --text-color: #ffffff;
      --card-bg: #111111;
      --border-color: rgba(220, 20, 60, 0.2);
    }

    body.light-mode {
      --bg-color: #f8f9fa;
      --text-color: #212529;
      --card-bg: #ffffff;
      --border-color: rgba(0, 0, 0, 0.1);
    }

    body {
      background: var(--bg-color) !important;
      color: var(--text-color) !important;
      font-family: 'Plus Jakarta Sans', sans-serif !important;
      transition: background 0.3s ease, color 0.3s ease;
      animation: pageFadeIn 0.4s ease forwards;
    }

    h1, h2, h3, h4, h5, h6, .display-1, .display-2, .display-3, .display-4, .section-title {
      font-family: 'DM Serif Display', serif !important;
    }

    body.light-mode p, body.light-mode li, body.light-mode .text-muted {
      color: #4b5563 !important;
    }

    body.light-mode .section, body.light-mode .portfolio-section {
      background: var(--bg-color) !important;
    }

    @keyframes pageFadeIn {
      from { opacity: 0; transform: translateY(6px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    
    html {
      scroll-behavior: smooth;
    }

    .theme-toggle-btn {
      background: rgba(255, 255, 255, 0.05);
      border: 1.5px solid var(--border-color);
      color: var(--text-color);
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 1.1rem;
    }
    .theme-toggle-btn:hover {
      background: rgba(220, 20, 60, 0.1);
      border-color: #dc143c;
      transform: rotate(15deg);
    }

    body.light-mode .theme-toggle-btn {
      background: rgba(0, 0, 0, 0.05);
    }
  </style>

  <script>
    // Immediate script to prevent flicker
    (function() {
      const theme = localStorage.getItem('theme') || 'dark';
      if (theme === 'light') {
        document.documentElement.classList.add('light-mode-init');
      }
    })();
  </script>

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
  <script>
    // Theme toggle logic
    function toggleTheme() {
      const body = document.body;
      const isLight = body.classList.toggle('light-mode');
      const icon = document.querySelector('.theme-icon');
      
      if (isLight) {
        localStorage.setItem('theme', 'light');
        if (icon) {
          icon.classList.remove('fa-moon-o');
          icon.classList.add('fa-sun-o');
        }
      } else {
        localStorage.setItem('theme', 'dark');
        if (icon) {
          icon.classList.remove('fa-sun-o');
          icon.classList.add('fa-moon-o');
        }
      }
    }

    // Initialize theme based on preference
    document.addEventListener('DOMContentLoaded', function() {
      const theme = localStorage.getItem('theme') || 'dark';
      const icon = document.querySelector('.theme-icon');
      if (theme === 'light') {
        document.body.classList.add('light-mode');
        if (icon) {
          icon.classList.remove('fa-moon-o');
          icon.classList.add('fa-sun-o');
        }
      }
    });
  </script>
  @yield('ExtraJS2')
</body>

</html>
