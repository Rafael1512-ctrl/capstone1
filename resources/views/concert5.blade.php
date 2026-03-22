@extends('layouts.headerconcert.masterconcert')

@section('title', (isset($event) ? $event->title : 'Bruno Mars') . ' | 24K Magic World Tour Jakarta')
@section('meta_description', isset($event) ? $event->description : 'Bruno Mars 24K Magic World Tour Live in Jakarta')

@section('ExtraCSS')
<style>
    .overlay::before {
        background: linear-gradient(45deg, rgba(0, 0, 0, 0.9), rgba(10, 5, 0, 0.7)) !important;
    }

    .boxed-btn3 {
        background: transparent !important;
        border: 1px solid #d4af37 !important;
        color: #d4af37 !important;
        transition: all 0.3s ease;
    }
    
    .boxed-btn3:hover {
        background: #d4af37 !important;
        color: #000 !important;
    }
</style>
@endsection

@section('banner_url', (isset($event) && $event->banner_url) ? Storage::url($event->banner_url) : 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?auto=format&fit=crop&q=80&w=2070')

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
                            <span class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".3s">{{ isset($event) ? $event->date->format('d M, Y') : 'November 10, 2027' }}</span>
                            <h3 class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".4s">{{ $event->title ?? '24K Magic World Tour Jakarta' }}</h3>
                            <p class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".5s">{{ $event->location ?? 'Jakarta International Stadium' }}</p>
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
                             <img src="https://images.unsplash.com/photo-1493225255756-d9584f8606e9?auto=format&fit=crop&q=80&w=600" alt="Bruno Mars" class="img-fluid" style="border-radius: 10px;">
                        </div>
                        <div class="performer_heading text-center mt-4">
                            <h4 class="text-light">Bruno Mars</h4>
                            <span style="color: #d4af37;">24K Magic Performance</span>
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
                <h3 class="wow fadeInRight text-light">{{ $event->title ?? 'Funk, Soul, and Magic' }}</h3>
                <p class="text-white opacity-75">{{ $event->description ?? 'Get ready for a night of pure gold. Bruno Mars is bringing 24K Magic to Jakarta.' }}</p>
            </div>
        </div>
    </div>

    <!-- Venue Area -->
    <div id="venue" class="map_area pt-120">
         <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="section_title mb-80 text-light">
                        <h3>Venue & Location</h3>
                    </div>
                </div>
            </div>
        </div>
        <div id="map" style="height: 600px; position: relative; overflow: hidden;">
             <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3967.103046755606!2d106.86214371476856!3d-6.123545595565545!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6a1e5080000001%3A0xd653f53835f8386b!2sJakarta%20International%20Stadium!5e0!3m2!1sen!2sid!4v1653556050510!5m2!1sen!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
@endsection

@php
    $footerDate = isset($event) ? $event->date->format('d M, Y') : 'Nov 10, 2027';
    $footerLocation = $event->location ?? 'JIS, Jakarta';
    $footerLocationClass = 'text-warning';
    $footerSlogan = 'Show them your magic.';
    $footerButtonLink = route('ticket', ['type' => 'concert5']);
    $footerButtonText = 'Get Your Ticket';
    $footerCopyright = ($event->title ?? 'Bruno Mars') . ' | Official Tour Jakarta';
@endphp