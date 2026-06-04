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
    $storyImage = ($about && $about->image2)
        ? asset('storage/images/about/' . $about->image2)
        : null;
    $title = optional($about)->title ?? 'About Kigali Drive Rentals';
    $tagline = optional($about)->subTitle ?? 'Rwanda\'s trusted car rental partner in Kigali.';
    $ctaFleet = optional($about)->cta_book_url ?? route('showCars');
    $ctaContact = optional($about)->cta_contact_url ?? route('contact');
    $wa = (optional($setting)->whatsapp_enabled ?? true) && (optional($setting)->whatsapp ?? optional($setting)->phone)
        ? 'https://wa.me/' . preg_replace('/\D+/', '', optional($setting)->whatsapp ?? optional($setting)->phone)
        : null;
@endphp

<section class="kdr-about-hero" aria-label="About us">
    @if($headerImage)
    <div class="kdr-about-hero__bg" style="background-image: url('{{ $headerImage }}');" aria-hidden="true"></div>
    @else
    <div class="kdr-about-hero__bg kdr-about-hero__bg--fallback" aria-hidden="true"></div>
    @endif
    <div class="kdr-about-hero__overlay" aria-hidden="true"></div>
    <div class="container kdr-about-hero__content">
        <span class="kdr-about-hero__eyebrow">About us</span>
        <h1 class="kdr-about-hero__title">{{ $title }}</h1>
        <p class="kdr-about-hero__lead">{{ $tagline }}</p>
    </div>
</section>

<div class="kdr-about-page">
    @if($about && $about->welcomeMessage)
    <section class="kdr-about-story">
        <div class="container">
            <div class="kdr-about-story__card">
                <div class="row g-4 g-lg-5 align-items-center">
                    <div class="{{ $storyImage ? 'col-lg-7' : 'col-12' }}">
                        <div class="kdr-about-prose">
                            {!! $about->welcomeMessage !!}
                        </div>
                        <ul class="kdr-about-stats" role="list">
                            <li><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Based in Kigali</li>
                            <li><i class="fas fa-dollar-sign" aria-hidden="true"></i> Clear USD rates</li>
                            <li><i class="fas fa-headset" aria-hidden="true"></i> Fast WhatsApp support</li>
                        </ul>
                    </div>
                    @if($storyImage)
                    <div class="col-lg-5">
                        <div class="kdr-about-story__media">
                            <img src="{{ $storyImage }}" alt="Kigali Drive Rentals team and fleet" loading="lazy">
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    @if($about && ($about->mission || $about->vision))
    <section class="kdr-about-values" aria-labelledby="kdr-about-values-heading">
        <div class="container">
            <h2 id="kdr-about-values-heading" class="kdr-about-section-title text-center">Mission &amp; vision</h2>
            <div class="row g-4">
                @if($about->mission)
                <div class="col-md-6">
                    <article class="kdr-about-value-card h-100">
                        <span class="kdr-about-value-card__icon" aria-hidden="true"><i class="fas fa-bullseye"></i></span>
                        <h3 class="kdr-about-value-card__title">Our mission</h3>
                        <div class="kdr-about-value-card__text">{!! nl2br(e($about->mission)) !!}</div>
                    </article>
                </div>
                @endif
                @if($about->vision)
                <div class="col-md-6">
                    <article class="kdr-about-value-card h-100">
                        <span class="kdr-about-value-card__icon" aria-hidden="true"><i class="fas fa-eye"></i></span>
                        <h3 class="kdr-about-value-card__title">Our vision</h3>
                        <div class="kdr-about-value-card__text">{!! nl2br(e($about->vision)) !!}</div>
                    </article>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    @if($about && $about->what_we_do)
    <section class="kdr-about-block kdr-about-block--cream" aria-labelledby="kdr-about-what-heading">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <h2 id="kdr-about-what-heading" class="kdr-about-section-title text-center">What we do</h2>
                    <div class="kdr-about-prose kdr-about-prose--center">
                        {!! $about->what_we_do !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if($about && $about->commitment)
    <section class="kdr-about-quote" aria-label="Our commitment">
        <div class="container">
            <blockquote class="kdr-about-quote__inner">
                <i class="fas fa-quote-left kdr-about-quote__mark" aria-hidden="true"></i>
                <p>{!! nl2br(e($about->commitment)) !!}</p>
            </blockquote>
        </div>
    </section>
    @endif

    <section class="kdr-about-cta-band" aria-label="Get started">
        <div class="container">
            <div class="kdr-about-cta-band__inner">
                <div>
                    <h2 class="kdr-about-cta-band__title">Ready to hire your car?</h2>
                    <p class="kdr-about-cta-band__lead mb-0">Browse our fleet or message our team — we reply quickly on WhatsApp.</p>
                </div>
                <div class="kdr-about-cta-band__actions">
                    <a href="{{ $ctaFleet }}" class="th-btn btn-kdr-primary">
                        <i class="fas fa-car me-2" aria-hidden="true"></i>Browse fleet
                    </a>
                    <a href="{{ $ctaContact }}" class="th-btn btn-kdr-outline-dark">Contact us</a>
                    @if($wa)
                    <a href="{{ $wa }}" target="_blank" rel="noopener" class="th-btn btn-kdr-outline-dark">
                        <i class="fab fa-whatsapp me-2" aria-hidden="true"></i>WhatsApp
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="kdr-about-reviews" id="reviews-sec" aria-labelledby="kdr-about-reviews-heading">
        <div class="container">
            <div class="text-center mb-4">
                <span class="kdr-cars-hero__eyebrow"><i class="fab fa-google me-2" aria-hidden="true"></i>Google reviews</span>
                <h2 id="kdr-about-reviews-heading" class="kdr-about-section-title mt-2">What clients say about us</h2>
                <p class="text-muted mb-0">Verified feedback from our Google Business Profile</p>
            </div>
            @include('frontend.partials.google-reviews-grid', [
                'googleData' => $googleData ?? [],
                'limit' => 3,
                'showSummary' => true,
            ])
            <div class="text-center mt-4">
                <a href="{{ route('reviews.index') }}" class="th-btn btn-kdr-outline-dark">View all Google reviews</a>
            </div>
        </div>
    </section>
</div>

@include('frontend.partials.kdr-why-choose-us')
@endsection
