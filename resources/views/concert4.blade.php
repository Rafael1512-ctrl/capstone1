@extends('layouts.headerconcert.masterconcert')

@section('title', (isset($event) ? $event->title : 'Arctic Monkeys') . ' | The Car World Tour')
@section('meta_description', isset($event) ? $event->description : 'Arctic Monkeys Live in Jakarta')

@section('ExtraCSS')
<style>
    /* Arctic Monkeys Dark Vibe */
    .cosmic-vibe {
        background: linear-gradient(to bottom, #1a1a1a, #2d2d2d);
        position: relative;
        overflow: hidden;
    }

    .overlay::before {
        background: linear-gradient(45deg, rgba(139, 0, 0, 0.5), rgba(32, 32, 32, 0.4)) !important;
    }

    .boxed-btn3 {
        background: linear-gradient(90deg, #8b0000, #1a1a1a) !important;
        border: 2px solid #dc143c !important;
    }
</style>
@endsection

@section('banner_url', (isset($event) && $event->banner_url) ? Storage::url($event->banner_url) : 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&q=80&w=2070')

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
                            <span class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".3s">{{ isset($event) ? $event->date->format('d M, Y') : 'May 25, 2027' }}</span>
                            <h3 class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".4s">{{ $event->title ?? 'The Car World Tour Jakarta' }}</h3>
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
                        <h3 class="wow fadeInRight text-light" data-wow-duration="1s" data-wow-delay=".3s">The Band</h3>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="single_performer wow fadeInUp" data-wow-duration="1s" data-wow-delay=".3s">
                                <div data-tilt class="thumb">
                                     <img src="https://images.unsplash.com/photo-1510915361894-db8b60106cb1?auto=format&fit=crop&q=80&w=600" alt="Arctic Monkeys" class="img-fluid" style="border-radius: 10px;">
                                </div>
                                <div class="performer_heading">
                                    <h4 class="text-light">Arctic Monkeys</h4>
                                    <span class="text-warning">British Rock Band - Formed 2002</span>
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
                        <h3 class="wow fadeInRight text-light" data-wow-duration="1s" data-wow-delay=".3s">{{ $event->title ?? 'Tranquility Base Hotel & Casino' }}</h3>
                        <p class="wow fadeInRight text-warning" data-wow-duration="1s" data-wow-delay=".4s">{{ $event->description ?? 'Arctic Monkeys returns with The Car, their latest masterpiece. A dark, introspective journey through synthesizers and profound lyrics.' }}</p>
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
                            <img src="https://images.unsplash.com/photo-1510915361894-db8b60106cb1?auto=format&fit=crop&q=80&w=1000"
                                alt="Arctic Monkeys The Car World Tour">
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-6">
                    <div class="about_info pl-68">
                        <h4 class=" wow fadeInLeft text-light" data-wow-duration="1s" data-wow-delay=".5s">A Return to Form</h4>
                        <p class=" wow fadeInLeft text-warning" data-wow-duration="1s" data-wow-delay=".6s">From AM to The Car - witness the evolution of modern rock. Expect classics, new tracks, and unforgettable live arrangements that showcase their growth as one of the decade's most important bands.</p>
                        <a href="#program" class="boxed-btn3  wow fadeInLeft" data-wow-duration="1s"
                            data-wow-delay=".7s">See Schedule</a>
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
                        <h3>Setlist Highlights</h3>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center text-white">
                <div class="col-lg-4">
                     <ul style="list-style-position: inside;">
                        <li>Do I Wanna Know?</li>
                        <li>Fluorescent Adolescent</li>
                        <li>R U Mine?</li>
                        <li>505</li>
                        <li>Bravado</li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <ul style="list-style-position: inside;">
                        <li>I Bet You Look Good on the Dancefloor</li>
                        <li>Mardy Bum</li>
                        <li>Cornerstone</li>
                        <li>Bodypaints</li>
                        <li>There'd Better Be a Mirrorball</li>
                    </ul>
                </div>
            </div>
            
            <div class="row justify-content-center mt-5">
                <div class="col-lg-8">
                    <div class="program_detail_wrap">
                        <div class="single_propram">
                            <div class="inner_wrap">
                                <div class="circle_img"></div>
                                <div class="porgram_top">
                                    <span class=" wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".3s">06:30 PM</span>
                                    <h4 class=" wow fadeInUp" data-wow-duration="1s" data-wow-delay=".4s">Doors Open</h4>
                                </div>
                                <div class="thumb wow fadeInUp" data-wow-duration="1s" data-wow-delay=".5s">
                                    <img src="https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&q=80&w=400" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="single_propram">
                            <div class="inner_wrap">
                                <div class="circle_img"></div>
                                <div class="porgram_top">
                                    <span class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".3s">09:15 PM</span>
                                    <h4 class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".4s">Arctic Monkeys Live</h4>
                                </div>
                                <div class="thumb  wow fadeInUp" data-wow-duration="1s" data-wow-delay=".5s">
                                    <img src="https://images.unsplash.com/photo-1510915361894-db8b60106cb1?auto=format&fit=crop&q=80&w=400" alt="">
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
    $footerDate = isset($event) ? $event->date->format('d M, Y') : 'May 25, 2027';
    $footerLocation = $event->location ?? 'GBK Stadium, Jakarta';
    $footerLocationClass = 'text-warning';
    $footerSlogan = 'Do I Wanna Know? Yes, You Do.';
    $footerButtonLink = route('ticket', ['type' => 'concert4']);
    $footerButtonText = 'Get Your Ticket';
    $footerCopyright = ($event->title ?? 'Arctic Monkeys') . ' | The Car World Tour Jakarta';
@endphp