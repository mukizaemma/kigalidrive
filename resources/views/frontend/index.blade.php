
@extends('layouts.frontbase')

@section('content')
    <style>
        .hero-home { min-height: 85vh; min-height: 85dvh; }
        @media (max-width: 991px) { .hero-home { min-height: 100vh; min-height: 100dvh; } }
        @media (max-width: 575px) { .hero-home { min-height: 100vh; min-height: 100dvh; } }

        /* Small screens: hero image and text fit and readable */
        @media (max-width: 767px) {
            #hero.hero-home {
                background-size: cover !important;
                background-position: center center !important;
                background-repeat: no-repeat !important;
                min-height: 100vh !important;
                min-height: 100dvh !important;
                display: flex !important;
                align-items: center !important;
                padding: 0 12px;
            }
            #hero .hero-overlay {
                background: linear-gradient(180deg, rgba(0,0,0,0.35) 0%, rgba(0,0,0,0.55) 100%) !important;
            }
            #hero .container {
                padding-left: 12px;
                padding-right: 12px;
                max-width: 100%;
            }
            #hero .row.align-items-center {
                min-height: auto !important;
                padding-top: 48px !important;
                padding-bottom: 24px !important;
            }
            #hero .hero-style7 {
                padding: 24px 0 20px 0 !important;
            }
            #hero .hero-title {
                font-size: clamp(1.35rem, 5vw, 1.85rem) !important;
                line-height: 1.3 !important;
                margin-bottom: 1rem !important;
                text-shadow: 0 1px 3px rgba(0,0,0,0.5), 0 2px 8px rgba(0,0,0,0.3) !important;
                padding: 0 8px;
            }
            #hero .btn-group {
                gap: 10px;
                margin-bottom: 1.25rem !important;
            }
            #hero .btn-group .th-btn {
                padding: 10px 18px !important;
                font-size: 0.95rem !important;
                min-height: 44px;
            }
            /* Hero search form: full-width rows on mobile, touch-friendly */
            #heroSearchForm {
                padding: 16px !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
                max-width: 100% !important;
            }
            #heroSearchForm .row.g-2 > [class*="col-"] {
                flex: 0 0 100% !important;
                max-width: 100% !important;
                margin-bottom: 8px !important;
            }
            #heroSearchForm .row.g-2 > [class*="col-"]:last-child {
                margin-bottom: 0 !important;
            }
            #heroSearchForm .form-control,
            #heroSearchForm .form-select,
            #heroSearchForm .form-control-sm {
                height: 46px !important;
                min-height: 46px !important;
                font-size: 16px !important;
                padding-left: 40px !important;
            }
            #heroSearchForm .position-absolute i {
                left: 12px !important;
                font-size: 14px !important;
            }
            #heroSearchForm .btn-primary {
                height: 46px !important;
                min-height: 46px !important;
                font-size: 1rem !important;
            }
        }
        @media (max-width: 575px) {
            #hero .hero-title {
                font-size: clamp(1.25rem, 4.5vw, 1.6rem) !important;
            }
            #hero .hero-style7 {
                padding: 20px 0 16px 0 !important;
            }
        }
    </style>
    <!--==============================
    Hero Area - full height with top bar on image
    ==============================-->
    @php
        $heroBg = (optional($setting)->home_background_image ?? null)
            ? asset('storage/images/site/' . optional($setting)->home_background_image)
            : (isset($about) && $about && $about->image1 ? asset('storage/images/about/' . $about->image1) : asset('assets/img/bg/breadcumb-bg-1.jpg'));
    @endphp
    <div class="th-hero-wrapper hero-7 hero-home" id="hero" data-bg-src="{{ $heroBg }}" style="background-size: cover; background-position: center; position: relative; overflow: hidden;">
        <!-- Overlay for readability -->
        <div class="hero-overlay" style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(0,0,0,0.25) 0%, rgba(0,0,0,0.45) 100%); z-index: 0;"></div>

        <div class="container h-100" style="position: relative; z-index: 1;">
            <div class="row align-items-center justify-content-center h-100" style="min-height: 60vh; min-height: 60dvh; padding-top: 56px;">
                <div class="col-12">
                    <div class="hero-style7 text-center" style="padding: 40px 0 30px 0;">
                        <h1 class="hero-title text-white mb-4" style="font-size: clamp(1.75rem, 4vw, 2.75rem); font-weight: 700;">Your Travel Booking Partner</h1>
                        <div class="btn-group mb-4 flex-wrap justify-content-center">
                            <a href="{{ route('hotels') }}" class="th-btn th-icon style3">Hotels</a>
                            <a href="{{ route('apartments') }}" class="th-btn style2 th-icon">Apartments</a>
                        </div>
                    </div>

                    {{-- Search bar (same as header - redirects to search results) --}}
                    <div class="row justify-content-center mt-4">
                        <div class="col-12">
                            <form action="{{ route('hotelsSearch') }}" method="GET" id="heroSearchForm" class="hero-search-form bg-white rounded-3 p-3" style="max-width: 900px; margin: 0 auto; background: #ffffff !important; border: 1px solid rgba(0,0,0,0.08) !important; box-shadow: 0 10px 40px rgba(0,0,0,0.2), 0 2px 10px rgba(0,0,0,0.1) !important;">
                                <div class="row g-2 align-items-center">
                                    <div class="col-12 col-md-4 col-xl-3">
                                        <div class="position-relative">
                                            <i class="fas fa-map-marker-alt position-absolute" style="left: 14px; top: 50%; transform: translateY(-50%); color: #888; font-size: 14px;"></i>
                                            <input type="text" name="location" list="hero-destinations" class="form-control form-control-sm" placeholder="Type destination, address or city — or choose All" value="{{ request('location') }}" autocomplete="off" style="padding-left: 38px; border-radius: 8px; height: 44px;">
                                            <datalist id="hero-destinations">
                                                <option value="All">All Destinations</option>
                                                @if(isset($searchLocations) && $searchLocations->isNotEmpty())
                                                    @foreach($searchLocations as $loc)
                                                        <option value="{{ $loc }}">{{ $loc }}</option>
                                                    @endforeach
                                                @elseif(isset($locations) && $locations->isNotEmpty())
                                                    @foreach($locations as $loc)
                                                        <option value="{{ $loc }}">{{ $loc }}</option>
                                                    @endforeach
                                                @endif
                                            </datalist>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3 col-xl-2">
                                        <div class="position-relative">
                                            <i class="fas fa-calendar-check position-absolute" style="left: 14px; top: 50%; transform: translateY(-50%); color: #888; font-size: 14px;"></i>
                                            <input type="date" name="checkin" id="checkinDate" class="form-control form-control-sm" value="{{ request('checkin') }}" min="{{ date('Y-m-d') }}" style="padding-left: 38px; border-radius: 8px; height: 44px;">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3 col-xl-2">
                                        <div class="position-relative">
                                            <i class="fas fa-calendar-times position-absolute" style="left: 14px; top: 50%; transform: translateY(-50%); color: #888; font-size: 14px;"></i>
                                            <input type="date" name="checkout" id="checkoutDate" class="form-control form-control-sm" value="{{ request('checkout') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" style="padding-left: 38px; border-radius: 8px; height: 44px;">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3 col-xl-2">
                                        @include('frontend.partials.guests_rooms_selector', ['selectorId' => 'hero-guests-rooms'])
                                    </div>
                                    <div class="col-12 col-md-4 col-xl-2">
                                        <button type="submit" class="btn btn-primary w-100" style="border-radius: 8px; height: 44px; font-weight: 600;">
                                            <i class="fas fa-search me-2"></i>Search
                                        </button>
                                    </div>
                                </div>
                                <div id="formMessages" class="small mt-2" style="display: none;"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--======== / Hero Section ========-->
    
    <!--==============================
    Featured Listings – 3 latest items: cover image, short description, rating, Check availability
    ==============================-->
    <section class="position-relative overflow-hidden space" id="featured-listings-sec">
        <div class="container">
            <div class="row justify-content-center mb-4">
                <div class="col-12">
                    <div class="title-area text-center">
                        <h2 class="sec-title">Explore Properties</h2>
                        <p class="sec-text">Our latest properties. Check availability and book your stay.</p>
                    </div>
                </div>
            </div>

            <div class="row g-4 justify-content-center">
                @foreach($latestProperties ?? [] as $item)
                    @php
                        $isProperty = $item instanceof \App\Models\Property;
                        $coverImage = $isProperty
                            ? ($item->featured_image ? asset('storage/images/properties/' . $item->featured_image) : asset('assets/img/tour/tour_3_1.jpg'))
                            : ($item->image ? asset('storage/images/hotels/' . $item->image) : asset('assets/img/tour/tour_3_1.jpg'));
                        $description = $item->description ?? '';
                        $shortDesc = \Illuminate\Support\Str::limit(strip_tags($description), 120);
                        $starsValue = $item->stars ?? 0;
                        preg_match('/\d+/', (string)$starsValue, $m);
                        $stars = !empty($m) ? max(0, min(5, (int)$m[0])) : 0;
                        $detailUrl = $isProperty ? route('hotel', $item->slug ?? $item->id) : route('hotelRooms', $item->slug);
                        $minPrice = null;
                        $currency = 'USD';
                        if ($isProperty && $item->units && $item->units->isNotEmpty()) {
                            $cheapest = $item->units->where('base_price_per_night', '>', 0)->sortBy('base_price_per_night')->first();
                            if ($cheapest) {
                                $minPrice = $cheapest->base_price_per_night;
                                $currency = $cheapest->currency ?? 'USD';
                            }
                        } elseif (!$isProperty && $item->rooms && $item->rooms->isNotEmpty()) {
                            $cheapest = $item->rooms->where('price_per_night', '>', 0)->sortBy('price_per_night')->first();
                            if ($cheapest) {
                                $minPrice = $cheapest->price_per_night ?? $cheapest->price ?? null;
                                $currency = $cheapest->currency ?? 'USD';
                            }
                        }
                        $currencySymbol = getCurrencySymbol($currency);
                    @endphp
                    <div class="col-lg-4 col-md-6">
                        <div class="tour-box th-ani h-100">
                            <div class="tour-box_img global-img">
                                <img src="{{ $coverImage }}" alt="{{ $item->name }}" style="width: 100%; height: 220px; object-fit: cover;">
                            </div>
                            <div class="tour-content">
                                <h3 class="box-title">
                                    <a href="{{ $detailUrl }}">{{ $item->name }}</a>
                                </h3>
                                <div class="star-rating mb-2" role="img" aria-label="Rated {{ $stars }} out of 5">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $stars)
                                            <i class="fa-solid fa-star text-warning" aria-hidden="true"></i>
                                        @else
                                            <i class="fa-regular fa-star text-warning" aria-hidden="true"></i>
                                        @endif
                                    @endfor
                                </div>
                                @if($shortDesc)
                                    <p class="mb-3" style="color: #666; font-size: 0.95rem; line-height: 1.5;">{{ $shortDesc }}</p>
                                @endif
                                <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
                                    @if($minPrice)
                                        <span class="text-success fw-semibold" style="font-size: 1.1rem;">{{ $currencySymbol }}{{ number_format($minPrice, 0) }}/night</span>
                                    @else
                                        <span class="text-muted small">Price on request</span>
                                    @endif
                                    <a href="{{ $detailUrl }}" class="th-btn style3 featured-listing-avail-btn">Check availability</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!--==============================
    Travel Services – buttons/icons with full-width background image
    ==============================-->
    @php
        $travelServicesBg = isset($about) && !empty($about->image1)
            ? asset('storage/images/about/' . $about->image1)
            : asset('assets/img/tour/tour_3_1.jpg');
    @endphp
    <section class="space travel-services-section" id="travel-services-sec">
        <div class="travel-services-bg" style="background-image: url('{{ $travelServicesBg }}');"></div>
        <div class="container position-relative" style="z-index: 2;">
            <div class="row justify-content-center mb-4">
                <div class="col-lg-8">
                    <div class="title-area text-center">
                        <h2 class="sec-title text-white" style="text-shadow: 0 1px 3px rgba(0,0,0,0.4);">Travel Services</h2>
                        <p class="sec-text text-white" style="text-shadow: 0 1px 2px rgba(0,0,0,0.3);">Complete your trip with our travel services</p>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <a href="{{ route('connect') }}" class="text-decoration-none">
                        <div class="card h-100 text-center border-0 shadow-sm travel-service-card" style="border-radius: 18px; transition: all 0.3s ease; background: rgba(255,255,255,0.95);">
                            <div class="card-body p-4">
                                <div style="width:64px; height:64px; border-radius:18px; background:rgba(37,211,102,0.2); display:flex; align-items:center; justify-content:center; margin:0 auto 20px;">
                                    <i class="fas fa-plane" style="color:#25D366; font-size:28px;"></i>
                                </div>
                                <h5 class="card-title mb-2" style="color:#1a1a1a;">Airport Transfers</h5>
                                <p class="card-text mb-0" style="font-size:14px; color:#555;">
                                    Reliable airport pickup and drop-off services
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="{{ route('showCars') }}" class="text-decoration-none">
                        <div class="card h-100 text-center border-0 shadow-sm travel-service-card" style="border-radius: 18px; transition: all 0.3s ease; background: rgba(255,255,255,0.95);">
                            <div class="card-body p-4">
                                <div style="width:64px; height:64px; border-radius:18px; background:rgba(37,211,102,0.2); display:flex; align-items:center; justify-content:center; margin:0 auto 20px;">
                                    <i class="fas fa-car" style="color:#25D366; font-size:28px;"></i>
                                </div>
                                <h5 class="card-title mb-2" style="color:#1a1a1a;">Car Rentals</h5>
                                <p class="card-text mb-0" style="font-size:14px; color:#555;">
                                    Rent a car for your journey
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="{{ route('tours') }}" class="text-decoration-none">
                        <div class="card h-100 text-center border-0 shadow-sm travel-service-card" style="border-radius: 18px; transition: all 0.3s ease; background: rgba(255,255,255,0.95);">
                            <div class="card-body p-4">
                                <div style="width:64px; height:64px; border-radius:18px; background:rgba(37,211,102,0.2); display:flex; align-items:center; justify-content:center; margin:0 auto 20px;">
                                    <i class="fas fa-map-marked-alt" style="color:#25D366; font-size:28px;"></i>
                                </div>
                                <h5 class="card-title mb-2" style="color:#1a1a1a;">Tours</h5>
                                <p class="card-text mb-0" style="font-size:14px; color:#555;">
                                    Discover tours and activities
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!--==============================
    Customer Reviews (Kigali Drive Rentals Wireframe)
    ==============================-->
    @if(isset($businessReviews) && $businessReviews->isNotEmpty())
    <section class="space" id="customer-reviews-sec" style="background:#f7f7f7;">
        <div class="container">
            <div class="row justify-content-center mb-4">
                <div class="col-lg-8">
                    <div class="title-area text-center">
                        <h2 class="sec-title">Customer Reviews</h2>
                        <p class="sec-text">What our guests say about their experience</p>
                    </div>
                </div>
            </div>
            <div class="row g-4">
                @foreach($businessReviews->take(3) as $review)
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm review-card" style="border-radius: 18px;">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                @php
                                    $stars = (int)($review->rating ?? 5);
                                    $stars = max(1, min(5, $stars));
                                @endphp
                                <div class="star-rating me-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $stars)
                                            <i class="fa-solid fa-star text-warning"></i>
                                        @else
                                            <i class="fa-regular fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                                @if($review->user)
                                <span class="text-muted small">{{ $review->user->name ?? 'Guest' }}</span>
                                @else
                                <span class="text-muted small">{{ $review->names ?? 'Guest' }}</span>
                                @endif
                            </div>
                            <p class="card-text mb-0" style="font-size: 14px; color: #555; line-height: 1.6;">
                                {{ \Illuminate\Support\Str::limit(strip_tags($review->testimony ?? ''), 120) }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('reviews.index') }}" class="th-btn style3 th-icon">View All Reviews</a>
            </div>
        </div>
    </section>
    @endif

    <!--==============================
Why Choose Stay Nets  
==============================-->
    <section class="space" style="background:#f7f7f7;">
        <div class="container">
            <div class="row gy-4 align-items-start">
                <!-- Left: Benefit cards -->
                <div class="col-lg-6">
                    <div class="title-area" style="margin-bottom: 24px;">
                        <h2 class="sec-title">Why Choose Stay Nets</h2>
                        <p class="sec-text">Trusted local experts for accommodation and travel across Rwanda & East Africa.</p>
                    </div>

                    <div class="d-flex flex-column">
                        <div class="row g-3">
                            <div class="col-sm-12">
                                <div style="display:flex; gap:14px; padding:14px 16px; border-radius:10px; background:#ffffff; box-shadow:0 8px 20px rgba(0,0,0,0.04);">
                                    <div style="width:34px; height:34px; border-radius:50%; background:#25d366; display:flex; align-items:center; justify-content:center; color:#fff; font-size:18px;">
                                        ★
                                    </div>
                                    <div>
                                        <h5 style="margin:0 0 4px; font-size:16px;">Tailor‑Made Tours</h5>
                                        <p style="margin:0; font-size:14px; color:#555;">Tailor-made tours across Rwanda &amp; East Africa.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div style="display:flex; gap:14px; padding:14px 16px; border-radius:10px; background:#ffffff; box-shadow:0 8px 20px rgba(0,0,0,0.04);">
                                    <div style="width:34px; height:34px; border-radius:50%; background:#25d366; display:flex; align-items:center; justify-content:center; color:#fff; font-size:18px;">
                                        ★
                                    </div>
                                    <div>
                                        <h5 style="margin:0 0 4px; font-size:16px;">Local Expert Guides</h5>
                                        <p style="margin:0; font-size:14px; color:#555;">Professional, local guides who know the region deeply.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div style="display:flex; gap:14px; padding:14px 16px; border-radius:10px; background:#ffffff; box-shadow:0 8px 20px rgba(0,0,0,0.04);">
                                    <div style="width:34px; height:34px; border-radius:50%; background:#25d366; display:flex; align-items:center; justify-content:center; color:#fff; font-size:18px;">
                                        ★
                                    </div>
                                    <div>
                                        <h5 style="margin:0 0 4px; font-size:16px;">Rich Experiences</h5>
                                        <p style="margin:0; font-size:14px; color:#555;">Wildlife, primates, culture &amp; scenic adventures.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div style="display:flex; gap:14px; padding:14px 16px; border-radius:10px; background:#ffffff; box-shadow:0 8px 20px rgba(0,0,0,0.04);">
                                    <div style="width:34px; height:34px; border-radius:50%; background:#25d366; display:flex; align-items:center; justify-content:center; color:#fff; font-size:18px;">
                                        ★
                                    </div>
                                    <div>
                                        <h5 style="margin:0 0 4px; font-size:16px;">For Every Budget</h5>
                                        <p style="margin:0; font-size:14px; color:#555;">Options for both luxury and budget-friendly travel.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div style="display:flex; gap:14px; padding:14px 16px; border-radius:10px; background:#ffffff; box-shadow:0 8px 20px rgba(0,0,0,0.04);">
                                    <div style="width:34px; height:34px; border-radius:50%; background:#25d366; display:flex; align-items:center; justify-content:center; color:#fff; font-size:18px;">
                                        ★
                                    </div>
                                    <div>
                                        <h5 style="margin:0 0 4px; font-size:16px;">Hassle‑Free Support</h5>
                                        <p style="margin:0; font-size:14px; color:#555;">Hassle-free booking and complete travel support.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Clean booking form -->
                <div class="col-lg-6">
                    <div class="card shadow-sm h-100" style="border-radius: 18px; border: none;">
                        <div class="card-body p-4 p-md-5">
                            <h3 class="mb-3">Ready to Plan Your Stay & Tour?</h3>
                            @php
                                $ctaChannels = app(\App\Services\SubmissionChannelService::class)->availableChannels($setting ?? null, 'contact');
                            @endphp
                            <form id="ctaForm" method="POST" action="{{ route('enquiries.store') }}">
                                @csrf
                                <input type="hidden" name="form_type" value="contact">
                                <input type="hidden" name="subject" value="General enquiry">
                                <div class="hp-field" aria-hidden="true" style="position:absolute;left:-9999px;height:0;overflow:hidden;">
                                    <input type="text" name="website_url" tabindex="-1" autocomplete="off">
                                </div>
                                @if(count($ctaChannels) > 0)
                                    @include('frontend.partials.kdr-submission-channels', ['channelContext' => 'contact'])
                                @endif
                                <div class="mb-3">
                                    <label class="form-label mb-1" style="font-weight: 600;">Full Name</label>
                                    <input type="text" name="names" class="form-control" placeholder="Your Name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label mb-1" style="font-weight: 600;">Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label mb-1" style="font-weight: 600;">Phone</label>
                                    <input type="text" name="phone" class="form-control" placeholder="+250 ..." >
                                </div>
                                <div class="mb-3">
                                    <label class="form-label mb-1" style="font-weight: 600;">What are you looking for?</label>
                                    <textarea name="message" class="form-control" rows="4" placeholder="Tell us about your accommodation or tour plans..." required></textarea>
                                </div>
                                <button type="submit" class="th-btn style3 w-100 mt-1">
                                    <i class="fas fa-paper-plane me-2"></i>Request a Custom Plan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--==============================
About Area  
==============================-->
    {{-- <div class="about-area position-relative overflow-hidden space" id="about-sec" data-bg-src="assets/img/bg/about_bg_1.jpg">
        <div class="container">
            <div class="row">
                <div class="col-xl-6">
                    <div class="img-box6">
                        <div class="img1">
                            <img src="assets/img/normal/about_6_1.jpg" alt="About">
                        </div>
                        <div class="img2">
                            <img src="assets/img/normal/about_6_2.jpg" alt="About">
                        </div>
                        <div class="img3">
                            <img src="assets/img/normal/about_6_3.jpg" alt="About">
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="ps-xl-5 ms-xl-3">
                        <div class="title-area mb-20">
                            <span class="sub-title style1">Let’s Go Together</span>
                            <h2 class="sec-title mb-20">Begin Your Travel Story with Us</h2>

                            <p class="sec-text mb-30">There are many variations of passages of available but the majority have suffered alteration in some form, by injected hum randomised words which don't look even slightly. </p>
                        </div>
                        <div class="about-item-wrap">
                            <div class="about-item">
                                <div class="about-item_img"><img src="assets/img/icon/map3.svg" alt=""></div>
                                <div class="about-item_centent">
                                    <h5 class="box-title">Exclusive Trip</h5>
                                    <p class="about-item_text">There are many variations of passages of available but the
                                        majority.</p>
                                </div>
                            </div>
                            <div class="about-item">
                                <div class="about-item_img"><img src="assets/img/icon/guide.svg" alt=""></div>
                                <div class="about-item_centent">
                                    <h5 class="box-title">Professional Guide</h5>
                                    <p class="about-item_text">There are many variations of passages of available but the
                                        majority.</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-35"><a href="{{ route('home') }}" class="th-btn style3 th-icon">Learn More</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
   
    <!--==============================


Service Area  
==============================-->


<style>
/* Featured Listings: small availability button */
#featured-listings-sec .featured-listing-avail-btn {
    padding: 6px 14px !important;
    font-size: 13px !important;
    white-space: nowrap;
}

/* Hero Section Fixes */
#hero.th-hero-wrapper.hero-7 {
    min-height: 500px !important;
    max-height: 600px;
    background-size: cover !important;
    background-position: center center !important;
    background-repeat: no-repeat !important;
    display: flex;
    align-items: center;
    position: relative;
}

#hero .container {
    width: 100%;
    position: relative;
    z-index: 2;
}

.hero-style7 {
    padding: 100px 0 30px 0 !important;
}

/* Hero search form - clear, high-contrast on image */
#heroSearchForm {
    transition: box-shadow 0.3s ease;
    background: #ffffff !important;
}

#heroSearchForm:hover {
    box-shadow: 0 14px 50px rgba(0, 0, 0, 0.25), 0 4px 15px rgba(0, 0, 0, 0.12) !important;
}

#heroSearchForm .form-control,
#heroSearchForm .form-select {
    transition: all 0.3s ease;
    background: #ffffff !important;
    color: #333 !important;
    border: 1px solid #dee2e6 !important;
}

#heroSearchForm .form-control:focus,
#heroSearchForm .form-select:focus {
    background: #ffffff !important;
    border-color: #25D366 !important;
    box-shadow: 0 0 0 0.2rem rgba(37, 211, 102, 0.2);
    outline: none;
    color: #333 !important;
}

#heroSearchForm .form-control::placeholder {
    color: #6c757d !important;
}

#heroSearchForm .position-absolute i {
    color: #6c757d !important;
}

#heroSearchForm select option {
    background: white;
    color: #333;
}

#heroSearchForm input[type="date"]::-webkit-calendar-picker-indicator {
    opacity: 0.6;
    cursor: pointer;
}

/* Button hover effect */
#searchBtn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(37, 211, 102, 0.5) !important;
}

@media (max-width: 991px) {
    #hero.th-hero-wrapper.hero-7 {
        min-height: 450px;
        max-height: none;
    }
    
    .hero-style7 {
        padding: 80px 0 25px 0 !important;
    }
    
    #heroSearchForm {
        padding: 20px !important;
    }
    
    #heroSearchForm .row.g-2 > div {
        margin-bottom: 12px;
    }
}

@media (max-width: 767px) {
    /* Hero height/layout handled by .hero-home and mobile block at top of page */
    .hero-style7 {
        padding: 60px 0 20px 0 !important;
    }
    
    #heroSearchForm .form-control,
    #heroSearchForm .form-select {
        font-size: 16px;
        height: 46px !important;
    }
    
    #heroSearchForm .form-item .position-absolute i {
        font-size: 14px;
        left: 12px !important;
    }
    
    #heroSearchForm .form-control,
    #heroSearchForm .form-select {
        padding-left: 40px !important;
    }
    
    #searchBtn {
        height: 46px !important;
        font-size: 1rem !important;
    }
}

/* Destination Card Hover Effects */
.destination-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12) !important;
}

.destination-card:hover .destination-card-img img {
    transform: scale(1.1);
}

/* Review Card Hover */
.review-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 25px rgba(0,0,0,0.12) !important;
}

/* Travel Service Card Hover */
.travel-service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(37, 211, 102, 0.2) !important;
}

/* Travel Services section – full-width background image */
.travel-services-section {
    position: relative;
    overflow: hidden;
}
.travel-services-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed; /* Parallax: background stays fixed while content scrolls */
}
@media (max-width: 768px) {
    .travel-services-bg {
        background-attachment: scroll; /* Fallback for mobile (fixed can cause issues) */
    }
}
.travel-services-bg::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.55) 0%, rgba(0, 0, 0, 0.4) 50%, rgba(0, 0, 0, 0.5) 100%);
    pointer-events: none;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const heroForm = document.getElementById('heroSearchForm');
    const checkinDate = document.getElementById('checkinDate');
    const checkoutDate = document.getElementById('checkoutDate');
    const formMessages = document.getElementById('formMessages');
    const searchBtn = document.getElementById('searchBtn');

    // Set minimum dates
    const today = new Date().toISOString().split('T')[0];
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const tomorrowStr = tomorrow.toISOString().split('T')[0];

    if (checkinDate && !checkinDate.value) {
        checkinDate.min = today;
    }
    if (checkoutDate && !checkoutDate.value) {
        checkoutDate.min = tomorrowStr;
    }

    // Update checkout minimum date when checkin changes
    if (checkinDate) {
        checkinDate.addEventListener('change', function() {
            if (this.value) {
                const checkin = new Date(this.value);
                checkin.setDate(checkin.getDate() + 1);
                checkoutDate.min = checkin.toISOString().split('T')[0];
                
                // If checkout is before new minimum, update it
                if (checkoutDate.value && checkoutDate.value < checkoutDate.min) {
                    checkoutDate.value = checkoutDate.min;
                }
            }
        });
    }

    // Form validation
    if (heroForm) {
        heroForm.addEventListener('submit', function(e) {
            let isValid = true;
            let errorMessage = '';

            // Validate dates
            if (checkinDate && checkoutDate) {
                if (checkinDate.value && checkoutDate.value) {
                    const checkin = new Date(checkinDate.value);
                    const checkout = new Date(checkoutDate.value);
                    
                    if (checkout <= checkin) {
                        isValid = false;
                        errorMessage = 'Check-out date must be after check-in date.';
                    }
                } else if (checkinDate.value && !checkoutDate.value) {
                    isValid = false;
                    errorMessage = 'Please select a check-out date.';
                } else if (!checkinDate.value && checkoutDate.value) {
                    isValid = false;
                    errorMessage = 'Please select a check-in date.';
                }
            }

            if (!isValid) {
                e.preventDefault();
                formMessages.textContent = errorMessage;
                formMessages.style.color = '#ff6b6b';
                formMessages.style.display = 'block';
                
                // Hide message after 5 seconds
                setTimeout(function() {
                    formMessages.style.display = 'none';
                }, 5000);
                return false;
            }

            // Show loading state
            if (searchBtn) {
                searchBtn.disabled = true;
                searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Searching...';
            }
        });
    }

    // Clear form messages on input
    const formInputs = heroForm?.querySelectorAll('input, select');
    if (formInputs) {
        formInputs.forEach(input => {
            input.addEventListener('input', function() {
                if (formMessages) {
                    formMessages.style.display = 'none';
                }
            });
        });
    }
});
</script>
@endsection