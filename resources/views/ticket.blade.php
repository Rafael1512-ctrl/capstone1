<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Ticket Details | Buy Your Concert Tickets</title>
    <meta name="description" content="Get your tickets now.">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('concert-assets/img/favicon.png') }}">

    <!-- CSS here -->
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
    <style>
        .ticket_bg {
            background-color: #0c0c0c;
            padding: 100px 0;
            color: #fff;
        }
        .ticket-card {
            background-color: #1a1a1a;
            border-radius: 10px;
            padding: 40px;
            margin-bottom: 30px;
            border: 1px solid #333;
            transition: 0.3s;
        }
        .ticket-card:hover {
            border-color: #ff00c1;
            transform: translateY(-5px);
        }
        .ticket-price {
            font-size: 36px;
            font-weight: bold;
            color: #ff00c1;
            margin-bottom: 15px;
        }
        .ticket-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .ticket-features {
            list-style: none;
            padding: 0;
            margin-bottom: 30px;
        }
        .ticket-features li {
            margin-bottom: 10px;
            color: #aaa;
        }
        .ticket-features li i {
            color: #ff00c1;
            margin-right: 10px;
        }
        .buy-btn {
            background-color: #ff00c1;
            color: white;
            padding: 12px 35px;
            border-radius: 30px;
            text-transform: uppercase;
            font-weight: bold;
            display: inline-block;
            transition: 0.3s;
            text-decoration: none;
        }
        .buy-btn:hover {
            background-color: #fff;
            color: #ff00c1;
            text-decoration: none;
        }
        .important-info-box {
            background-color: #111;
            border-left: 5px solid #ff00c1;
            padding: 30px;
            border-radius: 5px;
        }
        .important-info-box h4 {
            color: #fff;
            margin-bottom: 20px;
        }
        .important-info-box p {
            color: #bbb;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <!-- header-start -->
    @include('layouts.headerconcert.header')
    <!-- header-end -->

    <!-- bradcam_area_start -->
<<<<<<< HEAD
    <div class="bradcam_area" style="background-image: url('{{ $event->banner_url ? Storage::url($event->banner_url) : asset('cardboard-assets/img/concert_1.jpg') }}'); background-size: cover; background-position: center; padding: 180px 0; position: relative; z-index: 1;">
=======
    <div class="bradcam_area" style="background-image: url('{{ asset('cardboard-assets/img/' . $data['image']) }}'); background-size: cover; background-position: center; padding: 180px 0; position: relative; z-index: 1;">
>>>>>>> 2f5d83ff45da2a0b3e68ae99aade8a7880dd8a40
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="bradcam_text text-center wow fadeInUp" data-wow-duration="1s" data-wow-delay=".3s">
                        <span class="text-white text-uppercase" style="letter-spacing: 5px; font-weight: 600; margin-bottom: 20px; display: block;">Official Ticket Selection</span>
<<<<<<< HEAD
                        <h3 class="text-white font-weight-bold" style="font-size: 70px; font-family: 'DM Serif Display', serif;">{{ $event->title }}</h3>
                        <p class="text-white lead" style="font-size: 24px;"><i class="fa fa-calendar-o mr-2"></i> {{ $event->schedule_time ? $event->schedule_time->format('d M Y') : 'Date TBD' }} | <i class="fa fa-map-marker mr-2"></i> {{ $event->location }}</p>
=======
                        <h3 class="text-white font-weight-bold" style="font-size: 70px; font-family: 'DM Serif Display', serif;">{{ $data['title'] }}</h3>
                        <p class="text-white lead" style="font-size: 24px;"><i class="fa fa-calendar-o mr-2"></i> {{ $data['date'] }} | <i class="fa fa-map-marker mr-2"></i> {{ $data['location'] }}</p>
>>>>>>> 2f5d83ff45da2a0b3e68ae99aade8a7880dd8a40
                    </div>
                </div>
            </div>
        </div>
        <!-- overlay mask -->
        <div style="position: absolute; top:0; left:0; right:0; bottom:0; background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.9)); z-index:-1;"></div>
    </div>
    <!-- bradcam_area_end -->

    <!-- ticket_area_start -->
    <div class="ticket_bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="section_title text-center mb-80">
                        <span class="d-block text-primary text-uppercase mb-2" style="letter-spacing: 2px;">Premium Packages</span>
                        <h3 class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".3s" style="color:#fff; font-size: 45px;">Choose Your Experience</h3>
                        <div class="line mx-auto" style="width: 80px; height: 3px; background: #ff00c1; margin-top: 20px;"></div>
                    </div>
                </div>
            </div>
            <div class="row">
<<<<<<< HEAD
                @forelse($event->ticketTypes as $type)
                <!-- Dynamic Ticket Category -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="ticket-card text-center wow fadeInUp" data-wow-duration="1s" data-wow-delay="{{ 0.4 + ($loop->index * 0.1) }}s" 
                        @if(strtolower($type->name) == 'vip') style="border-color: #ff00c1; box-shadow: 0 10px 40px rgba(255, 0, 193, 0.15);" @endif>
                        
                        @if(strtolower($type->name) == 'vip')
                        <div style="position: absolute; top: -15px; left: 50%; transform: translateX(-50%); background: #ff00c1; color: white; padding: 5px 20px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase;">Most Popular</div>
                        @endif

                        <div class="ticket-title" @if(strtolower($type->name) == 'vip') style="color: #ff00c1;" @endif>{{ $type->name }}</div>
                        <div class="ticket-price">RP {{ number_format($type->price, 0, ',', '.') }}</div>
                        <p>Available Seats: {{ $type->availableStock() }} / {{ $type->quantity_total }}</p>
                        
                        <ul class="ticket-features text-left mt-4">
                            <li><i class="fa fa-check"></i> Standard entry for event</li>
                            <li><i class="fa fa-check"></i> Food & Beverage access</li>
                            @if(str_contains(strtolower($type->name), 'vip'))
                            <li><i class="fa fa-check"></i> Priority entry gate</li>
                            <li><i class="fa fa-check"></i> Free Welcome Drink</li>
                            @endif
                        </ul>
                        <a href="{{ route('public.checkout.show', [$event->event_id, $type->id]) }}" class="buy-btn mt-3">Select {{ $type->name }}</a>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center text-white py-5">
                    <h4>No ticket categories found for this event.</h4>
                </div>
                @endforelse
=======
                <!-- Regular Ticket -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="ticket-card text-center wow fadeInUp" data-wow-duration="1s" data-wow-delay=".4s">
                        <div class="ticket-title">General Admission</div>
                        <div class="ticket-price">RP {{ $data['reg'] }}</div>
                        <p>Enjoy the concert from the festival area with a great crowd vibe.</p>
                        <ul class="ticket-features text-left mt-4">
                            <li><i class="fa fa-check"></i> Standing area access</li>
                            <li><i class="fa fa-check"></i> Food & Beverage counter access</li>
                            <li><i class="fa fa-check"></i> Official Tour Poster (Digital)</li>
                        </ul>
                        <a href="{{ route('checkout', ['type' => request('type', 'concert1'), 'category' => 'reg']) }}" class="buy-btn mt-3">Select Ticket</a>
                    </div>
                </div>
                <!-- VIP Ticket -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="ticket-card text-center wow fadeInUp" data-wow-duration="1s" data-wow-delay=".5s" style="border-color: #ff00c1; box-shadow: 0 10px 40px rgba(255, 0, 193, 0.15);">
                        <div style="position: absolute; top: -15px; left: 50%; transform: translateX(-50%); background: #ff00c1; color: white; padding: 5px 20px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase;">Most Popular</div>
                        <div class="ticket-title" style="color: #ff00c1;">VIP Experience</div>
                        <div class="ticket-price">RP {{ $data['vip'] }}</div>
                        <p>Get closer to the stage and enjoy exclusive VIP perks.</p>
                        <ul class="ticket-features text-left mt-4">
                            <li><i class="fa fa-check"></i> Premium standing area (Front Row)</li>
                            <li><i class="fa fa-check"></i> Dedicated VIP entry gate</li>
                            <li><i class="fa fa-check"></i> Exclusive concert merchandise</li>
                            <li><i class="fa fa-check"></i> 1x Free Welcome Drink</li>
                        </ul>
                        <a href="{{ route('checkout', ['type' => request('type', 'concert1'), 'category' => 'vip']) }}" class="buy-btn mt-3">Select VIP</a>
                    </div>
                </div>
                <!-- VVIP Ticket -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="ticket-card text-center wow fadeInUp" data-wow-duration="1s" data-wow-delay=".6s">
                        <div class="ticket-title">VVIP Suite</div>
                        <div class="ticket-price">RP {{ $data['vvip'] }}</div>
                        <p>The ultimate luxury concert experience with full hospitality.</p>
                        <ul class="ticket-features text-left mt-4">
                            <li><i class="fa fa-check"></i> Private suite viewing area</li>
                            <li><i class="fa fa-check"></i> All-inclusive Food & Beverage</li>
                            <li><i class="fa fa-check"></i> Backstage tour pass (Limited)</li>
                            <li><i class="fa fa-check"></i> VIP Parking slot & Lounge</li>
                        </ul>
                        <a href="{{ route('checkout', ['type' => request('type', 'concert1'), 'category' => 'vvip']) }}" class="buy-btn mt-3">Select VVIP</a>
                    </div>
                </div>
>>>>>>> 2f5d83ff45da2a0b3e68ae99aade8a7880dd8a40
            </div>
        </div>
    </div>
    <!-- ticket_area_end -->

    <!-- Information Area -->
    <div class="about_area black_bg pb-5 pt-3">
        <div class="container">
            <div class="row pt-5 justify-content-center">
                <div class="col-lg-10">
                    <div class="important-info-box wow fadeInUp" data-wow-duration="1s" data-wow-delay=".4s">
                        <h4>Important Information</h4>
                        <p>1. Tickets are strictly non-refundable and non-transferable under any circumstances.</p>
                        <p>2. Please present your e-ticket along with a valid ID card matching the ticket name at the entrance.</p>
                        <p>3. Gates will open 2 hours prior to the show start time. Arrive early to avoid queues.</p>
                        <p>4. Professional cameras, recording devices, and outside food/beverages are strictly prohibited inside the venue.</p>
                        <p>5. The event organizer reserves the right to refuse entry or remove anyone violating the venue regulations without refund.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- footer_start  -->
    @include('layouts.headerconcert.footer', [
        'footerDate' => 'Get Your Tickets NOW',
        'footerLocation' => 'Limited Availability',
        'footerLocationClass' => 'text-warning',
        'footerSlogan' => 'Don\'t miss out on the greatest shows.',
        'footerSloganClass' => '',
        'footerButtonText' => 'Back to Home',
        'footerButtonLink' => route('home'),
        'footerCopyright' => 'Official Concert Tours. All rights reserved.'
    ])
    <!-- footer_end  -->

    <!-- JS here -->
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
    <script src="{{ asset('concert-assets/js/tilt.jquery.js') }}"></script>
    <script src="{{ asset('concert-assets/js/plugins.js') }}"></script>
    <script src="{{ asset('concert-assets/js/main.js') }}"></script>
</body>
</html>
