@php
    $googleData = $googleData ?? app(\App\Services\GoogleBusinessReviewService::class)->getData($setting ?? null);
    $writeUrl = $googleData['write_review_url'] ?? null;
    $profileUrl = $googleData['profile_url'] ?? null;
    $rating = $googleData['rating'] ?? null;
    $total = $googleData['total'] ?? null;
@endphp
<section class="kdr-google-reviews-cta py-5" id="google-reviews">
    <div class="container">
        <div class="kdr-card p-4 p-lg-5">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <span class="kdr-cars-hero__eyebrow d-inline-block mb-2"><i class="fab fa-google me-2"></i>Google Reviews</span>
                    <h2 class="h3 mb-2">Read and write reviews on Google</h2>
                    <p class="text-muted mb-0">
                        We do not collect star ratings on this website. All customer reviews are managed on our
                        <strong>Google Business Profile</strong> so feedback stays verified and public on Google.
                    </p>
                    @if($rating)
                    <p class="mb-0 mt-3">
                        <span class="text-warning">
                            @for($i = 1; $i <= 5; $i++)<i class="fas fa-star"></i>@endfor
                        </span>
                        <strong class="ms-1">{{ number_format($rating, 1) }}</strong>
                        @if($total)
                            <span class="text-muted">· {{ number_format($total) }} reviews on Google</span>
                        @endif
                    </p>
                    @endif
                </div>
                <div class="col-lg-4">
                    <div class="d-grid gap-2">
                        @if($writeUrl)
                        <a href="{{ $writeUrl }}" target="_blank" rel="noopener noreferrer" class="th-btn btn-kdr-primary">
                            <i class="fab fa-google me-2"></i>Write a review on Google
                        </a>
                        @endif
                        @if($profileUrl)
                        <a href="{{ $profileUrl }}" target="_blank" rel="noopener noreferrer" class="th-btn kdr-btn-navy">
                            <i class="fas fa-external-link-alt me-2"></i>View on Google Maps
                        </a>
                        @endif
                        <a href="{{ route('reviews.index') }}" class="btn btn-link text-muted">Browse all Google reviews</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
