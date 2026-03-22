@extends('layouts.headerconcert.masterconcert')

@section('title', (isset($event) ? $event->title : 'Taylor Swift') . ' | The Eras Tour')
@section('meta_description', isset($event) ? $event->description : 'Taylor Swift Live in Jakarta')

@section('ExtraCSS')
<style>
    /* Taylor Swift Eras Tour Vibe */
    .cosmic-vibe {
        background: linear-gradient(to bottom, #f8fafc, #ffe4fa);
        position: relative;
        overflow: hidden;
    }

    .overlay::before {
        background: linear-gradient(45deg, rgba(255, 192, 203, 0.5), rgba(138, 43, 226, 0.4)) !important;
    }

    .boxed-btn3 {
        background: linear-gradient(90deg, #ffb6c1, #8a2be2) !important;
        border: none !important;
    }
</style>
@endsection

@section('banner_url', (isset($event) && $event->banner_url) ? Storage::url($event->banner_url) : 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&q=80&w=2070')

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
                            <span class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".3s">{{ isset($event) ? $event->date->format('d M, Y') : 'March 20, 2027' }}</span>
                            <h3 class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".4s">{{ $event->title ?? 'The Eras Tour Jakarta' }}</h3>
                            <p class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".5s">{{ $event->location ?? 'Gelora Bung Karno Main Stadium, Jakarta' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- slider_area_end -->

    <!-- performar_area_start  -->
    <div id="performer" class="performar_area cosmic-vibe">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section_title mb-80">
                        <h3 class="wow fadeInRight text-dark" data-wow-duration="1s" data-wow-delay=".3s">The Performer</h3>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="single_performer wow fadeInUp" data-wow-duration="1s" data-wow-delay=".3s">
                                <div data-tilt class="thumb">
                                     <img src="{{ asset('cardboard-assets/img/taylorswift.png') }}" alt="Taylor Swift" class="img-fluid">
                                </div>
                                <div class="performer_heading">
                                    <h4 class="text-dark">Taylor Swift</h4>
                                    <span class="text-info">Singer-Songwriter, 12x Grammy Winner</span>
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
                        <h3 class="wow fadeInRight text-dark" data-wow-duration="1s" data-wow-delay=".3s">{{ $event->title ?? 'A Journey Through Eras' }}</h3>
                        <p class="wow fadeInRight text-info" data-wow-duration="1s" data-wow-delay=".4s">{{ $event->description ?? 'The Eras Tour is a celebration of every iconic Taylor Swift album. Experience the magic, love stories, and special moments from Fearless to Midnights, all in one spectacular night.' }}</p>
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
                                alt="Taylor Swift Eras Tour">
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-6">
                    <div class="about_info pl-68">
                        <h4 class=" wow fadeInLeft text-dark" data-wow-duration="1s" data-wow-delay=".5s">Every Era, Every Song</h4>
                        <p class=" wow fadeInLeft text-info" data-wow-duration="1s" data-wow-delay=".6s">From country roots to pop anthems, witness Taylor's evolution live on stage. Costumes, visuals, and setlist inspired by each era.</p>
                        <a href="#program" class="boxed-btn3  wow fadeInLeft" data-wow-duration="1s"
                            data-wow-delay=".7s">See Setlist</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- about_area_end  -->

    <!-- Setlist & Fun Facts -->
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
                                    <span class=" wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".3s">07:00 PM</span>
                                    <h4 class=" wow fadeInUp" data-wow-duration="1s" data-wow-delay=".4s">Doors Open</h4>
                                </div>
                                <div class="thumb wow fadeInUp" data-wow-duration="1s" data-wow-delay=".5s">
                                    <img src="https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&q=80&w=400" alt="">
                                </div>
                                <h4 class="wow fadeInUp" data-wow-duration="1s" data-wow-delay=".6s">Preshow Ambience</h4>
                            </div>
                        </div>
                        <div class="single_propram">
                            <div class="inner_wrap">
                                <div class="circle_img"></div>
                                <div class="porgram_top">
                                    <span class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".3s">08:00 PM</span>
                                    <h4 class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".4s">Opening Act</h4>
                                </div>
                            </div>
                        </div>
                        <div class="single_propram">
                            <div class="inner_wrap">
                                <div class="circle_img"></div>
                                <div class="porgram_top">
                                    <span class=" wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".3s">09:15 PM</span>
                                    <h4 class=" wow fadeInUp" data-wow-duration="1s" data-wow-delay=".4s">Taylor Swift</h4>
                                </div>
                                <div class="thumb  wow fadeInUp" data-wow-duration="1s" data-wow-delay=".5s">
                                    <img src="https://images.unsplash.com/photo-1501612722273-30ad506f52de?auto=format&fit=crop&q=80&w=400" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="single_propram">
                            <div class="inner_wrap">
                                <div class="circle_img"></div>
                                <div class="porgram_top">
                                    <span class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".3s">11:00 PM</span>
                                    <h4 class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".4s">Encore</h4>
                                </div>
                                <div class="thumb wow fadeInRight" data-wow-duration="1s" data-wow-delay=".5s">
                                    <img src="https://images.unsplash.com/photo-1514525253361-b83f859b71c0?auto=format&fit=crop&q=80&w=400" alt="">
                                </div>
                            </div>
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
    $footerDate = isset($event) ? $event->date->format('d M, Y') : 'March 20, 2027';
    $footerLocation = $event->location ?? 'GBK Stadium, Jakarta';
    $footerLocationClass = 'text-info';
    $footerSlogan = 'Are you ready for it?';
    $footerButtonLink = route('ticket', ['type' => 'concert3']);
    $footerButtonText = 'Get Your Ticket';
    $footerCopyright = ($event->title ?? 'Taylor Swift') . ' | The Eras Tour Jakarta';
@endphp