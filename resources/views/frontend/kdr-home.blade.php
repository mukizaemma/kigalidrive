@extends('layouts.frontbase')

@section('content')
@php
    $wa = (optional($setting)->whatsapp_enabled ?? true) && (optional($setting)->whatsapp ?? optional($setting)->phone)
        ? 'https://wa.me/' . preg_replace('/\D+/', '', optional($setting)->whatsapp ?? optional($setting)->phone)
        : null;
    $defaultHeroTitle = optional($setting)->tagline ?? 'Premium Car Rentals & Sales in Kigali';
    $defaultHeroSubtitle = 'Your trusted partner for self-drive and chauffeur rentals — plus quality vehicles for sale across Rwanda.';
    $overviewTitle = optional($about)->title ?? 'Welcome to Kigali Drive Rentals';
    $overviewTagline = optional($about)->subTitle ?? 'Rwanda\'s trusted car rental and sales partner';
    $overviewBodyRaw = optional($about)->welcomeMessage;
    $overviewBody = $overviewBodyRaw
        ? \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($overviewBodyRaw))), 520)
        : 'At Kigali Drive Rentals, we connect you with quality vehicles for rent and purchase in Kigali — with transparent pricing, flexible terms, and professional service.';
    $overviewImage = ($about && $about->image1)
        ? asset('storage/images/about/' . ltrim($about->image1, '/'))
        : asset('assets/img/bg/about_bg_1.jpg');
    $overviewUsesFallbackImage = !($about && $about->image1);
    $journeyTitle = optional($about)->mission
        ? \Illuminate\Support\Str::limit(trim(strip_tags($about->mission)), 80)
        : 'Your Journey Starts With the Right Vehicle';
    $journeyText = optional($about)->commitment
        ? trim(strip_tags($about->commitment))
        : (optional($about)->mission
            ? \Illuminate\Support\Str::limit(trim(strip_tags($about->mission)), 220)
            : 'We help you move with confidence — reliable fleet, clear rates, and trusted listings across Kigali.');
    $ctaServices = optional($about)->cta_services_url ?? route('showCars');
    $ctaBook = optional($about)->cta_book_url ?? route('showCars');
    $overviewImage2 = ($about && $about->image2)
        ? asset('storage/images/about/' . ltrim($about->image2, '/'))
        : ($overviewUsesFallbackImage ? asset('assets/img/bg/about_bg_2.jpg') : null);
    $highlightIcons = ['fa-car', 'fa-tags', 'fa-shield', 'fa-location-dot'];
    $introStats = [
        ['icon' => 'fa-location-dot', 'value' => 'Kigali', 'label' => 'Rwanda HQ'],
        ['icon' => 'fa-car', 'value' => 'Rent & Sale', 'label' => 'Full fleet'],
    ];
    if (!empty($googleReviews['rating'])) {
        $introStats[] = [
            'icon' => 'fa-star',
            'value' => number_format($googleReviews['rating'], 1) . '★',
            'label' => 'Google rating',
        ];
    } else {
        $introStats[] = ['icon' => 'fa-headset', 'value' => '24/7', 'label' => 'Client support'];
    }
    $highlights = [];
    if ($about && $about->WhyChooseUs) {
        $raw = strip_tags($about->WhyChooseUs);
        $parts = preg_split('/\||\n|<\/li>/i', $raw);
        foreach ($parts as $part) {
            $part = trim(preg_replace('/^[\-\*•\d\.\)\s]+/', '', $part));
            if ($part !== '') {
                $highlights[] = \Illuminate\Support\Str::limit($part, 48);
            }
        }
        $highlights = array_slice(array_unique($highlights), 0, 4);
    }
    if (empty($highlights)) {
        $highlights = ['Premium fleet', 'Rent or buy', 'Transparent pricing', 'Local expertise'];
    }
    $carsForSale = $featuredCars->filter(fn ($c) => in_array($c->listing_type ?? '', ['sale', 'both'], true))->take(3);
    if ($carsForSale->isEmpty()) {
        $carsForSale = $featuredCars->take(3);
    }
@endphp

@include('frontend.partials.kdr-hero-slider', [
    'heroSlides' => $slides ?? collect(),
    'defaultHeroTitle' => $defaultHeroTitle,
    'defaultHeroSubtitle' => $defaultHeroSubtitle,
])

@include('frontend.partials.kdr-home-car-search', ['carBrands' => $carBrands ?? collect()])

<section class="kdr-company-intro" aria-labelledby="kdr-overview-heading">
    <div class="kdr-company-intro__bg" aria-hidden="true"></div>
    <div class="container position-relative">
        <div class="row align-items-center g-4 g-lg-5">
            <div class="col-lg-5">
                <div class="kdr-company-intro__visual">
                    <div class="kdr-company-intro__frame" aria-hidden="true"></div>
                    <div class="kdr-company-intro__media" style="background-image:url('{{ $overviewImage }}');" role="img" aria-label="{{ $overviewTitle }}"></div>
                    @if($overviewImage2)
                    <div class="kdr-company-intro__media kdr-company-intro__media--accent" style="background-image:url('{{ $overviewImage2 }}');" aria-hidden="true"></div>
                    @endif
                    <span class="kdr-company-intro__badge"><i class="fas fa-location-dot" aria-hidden="true"></i> Kigali, Rwanda</span>
                    <ul class="kdr-company-intro__stats" role="list">
                        @foreach($introStats as $stat)
                        <li class="kdr-company-intro__stat">
                            <span class="kdr-company-intro__stat-icon" aria-hidden="true"><i class="fas {{ $stat['icon'] }}"></i></span>
                            <span class="kdr-company-intro__stat-value">{{ $stat['value'] }}</span>
                            <span class="kdr-company-intro__stat-label">{{ $stat['label'] }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="kdr-company-intro__content">
                    <p class="kdr-overview-eyebrow mb-2"><span class="kdr-overview-eyebrow__line" aria-hidden="true"></span>About us</p>
                    <h2 id="kdr-overview-heading" class="kdr-overview-title">{{ $overviewTitle }}</h2>
                    @if($overviewTagline)
                        <p class="kdr-overview-tagline">{{ $overviewTagline }}</p>
                    @endif
                    <p class="kdr-overview-text">{{ $overviewBody }}</p>
                    <ul class="kdr-overview-highlights" role="list">
                        @foreach($highlights as $index => $highlight)
                        <li class="kdr-overview-highlight">
                            <span class="kdr-overview-highlight__icon" aria-hidden="true"><i class="fas {{ $highlightIcons[$index % count($highlightIcons)] }}"></i></span>
                            <span class="kdr-overview-highlight__text">{{ $highlight }}</span>
                        </li>
                        @endforeach
                    </ul>
                    <div class="kdr-overview-actions">
                        <a href="{{ route('about') }}" class="th-btn btn-kdr-primary">Our story <i class="fas fa-arrow-right ms-2" aria-hidden="true"></i></a>
                        <a href="{{ route('contact') }}" class="th-btn btn-kdr-outline-dark">Contact us</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
            <div>
                <h2 class="kdr-section-title mb-1">Featured Cars for Rent</h2>
                <p class="text-muted mb-0">Daily, weekly, monthly &amp; custom — with or without driver</p>
            </div>
            <a href="{{ route('showCars') }}" class="th-btn btn-kdr-primary btn-sm">View all rentals</a>
        </div>
        <div class="row g-4">
            @forelse($featuredCars as $car)
            <div class="col-md-6 col-lg-4">
                @include('frontend.partials.car_card', ['car' => $car, 'rentalPeriod' => 'day'])
            </div>
            @empty
            <p class="text-muted">Premium vehicles coming soon.</p>
            @endforelse
        </div>
    </div>
</section>

@if($carsForSale->isNotEmpty())
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
            <div>
                <h2 class="kdr-section-title mb-1">Cars for Sale</h2>
                <p class="text-muted mb-0">Quality pre-owned and new vehicles — transparent pricing in RWF</p>
            </div>
            <a href="{{ route('showCars', ['listing_type' => 'sale']) }}" class="th-btn btn-kdr-primary btn-sm">View all for sale</a>
        </div>
        <div class="row g-4">
            @foreach($carsForSale as $car)
            <div class="col-md-6 col-lg-4">
                @include('frontend.partials.car_card', ['car' => $car])
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="kdr-home-journey py-5">
    <div class="container">
        <div class="row g-4 g-lg-5 align-items-stretch">
            <div class="col-lg-6">
                <div class="kdr-home-journey__panel h-100">
                    <p class="kdr-home-journey__eyebrow">Our promise</p>
                    <h2 class="kdr-home-journey__title">{{ $journeyTitle }}</h2>
                    <p class="kdr-home-journey__text">{{ $journeyText }}</p>
                    <div class="kdr-home-journey__actions">
                        <a href="{{ $ctaServices }}" class="th-btn btn-kdr-primary">Browse fleet</a>
                        <a href="{{ $ctaBook }}" class="th-btn kdr-btn-navy">Book now</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="kdr-home-reviews h-100">
                    <div class="kdr-home-reviews__header">
                        <div>
                            <p class="kdr-home-reviews__eyebrow mb-1">Testimonials</p>
                            <h3 class="kdr-home-reviews__title mb-0">What clients say</h3>
                        </div>
                        @if(!empty($googleReviews['rating']))
                        <div class="kdr-home-reviews__score" title="Google rating">
                            <span class="kdr-home-reviews__score-num">{{ number_format($googleReviews['rating'], 1) }}</span>
                            <span class="kdr-home-reviews__score-stars text-warning">
                                @for($i = 1; $i <= 5; $i++)<i class="fas fa-star"></i>@endfor
                            </span>
                            <small class="d-block text-muted">Google</small>
                        </div>
                        @else
                        <span class="small text-muted"><i class="fab fa-google"></i> Google</span>
                        @endif
                    </div>
                    @forelse($businessReviews as $review)
                    <blockquote class="mb-3 pb-3 border-bottom">
                        <div class="text-warning mb-1 small">
                            @for($i = 1; $i <= ($review['rating'] ?? 5); $i++)<i class="fas fa-star"></i>@endfor
                        </div>
                        <p class="mb-1 fst-italic">"{{ Str::limit($review['text'] ?? '', 120) }}"</p>
                        <small class="text-muted">— {{ $review['author_name'] ?? 'Google user' }}</small>
                    </blockquote>
                    @empty
                    <p class="text-muted mb-2">See what clients say about us on Google.</p>
                    @endforelse
                    @if(($googleReviews['write_review_url'] ?? null))
                    <a href="{{ $googleReviews['write_review_url'] }}" target="_blank" rel="noopener noreferrer" class="th-btn btn-kdr-primary btn-sm w-100 mt-2">
                        <i class="fab fa-google me-1"></i> Write a review
                    </a>
                    @endif
                    <a href="{{ route('reviews.index') }}" class="btn btn-link btn-sm w-100 mt-1 text-muted">View all reviews</a>
                </div>
            </div>
        </div>
    </div>
</section>

@if($wa)
<a href="{{ $wa }}" target="_blank" class="kdr-whatsapp-float" rel="noopener" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById('kdrHeroSlider');
    if (!el || typeof Swiper === 'undefined') return;
    var slideCount = el.querySelectorAll('.swiper-slide').length;
    new Swiper(el, {
        effect: 'fade',
        fadeEffect: { crossFade: true },
        loop: slideCount > 1,
        autoplay: slideCount > 1 ? { delay: 6000, disableOnInteraction: false } : false,
        pagination: {
            el: el.querySelector('.kdr-hero-pagination'),
            clickable: true
        },
        navigation: {
            nextEl: el.querySelector('.kdr-hero-nav--next'),
            prevEl: el.querySelector('.kdr-hero-nav--prev')
        }
    });
});
</script>
@endpush
