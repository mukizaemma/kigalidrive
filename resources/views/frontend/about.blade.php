@extends('layouts.frontbase')

@section('content')
@if(session('success'))
<div class="container mt-3">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
@endif
@php
    $about = $about ?? null;
    $headerImage = (optional($setting)->home_header_image ?? null)
        ? asset('storage/images/site/' . optional($setting)->home_header_image)
        : ($about && $about->image1 ? asset('storage/images/about/' . $about->image1) : null);
    $title = optional($about)->title ?? 'About Kigali Drive Rentals';
    $tagline = optional($about)->subTitle ?? 'Premium car rentals and sales in Kigali, Rwanda.';
    $connectUrl = route('contact');
    $ctaServices = optional($about)->cta_services_url ?? route('showCars');
    $ctaBook = optional($about)->cta_book_url ?? route('showCars');
    $ctaContact = optional($about)->cta_contact_url ?? route('contact');
@endphp

    {{-- Hero / Header --}}
    <section class="about-hero position-relative overflow-hidden">
        @if($headerImage)
        <div class="about-hero-bg" style="background-image: url('{{ $headerImage }}');"></div>
        @else
        <div class="about-hero-bg" style="background: linear-gradient(135deg, #1a365d 0%, #2c5282 100%);"></div>
        @endif
        <div class="about-hero-overlay"></div>
        <div class="container position-relative">
            <div class="row about-hero-row align-items-center py-5">
                <div class="col-lg-10 mx-auto text-center about-hero-text">
                    <h1 class="display-4 fw-bold mb-3 text-white">{{ $title }}</h1>
                    <p class="lead mb-0 text-white about-hero-tagline">{{ $tagline }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Intro --}}
    @if($about && ($about->welcomeMessage || $about->mission))
    <section class="py-5 bg-light">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    @if($about->welcomeMessage)
                    <div class="about-intro mb-4">
                        {!! $about->welcomeMessage !!}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Mission & Vision --}}
    @if($about && ($about->mission || $about->vision))
    <section class="py-5">
        <div class="container py-4">
            <div class="row g-4">
                @if($about->mission)
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="h5 text-primary mb-3">Our Mission</h3>
                            <div class="text-secondary">{!! nl2br(e($about->mission)) !!}</div>
                        </div>
                    </div>
                </div>
                @endif
                @if($about->vision)
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="h5 text-primary mb-3">Our Vision</h3>
                            <div class="text-secondary">{!! nl2br(e($about->vision)) !!}</div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- What We Do --}}
    @if($about && $about->what_we_do)
    <section class="py-5 bg-light">
        <div class="container py-4">
            <h2 class="text-center mb-4">What We Do</h2>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="about-what-we-do">
                        {!! $about->what_we_do !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Why Choose Us --}}
    @if($about && $about->WhyChooseUs)
    <section class="py-5">
        <div class="container py-4">
            <h2 class="text-center mb-4">Why Choose Stay Nets</h2>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="about-why-choose">
                        {!! $about->WhyChooseUs !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Our Commitment --}}
    @if($about && $about->commitment)
    <section class="py-5 bg-light">
        <div class="container py-4">
            <h2 class="text-center mb-4">Our Commitment</h2>
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="about-commitment lead text-secondary">
                        {!! nl2br(e($about->commitment)) !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- CTA Buttons --}}
    <section class="py-5 border-top">
        <div class="container py-4">
            <h2 class="text-center mb-4">Get Started</h2>
            <div class="row g-3 justify-content-center flex-wrap">
                <div class="col-auto">
                    <a href="{{ $ctaServices }}" class="btn btn-primary btn-lg px-4">
                        <i class="fa fa-concierge-bell me-2"></i>Explore Our Services
                    </a>
                </div>
                <div class="col-auto">
                    <a href="{{ $ctaBook }}" class="btn btn-outline-primary btn-lg px-4">
                        <i class="fa fa-calendar-check me-2"></i>Book Your Stay or Adventure
                    </a>
                </div>
                <div class="col-auto">
                    <a href="{{ $ctaContact }}" class="btn btn-outline-secondary btn-lg px-4">
                        <i class="fa fa-envelope me-2"></i>Contact Us
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="kdr-google-reviews-section py-5" id="reviews-sec">
        <div class="container">
            <div class="row justify-content-center mb-4">
                <div class="col-lg-8 text-center">
                    <span class="kdr-cars-hero__eyebrow"><i class="fab fa-google me-2"></i>Google Reviews</span>
                    <h2 class="kdr-section-title mt-2">What clients say about us</h2>
                    <p class="text-muted">Verified reviews from our Google Business Profile</p>
                </div>
            </div>
            @include('frontend.partials.google-reviews-grid', ['googleData' => $googleData ?? []])
            <div class="text-center mt-4">
                <a href="{{ route('reviews.index') }}" class="th-btn btn-kdr-outline-dark">View all Google reviews</a>
            </div>
        </div>
    </section>

<style>
.about-hero { min-height: 65vh; }
.about-hero-row { min-height: 65vh; }
.about-hero-text h1,
.about-hero-text .lead,
.about-hero-tagline { color: #fff !important; text-shadow: 0 1px 3px rgba(0,0,0,0.5); }
.about-hero-tagline { opacity: 0.95; }
.about-hero-bg {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}
.about-hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,.4) 0%, rgba(0,0,0,.65) 100%);
}
.about-hero .container { z-index: 1; }
.about-intro, .about-what-we-do, .about-why-choose { font-size: 1.05rem; line-height: 1.7; }
.about-intro p, .about-what-we-do p, .about-why-choose p { margin-bottom: 1rem; }
.about-what-we-do ul, .about-why-choose ul { padding-left: 1.25rem; }
.about-what-we-do li, .about-why-choose li { margin-bottom: 0.5rem; }
</style>
@endsection
