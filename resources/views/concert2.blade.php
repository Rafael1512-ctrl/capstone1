<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $event->title ?? 'Coldplay' }} | Music Of The Spheres World Tour</title>
    <meta name="description" content="{{ $event->description ?? 'Coldplay Live in Jakarta' }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- <link rel="manifest" href="site.webmanifest"> -->
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
        /* Coldplay Unique Vibrant Vibe */
        .slider_bg_1 {
            background-image: url("{{ (isset($event) && $event->banner_url) ? Storage::url($event->banner_url) : 'https://images.unsplash.com/photo-1493225255756-d9584f8606e9?auto=format&fit=crop&q=80&w=2070' }}");
            background-size: cover;
            background-position: center;
        }

        .overlay::before {
            background: linear-gradient(45deg, rgba(82, 0, 255, 0.5), rgba(255, 0, 150, 0.4)) !important;
        }

        .boxed-btn3 {
            background: linear-gradient(90deg, #ff0095, #00d2ff) !important;
            border: none !important;
        }

        .section_title h3::after {
            background: linear-gradient(90deg, #00d2ff, #ff0095) !important;
        }

        /* Cosmic Particle effect (simulated via CSS) */
        .cosmic-vibe {
            background: linear-gradient(to bottom, #000428, #004e92);
            position: relative;
            overflow: hidden;
        }
    </style>
</head>

<body>
    <!-- header-start -->
    @include('layouts.headerconcert.header')
    <!-- header-end -->

    <!-- slider_area_start -->
    <div class="slider_area">
        <div class="single_slider  d-flex align-items-center slider_bg_1 overlay">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-xl-12">
                        <div class="slider_text text-center">
                            <div class="shape_1 wow fadeInUp" data-wow-duration="1s" data-wow-delay=".2s">
                                <img src="{{ asset('concert-assets/img/shape/shape_1.svg') }}" alt="">
                            </div>
                            <div class="shape_2 wow fadeInDown" data-wow-duration="1s" data-wow-delay=".2s">
                                <img src="{{ asset('concert-assets/img/shape/shape_2.svg') }}" alt="">
                            </div>
                            <span class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".3s">{{ isset($event) ? $event->date->format('d M, Y') : '15 Nov, 2026' }}</span>
                            <h3 class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".4s">{{ $event->title ?? 'Music Of The Spheres' }}</h3>
                            <p class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".5s">{{ $event->location ?? 'Gelora Bung Karno Stadium, Indonesia' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- slider_area_end -->

    <!-- performar_area_start  -->
    <div id="performers" class="performar_area cosmic-vibe">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section_title mb-80">
                        <h3 class="wow fadeInRight text-white" data-wow-duration="1s" data-wow-delay=".3s">The Members
                        </h3>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="single_performer wow fadeInUp" data-wow-duration="1s" data-wow-delay=".3s">
                                <div data-tilt class="thumb">
                                    <img src="https://images.unsplash.com/photo-1516280440614-37939bbacd81?auto=format&fit=crop&q=80&w=400"
                                        alt="">
                                </div>
                                <div class="performer_heading">
                                    <h4 class="text-white">Chris Martin</h4>
                                    <span class="text-info">Lead Vocals / Piano</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="single_performer wow fadeInUp" data-wow-duration="1s" data-wow-delay=".4s">
                                <div data-tilt class="thumb">
                                    <img src="https://images.unsplash.com/photo-1514525253361-b83f859b71c0?auto=format&fit=crop&q=80&w=400"
                                        alt="">
                                </div>
                                <div class="performer_heading">
                                    <h4 class="text-white">Jonny Buckland</h4>
                                    <span class="text-info">Lead Guitar</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="single_performer wow fadeInUp" data-wow-duration="1s" data-wow-delay=".5s">
                                <div data-tilt class="thumb">
                                    <img src="https://images.unsplash.com/photo-1498038432885-c6f3f1b912ee?auto=format&fit=crop&q=80&w=400"
                                        alt="">
                                </div>
                                <div class="performer_heading">
                                    <h4 class="text-white">Guy Berryman</h4>
                                    <span class="text-info">Bass Guitar</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="single_performer wow fadeInUp" data-wow-duration="1s" data-wow-delay=".6s">
                                <div data-tilt class="thumb">
                                    <img src="https://images.unsplash.com/photo-1453090927415-5f45085b65c0?auto=format&fit=crop&q=80&w=400"
                                        alt="">
                                </div>
                                <div class="performer_heading">
                                    <h4 class="text-white">Will Champion</h4>
                                    <span class="text-info">Drums / Backing Vocals</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- performar_area_end  -->

    <!-- about_area_start  -->
    <div id="about" class="about_area cosmic-vibe py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="section_title text-center mb-80">
                        <h3 class="wow fadeInRight text-white" data-wow-duration="1s" data-wow-delay=".3s">A Galactic
                            Celebration</h3>
                        <p class="wow fadeInRight text-info" data-wow-duration="1s" data-wow-delay=".4s">Music Of The
                            Spheres is more than a tour—it's an interactive journey across the stars. From the kinetic
                            dancefloors to the floating moons, every note is a heartbeat.</p>
                    </div>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-7 col-md-6">
                    <div class="about_thumb">
                        <div class="shap_3  wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".4s">
                            <img src="{{ asset('concert-assets/img/shape/shape_3.svg') }}" alt="">
                        </div>
                        <div class="thumb_inner  wow fadeInUp" data-wow-duration="1s" data-wow-delay=".3s">
                            <img src="https://images.unsplash.com/photo-1508700115892-45ecd05ae2ad?auto=format&fit=crop&q=80&w=1000"
                                alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-6">
                    <div class="about_info pl-68">
                        <h4 class=" wow fadeInLeft text-white" data-wow-duration="1s" data-wow-delay=".5s">Lighting up
                            the Sky</h4>
                        <p class=" wow fadeInLeft text-info" data-wow-duration="1s" data-wow-delay=".6s">Experience the
                            legendary Xyloband light show, where the entire stadium becomes part of the band. A symphony
                            of light, sound, and pure emotion.</p>
                        <a href="#" class="boxed-btn3  wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".7s">Buy
                            Planet Ticket</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- about_area_end  -->

    <!-- Sustainability Area (Unique for Coldplay) -->
    <div id="sustainability" class="program_details_area detials_bg_1 overlay2"
        style="background-image: url('https://images.unsplash.com/photo-1502082553048-f009c37129b9?auto=format&fit=crop&q=80&w=1920');">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section_title text-center mb-80  wow fadeInRight" data-wow-duration="1s"
                        data-wow-delay=".3s">
                        <h3 class="text-white">Eco-Friendly Experience</h3>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center text-white">
                    <p class="lead mb-5">This tour is powered by renewable energy. From kinetic floors that generate
                        power as you dance to plant-based confetti.</p>
                    <div class="row">
                        <div class="col-md-4">
                            <i class="fa fa-leaf fa-3x text-success mb-3"></i>
                            <h4>Low Carbon</h4>
                            <p>50% reduction in CO2 emissions compared to previous tours.</p>
                        </div>
                        <div class="col-md-4">
                            <i class="fa fa-bolt fa-3x text-warning mb-3"></i>
                            <h4>Kinetic Floors</h4>
                            <p>Your movement powers the stage lights!</p>
                        </div>
                        <div class="col-md-4">
                            <i class="fa fa-tree fa-3x text-info mb-3"></i>
                            <h4>One Tree Per Ticket</h4>
                            <p>A tree is planted for every ticket sold.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- map_area_start  -->
    <div id="venue" class="map_area">
        <div id="map" style="height: 600px; position: relative; overflow: hidden;"></div>
        <script>
            function initMap() {
                var gbk = { lat: -6.2183, lng: 106.8022 };
                var styles = [{ featureType: "all", stylers: [{ hue: "#00d2ff" }, { saturation: 20 }] }];
                var map = new google.maps.Map(document.getElementById('map'), {
                    center: gbk, zoom: 15, styles: styles, scrollwheel: false
                });
                new google.maps.Marker({ position: gbk, map: map, title: 'Gelora Bung Karno Stadium' });
            }
        </script>
        <script
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDpfS1oRGreGSBU5HHjMmQ3o5NLw7VdJ6I&callback=initMap"></script>
        <div class="location_information black_bg wow fadeInUp" data-wow-duration="1s" data-wow-delay=".3s">
            <h3 class="text-info">GBK Stadium</h3>
            <div class="info_wrap">
                <div class="single_info">
                    <span>Venue:</span>
                    <p>Gelora, Tanah Abang <br>Jakarta Pusat, 10270</p>
                </div>
                <div class="single_info">
                    <span>Phone:</span>
                    <p>+62 (21) 573 4070</p>
                </div>
                <div class="single_info">
                    <span>Email:</span>
                    <p>tours@coldplay.com</p>
                </div>
            </div>
        </div>
    </div>
    <!-- map_area_end  -->

    @include('layouts.headerconcert.footer', [
        'footerDate' => isset($event) ? $event->date->format('d M, Y') : '15 Nov, 2026',
        'footerLocation' => $event->location ?? 'GBK, Jakarta',
        'footerLocationClass' => 'text-info',
        'footerSlogan' => 'Believe in Love.',
        'footerSloganClass' => '',
        'footerButtonText' => 'Get Your Ticket',
        'footerButtonLink' => isset($event) ? route('orders.create', $event->id) : '#',
        'footerCopyright' => ($event->title ?? 'Coldplay') . ' Official Tours. All rights reserved.'
    ])

    <!-- JS here -->
    <script src="{{ asset('concert-assets/js/vendor/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('concert-assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('concert-assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('concert-assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('concert-assets/js/main.js') }}"></script>
</body>

</html>