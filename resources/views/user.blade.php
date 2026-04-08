@extends('layouts.landingpageconcert.landingconcert')
@section('contentlandingconcert')
    <style>
        /* Custom Override for Concert Cards Hover Effect */
        .work-thumb {
            overflow: hidden !important;
            border: 4px solid #fff !important;
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

        /* New Elegant Concert Cards — Dark Red Theme */
        .concert-card {
            background: #1a0a0a;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            transition: all 0.3s ease;
            margin-bottom: 30px;
            border: 1px solid rgba(220, 20, 60, 0.2);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .concert-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(220, 20, 60, 0.3);
            border-color: #dc143c;
        }

        .concert-card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-bottom: 1px solid rgba(220, 20, 60, 0.2);
        }

        .concert-card-body {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            background: #1a0a0a;
        }

        .concert-card-title {
            font-family: 'DM Serif Display', serif;
            font-size: 1.25rem;
            color: #fff;
            margin-bottom: 10px;
            font-weight: 400;
        }

        .concert-meta {
            font-size: 0.9rem;
            color: #aaa;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .concert-meta i {
            margin-right: 8px;
            color: #dc143c;
            width: 16px;
        }

        .concert-price-box {
            margin-top: auto;
            padding-top: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid rgba(220, 20, 60, 0.2);
        }

        .price-label {
            font-size: 0.8rem;
            color: #888;
            display: block;
        }

        .price-value {
            font-weight: bold;
            color: #e0e0e0;
            font-size: 1.1rem;
        }

        .btn-buy {
            background: linear-gradient(135deg, #dc143c 0%, #8b0000 100%);
            color: #fff !important;
            border: none;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(220, 20, 60, 0.3);
        }

        .btn-buy:hover {
            opacity: 0.9;
            box-shadow: 0 6px 20px rgba(220, 20, 60, 0.5);
            transform: translateY(-1px);
        }

        /* Testimonial cards */
        .block-33:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(220, 20, 60, 0.15) !important;
        }

        /* Ticket cards */
        .ticket-history-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 45px rgba(220, 20, 60, 0.15) !important;
            border-color: #dc143c !important;
        }

        /* Banner Carousel Styles */
        .banner-carousel {
            position: relative;
            width: 100%;
            overflow: hidden;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        }

        .banner-slider {
            display: flex;
            transition: transform 0.6s cubic-bezier(0.645, 0.045, 0.355, 1.000);
            width: 100%;
        }

        .banner-slide {
            flex: 0 0 100%;
            width: 100%;
            min-height: 400px;
            position: relative;
            display: flex;
            align-items: center;
            background-size: cover;
            background-position: center;
            padding: 60px;
            color: white;
        }

        .banner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #8b0000, transparent);
            opacity: 0.7;
            z-index: 1;
        }

        .banner-content {
            position: relative;
            z-index: 2;
            width: 100%;
        }

        .banner-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            background: rgba(0, 0, 0, 0.3);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .banner-nav:hover {
            background: #dc143c;
            transform: translateY(-50%) scale(1.1);
        }

        .banner-nav-prev { left: 20px; }
        .banner-nav-next { right: 20px; }

        .banner-dots {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
            z-index: 10;
        }

        .banner-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .banner-dot.active {
            background: #dc143c;
            width: 24px;
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            .banner-slide { padding: 30px; min-height: 500px; }
            .concert-card img { height: 200px; }
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
                        style="border-radius: 50px; font-weight: 600; border-color: #dc143c; color: #dc143c; transition: all 0.3s ease;"
                        onmouseover="this.style.background='#dc143c'; this.style.color='white';"
                        onmouseout="this.style.background='transparent'; this.style.color='#dc143c';">
                        <i class="fa fa-user mr-2"></i>My Profile
                    </a>
                </div>

                @if($banners->count() > 0)
                    <div class="banner-carousel">
                        <div class="banner-slider" id="bannerSlider">
                            @foreach($banners as $banner)
                                @php
                                    $rawBg = $banner->background_url;
                                    if ($rawBg) {
                                        if (filter_var($rawBg, FILTER_VALIDATE_URL)) {
                                            $bgUrl = \App\Models\SiteSetting::forceDirectUrl($rawBg);
                                        } else {
                                            $bgUrl = str_starts_with($rawBg, '/') ? asset($rawBg) : Storage::url($rawBg);
                                        }
                                    } else {
                                        $bgUrl = asset('cardboard-assets/img/hero_1.jpg');
                                    }
                                    
                                    $isFullImage = empty($banner->badge_text) && empty($banner->subtitle) && ($banner->title == ('Banner #' . $banner->id) || empty($banner->title));
                                @endphp
                                <div class="banner-slide" 
                                     style="cursor: {{ $banner->link_url ? 'pointer' : 'default' }}; background-color: #1a0a0a;"
                                     @if($banner->link_url) onclick="window.location.href='{{ $banner->link_url }}'" @endif>
                                     
                                     <img src="{{ $bgUrl }}" referrerpolicy="no-referrer" style="position: absolute; top:0; left:0; width: 100%; height: 100%; object-fit: cover; z-index: 0;" alt="Slide Background" >
                                     
                                     @if(!$isFullImage)
                                        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)); z-index: 1;"></div>
                                     @endif

                                     @if(!$isFullImage)
                                         <div class="banner-overlay"></div>
                                         <div class="banner-content">
                                             <div class="row align-items-center">
                                                 <div class="col-md-7">
                                                     @if($banner->badge_text)
                                                         <span class="badge badge-danger mb-3 px-3 py-2" style="border-radius: 50px; font-weight: 600;">{{ $banner->badge_text }}</span>
                                                     @endif
                                                     <h2 class="display-4 font-weight-bold mb-4" style="font-family: 'DM Serif Display', serif; color: #fff;">{{ $banner->title }}</h2>
                                                     @if($banner->subtitle)
                                                         <p class="lead mb-4">{{ $banner->subtitle }}</p>
                                                     @endif
                                                     <a href="{{ $banner->link_url }}" class="btn btn-white px-5 py-3" style="border-radius: 50px; font-weight: bold; background: white; color: #333; text-decoration: none;">
                                                         {{ $banner->button_text ?: 'Learn More' }}
                                                     </a>
                                                 </div>
                                                 <div class="col-md-5 d-none d-md-block text-right">
                                                         @php
                                                             $rawImg = $banner->image_url;
                                                             $bannerImgUrl = null;
                                                             if ($rawImg) {
                                                                 if (filter_var($rawImg, FILTER_VALIDATE_URL)) {
                                                                     $bannerImgUrl = \App\Models\SiteSetting::forceDirectUrl($rawImg);
                                                                 } else {
                                                                     $bannerImgUrl = str_starts_with($rawImg, '/') ? asset($rawImg) : Storage::url($rawImg);
                                                                 }
                                                             }
                                                         @endphp
                                                         @if($bannerImgUrl)
                                                             <img src="{{ $bannerImgUrl }}" alt="{{ $banner->title }}" style="width: 250px; height: 250px; object-fit: cover; border-radius: 20px; border: 5px solid rgba(255,255,255,0.3); transform: rotate(5deg);" referrerpolicy="no-referrer">
                                                         @endif
                                                 </div>
                                             </div>
                                         </div>
                                     @endif
                                 </div>
                             @endforeach
                        </div>
                        
                        {{-- Controls --}}
                        @if($banners->count() > 1)
                            <button class="banner-nav banner-nav-prev" onclick="moveBanner(-1)">
                                <i class="fa fa-chevron-left"></i>
                            </button>
                            <button class="banner-nav banner-nav-next" onclick="moveBanner(1)">
                                <i class="fa fa-chevron-right"></i>
                            </button>
                            <div class="banner-dots">
                                @foreach($banners as $index => $banner)
                                    <div class="banner-dot {{ $index === 0 ? 'active' : '' }}" onclick="goToBanner({{ $index }})"></div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    {{-- Fallback: Original static banner if no dynamic banners found --}}
                    <div class="promo-banner-container"
                        style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ asset('cardboard-assets/img/hero_1.jpg') }}'); background-size: cover; background-position: center; border-radius: 20px; padding: 60px; color: white; position: relative; overflow: hidden; box-shadow: 0 15px 40px rgba(0,0,0,0.2);">
                        <div style="position: absolute; top:0; left:0; width:100%; height:100%; background: linear-gradient(90deg, #8b0000, transparent); opacity: 0.6;"></div>
                        <div class="row align-items-center" style="position: relative; z-index: 2;">
                            <div class="col-md-7">
                                <span class="badge badge-danger mb-3 px-3 py-2" style="border-radius: 50px; font-weight: 600;">UPCOMING DEALS</span>
                                <h2 class="display-4 font-weight-bold mb-4" style="font-family: 'DM Serif Display', serif; color: #fff;">Special Ticket Pre-Sale!</h2>
                                <p class="lead mb-4">Exclusive for registered members. Get early access to the most anticipated concerts of 2026/2027 before everyone else.</p>
                                <a href="#concert" class="btn btn-white px-5 py-3" style="border-radius: 50px; font-weight: bold; background: white; color: #333; text-decoration: none;">Browse Available Tickets</a>
                            </div>
                            <div class="col-md-5 d-none d-md-block text-right">
                                <img src="{{ asset('cardboard-assets/img/concert_1.jpg') }}" alt="Featured" style="width: 250px; height: 250px; object-fit: cover; border-radius: 20px; border: 5px solid rgba(255,255,255,0.3); transform: rotate(5deg);">
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endauth

    @auth
        <div class="section portfolio-section">
            <div class="container">
                <div class="row mb-5 justify-content-center" data-aos="fade-up">
                    <div class="col-md-8 text-center">
                        <h2 class="mb-4 section-title" style="color: #fff; font-family: 'DM Serif Display', serif;">Featured Concerts</h2>
                        <p style="color: #ccc; line-height: 1.6;">Discover the most prestigious musical events across the globe. From grand stadiums to intimate
                            stages, find
                            your rhythm here.</p>
                        <p><button id="viewAllBtn" class="btn btn-outline-light" style="border-radius: 50px; padding: 10px 30px; font-weight: 600;">View All Concerts</button></p>
                    </div>
                </div>
            </div>
            <div class="container-fluid px-md-5" id="concerts">
                <div class="row mb-5 justify-content-center flex-wrap" id="concert-list">
                    @php
                        $loopIndex = 0;
                    @endphp
                    @foreach($events as $event)
                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4 px-3 d-flex align-items-stretch concert-item" style="{{ $loopIndex >= 8 ? 'display: none !important;' : '' }}">
                            <div class="concert-card h-100 d-flex flex-column text-center shadow-sm rounded" style="width: 100%; min-width: 250px;">
                                @php
                                    $rawBanner = $event->banner_url;
                                    if ($rawBanner) {
                                        if (filter_var($rawBanner, FILTER_VALIDATE_URL)) {
                                            $imageUrl = \App\Models\SiteSetting::forceDirectUrl($rawBanner);
                                        } else {
                                            $path = str_starts_with($rawBanner, '/') ? asset($rawBanner) : Storage::url($rawBanner);
                                            $imageUrl = $path;
                                        }
                                    } else {
                                        $imageUrl = asset('cardboard-assets/img/concert_' . (($loopIndex % 4) + 1) . '.jpg');
                                    }
                                @endphp
                                <img src="{{ $imageUrl }}" referrerpolicy="no-referrer"
                                    alt="{{ $event->title }}" class="img-fluid rounded-top" >

                                <div class="concert-card-body p-3 d-flex flex-column justify-content-between">
                                    <div>
                                        <h3 class="concert-card-title mb-3">{{ $event->title }}</h3>
                                        <div class="concert-meta mb-2 text-muted justify-content-center">
                                            <i class="fa fa-calendar"></i>
                                            {{ $event->schedule_time ? $event->schedule_time->format('d M Y') : 'Date TBD' }}
                                        </div>
                                        <div class="concert-meta mb-3 text-muted justify-content-center">
                                            <i class="fa fa-map-marker"></i> {{ $event->location ?? 'Venue TBD' }}
                                        </div>
                                    </div>

                                    <div class="concert-price-box mt-auto">
                                        <div class="mb-2 text-left w-100">
                                            @php
                                                // Find 'Regular' ticket, or fallback to the cheapest one
                                                $regTicket = $event->ticketTypes->first(function($t) {
                                                    return stripos($t->name, 'reg') !== false;
                                                }) ?: $event->ticketTypes->sortBy('price')->first();
                                                
                                                $displayPrice = $regTicket ? $regTicket->price : 0;
                                                $priceTag = $regTicket ? ($regTicket->name . ' Price') : 'Starting from';
                                            @endphp
                                            <span class="price-label d-block text-center">{{ $priceTag }}</span>
                                            <span class="price-value font-weight-bold d-block text-center">
                                                Rp. {{ number_format($displayPrice, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <a href="{{ route('public.event.show', $event->event_id) }}"
                                            class="btn btn-buy w-100 mt-2">
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php $loopIndex++; @endphp
                    @endforeach
                </div>

                <div class="row mt-5">
                    <div class="col-12 text-center">
                        <p><a href="#" class="btn px-4 py-3"
                            style="border-radius: 50px; border: 2px solid #dc143c; color: #dc143c; font-weight: 600; transition: all 0.3s ease;"
                            onmouseover="this.style.background='#dc143c'; this.style.color='#fff';"
                            onmouseout="this.style.background='transparent'; this.style.color='#dc143c';">More Upcoming Events</a></p>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const btn = document.getElementById("viewAllBtn");
                    const items = document.querySelectorAll(".concert-item");
                    let isExpanded = false;

                    items.forEach((item, index) => {
                        if(index >= 8) {
                            item.style.display = "none";
                            item.style.setProperty("display", "none", "important");
                        }
                    });

                    btn.addEventListener("click", function (e) {
                        e.preventDefault();
                        isExpanded = !isExpanded;

                        items.forEach((item, index) => {
                            if (index >= 8) {
                                if (isExpanded) {
                                    item.style.setProperty("display", "block", "important");
                                } else {
                                    item.style.setProperty("display", "none", "important");
                                }
                            }
                        });

                        if (isExpanded) {
                            btn.innerText = "Show Less";
                        } else {
                            btn.innerText = "View All Concerts";
                        }
                    });
                });
            </script>
        </div>
    @endauth

    <div class="section" id="my-tickets">
        <div class="container">
            @auth
                <!-- My Tickets link has been moved to the Navigation Bar -->
            @endauth
        </div>
    </div>

    <!-- About Us Section (Visible to Everyone) -->
    <div class="section pt-0">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-4">
                    @php
                        $aboutUsRaw = \App\Models\SiteSetting::where('key', 'about_us_image_url')->first()?->value;
                        if ($aboutUsRaw) {
                            if (filter_var($aboutUsRaw, FILTER_VALIDATE_URL)) {
                                $aboutUsImage = \App\Models\SiteSetting::forceDirectUrl($aboutUsRaw);
                            } else {
                                $aboutUsImage = str_starts_with($aboutUsRaw, '/') ? asset($aboutUsRaw) : Storage::url($aboutUsRaw);
                            }
                        } else {
                            $aboutUsImage = asset('cardboard-assets/img/hero_1.jpg');
                        }
                    @endphp
                    <img src="{{ $aboutUsImage }}" alt="About Us" class="img-fluid rounded shadow" referrerpolicy="no-referrer">
                </div>

                <div class="col-lg-6 mb-4">
                    <span class="d-block text-uppercase" style="color: #dc143c;">About Us</span>
                    <h2 class="mb-4 section-title" style="color: #ffffff;">
                        {{ \App\Models\SiteSetting::getValue('about_us_title', 'Your Gateway to Exclusive Live Music Experiences') }}
                    </h2>
                    <p style="color: #cccccc; line-height: 1.8; font-size: 1rem;">
                        TIXLY is a premium platform dedicated to bringing the world's most magnificent concerts directly to
                        you.
                        We revolutionize ticket purchasing by combining cutting-edge technology with unparalleled customer
                        service.
                    </p>
                    <p style="color: #cccccc; line-height: 1.8; font-size: 1rem;">
                        Our mission is to eliminate barriers between fans and the artists they love.
                        From intimate performances to massive stadium shows, we curate the finest musical events
                        while ensuring absolute security and authenticity in every transaction.
                    </p>
                    <p style="color: #cccccc; line-height: 1.8; font-size: 1rem;">
                        With blockchain-backed ticketing and dedicated concierge support, TIXLY transforms concert
                        attendance
                        from a transaction into an unforgettable experience. We believe every seat deserves to be
                        extraordinary.
                    </p>
                    <p><a href="#" class="btn px-4 py-2"
                        style="border-radius: 50px; border: 1.5px solid #dc143c; color: #dc143c; transition: all 0.3s ease;"
                        onmouseover="this.style.background='#dc143c'; this.style.color='#fff';"
                        onmouseout="this.style.background='transparent'; this.style.color='#dc143c';">Learn More About TIXLY</a></p>
                </div>
            </div>
        </div>
    </div>

    <div class="section pt-0">
        <div class="container">
            <!-- User Profile Section -->
            @auth
                <div class="row mb-5" data-aos="fade-up" style="margin-top: 60px;">
                    <div class="col-12">
                        <div
                            style="background: linear-gradient(135deg, #1a0a0a 0%, #120505 100%); border-radius: 16px; padding: 40px; border-left: 5px solid #dc143c; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h3
                                        style="color: #fff; font-weight: 700; margin-bottom: 10px; font-family: 'DM Serif Display', serif;">
                                        {{ Auth::user()->name }}
                                    </h3>
                                    <p style="color: #aaa; font-size: 0.95rem; margin-bottom: 15px;">
                                        <i class="fa fa-envelope mr-2" style="color: #dc143c;"></i>
                                        {{ Auth::user()->email }}
                                    </p>
                                    <p style="color: #666; font-size: 0.85rem; margin-bottom: 0;">
                                        Member since
                                        {{ Auth::user()->created_at ? Auth::user()->created_at->format('d M Y') : 'N/A' }}
                                    </p>
                                </div>
                                <div class="col-md-4 text-right">
                                    <a href="{{ route('profile.show') }}" class="btn px-4 py-2"
                                        style="background: linear-gradient(135deg, #dc143c 0%, #8b0000 100%); color: white; border-radius: 50px; font-weight: 600; text-decoration: none; display: inline-block; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(220,20,60,0.35);"
                                        onmouseover="this.style.boxShadow='0 6px 20px rgba(220, 20, 60, 0.55)';"
                                        onmouseout="this.style.boxShadow='0 4px 15px rgba(220,20,60,0.35)';">
                                        <i class="fa fa-user mr-2"></i>View Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endauth

        </div>

        <!-- Testimonials Section -->
        <div class="section" style="background: linear-gradient(135deg, #0d0d0d 0%, #1a0505 100%);">
            <div class="container">
                <div class="row justify-content-center mb-5">
                    <div class="col-md-8 text-center">
                        <h2 class="mb-4 section-title" style="color: #fff;">What Our Users Say</h2>
                        <p style="color: #888; font-size: 1.1rem;">Join thousands of music enthusiasts who've experienced
                            the
                            TIXLY difference</p>
                    </div>
                </div>
                <div class="nonloop-block-11 owl-carousel">
                    <div class="item">
                        <div class="block-33 h-100"
                            style="background: #1a0a0a; padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.3); border: 1px solid rgba(220,20,60,0.15); transition: all 0.3s ease;">
                            <div class="vcard d-flex mb-3">
                                <div class="image align-self-center"><img
                                        src="{{ asset('cardboard-assets/img/person_1.jpg') }}" alt="Fan"
                                        style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid #dc143c;">
                                </div>
                                <div class="name-text align-self-center ml-3">
                                    <h2 class="heading" style="color: #fff; margin: 0; font-size: 1.1rem;">Alicia V.</h2>
                                    <span class="meta" style="color: #dc143c; font-size: 0.9rem;">Verified
                                        Attendee</span>
                                </div>
                            </div>
                            <div class="text">
                                <blockquote
                                    style="color: #aaa; border-left: 3px solid #dc143c; padding-left: 15px; margin: 0; line-height: 1.7;">
                                    <p>&rdquo; TIXLY made attending the Radiohead concert so easy. The VIP entrance was
                                        seamless and the
                                        seats were exactly as described. A truly premium experience. &ldquo;</p>
                                </blockquote>
                            </div>
                        </div>
                    </div>

                    <div class="item">
                        <div class="block-33 h-100"
                            style="background: #1a0a0a; padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.3); border: 1px solid rgba(220,20,60,0.15); transition: all 0.3s ease;">
                            <div class="vcard d-flex mb-3">
                                <div class="image align-self-center"><img
                                        src="{{ asset('cardboard-assets/img/person_2.jpg') }}" alt="Fan"
                                        style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid #dc143c;">
                                </div>
                                <div class="name-text align-self-center ml-3">
                                    <h2 class="heading" style="color: #fff; margin: 0; font-size: 1.1rem;">David K.</h2>
                                    <span class="meta" style="color: #dc143c; font-size: 0.9rem;">Music
                                        Enthusiast</span>
                                </div>
                            </div>
                            <div class="text">
                                <blockquote
                                    style="color: #aaa; border-left: 3px solid #dc143c; padding-left: 15px; margin: 0; line-height: 1.7;">
                                    <p>&rdquo; I've never had a more reliable experience buying tickets. No bots, no hidden
                                        fees, just pure
                                        music. Recommended for any serious concert-goer. &ldquo;</p>
                                </blockquote>
                            </div>
                        </div>
                    </div>

                    <div class="item">
                        <div class="block-33 h-100"
                            style="background: #1a0a0a; padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.3); border: 1px solid rgba(220,20,60,0.15); transition: all 0.3s ease;">
                            <div class="vcard d-flex mb-3">
                                <div class="image align-self-center"><img
                                        src="{{ asset('cardboard-assets/img/person_1.jpg') }}" alt="Fan"
                                        style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid #dc143c;">
                                </div>
                                <div class="name-text align-self-center ml-3">
                                    <h2 class="heading" style="color: #fff; margin: 0; font-size: 1.1rem;">Sarah J.</h2>
                                    <span class="meta" style="color: #dc143c; font-size: 0.9rem;">Pop Culture
                                        Critic</span>
                                </div>
                            </div>
                            <div class="text">
                                <blockquote
                                    style="color: #aaa; border-left: 3px solid #dc143c; padding-left: 15px; margin: 0; line-height: 1.7;">
                                    <p>&rdquo; The interface is as elegant as the concerts they host. Finding Taylor Swift
                                        tickets was a
                                        breeze! &ldquo;</p>
                                </blockquote>
                            </div>
                        </div>
                    </div>

                    <div class="item">
                        <div class="block-33 h-100"
                            style="background: #1a0a0a; padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.3); border: 1px solid rgba(220,20,60,0.15); transition: all 0.3s ease;">
                            <div class="vcard d-flex mb-3">
                                <div class="image align-self-center"><img
                                        src="{{ asset('cardboard-assets/img/person_2.jpg') }}" alt="Fan"
                                        style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid #dc143c;">
                                </div>
                                <div class="name-text align-self-center ml-3">
                                    <h2 class="heading" style="color: #fff; margin: 0; font-size: 1.1rem;">James L.</h2>
                                    <span class="meta" style="color: #dc143c; font-size: 0.9rem;">Rock Fan</span>
                                </div>
                            </div>
                            <div class="text">
                                <blockquote
                                    style="color: #aaa; border-left: 3px solid #dc143c; padding-left: 15px; margin: 0; line-height: 1.7;">
                                    <p>&rdquo; Arctic Monkeys in Jakarta was a dream come true, and TIXLY helped me get
                                        front-row access
                                        without any hassle. &ldquo;</p>
                                </blockquote>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- END testimonials -->
    </div>
@endsection


@section('ExtraJS2')
<script>
    let currentBannerIndex = 0;
    const bannerSlider = document.getElementById('bannerSlider');
    const bannerDots = document.querySelectorAll('.banner-dot');
    const totalBanners = {{ $banners->count() }};
    let bannerInterval;

    function updateBannerSlider() {
        if (!bannerSlider) return;
        bannerSlider.style.transform = `translateX(-${currentBannerIndex * 100}%)`;
        
        // Update dots
        bannerDots.forEach((dot, index) => {
            if (index === currentBannerIndex) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });
    }

    function moveBanner(direction) {
        currentBannerIndex += direction;
        if (currentBannerIndex >= totalBanners) {
            currentBannerIndex = 0;
        } else if (currentBannerIndex < 0) {
            currentBannerIndex = totalBanners - 1;
        }
        updateBannerSlider();
        if (typeof bannerInterval !== 'undefined') resetBannerInterval();
    }

    function goToBanner(index) {
        currentBannerIndex = index;
        updateBannerSlider();
        if (typeof bannerInterval !== 'undefined') resetBannerInterval();
    }

    function resetBannerInterval() {
        if (typeof bannerInterval !== 'undefined') clearInterval(bannerInterval);
        if (totalBanners > 1) {
            bannerInterval = setInterval(() => {
                moveBanner(1);
            }, 5000);
        }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        if (totalBanners > 1) {
            resetBannerInterval();
        }
    });
</script>
@endsection
