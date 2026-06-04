@extends('layouts.frontbase')

@section('title', 'FAQ | ' . (optional($setting)->company ?? 'Kigali Drive Rentals'))

@section('content')
@php
    $googleData = $googleData ?? [];
    $writeUrl = $googleData['write_review_url'] ?? null;
    $profileUrl = $googleData['profile_url'] ?? null;
    $rating = $googleData['rating'] ?? null;
    $total = $googleData['total'] ?? null;
    $wa = (optional($setting)->whatsapp_enabled ?? true) && (optional($setting)->whatsapp ?? optional($setting)->phone)
        ? 'https://wa.me/' . preg_replace('/\D+/', '', optional($setting)->whatsapp ?? optional($setting)->phone)
        : null;
@endphp

<section class="kdr-faq-page py-5 mt-4">
    <div class="container">
        <div class="row justify-content-center mb-4 mb-lg-5">
            <div class="col-lg-10 text-center">
                <h1 class="kdr-section-title mb-2">Frequently Asked Questions</h1>
                <p class="text-muted mb-0">Answers about renting, bookings, reviews, and our fleet in Kigali.</p>
            </div>
        </div>

        <div class="row g-4 g-lg-5 align-items-start">
            {{-- Column 1: FAQ --}}
            <div class="col-lg-5">
                <h2 class="kdr-faq-page__col-title"><i class="fas fa-circle-question me-2" aria-hidden="true"></i>FAQ</h2>
                @if($faqs->isNotEmpty())
                <div class="accordion kdr-faq-accordion" id="faqAccordion">
                    @foreach($faqs as $i => $faq)
                    <div class="accordion-item kdr-card mb-2 border-0">
                        <h3 class="accordion-header">
                            <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq{{ $faq->id }}"
                                    aria-expanded="{{ $i === 0 ? 'true' : 'false' }}">
                                {{ $faq->question }}
                            </button>
                        </h3>
                        <div id="faq{{ $faq->id }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}"
                             data-bs-parent="#faqAccordion">
                            <div class="accordion-body">{!! nl2br(e($faq->answer)) !!}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="kdr-empty-state text-center py-4">
                    <p class="text-muted mb-0">FAQ content is being updated. Please <a href="{{ route('contact') }}">contact us</a> for help.</p>
                </div>
                @endif
            </div>

            {{-- Column 2: Google reviews --}}
            <div class="col-lg-4">
                <h2 class="kdr-faq-page__col-title"><i class="fab fa-google me-2" aria-hidden="true"></i>Reviews</h2>
                <div class="kdr-faq-reviews-card kdr-card p-4 h-100">
                    <p class="text-muted small mb-3">
                        Customer feedback is on our <strong>Google Business Profile</strong> — verified and public on Google.
                    </p>
                    @if($rating && $total)
                    <div class="kdr-google-summary mb-3">
                        <span class="kdr-google-summary__logo"><i class="fab fa-google"></i></span>
                        <span class="kdr-google-summary__rating">{{ number_format($rating, 1) }}</span>
                        <span class="kdr-google-summary__stars text-warning">
                            @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star{{ $i <= round($rating) ? '' : '' }}" @if($i > ceil($rating)) style="opacity:0.25" @endif></i>
                            @endfor
                        </span>
                        <span class="kdr-google-summary__count text-muted d-block mt-1">{{ number_format($total) }} reviews</span>
                    </div>
                    @endif

                    @include('frontend.partials.google-reviews-grid', [
                        'googleData' => $googleData,
                        'limit' => 3,
                        'compact' => true,
                        'showSummary' => false,
                    ])

                    <div class="d-grid gap-2 mt-3">
                        @if($writeUrl)
                        <a href="{{ $writeUrl }}" target="_blank" rel="noopener noreferrer" class="th-btn btn-kdr-primary btn-sm">
                            <i class="fab fa-google me-2"></i>Write a review
                        </a>
                        @endif
                        @if($profileUrl)
                        <a href="{{ $profileUrl }}" target="_blank" rel="noopener noreferrer" class="th-btn btn-kdr-outline-dark btn-sm">
                            <i class="fas fa-external-link-alt me-2"></i>View on Google
                        </a>
                        @endif
                        <a href="{{ route('reviews.index') }}" class="btn btn-link text-muted btn-sm text-center">Browse all Google reviews</a>
                    </div>
                </div>
            </div>

            {{-- Column 3: Quick help --}}
            <div class="col-lg-3">
                <h2 class="kdr-faq-page__col-title"><i class="fas fa-headset me-2" aria-hidden="true"></i>Need help?</h2>
                <aside class="kdr-faq-sidebar kdr-card p-4">
                    <p class="text-muted small mb-3">Our team replies quickly — choose the option that suits you.</p>
                    <ul class="kdr-faq-sidebar__links list-unstyled mb-0">
                        <li>
                            <a href="{{ route('showCars') }}" class="kdr-faq-sidebar__link">
                                <span class="kdr-faq-sidebar__icon" aria-hidden="true"><i class="fas fa-car"></i></span>
                                <span>Browse rental fleet</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('contact') }}" class="kdr-faq-sidebar__link">
                                <span class="kdr-faq-sidebar__icon" aria-hidden="true"><i class="fas fa-envelope"></i></span>
                                <span>Contact us</span>
                            </a>
                        </li>
                        @if($wa)
                        <li>
                            <a href="{{ $wa }}" target="_blank" rel="noopener" class="kdr-faq-sidebar__link kdr-faq-sidebar__link--wa">
                                <span class="kdr-faq-sidebar__icon" aria-hidden="true"><i class="fab fa-whatsapp"></i></span>
                                <span>Chat on WhatsApp</span>
                            </a>
                        </li>
                        @endif
                        <li>
                            <a href="{{ route('services.index') }}" class="kdr-faq-sidebar__link">
                                <span class="kdr-faq-sidebar__icon" aria-hidden="true"><i class="fas fa-concierge-bell"></i></span>
                                <span>Our services</span>
                            </a>
                        </li>
                    </ul>
                </aside>
            </div>
        </div>
    </div>
</section>
@endsection
