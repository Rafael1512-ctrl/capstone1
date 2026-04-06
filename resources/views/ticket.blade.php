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
            padding: 25px 20px;
            margin-bottom: 30px;
            border: 1px solid #333;
            transition: 0.3s;
        }
        .ticket-card:hover {
            border-color: rgba(220,20,60,0.6);
            transform: translateY(-5px);
        }
        .ticket-price {
            font-size: 36px;
            font-weight: bold;
            color: #dc143c;
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
            color: #dc143c;
            margin-right: 10px;
        }
        .buy-btn {
            background: linear-gradient(135deg, #dc143c 0%, #8b0000 100%);
            color: white;
            padding: 12px 35px;
            border-radius: 30px;
            text-transform: uppercase;
            font-weight: bold;
            display: inline-block;
            transition: all 0.3s;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(220,20,60,0.35);
        }
        .buy-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(220,20,60,0.5);
            text-decoration: none;
            color: white !important;
        }
        .important-info-box {
            background-color: #111;
            border-left: 5px solid #dc143c;
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
    @include('layouts.headerconcert.headerconcert')
    <!-- header-end -->

    <!-- bradcam_area_start -->
    @php
        if ($event->banner_url) {
            if (str_starts_with($event->banner_url, '/storage/')) {
                $bannerUrl = $event->banner_url;
            } else {
                $bannerUrl = Storage::url($event->banner_url);
            }
        } else {
            $bannerUrl = asset('cardboard-assets/img/concert_1.jpg');
        }
    @endphp
    <div class="bradcam_area" style="background-image: url('{{ \App\Models\SiteSetting::forceDirectUrl($bannerUrl) }}'); background-size: cover; background-position: center; padding: 180px 0; position: relative; z-index: 1;">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="bradcam_text text-center wow fadeInUp" data-wow-duration="1s" data-wow-delay=".3s">
                        <span class="text-white text-uppercase" style="letter-spacing: 5px; font-weight: 600; margin-bottom: 20px; display: block;">Official Ticket Selection</span>
                        <h3 class="text-white font-weight-bold" style="font-size: 70px; font-family: 'DM Serif Display', serif;">{{ $event->title }}</h3>
                        <p class="text-white lead" style="font-size: 24px;"><i class="fa fa-calendar-o mr-2"></i> {{ $event->schedule_time ? $event->schedule_time->format('d M Y') : 'Date TBD' }} | <i class="fa fa-map-marker mr-2"></i> {{ $event->location }}</p>
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
        <div class="container-fluid px-lg-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="section_title text-center mb-80">
                        <span class="d-block text-primary text-uppercase mb-2" style="letter-spacing: 2px;">Premium Packages</span>
                        <h3 class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".3s" style="color:#fff; font-size: 45px;">Choose Your Experience</h3>
                        <div class="line mx-auto" style="width: 80px; height: 3px; background: #dc143c; margin-top: 20px;"></div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center flex-wrap">
                @php
                    $activeBatch = $event->active_batch;
                    $now = now();
                @endphp

                @if(!$activeBatch)
                    {{-- Penjualan belum dimulai atau sudah berakhir --}}
                    <div class="col-12 text-center text-white py-5">
                        <div class="important-info-box wow fadeInUp" data-wow-duration="1s">
                            @if($event->batch2_ended_at && $event->batch2_ended_at->isPast())
                                <h4 class="text-danger mb-3">Penjualan Telah Berakhir</h4>
                                <p>Terima kasih atas antusiasme Anda. Semua batch penjualan tiket event ini telah ditutup.</p>
                            @elseif($event->batch1_ended_at && $event->batch1_ended_at->isPast() && $event->batch2_start_at && $now->isBefore($event->batch2_start_at))
                                <h4 class="text-warning mb-3">Batch 1 Telah Diakhiri</h4>
                                <p>Silakan tunggu <strong>Batch 2</strong> yang akan dibuka pada:</p>
                                <h2 class="text-white mt-3">{{ $event->batch2_start_at->format('d M Y, H:i') }} WIB</h2>
                            @elseif($event->batch1_start_at && $now->isBefore($event->batch1_start_at))
                                <h4 class="text-primary mb-3">Penjualan Belum Dimulai</h4>
                                <p>Penjualan tiket <strong>Batch 1</strong> akan dibuka pada:</p>
                                <h2 class="text-white mt-3">{{ $event->batch1_start_at->format('d M Y, H:i') }} WIB</h2>
                            @else
                                <h4 class="text-danger mb-3">Tiket Tidak Tersedia</h4>
                                <p>Penjualan tiket saat ini tidak aktif atau telah ditutup oleh penyelenggara.</p>
                            @endif
                        </div>
                    </div>
                @else
                    {{-- Tampilkan Tiket untuk Batch yang sedang aktif --}}
                    @php
                        $batchNum = $activeBatch;
                        $categories = ['Regular', 'VIP', 'VVIP'];
                        $isBatch1SoldOut = true;
                        
                        // Check if Batch 1 is completely sold out to decide if we should mention Batch 2
                        if ($batchNum == 1) {
                            $isBatch1SoldOut = ($event->batch1_regular_sold >= $event->batch1_regular_quota) && 
                                               ($event->batch1_vip_sold >= $event->batch1_vip_quota) && 
                                               ($event->batch1_vvip_sold >= $event->batch1_vvip_quota);
                        }
                    @endphp

                    @if($batchNum == 1 && $isBatch1SoldOut && $event->batch2_start_at && $now->isBefore($event->batch2_start_at))
                        <div class="col-12 text-center text-white py-5">
                            <div class="important-info-box wow fadeInUp" data-wow-duration="1s">
                                <h4 class="text-danger mb-3">Tiket Batch 1 Sudah Habis</h4>
                                <p>Silakan tunggu <strong>Batch 2</strong> yang akan dibuka pada:</p>
                                <h2 class="text-white mt-3">{{ $event->batch2_start_at->format('d M Y, H:i') }} WIB</h2>
                            </div>
                        </div>
                    @else
                        @foreach($categories as $cat)
                            @php
                                $catLower = strtolower($cat);
                                $quotaLabel = "batch{$batchNum}_{$catLower}_quota";
                                $priceLabel = "batch{$batchNum}_{$catLower}_price";
                                $soldLabel = "batch{$batchNum}_{$catLower}_sold";
                                
                                $quota = $event->$quotaLabel;
                                $price = $event->$priceLabel;
                                $sold = $event->$soldLabel;
                                $remaining = max(0, $quota - $sold);
                                
                                $ticketType = $event->ticketTypes()
                                    ->where('batch_number', $batchNum)
                                    ->where('name', $cat)
                                    ->first();
                            @endphp

                            @if($quota > 0 && $ticketType)
                                <div class="col-12 col-md-6 col-lg-4 mb-4 px-3">
                                    <div class="ticket-card text-center wow fadeInUp" data-wow-duration="1s" data-wow-delay="{{ $loop->index * 0.2 }}s">
                                        <div style="position: absolute; top: -15px; left: 50%; transform: translateX(-50%); 
                                            background: {{ $batchNum == 1 ? 'linear-gradient(135deg, #007bff 0%, #004085 100%)' : 'linear-gradient(135deg, #17a2b8 0%, #0b515d 100%)' }}; 
                                            color: white; padding: 5px 20px; border-radius: 20px; font-size: 11px; font-weight: bold; text-transform: uppercase; z-index: 2;">
                                            Batch {{ $batchNum }}
                                        </div>
                                        
                                        <div class="ticket-title @if($cat == 'Regular') text-primary @elseif($cat == 'VIP') text-warning @else text-danger @endif">
                                            @if($cat == 'VVIP') <i class="fa fa-crown mr-2"></i> @endif {{ $cat }}
                                        </div>
                                        <div class="ticket-price">RP {{ number_format($price, 0, ',', '.') }}</div>
                                        
                                        @if($remaining <= 0)
                                            <div class="alert alert-danger bg-transparent border-danger text-danger mt-4 py-2 small">
                                                <i class="fa fa-exclamation-circle"></i> Tiket Kategori {{ $cat }} Habis.
                                            </div>
                                            <button class="buy-btn mt-3" style="background: #333; cursor: not-allowed; box-shadow: none; opacity: 0.6;" disabled>SOLD OUT</button>
                                        @else
                                            <p class="text-light mb-0">Tersedia: <strong>{{ $remaining }}</strong> tiket</p>
                                            <ul class="ticket-features text-left mt-4">
                                                <li><i class="fa fa-check"></i> Entry for {{ $event->title }}</li>
                                                <li><i class="fa fa-check"></i> {{ $cat }} Experience</li>
                                                @if($cat == 'VIP')
                                                    <li><i class="fa fa-check"></i> Standard Benefits</li>
                                                @elseif($cat == 'VVIP')
                                                    <li><i class="fa fa-check"></i> Front Row & Lounge Access</li>
                                                @endif
                                            </ul>
                                            <a href="{{ route('public.checkout.show', [$event->event_id, $ticketType->id]) }}" class="buy-btn mt-3 text-white">Beli Sekarang</a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                @endif
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
