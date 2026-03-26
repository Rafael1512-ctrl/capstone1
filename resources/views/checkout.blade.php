<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Secure Checkout | {{ $event->title }}</title>
    <meta name="description" content="Complete your purchase.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
        /* Modal Styling Improvements */
        #paymentModal {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            overflow-y: auto !important;
        }
        #paymentModal .modal-dialog {
            margin: 30px auto !important;
            max-width: 500px;
        }
        .modal-backdrop {
            z-index: 9998 !important;
        }
        .modal-content {
            z-index: 9999 !important;
        }
        a[href*="tickets.index"] {
            transition: all 0.2s;
        }
        a[href*="tickets.index"]:hover {
            text-decoration: underline !important;
            opacity: 0.8;
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
                            @php
                                if ($event->banner_url) {
                                    if (str_starts_with($event->banner_url, '/storage/')) {
                                        $imgUrl = $event->banner_url;
                                    } else {
                                        $imgUrl = Storage::url($event->banner_url);
                                    }
                                } else {
                                    $imgUrl = asset('cardboard-assets/img/concert_1.jpg');
                                }
                            @endphp
                            <img src="{{ $imgUrl }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 10px;" class="mr-3">
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
        <div class="modal-dialog" role="document" style="width: 90%; max-width: 500px;">
            <div class="modal-content" style="background-color: #ffffff !important; color: #111111 !important; border-radius: 20px; border: none; box-shadow: 0 25px 50px rgba(0,0,0,0.5); position: relative; max-height: 90vh; overflow-y: auto;">

                <!-- ======= STEP 1: Detail Pembayaran ======= -->
                <div id="p-step-1">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold" style="color: #333 !important;">Detail Pembayaran</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #333 !important; opacity: 1;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="p-3 rounded mb-3" style="background: #f8f9fa !important; border: 1px solid #eee;">
                            <p class="mb-1 font-weight-bold" style="color: #333 !important;">{{ $event->title }}</p>
                            <p class="small mb-0" style="color: #777 !important;">{{ $ticketType->name }}</p>
                        </div>
                        <div class="d-flex justify-content-between mb-2" style="color: #555 !important;">
                            <span>Harga Tiket</span>
                            <span>RP {{ number_format($ticketType->price, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2" style="color: #555 !important;">
                            <span>Jumlah</span>
                            <span id="modal-qty">1x</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2" style="color: #555 !important;">
                            <span>Metode Pembayaran</span>
                            <span id="modal-method" class="font-weight-bold" style="color: #dc143c !important;">QRIS</span>
                        </div>
                        <hr style="border-color: #eee;">
                        <div class="d-flex justify-content-between">
                            <span class="font-weight-bold" style="font-size: 1.1rem; color: #333 !important;">Total Bayar</span>
                            <span class="font-weight-bold" style="font-size: 1.1rem; color: #dc143c !important;" id="modal-total">RP {{ number_format($ticketType->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 px-4 pb-4">
                        <button type="button" class="btn w-100 py-3" onclick="goToStep2()" style="border-radius: 50px; background: linear-gradient(135deg, #dc143c 0%, #8b0000 100%) !important; border: none; color: white !important; font-weight: bold; font-size: 1rem; box-shadow: 0 4px 15px rgba(220,20,60,0.35);">
                            Lanjutkan Pembayaran <i class="fa fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- ======= STEP 2: Scan / Transfer ======= -->
                <div id="p-step-2" style="display:none;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold" style="color: #333 !important;">Selesaikan Pembayaran</h5>
                        <button type="button" class="close" onclick="goToStep1()" style="color: #333 !important; opacity: 1;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center p-4">
                        <!-- Total amount reminder -->
                        <div class="mb-3 p-2 rounded" style="background: rgba(220,20,60,0.06) !important; border: 1px solid rgba(220,20,60,0.15); border-radius: 10px !important;">
                            <span style="color: #888 !important; font-size: 13px;">Total Pembayaran</span><br>
                            <span class="font-weight-bold" style="font-size: 1.3rem; color: #dc143c !important;" id="modal-total-2">RP {{ number_format($ticketType->price, 0, ',', '.') }}</span>
                        </div>

                        <div id="qrisSection2">
                            <p class="mb-3" style="color: #666 !important; font-size: 14px;">Silakan pindai QRIS di bawah ini untuk membayar</p>
                            <div class="p-3 bg-white d-inline-block rounded-lg mb-3" style="border: 2px solid #f0f0f0;">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=00020101021226590014ID.LINKAJA.WWW011893600503000007672202150000000000000005204541153033605802ID5914LUXTIX%20OFFICIAL6007JAKARTA6105123456304CA5A" alt="QRIS" style="width: 220px; height: 220px;">
                            </div>
                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" height="25" class="mr-3">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e1/GPN_Logo.svg/1024px-GPN_Logo.svg.png" height="20">
                            </div>
                        </div>

                        <div id="vaSection2" style="display:none;">
                            <p class="mb-2" style="color: #666 !important; font-size: 14px;">Nomor Virtual Account</p>
                            <h2 class="font-weight-bold mb-3" style="letter-spacing: 2px; color: #dc143c !important; font-size: 1.5rem;">8806 0812 3456 7890</h2>
                            <div class="p-3 rounded mb-3 text-left" style="border: 1px solid #eee; background-color: #f8f9fa !important;">
                                <p class="small mb-1" style="color: #999 !important;">Nama Bank</p>
                                <p class="mb-0 font-weight-bold" style="color: #333 !important;">BCA Virtual Account</p>
                            </div>
                        </div>

                        <div class="timer mb-3 p-2 rounded" style="background: rgba(220,20,60,0.08) !important; color: #ff6b6b !important; border: 1px solid rgba(220,20,60,0.25) !important; font-weight: bold; border-radius: 10px !important; font-size: 14px;">
                            Batas waktu pembayaran: <span id="countdown">14:59</span>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 px-4 pb-4 d-flex flex-column">
                        <button type="button" class="btn w-100 py-3 mb-2" onclick="goToStep3()" style="border-radius: 50px; background: linear-gradient(135deg, #dc143c 0%, #8b0000 100%) !important; border: none; color: white !important; font-weight: bold; font-size: 1rem; box-shadow: 0 4px 15px rgba(220,20,60,0.35);">
                            <i class="fa fa-check mr-2"></i> Saya Sudah Bayar
                        </button>
                        <button type="button" class="btn w-100 py-2" onclick="goToStep1()" style="border-radius: 50px; background: transparent !important; border: 1px solid #ddd !important; color: #888 !important; font-weight: 500; font-size: 0.9rem;">
                            <i class="fa fa-arrow-left mr-1"></i> Kembali
                        </button>
                    </div>
                </div>

                <!-- ======= STEP 3: Konfirmasi Pembayaran ======= -->
                <div id="p-step-3" style="display:none;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold" style="color: #333 !important;">Konfirmasi Pembayaran</h5>
                    </div>
                    <div class="modal-body text-center p-4">
                        <div class="mb-4">
                            <i class="fa fa-question-circle" style="font-size: 70px; color: #f0ad4e !important;"></i>
                        </div>
                        <h4 class="font-weight-bold mb-2" style="color: #333 !important;">Apakah Anda yakin sudah membayar?</h4>
                        <p class="mb-3" style="color: #666 !important; font-size: 14px;">Pastikan pembayaran sebesar <strong style="color: #dc143c !important;" id="modal-total-3">RP 0</strong> sudah berhasil sebelum melanjutkan.</p>
                        <div class="p-3 rounded mb-3 text-left" style="background: #fff8e1 !important; border: 1px solid #ffe082; border-radius: 10px !important;">
                            <p class="mb-0 small" style="color: #795548 !important;"><i class="fa fa-info-circle mr-1"></i> Jika pembayaran belum dilakukan, tiket tidak akan diproses.</p>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 px-4 pb-4 d-flex flex-column">
                        <button type="button" class="btn w-100 py-3 mb-2" onclick="confirmPaymentDone()" style="border-radius: 50px; background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%) !important; border: none; color: white !important; font-weight: bold; font-size: 1rem; box-shadow: 0 4px 15px rgba(40,167,69,0.35);">
                            <i class="fa fa-check-circle mr-2"></i> Ya, Saya Sudah Bayar
                        </button>
                        <button type="button" class="btn w-100 py-2" onclick="backToStep2()" style="border-radius: 50px; background: transparent !important; border: 1px solid #ddd !important; color: #888 !important; font-weight: 500; font-size: 0.9rem;">
                            <i class="fa fa-arrow-left mr-1"></i> Belum, Kembali
                        </button>
                    </div>
                </div>

                <!-- ======= STEP 4: Berhasil ======= -->
                <div id="p-step-4" style="display:none;">
                    <div class="modal-body text-center p-4 pb-5">
                        <div class="mb-3" style="animation: successPop 0.5s ease;">
                            <i class="fa fa-check-circle" style="font-size: 90px; color: #28a745 !important;"></i>
                        </div>
                        <h2 class="font-weight-bold mb-2" style="color: #333 !important;">Pembayaran Berhasil!</h2>
                        <p class="mb-4" style="color: #666 !important; font-size: 14px;">Tiket Anda sudah terbeli. Silakan cek di halaman <strong>My Tickets</strong> untuk melihat detail tiket.</p>
                        <div class="p-3 rounded text-left mb-3" style="background: #f8f9fa !important; color: #333 !important; border: 1px solid #eee;">
                            <p class="mb-1 font-weight-bold" style="color: #333 !important;">{{ $event->title }}</p>
                            <div class="d-flex justify-content-between small" style="color: #777 !important;">
                                <span>{{ $ticketType->name }}</span>
                                <span id="modal-summary-qty">1x</span>
                            </div>
                            <hr style="border-color: #eee; margin: 8px 0;">
                            <div class="d-flex justify-content-between">
                                <span class="font-weight-bold" style="color: #333 !important;">Total</span>
                                <span class="font-weight-bold" style="color: #dc143c !important;" id="modal-total-4">RP {{ number_format($ticketType->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="p-3 rounded mb-4" style="background: #e8f5e9 !important; border: 1px solid #a5d6a7; border-radius: 10px !important;">
                            <p class="mb-0 small" style="color: #2e7d32 !important;"><i class="fa fa-ticket mr-1"></i> E-Tiket sudah tersedia di halaman <strong>My Tickets</strong></p>
                        </div>
                        <a href="{{ route('tickets.index') }}" class="btn w-100 py-3 mb-2" style="border-radius: 50px; background: linear-gradient(135deg, #dc143c 0%, #8b0000 100%) !important; border: none; color: white !important; font-weight: bold; font-size: 1rem; box-shadow: 0 4px 15px rgba(220,20,60,0.35); display: block; text-decoration: none;">
                            <i class="fa fa-ticket mr-2"></i> Lihat My Tickets
                        </a>
                        <a href="{{ route('home') }}" class="d-block mt-2 font-weight-bold" style="color: #dc143c !important; text-decoration: none; font-size: 14px;">Kembali ke Beranda</a>
                    </div>
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
    <style>
        @keyframes successPop {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
    <script>
        let qty = 1;
        let pricePerUnit = {{ $ticketType->price }};
        let selectedMethod = 'qris';
        let timerInterval = null;

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

        function formatPrice(amount) {
            return 'RP ' + amount.toLocaleString('id-ID');
        }

        function selectPayment(el, method) {
            $('.payment-option').removeClass('active').find('i.fa-check-circle').replaceWith('<i class="fa fa-circle-thin text-muted"></i>');
            $(el).addClass('active').find('i.fa-circle-thin').replaceWith('<i class="fa fa-check-circle text-primary"></i>');
            selectedMethod = method;
        }

        // Open modal at Step 1
        function confirmPurchase() {
            let total = qty * pricePerUnit;
            let totalFormatted = formatPrice(total);

            // Update all total displays
            $('#modal-qty').text(qty + 'x');
            $('#modal-method').text(selectedMethod === 'qris' ? 'QRIS' : 'Bank Transfer / VA');
            $('#modal-total, #modal-total-2, #modal-total-3, #modal-total-4').text(totalFormatted);
            $('#modal-summary-qty').text(qty + 'x');

            // Show step 1, hide others
            $('#p-step-1').show();
            $('#p-step-2, #p-step-3, #p-step-4').hide();

            $('#paymentModal').modal('show');
        }

        // Step 1 -> Step 2
        function goToStep2() {
            // Show correct payment section
            if(selectedMethod === 'qris') {
                $('#qrisSection2').show();
                $('#vaSection2').hide();
            } else {
                $('#qrisSection2').hide();
                $('#vaSection2').show();
            }

            $('#p-step-1').fadeOut(200, function() {
                $('#p-step-2').fadeIn(200);
            });

            // Start countdown timer
            startTimer(15 * 60);
        }

        // Step 2 -> Step 1
        function goToStep1() {
            if(timerInterval) clearInterval(timerInterval);
            $('#p-step-2').fadeOut(200, function() {
                $('#p-step-1').fadeIn(200);
            });
        }

        // Step 2 -> Step 3 (user clicks "Saya Sudah Bayar")
        function goToStep3() {
            if(timerInterval) clearInterval(timerInterval);
            $('#p-step-2').fadeOut(200, function() {
                $('#p-step-3').fadeIn(200);
            });
        }

        // Step 3 -> Step 2 (user clicks "Belum, Kembali")
        function backToStep2() {
            $('#p-step-3').fadeOut(200, function() {
                $('#p-step-2').fadeIn(200);
            });
            startTimer(15 * 60);
        }

        // Step 3 -> Step 4 (confirmed! - send to backend)
        function confirmPaymentDone() {
            let $btn = $('#p-step-3').find('button').first();
            let originalText = $btn.html();
            $btn.html('<i class="fa fa-spinner fa-spin mr-2"></i> Memproses...').prop('disabled', true);

            $.ajax({
                url: '{{ route("public.checkout.process", [$event->event_id, $ticketType->id]) }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    quantity: qty,
                    payment_method: selectedMethod === 'qris' ? 'QRIS' : 'Virtual Account'
                },
                success: function(response) {
                    if(response.success) {
                        $('#p-step-3').fadeOut(200, function() {
                            $('#p-step-4').fadeIn(200);
                        });
                    } else {
                        alert('Error: ' + response.message);
                        $btn.html(originalText).prop('disabled', false);
                    }
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan.';
                    alert('Error: ' + msg);
                    $btn.html(originalText).prop('disabled', false);
                }
            });
        }

        function startTimer(duration) {
            if(timerInterval) clearInterval(timerInterval);
            let timer = duration, minutes, seconds;
            timerInterval = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
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
