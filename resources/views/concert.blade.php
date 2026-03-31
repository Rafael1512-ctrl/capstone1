@extends('layouts.headerconcert.masterconcert')

@section('title', (isset($event) ? $event->title : 'Concert Details') . ' | LuxTix')
@section('meta_description', isset($event) ? $event->description : 'Join us for an unforgettable concert experience.')

@section('ExtraCSS')
<style>
    /* Premium Concert Aesthetics */
    .concert-title {
        font-family: 'DM Serif Display', serif;
        font-size: 3.5rem;
        background: -webkit-linear-gradient(#fff, #999);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
</style>
@endsection

@section('banner_url', \App\Models\SiteSetting::forceDirectUrl((isset($event) && $event->banner_url) ? (filter_var($event->banner_url, FILTER_VALIDATE_URL) ? $event->banner_url : Storage::url($event->banner_url)) : 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&q=80&w=1974'))

@section('content')
    <!-- slider_area_start -->
    <div class="slider_area" id="concert">
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
                            <span class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".3s">{{ isset($event) && $event->schedule_time ? $event->schedule_time->format('d M, Y') : 'Date TBD' }}</span>
                            <h3 class="wow fadeInLeft concert-title" data-wow-duration="1s" data-wow-delay=".4s">{{ $event->title ?? 'Live Concert' }}</h3>
                            <p class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".5s">{{ $event->location ?? 'Venue to be announced' }}</p>
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
                    <div class="section_title  text-center mb-80">
                        <h3 class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".3s">Event Description</h3>
                        <p class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".4s">
                            {{ $event->description ?? 'Join us for a unique performance that will redefine your music experience. Don\'t miss out on this incredible night.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- performar_area_end  -->

    <!-- Performers Section (Only for Festival) -->
    @php
        $isFestival = $event->category && (
            strtolower($event->category->name) === 'festival' || 
            str_contains(strtolower($event->category->name), 'festival')
        );
    @endphp
    @if ($isFestival && $event->performers && count($event->performers) > 0)
        <div id="performers-lineup" class="performar_area black_bg pt-120">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section_title  text-center mb-80">
                            <h3 class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".3s">
                                <i class="fas fa-users"></i>Line Up Performer
                            </h3>
                            <p class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".4s">
                                {{ count($event->performers) }} Artists yang akan memeriahkan acara ini
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Performers Grid -->
                <div class="row mt-5">
                    @foreach ($event->performers as $performer)
                        <div class="col-md-6 col-lg-4 mb-5 wow fadeInUp" data-wow-duration="1s" data-wow-delay=".5s">
                            <div class="performer-card text-center">
                                <div class="performer-image mb-3" style="position: relative; overflow: hidden; border-radius: 10px; height: 300px;">
                                    @if (isset($performer['photo']) && $performer['photo'])
                                        <img src="{{ \App\Models\SiteSetting::forceDirectUrl(filter_var($performer['photo'], FILTER_VALIDATE_URL) ? $performer['photo'] : url($performer['photo'])) }}" alt="{{ $performer['name'] }}"
                                            style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;" referrerpolicy="no-referrer">
                                    @else
                                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #cc2b5e 0%, #753a88 100%); display: flex; align-items: center; justify-content: center; color: white;">
                                            <i class="fas fa-music fa-3x"></i>
                                        </div>
                                    @endif
                                </div>
                                <h5 class="performer-name text-white mb-2">{{ $performer['name'] }}</h5>
                                <p class="performer-role text-warning mb-3">
                                    <i class="fas fa-microphone-alt"></i> {{ $performer['role'] }}
                                </p>
                                @if (isset($performer['description']) && $performer['description'])
                                    <p class="performer-description text-light">{{ $performer['description'] }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    <!-- Performers Section End -->

    <!-- venue_area_start  -->
    <div id="venue" class="venue_area black_bg pt-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="section_title text-center mb-80">
                        <h3 class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".3s">The Venue</h3>
                        <p class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".4s">Our chosen venue ensures an intimate yet grand experience, featuring world-class acoustics and accessibility.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="venue_map wow fadeInUp" data-wow-duration="1s" data-wow-delay=".5s">
                        <iframe 
                            src="{{ !empty($event->maps_url) ? $event->maps_url : 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3967.103046755606!2d106.86214371476856!3d-6.123545595565545!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6a1e5080000001%3A0xd653f53835f8386b!2sJakarta%20International%20Stadium!5e0!3m2!1sen!2sid!4v1653556050510!5m2!1sen!2sid' }}" 
                            width="100%" 
                            height="450" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy">
                        </iframe>
                    </div>
                    <div class="text-center mt-4 wow fadeInUp" data-wow-duration="1s" data-wow-delay=".6s">
                        <h4 class="text-white">{{ $event->location ?? 'Jakarta, Indonesia' }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- venue_area_end  -->
@endsection

@php
    $footerDate = isset($event) && $event->schedule_time ? $event->schedule_time->format('d M, Y') : 'Event Date';
    $footerLocation = $event->location ?? 'Jakarta, Indonesia';
    $isOverdue = $event->status === 'overdue' || ($event->schedule_time && $event->schedule_time < now());
    $footerButtonLink = (!$isOverdue && isset($event)) ? route('public.ticket.show', $event->event_id) : '#';
    $footerButtonText = $isOverdue ? 'Event Passed' : 'Get Your Ticket';
    $footerSlogan = $isOverdue ? 'SALES CLOSED' : 'GET YOUR TICKETS NOW';
    $footerCopyright = 'LuxTix';
@endphp