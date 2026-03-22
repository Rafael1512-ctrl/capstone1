@extends('layouts.headerconcert.masterconcert')

@section('title', (isset($event) ? $event->title : 'Radiohead') . ' | A Heart-Shaped Void World Tour')
@section('meta_description', isset($event) ? $event->description : 'Radiohead Live in Jakarta')

@section('ExtraCSS')
<style>
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
        0% { clip: rect(31px, 9999px, 94px, 0); }
        20% { clip: rect(62px, 9999px, 42px, 0); }
        40% { clip: rect(16px, 9999px, 78px, 0); }
        60% { clip: rect(60px, 9999px, 84px, 0); }
        80% { clip: rect(89px, 9999px, 86px, 0); }
        100% { clip: rect(50px, 9999px, 20px, 0); }
    }
</style>
@endsection

@section('banner_url', (isset($event) && $event->banner_url) ? Storage::url($event->banner_url) : 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&q=80&w=1974')

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
                            <span class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".3s">{{ isset($event) ? $event->date->format('d M, Y') : '20 Nov, 2026' }}</span>
                            <h3 class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".4s">{{ $event->title ?? 'Radiohead LIVE' }}</h3>
                            <p class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".5s">{{ $event->location ?? 'Jakarta International Stadium (JIS), Indonesia' }}</p>
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
                                    <img src="https://images.unsplash.com/photo-1549412650-ef3bb78330fc?auto=format&fit=crop&q=80&w=400" alt="">
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
                                    <img src="https://images.unsplash.com/photo-1510915361894-db8b60106cb1?auto=format&fit=crop&q=80&w=400" alt="">
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
                                    <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&q=80&w=400" alt="">
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
                                    <img src="https://images.unsplash.com/photo-1524368535928-5b5e00ddc76b?auto=format&fit=crop&q=80&w=400" alt="">
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
                        <h3 class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".3s">The Experience</h3>
                        <p class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".4s">Radiohead returns to Jakarta for a one-night-only performance that will redefine the concert experience. Prepare for a journey through the haunting melodies of "A Heart-Shaped Void".</p>
                    </div>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-7 col-md-6">
                    <div class="about_thumb">
                        <div class="thumb_inner  wow fadeInUp" data-wow-duration="1s" data-wow-delay=".3s">
                            <img src="https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?auto=format&fit=crop&q=80&w=800" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-6">
                    <div class="about_info">
                        <h4 class="wow fadeInUp" data-wow-duration="1s" data-wow-delay=".5s">Exclusive Merchandise</h4>
                        <p class="wow fadeInUp" data-wow-duration="1s" data-wow-delay=".6s">Early access to official tour merchandise will be available for VIP ticket holders. Limited edition prints and vinyl will be available on-site.</p>
                        <a href="#" class="boxed-btn3 wow fadeInUp" data-wow-duration="1s" data-wow-delay=".7s">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- about_area_end  -->

    <!-- venue_area_start  -->
    <div id="venue" class="venue_area black_bg pt-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="section_title text-center mb-80">
                        <h3 class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".3s">The Venue</h3>
                        <p class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".4s">Jakarta International Stadium (JIS) will provide the perfect backdrop for Radiohead's sonic landscapes with its state-of-the-art acoustics.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="venue_map wow fadeInUp" data-wow-duration="1s" data-wow-delay=".5s">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3967.103046755606!2d106.86214371476856!3d-6.123545595565545!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6a1e5080000001%3A0xd653f53835f8386b!2sJakarta%20International%20Stadium!5e0!3m2!1sen!2sid!4v1653556050510!5m2!1sen!2sid" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- venue_area_end  -->
@endsection

@php
    $footerDate = isset($event) ? $event->date->format('d M, Y') : '20 Nov, 2026';
    $footerLocation = $event->location ?? 'Jakarta International Stadium';
    $footerButtonLink = route('ticket', ['type' => 'concert1']);
    $footerButtonText = 'Get Your Ticket';
    $footerCopyright = 'LuxTix';
@endphp