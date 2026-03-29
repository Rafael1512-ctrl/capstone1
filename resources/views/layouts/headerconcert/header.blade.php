<header>
    <style>
        /* Override template's position:absolute and transparent background */
        .header-area {
            position: relative !important;
            background: #0a0a0a !important;
        }

        .header-area .main-header-area {
            background: #0a0a0a !important;
            border-bottom: 1px solid rgba(220, 20, 60, 0.25) !important;
            box-shadow: 0 2px 24px rgba(0, 0, 0, 0.6) !important;
            padding-top: 12px !important;
        }

        .header-area .main-header-area.sticky {
            background: #0a0a0a !important;
        }

        /* Reduce menu margins to prevent wrapping */
        .header-area .main-header-area .main-menu ul li {
            margin: 0 12px !important;
        }

        .header-area .main-header-area .main-menu ul li a {
            padding: 30px 0 !important;
        }
    </style>
    <div class="header-area">
        <div id="sticky-header" class="main-header-area">
            <div class="container">
                <div class="header_bottom_border">
                    <div class="row align-items-center">
                        <div class="col-xl-3 col-lg-3">
                            <div class="logo">
                                <a href="{{ route('home') }}">
                                    <h2
                                        style="background: linear-gradient(90deg, #dc143c, #8b0000); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: bold; margin-bottom: 0;">
                                        TIXLY</h2>
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6">
                            <div class="main-menu  d-none d-lg-block">
                                <nav>
                                    <ul id="navigation">
                                        <li><a href="{{ url('/') }}#concert">home</a></li>
                                        <li><a href="{{ url('/') }}#performers">Performer</a></li>
                                        <li><a href="{{ url('/') }}#about">About</a></li>
                                        <li><a href="{{ url('/') }}#program">Program</a></li>
                                        <li><a href="{{ url('/') }}#venue">Venue</a></li>
                                        @auth
                                            <li><a href="{{ route('tickets.index') }}">My Tickets</a></li>
                                        @endauth
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 d-none d-lg-block">
                            <div class="buy_tkt">
                                <div class="book_btn">
                                    @auth
                                        <a href="{{ auth()->user()->isAdmin() ? route('admin.starter') : (auth()->user()->isOrganizer() ? route('organizer.dashboard') : route('landing')) }}"
                                            class="boxed-btn3">Dashboard</a>
                                    @else
                                        <a href="{{ route('login') }}" class="boxed-btn3">Login</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mobile_menu d-block d-lg-none"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</header>