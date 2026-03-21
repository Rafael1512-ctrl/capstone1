@extends('layouts.headerconcert.masterconcert')

@section('title', (isset($event) ? $event->title : 'Coldplay') . ' | Music Of The Spheres World Tour')
@section('meta_description', isset($event) ? $event->description : 'Coldplay Live in Jakarta')

@section('ExtraCSS')
<style>
    /* Coldplay Unique Vibrant Vibe */
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
@endsection

@section('banner_url', (isset($event) && $event->banner_url) ? Storage::url($event->banner_url) : 'https://images.unsplash.com/photo-1493225255756-d9584f8606e9?auto=format&fit=crop&q=80&w=2070')

@section('content')
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
                        <h3 class="wow fadeInRight text-white" data-wow-duration="1s" data-wow-delay=".3s">The Members</h3>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="single_performer wow fadeInUp" data-wow-duration="1s" data-wow-delay=".3s">
                                <div data-tilt class="thumb">
                                    <img src="https://images.unsplash.com/photo-1516280440614-37939bbacd81?auto=format&fit=crop&q=80&w=400" alt="">
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
                                    <img src="https://images.unsplash.com/photo-1514525253361-b83f859b71c0?auto=format&fit=crop&q=80&w=400" alt="">
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
                                    <img src="https://images.unsplash.com/photo-1498038432885-c6f3f1b912ee?auto=format&fit=crop&q=80&w=400" alt="">
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
                                    <img src="https://images.unsplash.com/photo-1453090927415-5f45085b65c0?auto=format&fit=crop&q=80&w=400" alt="">
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
                        <h3 class="wow fadeInRight text-white" data-wow-duration="1s" data-wow-delay=".3s">A Galactic Celebration</h3>
                        <p class="wow fadeInRight text-info" data-wow-duration="1s" data-wow-delay=".4s">Music Of The Spheres is more than a tour—it's an interactive journey across the stars. From the kinetic dancefloors to the floating moons, every note is a heartbeat.</p>
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
                            <img src="https://images.unsplash.com/photo-1508700115892-45ecd05ae2ad?auto=format&fit=crop&q=80&w=1000" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-6">
                    <div class="about_info pl-68">
                        <h4 class=" wow fadeInLeft text-white" data-wow-duration="1s" data-wow-delay=".5s">Lighting up the Sky</h4>
                        <p class=" wow fadeInLeft text-info" data-wow-duration="1s" data-wow-delay=".6s">Experience the legendary Xyloband light show, where the entire stadium becomes part of the band. A symphony of light, sound, and pure emotion.</p>
                        <a href="#" class="boxed-btn3  wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".7s">Buy Planet Ticket</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- about_area_end  -->

    <!-- Sustainability Area (Unique for Coldplay) -->
    <div id="sustainability" class="program_details_area detials_bg_1 overlay2" style="background-image: url('https://images.unsplash.com/photo-1502082553048-f009c37129b9?auto=format&fit=crop&q=80&w=1920');">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section_title text-center mb-80  wow fadeInRight" data-wow-duration="1s" data-wow-delay=".3s">
                        <h3 class="text-white">Eco-Friendly Experience</h3>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center text-white">
                    <p class="lead mb-5">This tour is powered by renewable energy. From kinetic floors that generate power as you dance to plant-based confetti.</p>
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
    <div id="venue" class="map_area pt-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="section_title text-center mb-80">
                        <h3 class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".3s">The Venue</h3>
                    </div>
                </div>
            </div>
        </div>
        <div id="map" style="height: 600px; position: relative; overflow: hidden;">
             <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.4529322676063!2d106.80001!3d-6.2185!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f14d3066042b%3A0x6b42b6d194565735!2sGelora%20Bung%20Karno%20Main%20Stadium!5e0!3m2!1sen!2sid!4v1653556050510!5m2!1sen!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
    <!-- map_area_end  -->
@endsection

@php
    $footerDate = isset($event) ? $event->date->format('d M, Y') : '15 Nov, 2026';
    $footerLocation = $event->location ?? 'GBK, Jakarta';
    $footerLocationClass = 'text-info';
    $footerSlogan = 'Believe in Love.';
    $footerButtonLink = route('ticket', ['type' => 'concert2']);
    $footerButtonText = 'Get Your Ticket';
    $footerCopyright = ($event->title ?? 'Coldplay') . ' Official Tours';
@endphp