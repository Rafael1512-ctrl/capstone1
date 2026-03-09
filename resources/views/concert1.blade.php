<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Radiohead | A Heart-Shaped Void World Tour</title>
    <meta name="description" content="Radiohead Live in Jakarta - Experience the magic of their soundscapes.">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- <link rel="manifest" href="site.webmanifest"> -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('concert-assets/img/favicon.png') }}">
    <!-- Place favicon.ico in the root directory -->

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
    <!-- <link rel="stylesheet" href="{{ asset('concert-assets/css/responsive.css') }}"> -->
    <style>
        .slider_bg_1 {
            background-image: url("https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&q=80&w=1974&ixlib=rb-4.0.3");
            background-size: cover;
            background-position: center;
        }

        /* Radiohead Unique Glitch Effect */
        .glitch-text {
            position: relative;
            color: white;
            mix-blend-mode: exclusion;
        }

        .glitch-text::before,
        .glitch-text::after {
            content: attr(data-text);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .glitch-text::before {
            left: 2px;
            text-shadow: -2px 0 #ff00c1;
            clip: rect(44px, 450px, 56px, 0);
            animation: glitch-anim 5s infinite linear alternate-reverse;
        }

        .glitch-text::after {
            left: -2px;
            text-shadow: -2px 0 #00fff9, 2px 2px #ff00c1;
            animation: glitch-anim2 1s infinite linear alternate-reverse;
        }

        @keyframes glitch-anim {
            0% {
                clip: rect(31px, 9999px, 94px, 0);
            }

            20% {
                clip: rect(62px, 9999px, 42px, 0);
            }

            40% {
                clip: rect(16px, 9999px, 78px, 0);
            }

            60% {
                clip: rect(60px, 9999px, 84px, 0);
            }

            80% {
                clip: rect(89px, 9999px, 86px, 0);
            }

            100% {
                clip: rect(50px, 9999px, 20px, 0);
            }
        }
    </style>
</head>

<body>
    <!--[if lte IE 9]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
        <![endif]-->

    <!-- header-start -->
    <header>
        <div class="header-area ">
            <div id="sticky-header" class="main-header-area">
                <div class="container">
                    <div class="header_bottom_border">
                        <div class="row align-items-center">
                            <div class="col-xl-3 col-lg-3">
                                <div class="logo">
                                    <a href="{{ route('concert1') }}">
                                        <h2 class="glitch-text" data-text="RH."
                                            style="color: white; font-weight: bold; margin-bottom: 0;">RH.</h2>
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
                                    <div class="book_btn d-none d-lg-block">
                                        <a href="#">Join Waitlist</a>
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
                            <span class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".3s">20 Nov, 2026</span>
                            <h3 class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".4s">Radiohead LIVE</h3>
                            <p class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".5s">Jakarta International
                                Stadium (JIS), Indonesia</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- slider_area_end -->

    <!-- performar_area_start  -->
    <div id="performers" class="performar_area black_bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section_title mb-80">
                        <h3 class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".3s">The Band</h3>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="single_performer wow fadeInUp" data-wow-duration="1s" data-wow-delay=".3s">
                                <div data-tilt class="thumb">
                                    <img src="https://images.unsplash.com/photo-1549412650-ef3bb78330fc?auto=format&fit=crop&q=80&w=400"
                                        alt="">
                                </div>
                                <div class="performer_heading">
                                    <h4>Thom Yorke</h4>
                                    <span>Vocals / Keys / Guitar</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="single_performer wow fadeInUp" data-wow-duration="1s" data-wow-delay=".4s">
                                <div data-tilt class="thumb">
                                    <img src="https://images.unsplash.com/photo-1510915361894-db8b60106cb1?auto=format&fit=crop&q=80&w=400"
                                        alt="">
                                </div>
                                <div class="performer_heading">
                                    <h4>Jonny Greenwood</h4>
                                    <span>Lead Guitar / Keyboard</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="single_performer wow fadeInUp" data-wow-duration="1s" data-wow-delay=".5s">
                                <div data-tilt class="thumb">
                                    <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&q=80&w=400"
                                        alt="">
                                </div>
                                <div class="performer_heading">
                                    <h4>Ed O'Brien</h4>
                                    <span>Rhythm Guitar / Backing Vocals</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="single_performer wow fadeInUp" data-wow-duration="1s" data-wow-delay=".6s">
                                <div data-tilt class="thumb">
                                    <img src="https://images.unsplash.com/photo-1524368535928-5b5e00ddc76b?auto=format&fit=crop&q=80&w=400"
                                        alt="">
                                </div>
                                <div class="performer_heading">
                                    <h4>Philip Selway</h4>
                                    <span>Drums / Percussion</span>
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
    <div id="about" class="about_area black_bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="section_title text-center mb-80">
                        <h3 class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".3s">A Heart-Shaped Void</h3>
                        <p class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".4s">Radiohead returns with
                            their most ambitious world tour yet. Merging immersive visual art with their groundbreaking
                            sonic landscapes, this isn't just a concert—it's a descent into the sublime.</p>
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
                            <img src="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&q=80&w=1000"
                                alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-6">
                    <div class="about_info pl-68">
                        <h4 class=" wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".5s">Experience it live in
                            Jakarta</h4>
                        <p class=" wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".6s">After years of
                            anticipation, the Oxford quintessential art-rockers are finally landing on Indonesian soil.
                            With a setlist spanning three decades of mastery, from OK Computer to A Moon Shaped Pool.
                        </p>
                        <a href="#" class="boxed-btn3  wow fadeInLeft" data-wow-duration="1s"
                            data-wow-delay=".7s">Waitlist Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- about_area_end  -->

    <div id="program" class="program_details_area detials_bg_1 overlay2">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section_title text-center mb-80  wow fadeInRight" data-wow-duration="1s"
                        data-wow-delay=".3s">
                        <h3>Setlist Expectations</h3>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="program_detail_wrap">
                        <div class="single_propram">
                            <div class="inner_wrap">
                                <div class="circle_img"></div>
                                <div class="porgram_top">
                                    <span class=" wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".3s">07:00
                                        PM</span>
                                    <h4 class=" wow fadeInUp" data-wow-duration="1s" data-wow-delay=".4s">Doors Open
                                    </h4>
                                </div>
                                <div class="thumb wow fadeInUp" data-wow-duration="1s" data-wow-delay=".5s">
                                    <img src="https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&q=80&w=400"
                                        alt="">
                                </div>
                                <h4 class="wow fadeInUp" data-wow-duration="1s" data-wow-delay=".6s">Preshow Ambience
                                </h4>
                            </div>
                        </div>
                        <div class="single_propram">
                            <div class="inner_wrap">
                                <div class="circle_img"></div>
                                <div class="porgram_top">
                                    <span class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".3s">08:00
                                        PM</span>
                                    <h4 class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".4s">Opening Act
                                    </h4>
                                </div>
                                <div class="thumb wow fadeInRight" data-wow-duration="1s" data-wow-delay=".5s">
                                    <img src="https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?auto=format&fit=crop&q=80&w=400"
                                        alt="">
                                </div>
                                <h4 class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".6s">The Smile
                                    (Special Guest)</h4>
                            </div>
                        </div>
                        <div class="single_propram">
                            <div class="inner_wrap">
                                <div class="circle_img"></div>
                                <div class="porgram_top">
                                    <span class=" wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".3s">09:15
                                        PM</span>
                                    <h4 class=" wow fadeInUp" data-wow-duration="1s" data-wow-delay=".4s">Radiohead</h4>
                                </div>
                                <div class="thumb  wow fadeInUp" data-wow-duration="1s" data-wow-delay=".5s">
                                    <img src="https://images.unsplash.com/photo-1501612722273-30ad506f52de?auto=format&fit=crop&q=80&w=400"
                                        alt="">
                                </div>
                                <h4 class=" wow fadeInUp" data-wow-duration="1s" data-wow-delay=".6s">Main Set
                                    Performance</h4>
                            </div>
                        </div>
                        <div class="single_propram">
                            <div class="inner_wrap">
                                <div class="circle_img"></div>
                                <div class="porgram_top">
                                    <span class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".3s">11:00
                                        PM</span>
                                    <h4 class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".4s">Encore</h4>
                                </div>
                                <div class="thumb wow fadeInRight" data-wow-duration="1s" data-wow-delay=".5s">
                                    <img src="https://images.unsplash.com/photo-1514525253361-b83f859b71c0?auto=format&fit=crop&q=80&w=400"
                                        alt="">
                                </div>
                                <h4 class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".6s">Closing Rituals
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- map_area_start  -->
    <div id="venue" class="map_area">
        <div id="map" style="height: 600px; position: relative; overflow: hidden;">

        </div>
        <script>
            function initMap() {
                var uluru = {
                    lat: -6.1245,
                    lng: 106.8211
                };
                var grayStyles = [{
                    featureType: "all",
                    stylers: [{
                        saturation: -90
                    },
                    {
                        lightness: 50
                    }
                    ]
                },
                {
                    elementType: 'labels.text.fill',
                    stylers: [{
                        color: '#ccdee9'
                    }]
                }
                ];
                var map = new google.maps.Map(document.getElementById('map'), {
                    center: uluru,
                    zoom: 15,
                    styles: grayStyles,
                    scrollwheel: false
                });
                var marker = new google.maps.Marker({
                    position: uluru,
                    map: map,
                    title: 'Jakarta International Stadium'
                });
            }
        </script>
        <script
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDpfS1oRGreGSBU5HHjMmQ3o5NLw7VdJ6I&amp;callback=initMap">
            </script>
        <div class="location_information black_bg wow fadeInUp" data-wow-duration="1s" data-wow-delay=".3s">
            <h3>JIS - Jakarta</h3>
            <div class="info_wrap">
                <div class="single_info">
                    <span>Venue:</span>
                    <p>Papanggo, Tj. Priok <br>Jakarta Utara, 14340</p>
                </div>
                <div class="single_info">
                    <span>Phone:</span>
                    <p>+62 (21) 123 4567</p>
                </div>
                <div class="single_info">
                    <span>Email:</span>
                    <p>tours@radiohead.com</p>
                </div>
            </div>
        </div>
    </div>
    <!-- map_area_end  -->

    <!-- brand_area_start  -->
    <div class="brand_area black_bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section_title text-center mb-80">
                        <h4 class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".3s">Tour Partners</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="brand_wrap">
                        <div class="brand_active owl-carousel">
                            <div class="single_brand text-center">
                                <img src="{{ asset('concert-assets/img/brand/1.png') }}" alt="">
                            </div>
                            <div class="single_brand text-center">
                                <img src="{{ asset('concert-assets/img/brand/2.png') }}" alt="">
                            </div>
                            <div class="single_brand text-center">
                                <img src="{{ asset('concert-assets/img/brand/3.png') }}" alt="">
                            </div>
                            <div class="single_brand text-center">
                                <img src="{{ asset('concert-assets/img/brand/4.png') }}" alt="">
                            </div>
                            <div class="single_brand text-center">
                                <img src="{{ asset('concert-assets/img/brand/5.png') }}" alt="">
                            </div>
                            <div class="single_brand text-center">
                                <img src="{{ asset('concert-assets/img/brand/1.png') }}" alt="">
                            </div>
                            <div class="single_brand text-center">
                                <img src="{{ asset('concert-assets/img/brand/2.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- brand_area_end  -->
    <!-- footer_start  -->
    <footer class="footer">
        <div class="footer_top">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-8">
                        <div class="footer_widget">
                            <div class="address_details text-center">
                                <h4 class="wow fadeInUp" data-wow-duration="1s" data-wow-delay=".3s">20 Nov, 2026</h4>
                                <h3 class="wow fadeInUp" data-wow-duration="1s" data-wow-delay=".4s">JIS, Jakarta</h3>
                                <p class="wow fadeInUp" data-wow-duration="1s" data-wow-delay=".5s">Everything in its
                                    right place.</p>
                                <a href="#" class="boxed-btn3 wow fadeInUp" data-wow-duration="1s"
                                    data-wow-delay=".6s">Stay Notified</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="copy-right_text">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <p class="copy_right text-center wow fadeInDown" data-wow-duration="1s" data-wow-delay=".5s">
                            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                            Copyright &copy;
                            <script>document.write(new Date().getFullYear());</script> Radiohead Official Tours. All
                            rights reserved.
                            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
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

    <!--contact js-->
    <script src="{{ asset('concert-assets/js/contact.js') }}"></script>
    <script src="{{ asset('concert-assets/js/jquery.ajaxchimp.min.js') }}"></script>
    <script src="{{ asset('concert-assets/js/jquery.form.js') }}"></script>
    <script src="{{ asset('concert-assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('concert-assets/js/mail-script.js') }}"></script>

    <script src="{{ asset('concert-assets/js/main.js') }}"></script>
</body>

</html>