<div class="hero-10" id="hero">
    <div class="swiper hero-slider-10" id="heroSlide10">
        <div class="swiper-wrapper">
            @foreach ($slides as $slide)
                <div class="swiper-slide">
                    <div class="hero-inner">
                        <div class="th-hero-bg" data-bg-src="{{ asset('storage/images/slides/' . $slide->image) }}">
                        </div>

                        <div class="container">
                            <div class="hero-style10">
                                <h1 class="hero-title" data-ani="slideinup" data-ani-delay="0.4s">
                                    <span class="hero-title2">{{ $slide->heading }}</span>
                                </h1>
                                <div class="btn-group" data-ani="slideinup" data-ani-delay="0.6s">
                                    <a href="{{ route('contact') }}" class="th-btn style2 th-icon">Book Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="hero3-wrapper">
        <div class="container">
            <div class="row justify-content-center align-items-end flex-row-reverse">
                <div class="col-lg-4">
                    {{-- <div class="hero10-swiper-custom">
                        <button data-slider-prev="#heroSlide10" class="swiper-button-next">
                            <img src="{{ asset('assets/img/icon/hero-arrow-right.svg') }}" alt="">
                        </button>
                        <div class="swiper-pagination"></div>
                        <button data-slider-next="#heroSlide10" class="swiper-button-prev">
                            <img src="{{ asset('assets/img/icon/hero-arrow-left.svg') }}" alt="">
                        </button>
                    </div> --}}

                    <!-- Dynamic Thumbnails -->
                    <div class="swiper hero10Thumbs" id="heroSlide11">
                        <div class="swiper-wrapper">
                            {{-- @foreach ($slides as $slide)
                                <div class="swiper-slide">
                                    <div class="hero-inner">
                                        <div class="hero10-card">
                                            <div class="hero-img">
                                                <img src="{{ asset('storage/images/slides/' . $slide->image) }}" alt="{{ $slide->heading }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach --}}
                        </div>
                    </div>
                </div>

                <!-- Booking Section (unchanged) -->
                {{-- <div class="col-lg-8">
                    <div class="hero-booking style2">
                        <form action="mail.php" method="POST" class="booking-form style5 ajax-contact">
                            <div class="input-wrap">
                                <div class="row align-items-center justify-content-between">
                                        <h4>Your Comfort, Our Commitment</h4>
                                    <div class="form-btn col-md-6 col-xl-auto">
                                        <button class="th-btn">
                                            <img src="{{ asset('assets/img/icon/search.svg') }}" alt=""> Book Now
                                        </button>
                                    </div>
                                </div>
                                <p class="form-messages mb-0 mt-3"></p>
                            </div>
                        </form>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>

    <div class="scroll-down">
        <a href="#destination-sec" class="scroll-wrap">
            <span><img src="{{ asset('assets/img/icon/down-arrow.svg') }}" alt=""></span>
            Scroll Down
        </a>
    </div>
</div>
