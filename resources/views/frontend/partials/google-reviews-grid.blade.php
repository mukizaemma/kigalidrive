@php
    $googleData = $googleData ?? [];
    $reviews = $googleData['reviews'] ?? [];
    $writeUrl = $googleData['write_review_url'] ?? null;
    $profileUrl = $googleData['profile_url'] ?? null;
    $rating = $googleData['rating'] ?? null;
    $total = $googleData['total'] ?? null;
    $compact = $compact ?? false;
@endphp

@if($writeUrl)
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

<div class="row gy-4">
    @forelse($reviews as $review)
    <div class="{{ $compact ? 'col-12' : 'col-lg-4 col-md-6' }}">
        <article class="kdr-google-review-card h-100">
            <div class="kdr-google-review-card__head">
                @if(!empty($review['author_photo']))
                <img src="{{ $review['author_photo'] }}" alt="" class="kdr-google-review-card__avatar" loading="lazy" referrerpolicy="no-referrer">
                @else
                <div class="kdr-google-review-card__avatar kdr-google-review-card__avatar--fallback">
                    {{ strtoupper(substr($review['author_name'] ?? 'G', 0, 1)) }}
                </div>
                @endif
                <div>
                    <h3 class="kdr-google-review-card__name">{{ $review['author_name'] }}</h3>
                    <div class="kdr-google-review-card__meta">
                        <span class="text-warning">
                            @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star{{ $i <= ($review['rating'] ?? 5) ? '' : ' text-muted' }}" style="{{ $i > ($review['rating'] ?? 5) ? 'opacity:0.25' : '' }}"></i>
                            @endfor
                        </span>
                        @if(!empty($review['relative_time']))
                        <span class="text-muted small ms-1">· {{ $review['relative_time'] }}</span>
                        @endif
                    </div>
                </div>
                <span class="kdr-google-review-card__badge" title="Posted on Google"><i class="fab fa-google"></i></span>
            </div>
            <p class="kdr-google-review-card__text">{{ $review['text'] }}</p>
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
            <p class="small text-muted mt-3 mb-0">Add <code>GOOGLE_PLACES_API_KEY</code> to your .env file to show reviews here automatically.</p>
            @endif
            @else
            <p class="text-muted mb-0">Google Business Profile URL is not configured yet. Add it in <a href="{{ route('setting') }}">Admin → Settings</a>.</p>
            @endif
        </div>
    </div>
    @endforelse
</div>

@if($profileUrl && count($reviews) > 0)
<div class="text-center mt-4">
    <a href="{{ $profileUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-link text-muted">
        See all reviews on Google <i class="fas fa-external-link-alt ms-1 small"></i>
    </a>
</div>
@endif
