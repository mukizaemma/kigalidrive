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
                            @if($car->price_to_buy)
                                <div style="font-size:20px;font-weight:700;">
                                    {{ number_format($car->price_to_buy) }} RWF
                                    <small class="text-muted">For Sale</small>
                                </div>
                            @elseif($car->price_per_day)
                                <div style="font-size:20px;font-weight:700;">
                                    {{ number_format($car->price_per_day) }} RWF
                                    <small class="text-muted">/ day</small>
                                </div>
                            @endif
                            <button type="button" class="th-btn style4 mt-2" data-bs-toggle="modal" data-bs-target="#carBookingModal">
                                Book Now
                            </button>
                        </div>
                    </div>

                    {{-- ================= DESCRIPTION ================= --}}
                    <h4 class="box-title mb-2">Advert Description</h4>
                    <p class="box-text mb-30">
                        {!! nl2br(e($car->description ?? 'No description available.')) !!}
                    </p>

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
                                            @if($car->price_to_buy)
                                                {{ number_format($car->price_to_buy) }} RWF
                                                <small class="text-muted">For Sale</small>
                                            @else
                                                {{ number_format($car->price_per_day) }} RWF
                                                <small class="text-muted">/ day</small>
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
                                                                {{ number_format($r->price_per_day) }} RWF
                                                                <span class="text-muted fw-normal">/ day</span>
                                                            </p>
                                                        @elseif($r->price_to_buy)
                                                            <p class="fw-bold mb-2">
                                                                {{ number_format($r->price_to_buy) }} RWF
                                                                <span class="text-muted fw-normal">For Sale</span>
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

<!-- Car Booking Modal -->
<style>
    #carBookingModal.show {
        display: block !important;
    }
    #view_car_fields {
        display: none !important;
    }
    #view_car_fields.show,
    #view_car_fields[style*="block"] {
        display: block !important;
    }
    #rent_buy_fields {
        display: none !important;
    }
    #rent_buy_fields.show,
    #rent_buy_fields[style*="block"] {
        display: block !important;
    }
</style>
<div class="modal fade" id="carBookingModal" tabindex="-1" aria-labelledby="carBookingModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered kdr-modal-landscape">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="carBookingModalLabel">Book {{ $car->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('reservations.car') }}" method="POST" id="carBookingForm" class="d-flex flex-column flex-grow-1 overflow-hidden">
                @csrf
                <input type="hidden" name="car_id" value="{{ $car->id }}">
            <div class="modal-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                    <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Booking Type <span class="text-danger">*</span></label>
                        <select name="booking_type" id="booking_type" class="form-select" required>
                            <option value="">Select Booking Type</option>
                            <option value="view_car">Request to View Car</option>
                            @if($car->price_per_day || $car->price_per_month)
                                <option value="rent">Book for Rent</option>
                            @endif
                            @if($car->price_to_buy)
                                <option value="buy">Book for Purchase</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-12">
                        @include('frontend.partials.kdr-submission-channels', ['channelContext' => 'booking'])
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control"
                               value="{{ auth()->check() ? auth()->user()->name : old('name') }}"
                               required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control"
                               value="{{ auth()->check() ? auth()->user()->email : old('email') }}"
                               required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <input type="tel" name="phone" class="form-control"
                               value="{{ old('phone') }}"
                               placeholder="+250 7XX XXX XXX" required>
                    </div>
                    </div>

                    <!-- View Car Fields -->
                    <div id="view_car_fields" class="mb-3 d-none">
                        <h6 class="mb-3 border-bottom pb-2"><i class="fa fa-calendar me-2"></i>Viewing Appointment Details</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Preferred Date <span class="text-danger">*</span></label>
                                <input type="date" name="preferred_date" id="preferred_date" 
                                       class="form-control" 
                                       min="{{ date('Y-m-d') }}"
                                       value="{{ old('preferred_date') }}"
                                       required>
                                <small class="text-muted">Select a date to view the car</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Preferred Time <span class="text-muted">(Optional)</span></label>
                                <input type="time" name="preferred_time" id="preferred_time" 
                                       class="form-control"
                                       value="{{ old('preferred_time') }}">
                                <small class="text-muted">Select a preferred time (optional)</small>
                            </div>
                        </div>
                    </div>

                    <!-- Rent/Buy Fields -->
                    <div id="rent_buy_fields" class="mb-3 d-none">
                        <h6 class="mb-3 border-bottom pb-2">Rental/Purchase Details</h6>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Pickup Location</label>
                                <input type="text" name="pickup_location" id="pickup_location" 
                                       class="form-control" 
                                       placeholder="Enter pickup location">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Drop-off Location</label>
                                <input type="text" name="dropoff_location" id="dropoff_location" 
                                       class="form-control" 
                                       placeholder="Enter drop-off location">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Pickup Date</label>
                                <input type="date" name="pickup_date" id="pickup_date" 
                                       class="form-control" min="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Drop-off Date</label>
                                <input type="date" name="dropoff_date" id="dropoff_date" 
                                       class="form-control" min="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Your address</label>
                        <input type="text" name="full_address" class="form-control" value="{{ old('full_address') }}" placeholder="Pickup or home address">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Time needed <span class="text-danger">*</span></label>
                        <input type="text" name="time_needed" class="form-control" required value="{{ old('time_needed') }}" placeholder="e.g. 3 days from 15 May, or 10:00 AM viewing">
                    </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Rental duration</label>
                            <select name="rental_duration" class="form-select">
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Driver</label>
                            <select name="with_driver" class="form-select">
                                <option value="1">With driver</option>
                                <option value="0">Self-drive</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Additional request</label>
                        <textarea name="additional_request" class="form-control" rows="3" placeholder="Special requests…">{{ old('additional_request') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary th-btn btn-kdr-primary">
                        <i class="fa fa-send me-2"></i>Submit reservation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Auto-open modal if there are validation errors
    @if($errors->any() || (session('error') && old('car_id') == $car->id))
        const bookingModal = new bootstrap.Modal(document.getElementById('carBookingModal'));
        bookingModal.show();
    @endif

    // Car Booking Modal Fields Toggle
    const bookingType = document.getElementById('booking_type');
    const viewCarFields = document.getElementById('view_car_fields');
    const rentBuyFields = document.getElementById('rent_buy_fields');
    
    function toggleBookingFields() {
        if (!bookingType || !viewCarFields || !rentBuyFields) {
            console.error('Booking form elements not found');
            return;
        }
        
        const type = bookingType.value;
        
        // Hide all fields first
        viewCarFields.classList.add('d-none');
        viewCarFields.classList.remove('show');
        viewCarFields.style.display = 'none';
        rentBuyFields.classList.add('d-none');
        rentBuyFields.classList.remove('show');
        rentBuyFields.style.display = 'none';
        
        // Get all date/time fields
        const preferredDate = document.getElementById('preferred_date');
        const preferredTime = document.getElementById('preferred_time');
        const pickupDate = document.getElementById('pickup_date');
        const dropoffDate = document.getElementById('dropoff_date');
        
        // Remove required attributes from all fields
        [preferredDate, preferredTime, pickupDate, dropoffDate].forEach(field => {
            if (field) {
                field.required = false;
                field.removeAttribute('required');
                field.setAttribute('aria-required', 'false');
            }
        });
        
        // Show and set required fields based on booking type
        if (type === 'view_car') {
            // Show the fields by removing d-none class and adding show class
            viewCarFields.classList.remove('d-none');
            viewCarFields.classList.add('show');
            viewCarFields.style.display = 'block';
            
            // Make date required
            if (preferredDate) {
                preferredDate.required = true;
                preferredDate.setAttribute('required', 'required');
                preferredDate.setAttribute('aria-required', 'true');
            }
            // Time is optional - don't set required
            if (preferredTime) {
                preferredTime.required = false;
                preferredTime.removeAttribute('required');
                preferredTime.setAttribute('aria-required', 'false');
            }
        } else if (type === 'rent') {
            rentBuyFields.classList.remove('d-none');
            rentBuyFields.classList.add('show');
            rentBuyFields.style.display = 'block';
            
            if (pickupDate) {
                pickupDate.required = true;
                pickupDate.setAttribute('required', 'required');
                pickupDate.setAttribute('aria-required', 'true');
            }
            if (dropoffDate) {
                dropoffDate.required = true;
                dropoffDate.setAttribute('required', 'required');
                dropoffDate.setAttribute('aria-required', 'true');
            }
        } else if (type === 'buy') {
            rentBuyFields.classList.remove('d-none');
            rentBuyFields.classList.add('show');
            rentBuyFields.style.display = 'block';
        }
    }
    
    // Set up event listener
    if (bookingType) {
        bookingType.addEventListener('change', function() {
            toggleBookingFields();
            // Force a reflow to ensure display changes take effect
            void bookingType.offsetHeight;
        });
        
        // Set initial state based on old value if validation failed
        @if(old('booking_type'))
            bookingType.value = '{{ old('booking_type') }}';
        @endif
        
        // Call toggle function immediately and after delays to ensure DOM is ready
        toggleBookingFields();
        setTimeout(toggleBookingFields, 50);
        setTimeout(toggleBookingFields, 200);
    }
    
    // Also trigger on modal show to ensure fields are visible
    const carBookingModal = document.getElementById('carBookingModal');
    if (carBookingModal) {
        carBookingModal.addEventListener('shown.bs.modal', function() {
            // Wait a bit for modal animation to complete
            setTimeout(function() {
                toggleBookingFields();
                // If view_car is selected, ensure fields are visible
                if (bookingType && bookingType.value === 'view_car') {
                    const viewFields = document.getElementById('view_car_fields');
                    if (viewFields) {
                        viewFields.classList.remove('d-none');
                        viewFields.classList.add('show');
                        viewFields.style.display = 'block';
                    }
                }
            }, 300);
        });
        
        // Also trigger when modal is about to show
        carBookingModal.addEventListener('show.bs.modal', function() {
            setTimeout(toggleBookingFields, 100);
        });
    }

    // Form submission handling
    const bookingForm = document.getElementById('carBookingForm');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            // Don't prevent default - let form submit naturally
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Submitting...';
            }
        });
        
        // Reset form on modal close if successful
        const bookingModal = document.getElementById('carBookingModal');
        if (bookingModal) {
            bookingModal.addEventListener('hidden.bs.modal', function () {
                @if(session('success') && !$errors->any())
                    // Reset form if submission was successful
                    bookingForm.reset();
                    // Reset field visibility
                    if (viewCarFields) viewCarFields.style.display = 'none';
                    if (rentBuyFields) rentBuyFields.style.display = 'none';
                    if (bookingType) bookingType.value = '';
                @endif
            });
        }
    }
    
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

@endsection
