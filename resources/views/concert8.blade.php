@extends('layouts.headerconcert.masterconcert')

@section('title', (isset($event) ? $event->title : 'Billie Eilish') . ' | Hit Me Hard and Soft Tour Jakarta')
@section('meta_description', isset($event) ? $event->description : 'Billie Eilish Hit Me Hard and Soft Tour Live in Jakarta')

@section('ExtraCSS')
<style>
    .overlay::before {
        background: linear-gradient(45deg, rgba(0, 0, 0, 0.85), rgba(30, 144, 255, 0.15)) !important;
    }

    .boxed-btn3 {
        background: transparent !important;
        border: 1px solid #1e90ff !important;
        color: #1e90ff !important;
        transition: all 0.3s ease;
    }
    
    .boxed-btn3:hover {
        background: #1e90ff !important;
        color: #fff !important;
    }
</style>
@endsection

@section('banner_url', (isset($event) && $event->banner_url) ? Storage::url($event->banner_url) : 'https://images.unsplash.com/photo-1501612722273-30ad506f52de?auto=format&fit=crop&q=80&w=2070')

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
                            <span class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".3s">{{ isset($event) ? $event->date->format('d M, Y') : 'August 18, 2027' }}</span>
                            <h3 class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".4s">{{ $event->title ?? 'Hit Me Hard and Soft Tour Jakarta' }}</h3>
                            <p class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".5s">{{ $event->location ?? 'Indonesia Arena, Jakarta' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- slider_area_end -->

    <!-- performar_area_start  -->
    <div id="performer" class="performar_area black_bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section_title mb-80">
                        <h3 class="wow fadeInRight text-light" data-wow-duration="1s" data-wow-delay=".3s">The Artist</h3>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="single_performer wow fadeInUp" data-wow-duration="1s" data-wow-delay=".3s">
                        <div data-tilt class="thumb text-center">
                             <img src="https://images.unsplash.com/photo-1574169208507-84376144848b?auto=format&fit=crop&q=80&w=600" alt="Billie Eilish" class="img-fluid" style="border-radius: 10px;">
                        </div>
                        <div class="performer_heading text-center mt-4">
                            <h4 class="text-light">Billie Eilish</h4>
                            <span style="color: #1e90ff;">Hit Me Hard and Soft Tour</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- about_area_start  -->
    <div id="about" class="about_area black_bg py-5">
        <div class="container text-center">
            <div class="section_title mb-80">
                <h3 class="wow fadeInRight text-light">{{ $event->title ?? 'Hit Me Hard and Soft' }}</h3>
                <p class="text-white opacity-75">{{ $event->description ?? 'Billie Eilish brings her emotional and powerful performances back to Jakarta.' }}</p>
            </div>
        </div>
    </div>

    <!-- Venue Area -->
    <div id="venue" class="map_area pt-120">
         <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="section_title mb-80 text-light">
                        <h3>Venue</h3>
                    </div>
                </div>
            </div>
        </div>
        <div id="map" style="height: 600px; position: relative; overflow: hidden;">
             <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.4529322676063!2d106.80001!3d-6.2185!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f14d3066042b%3A0x6b42b6d194565735!2sGelora%20Bung%20Karno%20Main%20Stadium!5e0!3m2!1sen!2sid!4v1653556050510!5m2!1sen!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
@endsection

@php
    $footerDate = isset($event) ? $event->date->format('d M, Y') : 'Aug 18, 2027';
    $footerLocation = $event->location ?? 'GBK, Jakarta';
    $footerLocationClass = 'text-info';
    $footerSlogan = 'Hit Me Hard and Soft.';
    $footerButtonLink = route('ticket', ['type' => 'concert8']);
    $footerButtonText = 'Get Your Ticket';
    $footerCopyright = ($event->title ?? 'Billie Eilish') . ' | Hit Me Hard and Soft Tour Jakarta';
@endphp