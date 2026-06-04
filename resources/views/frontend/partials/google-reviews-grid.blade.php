@php
    $googleData = $googleData ?? [];
    $limit = $limit ?? null;
    $reviews = collect($googleData['reviews'] ?? []);
    if ($limit) {
        $reviews = $reviews->take((int) $limit);
    }
    $reviews = $reviews->all();
    $writeUrl = $googleData['write_review_url'] ?? null;
    $profileUrl = $googleData['profile_url'] ?? null;
    $rating = $googleData['rating'] ?? null;
    $total = $googleData['total'] ?? null;
    $compact = $compact ?? false;
    $variant = $variant ?? 'light';
    $showSummary = $showSummary ?? true;
    $gridClass = $variant === 'dark' ? 'kdr-google-reviews-grid kdr-google-reviews-grid--dark' : 'kdr-google-reviews-grid';
@endphp

@if($showSummary && $writeUrl)
<div class="row mb-4">
    <div class="col-12 d-flex flex-wrap justify-content-between align-items-center gap-3">
        @if($rating && $total)
        <div class="kdr-google-summary">
            <span class="kdr-google-summary__logo"><i class="fab fa-google"></i></span>
            <span class="kdr-google-summary__rating">{{ number_format($rating, 1) }}</span>
            <span class="kdr-google-summary__stars text-warning">
                @for($i = 1; $i <= 5; $i++)
                <i class="fas fa-star{{ $i <= round($rating) ? '' : '-half-alt' }}" style="{{ $i > ceil($rating) ? 'opacity:0.25' : '' }}"></i>
                @endfor
            </span>
            <span class="kdr-google-summary__count text-muted">{{ number_format($total) }} reviews on Google</span>
        </div>
        @else
        <p class="text-muted mb-0 small"><i class="fab fa-google me-1"></i> Verified reviews from Google</p>
        @endif
        <a href="{{ $writeUrl }}" target="_blank" rel="noopener noreferrer" class="th-btn btn-kdr-primary">
            <i class="fab fa-google me-2"></i>Write a review on Google
        </a>
    </div>
</div>
@endif

<div class="{{ $gridClass }}">
    <div class="row gy-4">
        @forelse($reviews as $review)
        <div class="{{ $compact ? 'col-12' : 'col-lg-4 col-md-6' }}">
            <article class="kdr-google-review-card h-100" data-kdr-review-card>
                <div class="kdr-google-review-card__head">
                    @if(!empty($review['author_photo']))
                    <img src="{{ $review['author_photo'] }}" alt="" class="kdr-google-review-card__avatar" loading="lazy" referrerpolicy="no-referrer">
                    @else
                    <div class="kdr-google-review-card__avatar kdr-google-review-card__avatar--fallback" aria-hidden="true">
                        {{ strtoupper(substr($review['author_name'] ?? 'G', 0, 1)) }}
                    </div>
                    @endif
                    <div class="kdr-google-review-card__identity">
                        <h3 class="kdr-google-review-card__name">{{ $review['author_name'] }}</h3>
                        @if(!empty($review['relative_time']))
                        <p class="kdr-google-review-card__when">{{ $review['relative_time'] }}</p>
                        @endif
                    </div>
                    <span class="kdr-google-review-card__badge" title="Posted on Google"><i class="fab fa-google"></i></span>
                </div>
                <div class="kdr-google-review-card__rating-row">
                    <span class="kdr-google-review-card__stars text-warning" aria-label="{{ $review['rating'] ?? 5 }} out of 5 stars">
                        @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star{{ $i <= ($review['rating'] ?? 5) ? '' : ' text-muted' }}" @if($i > ($review['rating'] ?? 5)) style="opacity:0.25" @endif></i>
                        @endfor
                    </span>
                    <span class="kdr-google-review-card__verified" title="Verified Google review"><i class="fas fa-check-circle"></i></span>
                </div>
                <div class="kdr-google-review-card__body">
                    <p class="kdr-google-review-card__text" data-kdr-review-text>{{ $review['text'] }}</p>
                    <button type="button" class="kdr-google-review-card__more d-none" data-kdr-review-more hidden>Read more</button>
                </div>
            </article>
        </div>
        @empty
        <div class="col-12">
            <div class="kdr-empty-state text-center py-5">
                @if($profileUrl)
                <p class="text-muted mb-3">View our latest reviews on Google Business Profile.</p>
                <a href="{{ $profileUrl }}" target="_blank" rel="noopener noreferrer" class="th-btn btn-kdr-primary me-2">
                    <i class="fab fa-google me-2"></i>View on Google
                </a>
                @if($writeUrl)
                <a href="{{ $writeUrl }}" target="_blank" rel="noopener noreferrer" class="th-btn btn-kdr-outline-dark">
                    Write a review
                </a>
                @endif
                @if(!config('services.google.places_api_key'))
                <p class="small text-muted mt-3 mb-0">Add <code>GOOGLE_PLACES_API_KEY</code> to your .env and set your Google Business URL in Admin → Settings to show reviews here automatically.</p>
                @endif
                @else
                <p class="text-muted mb-2">We share client feedback on Google. Check back soon or get in touch — we are happy to answer any questions.</p>
                <a href="{{ route('contact') }}" class="th-btn btn-kdr-primary btn-sm">Contact us</a>
                @endif
            </div>
        </div>
        @endforelse
    </div>
</div>

@if($showSummary && $profileUrl && count($reviews) > 0)
<div class="text-center mt-4">
    <a href="{{ $profileUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-link text-muted">
        See all reviews on Google <i class="fas fa-external-link-alt ms-1 small"></i>
    </a>
</div>
@endif
