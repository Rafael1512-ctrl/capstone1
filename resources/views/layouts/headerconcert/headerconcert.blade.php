    <header>
        <style>
            /* Ensure header looks good on both transparent and dark backgrounds */
            @if(!request()->routeIs('public.event.show'))
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
            @endif

            /* Navigation menu styles */
            .header-area .main-header-area .main-menu ul li {
                margin: 0 10px !important;
            }
            
            @media (max-width: 991px) {
                .main-menu { display: none; }
            }
        </style>
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
                                            @php
                                                $isEventPage = request()->routeIs('public.event.show');
                                                $eventUrl = isset($event) ? route('public.event.show', $event->event_id) : route('home');
                                            @endphp
                                            <li><a href="{{ route('home') }}">Main Home</a></li>

                                            <li><a href="{{ $isEventPage ? '#venue' : $eventUrl . '#venue' }}">Venue</a></li>
                                            <!-- My Ticket link to scroll to ticket section -->
                                            @if (isset($event) && $event->category && (strtolower($event->category->name) === 'festival' || str_contains(strtolower($event->category->name), 'festival')))
                                                <li><a href="{{ $isEventPage ? '#performers' : $eventUrl . '#performers' }}">Performer</a></li>
                                            @endif
                                            <li><a href="{{ $isEventPage ? '#tickets' : $eventUrl . '#tickets' }}">My Ticket</a></li>

                                            
                                            
                                            
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 d-none d-lg-block">
                                <div class="buy_tkt">
                                    <div class="book_btn">
                                        @auth
                                            {{-- Dashboard button removed per user request --}}
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
