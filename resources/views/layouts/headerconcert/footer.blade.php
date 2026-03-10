<footer class="footer">
        <div class="footer_top">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-8">
                        <div class="footer_widget">
                            <div class="address_details text-center">
                                <h4 class="wow fadeInUp" data-wow-duration="1s" data-wow-delay=".3s">{{ $footerDate }}</h4>
                                <h3 class="wow fadeInUp {{ $footerLocationClass ?? 'text-warning' }}" data-wow-duration="1s" data-wow-delay=".4s">{{ $footerLocation }}</h3>
                                <p class="wow fadeInUp {{ $footerSloganClass ?? 'text-light' }}" data-wow-duration="1s" data-wow-delay=".5s">{{ $footerSlogan }}</p>
                                <a href="#" class="boxed-btn3 wow fadeInUp" data-wow-duration="1s"
                                    data-wow-delay=".6s">{{ $footerButtonText }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="copy-right_text">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <p class="copy_right text-center wow fadeInDown" data-wow-duration="1s" data-wow-delay=".5s">
                            Copyright &copy;
                            <script>document.write(new Date().getFullYear());</script> {{ $footerCopyright }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>