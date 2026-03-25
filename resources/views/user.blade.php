@extends('layouts.landingpageconcert.landingconcert')
@section('contentlandingconcert')
    <style>
        /* Custom Override for Concert Cards Hover Effect */
        .work-thumb {
            overflow: hidden !important;
            border: 4px solid #fff !important;
            /* Keep the white border static on the container */
        }

        .work-thumb img {
            border: none !important;
            transition: transform 0.5s ease !important;
            width: 100%;
            display: block;
        }

        .work-thumb:hover img {
            transform: scale(1.1) !important;
        }

        .work-thumb:before {
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
        }

        /* New Elegant Concert Cards */
        .concert-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            margin-bottom: 30px;
            border: 1px solid rgba(0, 0, 0, 0.05);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .concert-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .concert-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .concert-card-body {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .concert-card-title {
            font-family: 'DM Serif Display', serif;
            font-size: 1.4rem;
            color: #333;
            margin-bottom: 12px;
            font-weight: 400;
        }

        .concert-meta {
            font-size: 0.9rem;
            color: #777;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .concert-meta i {
            margin-right: 8px;
            color: #9d50bb;
            width: 16px;
        }

        .concert-price-box {
            margin-top: auto;
            padding-top: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .price-label {
            font-size: 0.8rem;
            color: #999;
            display: block;
        }

        .price-value {
            font-weight: bold;
            color: #333;
            font-size: 1.1rem;
        }

        .btn-buy {
            background: linear-gradient(to right, #9d50bb, #6e48aa);
            color: #fff !important;
            border: none;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-buy:hover {
            opacity: 0.9;
            box-shadow: 0 4px 15px rgba(157, 80, 187, 0.3);
        }
    </style>
    <div class="slider-item overlay" data-stellar-background-ratio="0.5"
        style="background-image: url('{{ asset('cardboard-assets/img/hero_2.jpg') }}');">
        <div class="container">
            <div class="row slider-text align-items-center justify-content-center">
                <div class="col-lg-12 text-center col-sm-12">
                    <h1 class="mb-4" data-aos="fade-up" data-aos-delay="100">Experience the Magic<br>Feel the Music.</h1>
                    <p class="text-white lead" data-aos="fade-up" data-aos-delay="200">The most exclusive access to the
                        world's most
                        anticipated concerts.</p>
                </div>
            </div>
        </div>
    </div>
    @auth
        <div class="section pb-0" data-aos="fade-up">
            <div class="container">
                <div style="display: flex; justify-content: flex-end; margin-bottom: 20px;">
                    <a href="{{ route('profile.show') }}" class="btn btn-outline-primary px-4 py-2"
                        style="border-radius: 50px; font-weight: 600; border-color: #9d50bb; color: #9d50bb; transition: all 0.3s ease;"
                        onmouseover="this.style.background='#9d50bb'; this.style.color='white';"
                        onmouseout="this.style.background='transparent'; this.style.color='#9d50bb';">
                        <i class="fa fa-user mr-2"></i>My Profile
                    </a>
                </div>
                <div class="promo-banner-container"
                    style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ asset('cardboard-assets/img/hero_1.jpg') }}'); background-size: cover; background-position: center; border-radius: 20px; padding: 60px; color: white; position: relative; overflow: hidden; box-shadow: 0 15px 40px rgba(0,0,0,0.2);">
                    <div
                        style="position: absolute; top:0; left:0; width:100%; height:100%; background: linear-gradient(90deg, #9d50bb, transparent); opacity: 0.6;">
                    </div>
                    <div class="row align-items-center" style="position: relative; z-index: 2;">
                        <div class="col-md-7">
                            <span class="badge badge-danger mb-3 px-3 py-2"
                                style="border-radius: 50px; font-weight: 600;">UPCOMING DEALS</span>
                            <h2 class="display-4 font-weight-bold mb-4"
                                style="font-family: 'DM Serif Display', serif; color: #fff;">Special Ticket Pre-Sale!</h2>
                            <p class="lead mb-4">Exclusive for registered members. Get early access to the most anticipated
                                concerts of 2026/2027 before everyone else.</p>
                            <a href="#concert" class="btn btn-white px-5 py-3"
                                style="border-radius: 50px; font-weight: bold; background: white; color: #333; text-decoration: none;">Browse
                                Available Tickets</a>
                        </div>
                        <div class="col-md-5 d-none d-md-block text-right">
                            <img src="{{ asset('cardboard-assets/img/concert_1.jpg') }}" alt="Featured"
                                style="width: 250px; height: 250px; object-fit: cover; border-radius: 20px; border: 5px solid rgba(255,255,255,0.3); transform: rotate(5deg);">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endauth

    @auth
        <div class="section portfolio-section">
            <div class="container">
                <div class="row mb-5 justify-content-center" data-aos="fade-up">
                    <div class="col-md-8 text-center">
                        <h2 class="mb-4 section-title text-prim">Featured Concerts</h2>
                        <p>Discover the most prestigious musical events across the globe. From grand stadiums to intimate
                            stages, find
                            your rhythm here.</p>
                        <p><a href="#" class="btn btn-outline-black">View All Concerts</a></p>
                    </div>
                </div>
            </div>
            <div class="container-fluid" id="concerts">
                <div class="row mb-5 justify-content-center g-4">
                    @forelse($events as $event)
                        <div class="col-sm-6 col-md-4 col-lg-3 d-flex align-items-stretch" data-aos="fade-up"
                            data-aos-delay="{{ $loop->iteration * 100 }}">
                            <div class="concert-card h-100 d-flex flex-column text-center shadow-sm rounded">
                                <img src="{{ $event->banner_url ? Storage::url($event->banner_url) : asset('cardboard-assets/img/concert_' . (($loop->iteration % 4) + 1) . '.jpg') }}"
                                    alt="{{ $event->title }}" class="img-fluid rounded-top">

                                <div class="concert-card-body p-3 d-flex flex-column justify-content-between">
                                    <div>
                                        <h3 class="concert-card-title mb-3">{{ $event->title }}</h3>
                                        <div class="concert-meta mb-2 text-muted">
                                            <i class="fa fa-calendar"></i>
                                            {{ $event->schedule_time ? $event->schedule_time->format('d M Y') : 'Date TBD' }}
                                        </div>
                                        <div class="concert-meta mb-3 text-muted">
                                            <i class="fa fa-map-marker"></i> {{ $event->location ?? 'Venue TBD' }}
                                        </div>
                                    </div>

                                    <div class="concert-price-box mt-auto">
                                        <div class="mb-2">
                                            <span class="price-label d-block">Starting from</span>
                                            <span class="price-value font-weight-bold">
                                                Rp. {{ number_format($event->ticket_price ?? 0, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <a href="{{ route('public.event.show', $event->event_id) }}"
                                            class="btn btn-sm btn-primary">
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <p class="text-muted">No featured concerts available at the moment. Please check back later!</p>
                        </div>
                    @endforelse
                </div>

                <div class="row mt-5">
                    <div class="col-12 text-center">
                        <p><a href="#" class="btn btn-outline-primary px-4 py-3">More Upcoming Events</a></p>
                    </div>
                </div>
            </div>
        </div>
    @endauth

    <div class="section" id="my-tickets">
        <div class="container">
            @auth
                <div class="row mb-5" data-aos="fade-up">
                    <div class="col-md-8">
                        <h2 class="mb-4 section-title" style="font-family: 'DM Serif Display', serif;">My Tickets</h2>
                        <p>Your current and past concert experiences are gathered here. Access your tickets anytime with ease.
                        </p>
                    </div>
                </div>

                @if ($myTickets->isEmpty())
                    <div class="row" data-aos="fade-up">
                        <div class="col-md-12 text-center py-5 bg-white rounded-lg shadow-sm" style="border: 1px dashed #ccc;">
                            <div class="mb-3">
                                <i class="fa fa-ticket text-muted" style="font-size: 50px; opacity: 0.3;"></i>
                            </div>
                            <h4 class="text-muted">You haven't ordered any tickets yet.</h4>
                            <p>Discover our featured concerts above to start your musical journey!</p>
                            <a href="#concerts" class="btn btn-primary btn-sm px-4 py-2 mt-2"
                                style="border-radius: 50px; background: #9d50bb; border: none;">Browse Concerts</a>
                        </div>
                    </div>
                @else
                    <div class="row no-gutters" data-aos="fade-up">
                        @foreach ($myTickets as $ticket)
                            <div class="col-lg-4 col-md-6 mb-4 px-2">
                                <div class="ticket-history-card p-4 rounded-lg bg-white"
                                    style="box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #eee; transition: all 0.3s ease; height: 100%;">
                                    <div class="d-flex justify-content-between mb-3 align-items-center">
                                        <span
                                            class="badge {{ $ticket->isActive() ? 'badge-success' : 'badge-secondary' }} px-3 py-1"
                                            style="border-radius: 20px;">
                                            {{ $ticket->ticket_status }}
                                        </span>
                                        <small
                                            class="text-muted">{{ $ticket->created_at ? $ticket->created_at->format('d M Y') : 'N/A' }}</small>
                                    </div>

                                    <h4 class="font-weight-bold mb-1" style="color: #333; font-size: 1.1rem;">
                                        {{ $ticket->ticketType->event->title ?? 'Concert Event' }}</h4>
                                    <p class="small text-muted mb-3"><i class="fa fa-map-marker mr-1"></i>
                                        {{ $ticket->ticketType->event->location ?? 'Venue Location' }}</p>

                                    <div class="pt-3 border-top d-flex justify-content-between align-items-center mt-auto">
                                        <div>
                                            <p class="mb-0 small text-uppercase font-weight-bold text-muted"
                                                style="font-size: 0.7rem;">Category</p>
                                            <p class="mb-0 font-weight-bold" style="color: #9d50bb;">
                                                {{ $ticket->ticketType->name }}</p>
                                        </div>
                                        <a href="{{ route('tickets.view', $ticket->ticket_id) }}"
                                            class="btn btn-outline-primary btn-sm"
                                            style="border-radius: 20px; border-color: #9d50bb; color: #9d50bb;">View Detail</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <style>
                    .ticket-history-card:hover {
                        transform: translateY(-5px);
                        box-shadow: 0 15px 45px rgba(0, 0, 0, 0.1) !important;
                        border-color: #9d50bb !important;
                    }
                </style>
            @else
                <div class="row">
                    <!-- Bagian Welcome di atas -->
                    <div class="col-12 text-center mb-5">
                        <h2 class="mb-4 section-title text-primary" style="font-family: 'DM Serif Display', serif;">
                            Welcome to LuxTix
                        </h2>
                        <p class="mb-4">
                            Your gateway to the most exclusive live music experiences.
                            Please log in or register to access your personalized concert dashboard
                            and start exploring our featured events.
                        </p>
                    </div>
                </div>

                <div class="row">
                    <!-- Kolom Gambar -->
                    <div class="col-lg-6 mb-4">
                        <img src="your-image.jpg" alt="Concert Experience" class="img-fluid rounded shadow">
                    </div>

                    <!-- Kolom About Us -->
                    <div class="col-lg-6 mb-4">
                        <span class="d-block text-uppercase text-primary">About Us</span>
                        <h2 class="mb-4 section-title" style="color: #ffffff;">
                            Your Gateway to Exclusive Live Music Experiences
                        </h2>
                        <p style="color: #ffffff; line-height: 1.8; font-size: 1rem;">
                            LuxTix is a premium platform dedicated to bringing the world's most magnificent concerts directly to
                            you.
                            We revolutionize ticket purchasing by combining cutting-edge technology with unparalleled customer
                            service.
                        </p>
                        <p style="color: #ffffff; line-height: 1.8; font-size: 1rem;">
                            Our mission is to eliminate barriers between fans and the artists they love.
                            From intimate performances to massive stadium shows, we curate the finest musical events
                            while ensuring absolute security and authenticity in every transaction.
                        </p>
                        <p style="color: #ffffff; line-height: 1.8; font-size: 1rem;">
                            With blockchain-backed ticketing and dedicated concierge support, LuxTix transforms concert
                            attendance
                            from a transaction into an unforgettable experience. We believe every seat deserves to be
                            extraordinary.
                        </p>
                        <p><a href="#" class="btn btn-outline-white">Learn More About LuxTix</a></p>
                    </div>
                </div>

            @endauth

            <!-- User Profile Section (for authenticated users) -->
            @auth
                <div class="row mb-5" data-aos="fade-up" style="margin-top: 60px;">
                    <div class="col-12">
                        <div
                            style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 16px; padding: 40px; border-left: 5px solid #9d50bb;">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h3
                                        style="color: #333; font-weight: 700; margin-bottom: 10px; font-family: 'DM Serif Display', serif;">
                                        {{ Auth::user()->name }}
                                    </h3>
                                    <p style="color: #666; font-size: 0.95rem; margin-bottom: 15px;">
                                        <i class="fa fa-envelope mr-2" style="color: #9d50bb;"></i>
                                        {{ Auth::user()->email }}
                                    </p>
                                    <p style="color: #999; font-size: 0.85rem; margin-bottom: 0;">
                                        Member since
                                        {{ Auth::user()->created_at ? Auth::user()->created_at->format('d M Y') : 'N/A' }}
                                    </p>
                                </div>
                                <div class="col-md-4 text-right">
                                    <a href="{{ route('profile.show') }}" class="btn px-4 py-2"
                                        style="background: linear-gradient(to right, #9d50bb, #6e48aa); color: white; border-radius: 50px; font-weight: 600; text-decoration: none; display: inline-block; transition: all 0.3s ease;"
                                        onmouseover="this.style.boxShadow='0 6px 20px rgba(157, 80, 187, 0.3)';"
                                        onmouseout="this.style.boxShadow='none';">
                                        <i class="fa fa-user mr-2"></i>View Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endauth
            }
            </style>
        </div>

        <div class="section" style="background: linear-gradient(135deg, #ffffff 0%, #f5f5f5 100%);">
            <div class="container">
                <div class="row justify-content-center mb-5">
                    <div class="col-md-8 text-center">
                        <h2 class="mb-4 section-title" style="color: #333;">What Our Users Say</h2>
                        <p style="color: #666; font-size: 1.1rem;">Join thousands of music enthusiasts who've experienced
                            the
                            LuxTix difference</p>
                    </div>
                </div>
                <div class="nonloop-block-11 owl-carousel">
                    <div class="item">
                        <div class="block-33 h-100"
                            style="background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.08); transition: all 0.3s ease;">
                            <div class="vcard d-flex mb-3">
                                <div class="image align-self-center"><img
                                        src="{{ asset('cardboard-assets/img/person_1.jpg') }}" alt="Fan"
                                        style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                                </div>
                                <div class="name-text align-self-center ml-3">
                                    <h2 class="heading" style="color: #333; margin: 0; font-size: 1.1rem;">Alicia V.</h2>
                                    <span class="meta" style="color: #9d50bb; font-size: 0.9rem;">Verified
                                        Attendee</span>
                                </div>
                            </div>
                            <div class="text">
                                <blockquote
                                    style="color: #666; border-left: 3px solid #9d50bb; padding-left: 15px; margin: 0; line-height: 1.7;">
                                    <p>&rdquo; LuxTix made attending the Radiohead concert so easy. The VIP entrance was
                                        seamless and the
                                        seats were exactly as described. A truly premium experience. &ldquo;</p>
                                </blockquote>
                            </div>
                        </div>
                    </div>

                    <div class="item">
                        <div class="block-33 h-100"
                            style="background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.08); transition: all 0.3s ease;">
                            <div class="vcard d-flex mb-3">
                                <div class="image align-self-center"><img
                                        src="{{ asset('cardboard-assets/img/person_2.jpg') }}" alt="Fan"
                                        style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                                </div>
                                <div class="name-text align-self-center ml-3">
                                    <h2 class="heading" style="color: #333; margin: 0; font-size: 1.1rem;">David K.</h2>
                                    <span class="meta" style="color: #9d50bb; font-size: 0.9rem;">Music
                                        Enthusiast</span>
                                </div>
                            </div>
                            <div class="text">
                                <blockquote
                                    style="color: #666; border-left: 3px solid #9d50bb; padding-left: 15px; margin: 0; line-height: 1.7;">
                                    <p>&rdquo; I've never had a more reliable experience buying tickets. No bots, no hidden
                                        fees, just pure
                                        music. Recommended for any serious concert-goer. &ldquo;</p>
                                </blockquote>
                            </div>
                        </div>
                    </div>

                    <div class="item">
                        <div class="block-33 h-100"
                            style="background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.08); transition: all 0.3s ease;">
                            <div class="vcard d-flex mb-3">
                                <div class="image align-self-center"><img
                                        src="{{ asset('cardboard-assets/img/person_1.jpg') }}" alt="Fan"
                                        style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                                </div>
                                <div class="name-text align-self-center ml-3">
                                    <h2 class="heading" style="color: #333; margin: 0; font-size: 1.1rem;">Sarah J.</h2>
                                    <span class="meta" style="color: #9d50bb; font-size: 0.9rem;">Pop Culture
                                        Critic</span>
                                </div>
                            </div>
                            <div class="text">
                                <blockquote
                                    style="color: #666; border-left: 3px solid #9d50bb; padding-left: 15px; margin: 0; line-height: 1.7;">
                                    <p>&rdquo; The interface is as elegant as the concerts they host. Finding Taylor Swift
                                        tickets was a
                                        breeze! &ldquo;</p>
                                </blockquote>
                            </div>
                        </div>
                    </div>

                    <div class="item">
                        <div class="block-33 h-100"
                            style="background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.08); transition: all 0.3s ease;">
                            <div class="vcard d-flex mb-3">
                                <div class="image align-self-center"><img
                                        src="{{ asset('cardboard-assets/img/person_2.jpg') }}" alt="Fan"
                                        style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                                </div>
                                <div class="name-text align-self-center ml-3">
                                    <h2 class="heading" style="color: #333; margin: 0; font-size: 1.1rem;">James L.</h2>
                                    <span class="meta" style="color: #9d50bb; font-size: 0.9rem;">Rock Fan</span>
                                </div>
                            </div>
                            <div class="text">
                                <blockquote
                                    style="color: #666; border-left: 3px solid #9d50bb; padding-left: 15px; margin: 0; line-height: 1.7;">
                                    <p>&rdquo; Arctic Monkeys in Jakarta was a dream come true, and LuxTix helped me get
                                        front-row access
                                        without any hassle. &ldquo;</p>
                                </blockquote>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <style>
                .block-33:hover {
                    transform: translateY(-10px);
                    box-shadow: 0 15px 40px rgba(157, 80, 187, 0.2) !important;
                }
            </style>
        </div>
        <!-- END .block-4 -->
    </div>
@endsection


@section('ExtraJS2')
@endsection
