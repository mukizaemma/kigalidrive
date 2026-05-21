@extends('layouts.frontbase')

@section('title', 'Google Reviews | ' . (optional($setting)->company ?? 'Kigali Drive Rentals'))

@section('content')
<section class="kdr-google-reviews-page py-4 py-lg-5">
    <div class="container">
        <div class="kdr-cars-hero mb-5">
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

        @include('frontend.partials.google-reviews-grid', ['googleData' => $googleData])
    </div>
</section>
@endsection
