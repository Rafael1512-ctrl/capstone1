<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Secure Checkout | {{ $event->title }}</title>
    <meta name="description" content="Complete your purchase.">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('concert-assets/img/favicon.png') }}">

    <!-- CSS here -->
    <link rel="stylesheet" href="{{ asset('concert-assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('concert-assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('concert-assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('concert-assets/css/style.css') }}">
    <style>
        body {
            background-color: #0d0d0d;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            animation: pageFadeIn 0.4s ease forwards;
        }
        @keyframes pageFadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .checkout-container {
            padding: 100px 0 60px;
        }
        .checkout-card {
            background-color: #161616;
            border-radius: 20px;
            padding: 40px;
            border: 1px solid rgba(220,20,60,0.15);
            box-shadow: 0 10px 40px rgba(0,0,0,0.4);
            transition: border-color 0.3s;
        }
        .checkout-card:hover {
            border-color: rgba(220,20,60,0.25);
        }
        .event-summary {
            background: linear-gradient(135deg, #1f0808 0%, #120505 100%);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border-left: 5px solid #dc143c;
        }
        .form-label {
            color: rgba(255,255,255,0.55);
            font-weight: 500;
            margin-bottom: 10px;
        }
        .form-control, .form-select {
            background-color: #0d0d0d;
            border: 1px solid rgba(255,255,255,0.1);
            color: #fff;
            padding: 14px 16px;
            border-radius: 10px;
        }
        .form-control::placeholder { color: rgba(255,255,255,0.25); }
        .form-control:focus, .form-select:focus {
            background-color: #111;
            border-color: #dc143c;
            color: #fff;
            box-shadow: 0 0 0 3px rgba(220,20,60,0.12);
        }
        .qty-controls {
            display: flex;
            align-items: center;
            gap: 20px;
            background: #0d0d0d;
            border: 1px solid rgba(255,255,255,0.08);
            padding: 10px 24px;
            border-radius: 50px;
            width: fit-content;
        }
        .qty-btn {
            background: none;
            border: none;
            color: #dc143c;
            font-size: 22px;
            cursor: pointer;
            transition: color 0.2s;
        }
        .qty-btn:hover { color: #ff6b6b; }
        .payment-option {
            background: #0d0d0d;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 14px;
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .payment-option:hover {
            border-color: rgba(220,20,60,0.3);
        }
        .payment-option.active {
            border-color: #dc143c;
            background: rgba(220, 20, 60, 0.06);
        }
        .payment-option input { display: none; }
        .order-total-box {
            background: #0d0d0d;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 15px;
            padding: 28px;
        }
        .confirm-btn {
            background: linear-gradient(135deg, #dc143c 0%, #8b0000 100%);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 50px;
            width: 100%;
            font-weight: 700;
            font-size: 1.1rem;
            margin-top: 20px;
            transition: all 0.3s;
            box-shadow: 0 4px 20px rgba(220,20,60,0.35);
            letter-spacing: 0.5px;
        }
        .confirm-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(220, 20, 60, 0.5);
        }
    </style>
</head>

<body>
    @include('layouts.headerconcert.header')

    <div class="checkout-container">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="checkout-card mb-4 wow fadeInLeft">
                        <h2 class="mb-4">Billing Information</h2>
                        <form id="checkoutForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control" placeholder="Enter your name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" class="form-control" placeholder="Enter your email" required>
                                </div>
                            </div>
                            
                            <h4 class="mt-4 mb-3">Quantity Selection</h4>
                            <div class="qty-controls mb-4">
                                <button type="button" class="qty-btn" id="minus">-</button>
                                <span id="qty-val" style="font-size: 20px; font-weight: bold;">1</span>
                                <button type="button" class="qty-btn" id="plus">+</button>
                            </div>

                            <h4 class="mt-4 mb-3">Payment Method</h4>
                            <div class="payment-option active" onclick="selectPayment(this, 'qris')">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-qrcode mr-3 text-primary"></i>
                                    <div>
                                        <div class="font-weight-bold">QRIS</div>
                                        <small class="text-muted">GOPAY, OVO, DANA, SHOPEEPAY</small>
                                    </div>
                                </div>
                                <i class="fa fa-check-circle text-primary"></i>
                            </div>
                            <div class="payment-option" onclick="selectPayment(this, 'va')">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-credit-card mr-3 text-primary"></i>
                                    <div>
                                        <div class="font-weight-bold">Bank Transfer / VA</div>
                                        <small class="text-muted">BCA, Mandiri, BNI, BRI</small>
                                    </div>
                                </div>
                                <i class="fa fa-circle-thin text-muted"></i>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="checkout-card wow fadeInRight">
                        <h3 class="mb-4">Order Summary</h3>
                        <div class="event-summary d-flex align-items-start">
                            <img src="{{ $event->banner_url ? Storage::url($event->banner_url) : asset('cardboard-assets/img/concert_1.jpg') }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 10px;" class="mr-3">
                            <div>
                                <h5 class="mb-1 text-white">{{ $event->title }}</h5>
                                <p class="small text-muted mb-0"><i class="fa fa-calendar mr-1"></i> {{ $event->schedule_time ? $event->schedule_time->format('d M Y') : 'Date TBD' }}</p>
                                <p class="small text-muted mb-0"><i class="fa fa-map-marker mr-1"></i> {{ $event->location }}</p>
                            </div>
                        </div>

                        <div class="order-total-box">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Ticket Category</span>
                                <span class="font-weight-bold">{{ $ticketType->name }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Unit Price</span>
                                <span>RP {{ number_format($ticketType->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Quantity</span>
                                <span id="summary-qty">1x</span>
                            </div>
                            <hr style="border-color: #333;">
                            <div class="d-flex justify-content-between mb-0">
                                <h4 class="mb-0">Total Amount</h4>
                                <h4 class="mb-0 text-primary" id="total-amount">RP {{ number_format($ticketType->price, 0, ',', '.') }}</h4>
                            </div>
                        </div>

                        <button type="button" class="confirm-btn" onclick="confirmPurchase()">Confirm & Pay Now</button>
                        <p class="text-center mt-3 text-muted small"><i class="fa fa-lock mr-1"></i> SSL Encrypted Payment</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" style="z-index: 9999;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="background-color: #ffffff !important; color: #111111 !important; border-radius: 20px; border: none; box-shadow: 0 25px 50px rgba(0,0,0,0.5); z-index: 10000; position: relative;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold" style="color: #333 !important;">Selesaikan Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModalBtn" style="color: #333 !important; opacity: 1;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <!-- Step 1: Payment Info -->
                <div class="modal-body text-center p-4" id="p-step-1">
                    <div id="qrisSection">
                        <p class="mb-4 text-muted" style="color: #666 !important;">Silakan pindai QRIS di bawah ini untuk membayar</p>
                        <div class="qris-box p-3 bg-white d-inline-block rounded-lg mb-4" style="border: 2px solid #f0f0f0;">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=00020101021226590014ID.LINKAJA.WWW011893600503000007672202150000000000000005204541153033605802ID5914LUXTIX%20OFFICIAL6007JAKARTA6105123456304CA5A" alt="QRIS" style="width: 250px; height: 250px;">
                        </div>
                        <div class="d-flex justify-content-center align-items-center mb-3">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" height="30" class="mr-3">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e1/GPN_Logo.svg/1024px-GPN_Logo.svg.png" height="25">
                        </div>
                    </div>
                    
                    <div id="vaSection" style="display:none;">
                        <p class="mb-2 text-muted" style="color: #666 !important;">Nomor Virtual Account</p>
                        <h2 class="font-weight-bold mb-4" style="letter-spacing: 2px; color: #dc143c !important;">8806 0812 3456 7890</h2>
                        <div class="bg-light p-3 rounded mb-4 text-left" style="border: 1px solid #eee; background-color: #f8f9fa !important;">
                            <p class="small text-muted mb-1">Nama Bank</p>
                            <p class="mb-0 font-weight-bold" style="color: #333 !important;">BCA Virtual Account</p>
                        </div>
                    </div>

                    <div class="timer mb-4 p-2 rounded" style="background: rgba(220,20,60,0.08) !important; color: #ff6b6b !important; border: 1px solid rgba(220,20,60,0.25) !important; font-weight: bold; border-radius: 10px !important;">
                        Menunggu pembayaran: <span id="countdown">14:59</span>
                    </div>

                    <div id="detection-status" class="alert alert-secondary py-2" style="border: none; border-radius: 10px; font-size: 13px; background: #f0f0f0 !important; color: #444 !important;">
                        <i class="fa fa-spinner fa-spin mr-2"></i> Sistem sedang mendeteksi pembayaran Anda...
                    </div>
                </div>

                <!-- Step 2: Success Simulation -->
                <div class="modal-body text-center p-5" id="p-step-2" style="display:none;">
                    <div class="success-icon mb-4">
                        <i class="fa fa-check-circle" style="font-size: 100px; color: #28a745 !important;"></i>
                    </div>
                    <h2 class="font-weight-bold mb-2" style="color: #333 !important;">Pembayaran Berhasil!</h2>
                    <p class="text-muted" style="color: #666 !important;">E-Tiket Anda telah dikirim ke email dan tersedia di menu History.</p>
                    <div class="order-summary-mini mt-4 p-3 rounded text-left" style="background: #f8f9fa !important; color: #333 !important; border: 1px solid #eee;">
                        <p class="mb-1"><strong>{{ $event->title }}</strong></p>
                        <p class="small text-muted mb-0" style="color: #777 !important;">{{ $ticketType->name }} | RP {{ number_format($ticketType->price, 0, ',', '.') }}</p>
                    </div>
                    <button type="button" class="btn btn-primary w-100 py-3 mt-4" style="border-radius: 50px; background: #28a745 !important; border: none; color: white !important; font-weight: bold; cursor: pointer;" onclick="window.location.href='{{ route('home') }}'">Kembali ke Beranda</button>
                    <a href="{{ route('tickets.index') }}" class="d-block mt-3 font-weight-bold" style="color: #dc143c !important; text-decoration: none; cursor: pointer;">Lihat History Tiket</a>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.headerconcert.footer', [
        'footerDate' => 'Secure Transaction',
        'footerLocation' => 'LuxTix Official',
        'footerLocationClass' => 'text-warning',
        'footerSlogan' => 'Easy and Secure Payment',
        'footerSloganClass' => '',
        'footerButtonText' => 'Cancel Order',
        'footerButtonLink' => route('public.ticket.show', $event->event_id),
        'footerCopyright' => 'LuxTix © 2026. All rights reserved.'
    ])

    <script src="{{ asset('concert-assets/js/vendor/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('concert-assets/js/bootstrap.min.js') }}"></script>
    <script>
        let qty = 1;
        let pricePerUnit = {{ $ticketType->price }};
        let selectedMethod = 'qris';
        let paymentDetected = false;

        $('#plus').click(function() {
            if(qty < 5) {
                qty++;
                updateOrder();
            }
        });

        $('#minus').click(function() {
            if(qty > 1) {
                qty--;
                updateOrder();
            }
        });

        function updateOrder() {
            $('#qty-val').text(qty);
            $('#summary-qty').text(qty + 'x');
            let total = qty * pricePerUnit;
            $('#total-amount').text('RP ' + total.toLocaleString('id-ID'));
        }

        function selectPayment(el, method) {
            $('.payment-option').removeClass('active').find('i.fa-check-circle').replaceWith('<i class="fa fa-circle-thin text-muted"></i>');
            $(el).addClass('active').find('i.fa-circle-thin').replaceWith('<i class="fa fa-check-circle text-primary"></i>');
            selectedMethod = method;
        }

        function confirmPurchase() {
            // Reset modal steps
            $('#p-step-1').show();
            $('#p-step-2').hide();
            $('#closeModalBtn').show();
            paymentDetected = false;

            if(selectedMethod === 'qris') {
                $('#qrisSection').show();
                $('#vaSection').hide();
            } else {
                $('#qrisSection').hide();
                $('#vaSection').show();
            }
            
            $('#paymentModal').modal('show');
            startTimer(15 * 60);

            // SIMULASI DETEKSI OTOMATIS (Setelah 7 detik)
            setTimeout(function() {
                simulateSuccess();
            }, 7000);
        }

        function simulateSuccess() {
            if(paymentDetected) return;
            paymentDetected = true;
            
            $('#detection-status').html('<i class="fa fa-check-circle mr-2"></i> Pembayaran Terdeteksi!').addClass('alert-success').removeClass('alert-secondary');
            
            setTimeout(function() {
                $('#p-step-1').fadeOut(400, function() {
                    $('#p-step-2').fadeIn();
                    $('#closeModalBtn').hide(); // Paksa user liat sukses
                });
            }, 1500);
        }

        function startTimer(duration) {
            let timer = duration, minutes, seconds;
            let timerInterval = setInterval(function () {
                if(paymentDetected) {
                    clearInterval(timerInterval);
                    return;
                }
                minutes = parseInt(timer / 60, 10)
                seconds = parseInt(timer % 60, 10);
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;
                $('#countdown').text(minutes + ":" + seconds);
                if (--timer < 0) {
                    clearInterval(timerInterval);
                    timer = 0;
                }
            }, 1000);
        }
    </script>
</body>
</html>
