    <header>
        <div class="header-area ">
            <div id="sticky-header" class="main-header-area">
                <div class="container">
                    <div class="header_bottom_border">
                        <div class="row align-items-center">
                            <div class="col-xl-3 col-lg-3">
                                <div class="logo">
                                    <a href="{{ route('home') }}">
                                        <h2 style="background: linear-gradient(90deg, #dc143c, #8b0000); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: bold; margin-bottom: 0;">TIXLY</h2>
                                    </a>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <div class="main-menu  d-none d-lg-block">
                                    <nav>
                                        <ul id="navigation">
                                            <li><a href="{{ route('concert1') }}">home</a></li>
                                            <li><a href="#performers">Performer</a></li>
                                            <li><a href="#about">About</a></li>
                                            <li><a href="#program">Program</a></li>
                                            <li><a href="#venue">Venue</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 d-none d-lg-block">
                                <div class="buy_tkt">
                                    <div class="book_btn">
                                        @auth
                                            <a href="{{ auth()->user()->isAdmin() ? route('admin.starter') : (auth()->user()->isOrganizer() ? route('organizer.dashboard') : route('landing')) }}" class="boxed-btn3">Dashboard</a>
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