@extends('layouts.frontbase')

@section('content')

<section class="space">
    <div class="container">
        <div style="width:90%; margin:0 auto;">
            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-circle me-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="tour-page-single">

                {{-- ================= IMAGE SLIDER ================= --}}
                @php
                    // Collect all images: cover image first, then additional images
                    $allImages = collect();
                    
                    // Add cover image if exists
                    if ($car->image) {
                        $allImages->push([
                            'image' => $car->image,
                            'type' => 'cover'
                        ]);
                    }
                    
                    // Add additional images from carimages table
                    if ($images && $images->count() > 0) {
                        foreach ($images as $carImage) {
                            if ($carImage->image) {
                                $allImages->push([
                                    'image' => $carImage->image,
                                    'type' => 'gallery'
                                ]);
                            }
                        }
                    }
                    
                    // If no images, use placeholder
                    if ($allImages->isEmpty()) {
                        $allImages->push([
                            'image' => 'placeholder',
                            'type' => 'placeholder'
                        ]);
                    }
                @endphp
                
                <div class="slider-area tour-slider1">
                    {{-- Main Carousel --}}
                    <div class="swiper th-slider mb-4" id="tourSlider4" data-slider-options='{"effect":"fade","loop":true,"thumbs":{"swiper":".tour-thumb-slider"},"autoplayDisableOnInteraction":"true"}'>
                        <div class="swiper-wrapper">
                            @foreach($allImages as $img)
                            <div class="swiper-slide">
                                <div class="tour-slider-img">
                                        @if($img['image'] === 'placeholder')
                                            <img src="{{ asset('assets/img/tour/tour_3_1.jpg') }}" 
                                                 alt="{{ $car->name }}" 
                                                 style="width:100%; height:560px; object-fit:cover;">
                                        @else
                                            <img src="{{ asset('storage/images/cars/' . $img['image']) }}" 
                                                 alt="{{ $car->name }}" 
                                                 style="width:100%; height:560px; object-fit:cover;">
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <button data-slider-prev="#tourSlider4" class="slider-arrow style3 slider-prev">
                            <img src="{{ asset('assets/img/icon/hero-arrow-left.svg') }}" alt="">
                        </button>
                        <button data-slider-next="#tourSlider4" class="slider-arrow style3 slider-next">
                            <img src="{{ asset('assets/img/icon/hero-arrow-right.svg') }}" alt="">
                        </button>
                    </div>

                    {{-- Thumbnail Slider (only show if more than 1 image) --}}
                    @if($allImages->count() > 1)
                    <div class="swiper th-slider tour-thumb-slider mt-3" data-slider-options='{"effect":"slide","loop":false,"breakpoints":{"0":{"slidesPerView":2},"576":{"slidesPerView":"3"},"768":{"slidesPerView":"4"},"992":{"slidesPerView":"5"},"1200":{"slidesPerView":"6"}},"autoplayDisableOnInteraction":"true","spaceBetween":10}'>
                        <div class="swiper-wrapper">
                            @foreach($allImages as $img)
                                <div class="swiper-slide">
                                    <div class="tour-slider-img" style="cursor:pointer;">
                                        @if($img['image'] === 'placeholder')
                                            <img src="{{ asset('assets/img/tour/tour_3_1.jpg') }}" 
                                                 alt="Thumbnail" 
                                                 style="width:100%; height:80px; object-fit:cover; border-radius:6px; border:2px solid transparent;">
                                        @else
                                            <img src="{{ asset('storage/images/cars/' . $img['image']) }}" 
                                                 alt="Thumbnail" 
                                                 style="width:100%; height:80px; object-fit:cover; border-radius:6px; border:2px solid transparent;">
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- ================= HEADER ================= --}}
                <div class="page-content mt-4">

                    <div class="page-meta mb-30 d-flex justify-content-between flex-wrap">
                        <div>
                            <h2 class="mb-1">{{ $car->name }}</h2>
                            <div class="small text-muted">
                                {{ $car->model }} • {{ $car->fuel_type }} • {{ $car->transmission }}
                            </div>
                        </div>

                        <div class="text-end">
                            @if($car->price_per_day)
                                <div style="font-size:20px;font-weight:700;">
                                    {{ formatUsd($car->price_per_day) }}
                                    <small class="text-muted">/ day</small>
                                </div>
                            @elseif($car->price_per_week)
                                <div style="font-size:20px;font-weight:700;">
                                    {{ formatUsd($car->price_per_week) }}
                                    <small class="text-muted">/ week</small>
                                </div>
                            @elseif($car->price_per_month)
                                <div style="font-size:20px;font-weight:700;">
                                    {{ formatUsd($car->price_per_month) }}
                                    <small class="text-muted">/ month</small>
                                </div>
                            @endif
                            <button type="button" class="th-btn style4 mt-2" data-bs-toggle="modal" data-bs-target="#carBookingModal">
                                Book Now
                            </button>
                        </div>
                    </div>

                    {{-- ================= DESCRIPTION ================= --}}
                    <h4 class="box-title mb-2">Advert Description</h4>
                    <div class="box-text mb-30 kdr-rich-text">
                        @if(filled($car->description))
                            {!! strip_tags($car->description, '<p><br><ul><ol><li><strong><em><b><i><a><h2><h3><h4><span>') !!}
                        @else
                            <p class="text-muted mb-0">No description available.</p>
                        @endif
                    </div>

                    {{-- ================= CAR SPECIFICATIONS ================= --}}
                    <div class="tour-snapshot mb-4">
                        <h4 class="box-title">Car Specifications</h4>

                        <div class="tour-snap-wrapp d-flex flex-wrap gap-3">

                            <div class="tour-snap">
                                <div class="icon"><i class="fa-solid fa-car"></i></div>
                                <div class="content">
                                    <span class="title">Model</span>
                                    <span>{{ $car->model }}</span>
                                </div>
                            </div>

                            <div class="tour-snap">
                                <div class="icon"><i class="fa-solid fa-gas-pump"></i></div>
                                <div class="content">
                                    <span class="title">Fuel</span>
                                    <span>{{ $car->fuel_type }}</span>
                                </div>
                            </div>

                            <div class="tour-snap">
                                <div class="icon"><i class="fa-solid fa-cogs"></i></div>
                                <div class="content">
                                    <span class="title">Transmission</span>
                                    <span>{{ $car->transmission }}</span>
                                </div>
                            </div>

                            <div class="tour-snap">
                                <div class="icon"><i class="fa-solid fa-users"></i></div>
                                <div class="content">
                                    <span class="title">Seats</span>
                                    <span>{{ $car->seats }}</span>
                                </div>
                            </div>

                            <div class="tour-snap">
                                <div class="icon"><i class="fa-solid fa-check-circle"></i></div>
                                <div class="content">
                                    <span class="title">Status</span>
                                    <span class="badge"
                                          style="background:#e6f9ee;color:#0b7a3a;">
                                          {{ ucfirst($car->status) }}
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>


                            {{-- ================= SUMMARY CARD ================= --}}
                            <div class="col-lg-4 mt-4 mt-lg-0">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <h5>{{ $car->name }}</h5>
                                        <p class="text-muted mb-2">
                                            {{ $car->model }} • {{ $car->fuel_type }}
                                        </p>

                                        <p style="font-size:18px;font-weight:700;">
                                            @if($car->price_per_day)
                                                {{ formatUsd($car->price_per_day) }}
                                                <small class="text-muted">/ day</small>
                                            @elseif($car->price_per_week)
                                                {{ formatUsd($car->price_per_week) }}
                                                <small class="text-muted">/ week</small>
                                            @elseif($car->price_per_month)
                                                {{ formatUsd($car->price_per_month) }}
                                                <small class="text-muted">/ month</small>
                                            @endif
                                        </p>

                                        <ul class="list-unstyled small">
                                            <li>Seats: <strong>{{ $car->seats }}</strong></li>
                                            <li>Status: <strong>{{ ucfirst($car->status) }}</strong></li>
                                        </ul>

                                        <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#carBookingModal">
                                            Book Now
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- ================= RELATED CARS ================= --}}
                    @if($allCars->isNotEmpty())
                        <div class="related-rooms mt-5">
                            <h4 class="box-title mb-3">Related Cars</h4>

                            <div class="row gy-4">
                                @foreach($allCars->take(3) as $r)
                                    <div class="col-xxl-4 col-xl-6">
                                        <div class="tour-box th-ani">
                                        <div class="tour-box_img global-img"
                                            style="height:250px; overflow:hidden;">
                                                @if($r->image && file_exists(storage_path('app/public/images/cars/' . $r->image)))
                                                    <img src="{{ asset('storage/images/cars/' . $r->image) }}"
                                                        alt="{{ $r->name }}"
                                                    style="width:100%; height:100%; object-fit:cover;">
                                            @else
                                                <img src="{{ asset('assets/img/tour/tour_3_1.jpg') }}"
                                                        alt="{{ $r->name }}"
                                                    style="width:100%; height:100%; object-fit:cover;">
                                            @endif
                                        </div>

                                            <div class="tour-content">
                                                <h3 class="box-title">
                                                    <a href="{{ route('carDetails', $r->slug ?? $r->id) }}">{{ $r->name }}</a>
                                                </h3>

                                                <ul class="list-unstyled mb-3 small text-muted row">
                                                    <li class="col-6 mb-1"><i class="fa fa-car me-1"></i> {{ $r->model }}</li>
                                                    <li class="col-6 mb-1"><i class="fa fa-gas-pump me-1"></i> {{ $r->fuel_type }}</li>
                                                    <li class="col-6 mb-1"><i class="fa fa-cogs me-1"></i> {{ $r->transmission }}</li>
                                                    <li class="col-6 mb-1"><i class="fa fa-users me-1"></i> {{ $r->seats }} seats</li>
                                                </ul>

                                                <div class="tour-action">
                                                    <div class="mt-auto">
                                                        @if($r->price_per_day !== null)
                                                            <p class="fw-bold mb-2">
                                                                {{ formatUsd($r->price_per_day) }}
                                                                <span class="text-muted fw-normal">/ day</span>
                                                            </p>
                                                        @elseif($r->price_per_week)
                                                            <p class="fw-bold mb-2">
                                                                {{ formatUsd($r->price_per_week) }}
                                                                <span class="text-muted fw-normal">/ week</span>
                                                            </p>
                                                        @elseif($r->price_per_month)
                                                            <p class="fw-bold mb-2">
                                                                {{ formatUsd($r->price_per_month) }}
                                                                <span class="text-muted fw-normal">/ month</span>
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <a href="{{ route('carDetails', $r->slug ?? $r->id) }}" class="th-btn style3">Book Now</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</section>

@include('frontend.partials.car-booking-form')

@push('scripts')
<script src="{{ asset('assets/js/kdr-car-booking.js') }}" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    @if($errors->any() || (session('error') && old('car_id') == $car->id))
        const bookingModal = new bootstrap.Modal(document.getElementById('carBookingModal'));
        bookingModal.show();
    @endif

    // Swiper Slider Initialization
    if (typeof Swiper !== 'undefined') {
        // Get image count from DOM
        const mainSliderEl = document.querySelector('#tourSlider4');
        const thumbSlider = document.querySelector('.tour-thumb-slider');
        const imageCount = mainSliderEl ? mainSliderEl.querySelectorAll('.swiper-slide').length : 0;
        
        // Initialize thumbnail slider first (if it exists)
        let thumbsSwiper = null;
        
        if (thumbSlider && imageCount > 1) {
            thumbsSwiper = new Swiper('.tour-thumb-slider', {
            slidesPerView: 4,
                spaceBetween: 10,
                freeMode: true,
                watchSlidesProgress: true,
                breakpoints: {
                    0: { slidesPerView: 2 },
                    576: { slidesPerView: 3 },
                    768: { slidesPerView: 4 },
                    992: { slidesPerView: 5 },
                    1200: { slidesPerView: 6 }
                }
            });
        }

        // Initialize main slider
        if (mainSliderEl) {
            const mainSlider = new Swiper('#tourSlider4', {
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
                loop: imageCount > 1,
                autoplay: imageCount > 1 ? {
                    delay: 5000,
                    disableOnInteraction: false
                } : false,
                navigation: {
                    nextEl: '[data-slider-next="#tourSlider4"]',
                    prevEl: '[data-slider-prev="#tourSlider4"]',
                },
                thumbs: thumbsSwiper ? {
                    swiper: thumbsSwiper
                } : undefined
            });

            // Click on thumbnails to change main slide
            if (thumbSlider && thumbsSwiper) {
                const thumbSlides = thumbSlider.querySelectorAll('.swiper-slide');
                thumbSlides.forEach((thumb, index) => {
                    thumb.style.cursor = 'pointer';
                    thumb.addEventListener('click', function() {
                        mainSlider.slideTo(index);
                        // Update active thumbnail border
                        thumbSlides.forEach(t => {
                            const img = t.querySelector('img');
                            if (img) {
                                img.style.borderColor = 'transparent';
                            }
                        });
                        const activeImg = thumb.querySelector('img');
                        if (activeImg) {
                            activeImg.style.borderColor = '#007bff';
                            activeImg.style.borderWidth = '2px';
                        }
                    });
                });
                
                // Set first thumbnail as active initially
                if (thumbSlides.length > 0) {
                    const firstImg = thumbSlides[0].querySelector('img');
                    if (firstImg) {
                        firstImg.style.borderColor = '#007bff';
                        firstImg.style.borderWidth = '2px';
                    }
                }
            }
        }
    }
});
</script>
@endpush
