@extends('layouts.frontbase')

@section('title', 'Google Reviews | ' . (optional($setting)->company ?? 'Kigali Drive Rentals'))

@section('content')
<section class="kdr-google-reviews-page py-4 py-lg-5">
    <div class="container">
        <div class="kdr-cars-hero mb-4 mb-lg-5">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <span class="kdr-cars-hero__eyebrow"><i class="fab fa-google me-2"></i>Google Reviews</span>
                    <h1 class="kdr-cars-hero__title">What our customers say</h1>
                    <p class="kdr-cars-hero__lead mb-0">Real reviews from our Google Business Profile — trusted feedback from clients who rented or bought vehicles through us in Kigali.</p>
                </div>
                @if($googleData['write_review_url'] ?? null)
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ $googleData['write_review_url'] }}" target="_blank" rel="noopener noreferrer" class="th-btn btn-kdr-primary">
                        <i class="fab fa-google me-2"></i>Write a review on Google
                    </a>
                </div>
                @endif
            </div>
        </div>

        @if(!empty($googleData['error']))
            <div class="alert alert-warning mb-4" role="alert">{{ $googleData['error'] }}</div>
        @endif

        <section class="kdr-google-reviews-latest mb-5" aria-labelledby="kdrLatestReviewsTitle">
            <h2 id="kdrLatestReviewsTitle" class="kdr-google-reviews-latest__title text-center">From our Happy Clients</h2>
            @if($googleData['rating'] ?? null)
            <p class="text-center text-muted mb-4 small">
                <i class="fab fa-google text-primary me-1"></i>
                {{ number_format($googleData['rating'], 1) }} ★
                @if($googleData['total'] ?? null)
                    · {{ number_format($googleData['total']) }} reviews on Google
                @endif
            </p>
            @endif

            @include('frontend.partials.google-reviews-grid', [
                'googleData' => array_merge($googleData, ['reviews' => $latestReviews ?? []]),
                'limit' => 6,
                'variant' => 'dark',
                'showSummary' => false,
            ])
        </section>

        @if(($googleData['profile_url'] ?? null) && count($latestReviews ?? []) > 0)
        <div class="text-center">
            <a href="{{ $googleData['profile_url'] }}" target="_blank" rel="noopener noreferrer" class="th-btn btn-kdr-outline-dark">
                <i class="fab fa-google me-2"></i>See all reviews on Google
            </a>
        </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/kdr-google-reviews.js') }}" defer></script>
@endpush
