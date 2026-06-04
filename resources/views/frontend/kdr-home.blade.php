@extends('layouts.frontbase')

@section('content')
@php
    $wa = (optional($setting)->whatsapp_enabled ?? true) && (optional($setting)->whatsapp ?? optional($setting)->phone)
        ? 'https://wa.me/' . preg_replace('/\D+/', '', optional($setting)->whatsapp ?? optional($setting)->phone)
        : null;
    $defaultHeroTitle = optional($setting)->tagline ?? 'Premium Car Rentals in Kigali';
    $defaultHeroSubtitle = 'Self-drive or with driver — daily, weekly & monthly hire across Rwanda.';
@endphp

@include('frontend.partials.kdr-hero-composite', [
    'heroSlides' => $slides ?? collect(),
    'defaultHeroTitle' => $defaultHeroTitle,
    'defaultHeroSubtitle' => $defaultHeroSubtitle,
    'hireIntro' => $hireIntro ?? null,
    'hireScenarios' => $hireScenarios ?? collect(),
    'setting' => $setting ?? null,
    'fleetCount' => $fleetCount ?? 0,
    'heroFromPrice' => $heroFromPrice ?? null,
    'whatsappUrl' => $wa,
])

@include('frontend.partials.kdr-home-marketing-band', [
    'hireIntro' => $hireIntro ?? null,
    'hireScenarios' => $hireScenarios ?? collect(),
    'googleReviews' => $googleReviews ?? [],
])

<section class="py-5" id="featured-rentals">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
            <div>
                <h2 class="kdr-section-title mb-1">Our rental fleet</h2>
                <p class="text-muted mb-0">Hand-picked vehicles — transparent USD rates, with or without driver</p>
            </div>
            <a href="{{ route('showCars') }}" class="th-btn btn-kdr-primary btn-sm">View all cars</a>
        </div>
        <div class="row g-4">
            @forelse($featuredCars as $car)
            <div class="col-md-6 col-lg-4">
                @include('frontend.partials.car_card', ['car' => $car, 'rentalPeriod' => 'day'])
            </div>
            @empty
            <div class="col-12">
                <div class="kdr-empty-state text-center py-5">
                    <p class="text-muted mb-3">New vehicles are being added to our fleet.</p>
                    <a href="{{ route('contact') }}" class="th-btn btn-kdr-primary">Tell us what you need</a>
                </div>
            </div>
            @endforelse
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
    var el = document.getElementById('kdrHeroVisualSlider');
    if (!el || typeof Swiper === 'undefined') return;
    var slideCount = el.querySelectorAll('.swiper-slide').length;
    new Swiper(el, {
        effect: 'fade',
        fadeEffect: { crossFade: true },
        speed: 900,
        loop: slideCount > 1,
        autoplay: slideCount > 1 ? { delay: 6000, disableOnInteraction: false } : false,
        pagination: {
            el: el.querySelector('.kdr-hero-visual__pagination'),
            clickable: true
        },
        navigation: {
            nextEl: el.querySelector('.kdr-hero-visual__nav--next'),
            prevEl: el.querySelector('.kdr-hero-visual__nav--prev')
        }
    });
});
</script>
@endpush
