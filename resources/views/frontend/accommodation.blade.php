@extends('layouts.frontbase')

@section('content')
<style>
    .property-description { line-height: 1.65; color: #333; }
    .property-description p:last-child { margin-bottom: 0; }
    .property-description ul, .property-description ol { margin: 0.75rem 0; padding-left: 1.25rem; }
    .property-description h1, .property-description h2, .property-description h3 { margin-top: 1rem; margin-bottom: 0.5rem; font-size: 1.15rem; }
    /* Booking.com Style Layout */
    .property-header-section {
        margin-bottom: 16px;
    }
    .property-header-row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 16px 24px;
        width: 100%;
    }
    .property-title {
        font-size: 24px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
        line-height: 1.3;
    }
    .property-location {
        color: #0071c2;
        font-size: 14px;
        margin: 0;
    }
    .property-location:hover {
        text-decoration: underline;
    }
    .property-stars-inline {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .property-stars-inline .fa-star {
        font-size: 14px;
    }
    .property-rating-badge {
        display: inline-flex;
        align-items: center;
        background: #003580;
        color: white;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        margin: 0;
    }
    .property-rating-badge .rating-score {
        font-size: 18px;
        margin-right: 8px;
    }
    
    /* Image Gallery */
    .image-gallery-main {
        position: relative;
        width: 100%;
        height: 500px;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 10px;
        background: #f0f0f0;
    }
    .image-gallery-main img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .image-gallery-thumbs {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }
    .gallery-thumb {
        position: relative;
        height: 120px;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        opacity: 0.8;
        transition: opacity 0.3s;
    }
    .gallery-thumb:hover {
        opacity: 1;
    }
    .gallery-thumb.active {
        opacity: 1;
        border: 3px solid #0071c2;
    }
    .gallery-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .gallery-overlay {
        position: absolute;
        bottom: 15px;
        right: 15px;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 8px 15px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
    }
    
    /* Content Sections */
    .content-section {
        background: white;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .section-title {
        font-size: 22px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    /* Facilities Grid */
    .facilities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    .facility-item {
        display: flex;
        align-items: center;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    .facility-item i {
        color: #0071c2;
        margin-right: 12px;
        font-size: 18px;
        width: 24px;
    }
    
    /* Rooms Availability Table */
    .rooms-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .rooms-table thead {
        background: #f8f9fa;
    }
    .rooms-table th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: #1a1a1a;
        border-bottom: 2px solid #e0e0e0;
    }
    .rooms-table td {
        padding: 20px 15px;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
    }
    .rooms-table tr:hover {
        background: #f8f9fa;
    }
    .room-type-name {
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 5px;
    }
    .room-type-details {
        font-size: 13px;
        color: #666;
    }
    .room-price {
        font-size: 20px;
        font-weight: 700;
        color: #1a1a1a;
    }
    .room-price-label {
        font-size: 12px;
        color: #666;
        margin-top: 3px;
    }
    .room-availability {
        font-size: 14px;
        color: #0b7a3a;
        font-weight: 600;
    }
    .room-availability.unavailable {
        color: #d32f2f;
    }
    .btn-book-room {
        background: #0071c2;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
    }
    .btn-book-room:hover {
        background: #005a9e;
    }
    .btn-book-room:disabled {
        background: #ccc;
        cursor: not-allowed;
    }
    
    /* Right Sidebar */
    .sidebar-sticky {
        position: sticky;
        top: 20px;
    }
    .reserve-box {
        background: white;
        border: 2px solid #0071c2;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .reserve-box-header {
        text-align: center;
        margin-bottom: 20px;
    }
    .reserve-box-price {
        font-size: 32px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 5px;
    }
    .reserve-box-price-label {
        font-size: 14px;
        color: #666;
    }
    .reserve-form-group {
        margin-bottom: 20px;
    }
    .reserve-form-group label {
        display: block;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 8px;
        font-size: 14px;
    }
    .reserve-form-group input,
    .reserve-form-group select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
    }
    .reserve-form-group input:focus,
    .reserve-form-group select:focus {
        outline: none;
        border-color: #0071c2;
    }
    .btn-reserve {
        width: 100%;
        background: #0071c2;
        color: white;
        border: none;
        padding: 15px;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.3s;
        margin-top: 10px;
    }
    .btn-reserve:hover {
        background: #005a9e;
    }
    .btn-reserve:disabled {
        background: #ccc;
        cursor: not-allowed;
    }
    
    /* Reviews Section */
    .reviews-summary {
        background: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .reviews-score {
        text-align: center;
        margin-bottom: 20px;
    }
    .reviews-score-number {
        font-size: 48px;
        font-weight: 700;
        color: #1a1a1a;
        line-height: 1;
    }
    .reviews-score-label {
        font-size: 14px;
        color: #666;
        margin-top: 5px;
    }
    .review-item {
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .review-item:last-child {
        border-bottom: none;
    }
    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }
    .review-author {
        font-weight: 600;
        color: #1a1a1a;
    }
    .review-date {
        font-size: 12px;
        color: #666;
    }
    .review-text {
        font-size: 14px;
        color: #333;
        line-height: 1.6;
    }
    
    /* Map Section */
    .map-container {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .map-wrapper {
        height: 300px;
        border-radius: 8px;
        overflow: hidden;
        margin-top: 15px;
    }
    .map-wrapper iframe {
        width: 100%;
        height: 100%;
        border: none;
    }
    
    /* Mobile Responsive */
    @media (max-width: 991px) {
        .image-gallery-main {
            height: 300px;
        }
        .image-gallery-thumbs {
            grid-template-columns: repeat(3, 1fr);
        }
        .sidebar-sticky {
            position: relative;
            top: 0;
        }
        .rooms-table {
            font-size: 14px;
        }
        .rooms-table th,
        .rooms-table td {
            padding: 12px 8px;
        }
        .room-price {
            font-size: 18px;
        }
    }
    @media (max-width: 767px) {
        .property-title {
            font-size: 20px;
        }
        .property-header-row {
            gap: 10px 16px;
        }
        .image-gallery-thumbs {
            grid-template-columns: repeat(2, 1fr);
        }
        .facilities-grid {
            grid-template-columns: 1fr;
        }
        .rooms-table {
            display: block;
            overflow-x: auto;
        }
    }
</style>

<div class="container-fluid py-3" style="max-width: 1400px;">
    <!-- Property Header - single row full width -->
    <div class="property-header-section">
        <div class="property-header-row">
            @php
                $starsValue = $hotel->stars ?? 0;
                preg_match('/\d+/', (string)$starsValue, $m);
                $propertyStars = !empty($m) ? max(0, min(5, (int)$m[0])) : 0;
                $avgRating = (float) ($hotel->average_rating ?? 0);
                $totalReviews = (int) ($hotel->total_reviews ?? 0);
            @endphp
            <h1 class="property-title">{{ $hotel->name }}</h1>
            <div class="property-location">
                <i class="fas fa-map-marker-alt me-1"></i>
                {{ $hotel->location ?? $hotel->city ?? $hotel->address }}
                @if($hotel->category)
                    <span class="text-muted">• {{ $hotel->category->name }}</span>
                @endif
            </div>
            @if($propertyStars > 0)
                <div class="property-stars-inline">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $propertyStars)
                            <i class="fa-solid fa-star text-warning"></i>
                        @else
                            <i class="fa-regular fa-star text-warning"></i>
                        @endif
                    @endfor
                    <span class="ms-1" style="font-size: 14px; color: #333;">{{ $propertyStars }} {{ $propertyStars == 1 ? 'Star' : 'Stars' }} property</span>
                </div>
            @endif
            @if($totalReviews > 0)
                <div class="property-rating-badge">
                    <span class="rating-score">{{ number_format($avgRating, 1) }}</span>
                    <div>
                        <div class="star-rating">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($avgRating))
                                    <i class="fa-solid fa-star text-warning" style="font-size: 12px;"></i>
                                @else
                                    <i class="fa-regular fa-star text-warning" style="font-size: 12px;"></i>
                                @endif
                            @endfor
                        </div>
                        <div style="font-size: 11px; margin-top: 2px;">{{ $totalReviews }} {{ $totalReviews == 1 ? 'review' : 'reviews' }} from guests</div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Property Gallery (only property images - room galleries are on their own pages) -->
            @php
                $allImages = collect();
                
                // Add property images only (from property_images table)
                if($hotel->images && $hotel->images->isNotEmpty()) {
                    foreach($hotel->images as $img) {
                        if(isset($img->image_path)) {
                            $allImages->push([
                                'url' => asset('storage/images/properties/' . $img->image_path),
                                'type' => 'property',
                                'caption' => ($img->caption ?? $hotel->name)
                            ]);
                        } elseif(isset($img->image)) {
                            $allImages->push([
                                'url' => asset('storage/images/hotels/' . $img->image),
                                'type' => 'property',
                                'caption' => ($img->caption ?? $hotel->name)
                            ]);
                        }
                    }
                }
                
                // Add featured image if exists and not already in gallery
                $featuredImage = $hotel->featured_image ?? null;
                if(!empty($featuredImage)) {
                    $featuredUrl = asset('storage/images/properties/' . $featuredImage);
                    if(!$allImages->contains('url', $featuredUrl)) {
                        $allImages->prepend([
                            'url' => $featuredUrl,
                            'type' => 'property',
                            'caption' => $hotel->name . ' - Featured'
                        ]);
                    }
                }
                
                // Fallback if no property images
                if($allImages->isEmpty()) {
                    $allImages->push([
                        'url' => asset('assets/img/tour/tour_3_1.jpg'),
                        'type' => 'property',
                        'caption' => $hotel->name
                    ]);
                }
                
                $mainImage = $allImages->first()['url'];
            @endphp
            
            <div class="content-section" id="property-gallery">
                <div class="image-gallery-main">
                    <img src="{{ $mainImage }}" alt="{{ $hotel->name }}" id="mainGalleryImage" onclick="openGalleryModal(0)">
                    @if($allImages->count() > 1)
                        <div class="gallery-overlay" onclick="openGalleryModal(0)">
                            <i class="fas fa-images"></i> View all {{ $allImages->count() }} photos
                        </div>
                    @endif
                </div>
                @if($allImages->count() > 1)
                    <div class="image-gallery-thumbs">
                        @foreach($allImages->take(4) as $index => $img)
                            <div class="gallery-thumb {{ $index == 0 ? 'active' : '' }}" onclick="changeMainImage('{{ $img['url'] }}', this, {{ $index }})">
                                <img src="{{ $img['url'] }}" alt="Gallery thumbnail">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Tabs: About | Amenities | Reviews -->
            <div class="content-section">
                <ul class="nav nav-tabs mb-4" id="propertyTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-about" data-bs-toggle="tab" data-bs-target="#content-about" type="button" role="tab">About this property</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-listing-terms" data-bs-toggle="tab" data-bs-target="#content-listing-terms" type="button" role="tab">Terms &amp; conditions</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-cancellation" data-bs-toggle="tab" data-bs-target="#content-cancellation" type="button" role="tab">Cancellation policy</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-amenities" data-bs-toggle="tab" data-bs-target="#content-amenities" type="button" role="tab">Amenities</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-reviews" data-bs-toggle="tab" data-bs-target="#content-reviews" type="button" role="tab">Reviews ({{ $hotel->total_reviews }})</button>
                    </li>
                </ul>
                <div class="tab-content" id="propertyTabsContent">
                    <div class="tab-pane fade show active" id="content-about" role="tabpanel">
                        <div class="property-description">
                            {!! $hotel->description ?? '<p class="text-muted">No description available.</p>' !!}
                        </div>
                    </div>
                    <div class="tab-pane fade" id="content-listing-terms" role="tabpanel">
                        @include('layouts.includes.property-listing-terms', ['listing' => $hotel])
                    </div>
                    <div class="tab-pane fade" id="content-cancellation" role="tabpanel">
                        @include('layouts.includes.property-cancellation-policy', ['listing' => $hotel])
                    </div>
                    <div class="tab-pane fade" id="content-amenities" role="tabpanel">
                        @if($hotel->facilities->isNotEmpty())
                            <div class="facilities-grid">
                                @foreach($hotel->facilities as $facility)
                                    <div class="facility-item">
                                        <i class="{{ $facility->icon ?? 'fas fa-check-circle' }}"></i>
                                        <span>{{ $facility->title }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">No amenities listed.</p>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="content-reviews" role="tabpanel">
                        {{-- Guest rating summary --}}
                        @if($hotel->reviews->isNotEmpty())
                            <div class="reviews-summary mb-4">
                                <div class="reviews-score d-inline-block me-4">
                                    <div class="reviews-score-number">{{ number_format($hotel->average_rating, 1) }}</div>
                                    <div class="star-rating mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= round($hotel->average_rating))
                                                <i class="fa-solid fa-star text-warning"></i>
                                            @else
                                                <i class="fa-regular fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <div class="reviews-score-label">Total: <strong>{{ $hotel->total_reviews }}</strong> {{ $hotel->total_reviews == 1 ? 'review' : 'reviews' }}</div>
                                </div>
                            </div>
                            <div class="reviews-list mb-4">
                                @foreach($hotel->reviews as $review)
                                    <div class="review-item border-bottom pb-3 mb-3">
                                        <div class="review-header d-flex justify-content-between flex-wrap">
                                            <span class="review-author fw-semibold">{{ $review->user->name ?? $review->guest_name ?? 'Anonymous' }}</span>
                                            <div>
                                                <div class="star-rating d-inline-block">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <i class="fa-solid fa-star text-warning" style="font-size: 12px;"></i>
                                                        @else
                                                            <i class="fa-regular fa-star text-warning" style="font-size: 12px;"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="text-muted ms-2 small">{{ $review->created_at->format('M j, Y') }}</span>
                                            </div>
                                        </div>
                                        @if($review->title)
                                            <div class="review-title fw-semibold mt-1">{{ $review->title }}</div>
                                        @endif
                                        @if($review->comment)
                                            <div class="review-text mt-1">{{ $review->comment }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-4">No reviews yet. Be the first to rate this property.</p>
                        @endif

                        {{-- Rate this property form (anyone can submit) --}}
                        <div class="content-section" style="padding: 20px; background: #f8f9fa; border-radius: 12px;">
                            <h4 class="mb-3">Rate this property</h4>
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            <form action="{{ url('/accommodations/' . ($hotel->slug ?? $hotel->id) . '/reviews') }}" method="POST" id="propertyReviewForm">
                                @csrf
                                @guest
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Your name <span class="text-danger">*</span></label>
                                        <input type="text" name="guest_name" class="form-control" value="{{ old('guest_name') }}" required maxlength="255">
                                        @error('guest_name')<div class="text-danger small">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Your email <span class="text-danger">*</span></label>
                                        <input type="email" name="guest_email" class="form-control" value="{{ old('guest_email') }}" required>
                                        @error('guest_email')<div class="text-danger small">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                @endguest
                                <div class="mb-3">
                                    <label class="form-label">Rating <span class="text-danger">*</span></label>
                                    <div class="star-rating-input d-flex gap-1 align-items-center" role="radiogroup" aria-label="Rate from 1 to 5 stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="star-rating-star mb-0" data-value="{{ $i }}" title="{{ $i }} {{ $i == 1 ? 'star' : 'stars' }}">
                                                <input type="radio" name="rating" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }} required class="visually-hidden">
                                                <i class="fa-star fa-2x {{ old('rating') && old('rating') >= $i ? 'fa-solid text-warning' : 'fa-regular text-warning' }}" style="transition: all 0.2s ease; opacity: 0.9;"></i>
                                            </label>
                                        @endfor
                                        <span class="ms-2 text-muted small" id="ratingLabel">Select your rating</span>
                                    </div>
                                    @error('rating')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Title (optional)</label>
                                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" maxlength="255" placeholder="e.g. Great stay">
                                    @error('title')<div class="text-danger small">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Your review (optional)</label>
                                    <textarea name="comment" class="form-control" rows="3" maxlength="1000" placeholder="Share your experience...">{{ old('comment') }}</textarea>
                                    @error('comment')<div class="text-danger small">{{ $message }}</div>@enderror
                                </div>
                                <button type="submit" class="btn btn-primary">Submit rating</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rooms Availability Table -->
            @if($rooms->isNotEmpty())
                <div class="content-section">
                    <h2 class="section-title">Available {{ $hotel->property_type == 'hotel' ? 'Rooms' : 'Units' }}</h2>
                    <div class="table-responsive">
                        <table class="rooms-table">
                            <thead>
                                <tr>
                                    <th>Room Type</th>
                                    <th>Price</th>
                                    <th>Availability</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rooms as $unit)
                                    @php
                                        $unitImage = $unit->images->where('is_primary', true)->first() ?? $unit->images->first();
                                        $unitFeaturedImg = $unit->featured_image ?? null;
                                        $unitImagePath = $unitImage ? asset('storage/' . $unitImage->image_path) : 
                                                        (!empty($unitFeaturedImg) ? asset('storage/images/units/' . $unitFeaturedImg) : 
                                                        asset('assets/img/tour/tour_3_1.jpg'));
                                    @endphp
                                    <tr>
                                        <td>
                                            @php
                                                $unitDisplayName = $unit->name ?? 'Unit ' . $unit->id;
                                                $unitTypeName = $unit->unitType->name ?? null;
                                            @endphp
                                            <div class="room-type-name">
                                                @if($unitTypeName && $unitTypeName !== $unitDisplayName)
                                                    {{ $unitTypeName }} – {{ $unitDisplayName }}
                                                @else
                                                    {{ $unitDisplayName }}
                                                @endif
                                            </div>
                                            <div class="room-type-details">
                                                @if($unit->max_occupancy)
                                                    <i class="fas fa-users"></i> {{ $unit->max_occupancy }} guests
                                                @endif
                                                @if($unit->bedrooms)
                                                    <span class="ms-2"><i class="fas fa-bed"></i> {{ $unit->bedrooms }} {{ $unit->bedrooms == 1 ? 'bedroom' : 'bedrooms' }}</span>
                                                @endif
                                                @if($unit->bathrooms)
                                                    <span class="ms-2"><i class="fas fa-bath"></i> {{ $unit->bathrooms }} {{ $unit->bathrooms == 1 ? 'bathroom' : 'bathrooms' }}</span>
                                                @endif
                                                @if($unit->size_sqm)
                                                    <span class="ms-2"><i class="fas fa-ruler-combined"></i> {{ $unit->size_sqm }} m²</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $currency = $unit->currency ?? 'USD';
                                                $currencySymbol = getCurrencySymbol($currency);
                                                $uPt = $unit->price_display_type ?? 'per_night';
                                            @endphp
                                            @if($uPt === 'per_month')
                                                <div class="room-price">{{ $currencySymbol }}{{ number_format($unit->base_price_per_month ?? 0, 0) }}</div>
                                                <div class="room-price-label">per month</div>
                                            @elseif($uPt === 'both')
                                                <div class="room-price">{{ $currencySymbol }}{{ number_format($unit->base_price_per_night ?? 0, 0) }}</div>
                                                <div class="room-price-label">per night</div>
                                                @if(!empty($unit->base_price_per_month))
                                                    <div class="room-price mt-1">{{ $currencySymbol }}{{ number_format($unit->base_price_per_month, 0) }}</div>
                                                    <div class="room-price-label">per month</div>
                                                @endif
                                            @else
                                                <div class="room-price">{{ $currencySymbol }}{{ number_format($unit->base_price_per_night ?? 0, 0) }}</div>
                                                <div class="room-price-label">per night</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($unit->available_units > 0)
                                                <div class="room-availability">
                                                    <i class="fas fa-check-circle"></i> {{ $unit->available_units }} {{ $unit->available_units == 1 ? 'room' : 'rooms' }} available
                                                </div>
                                            @else
                                                <div class="room-availability unavailable">
                                                    <i class="fas fa-times-circle"></i> Not available
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                                <a href="{{ route('unit.details', ['property' => $hotel->slug ?? $hotel->id, 'unit' => $unit->slug ?? $unit->id]) }}" 
                                                   class="btn btn-sm btn-outline-primary" style="padding: 8px 14px; border-radius: 6px; text-decoration: none;">
                                                    <i class="fas fa-images me-1"></i>View room
                                                </a>
                                                @if($unit->available_units > 0)
                                                    @php
                                                        $unitCurrency = $unit->currency ?? 'USD';
                                                        $unitCurrencySymbol = getCurrencySymbol($unitCurrency);
                                                        $primaryPrice = ($uPt === 'per_month') ? ($unit->base_price_per_month ?? 0) : ($unit->base_price_per_night ?? 0);
                                                        $unitDisplayNameForJs = $unitTypeName && $unitTypeName !== $unitDisplayName
                                                            ? $unitTypeName . ' – ' . $unitDisplayName
                                                            : $unitDisplayName;
                                                    @endphp
                                                    <button class="btn-book-room" onclick="selectRoom({{ $unit->id }}, {{ $primaryPrice }}, {{ json_encode($unitDisplayNameForJs) }}, '{{ $unitCurrency }}', '{{ $unitCurrencySymbol }}', '{{ $uPt }}')">
                                                        Select room
                                                    </button>
                                                @else
                                                    <button class="btn-book-room" disabled>Unavailable</button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="content-section">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>No {{ $hotel->property_type == 'hotel' ? 'rooms' : 'units' }} available at the moment.
                    </div>
                </div>
            @endif

            <!-- Property Location (Map) -->
            @if($hotel->map_embed_code || ($hotel->latitude && $hotel->longitude) || ($hotel->address || $hotel->location || $hotel->city))
                <div class="content-section map-container">
                    <h3 class="section-title"><i class="fas fa-map-marker-alt me-2"></i>Property location</h3>
                    <p class="text-muted mb-3" style="font-size: 14px;">Use the map below to explore the area and plan your route.</p>
                    <div class="map-wrapper">
                        @if($hotel->map_embed_code)
                            @php
                                $embedCode = trim($hotel->map_embed_code);
                                if (preg_match('/<iframe[^>]*>.*?<\/iframe>/is', $embedCode, $matches)) {
                                    $embedCode = $matches[0];
                                }
                                $embedCode = preg_replace('/\s*width\s*=\s*["\'][^"\']*["\']/i', '', $embedCode);
                                $embedCode = preg_replace('/\s*height\s*=\s*["\'][^"\']*["\']/i', '', $embedCode);
                                if (preg_match('/style\s*=\s*["\']([^"\']*)["\']/i', $embedCode, $styleMatches)) {
                                    $existingStyle = $styleMatches[1];
                                    $existingStyle = preg_replace('/width\s*:\s*[^;]+;?/i', '', $existingStyle);
                                    $existingStyle = preg_replace('/height\s*:\s*[^;]+;?/i', '', $existingStyle);
                                    $newStyle = trim($existingStyle, '; ') . '; width:100%;height:100%;border:0;';
                                    $embedCode = preg_replace('/style\s*=\s*["\'][^"\']*["\']/i', 'style="' . $newStyle . '"', $embedCode);
                                } else {
                                    $embedCode = preg_replace('/(<iframe[^>]*)(>)/i', '$1 style="width:100%;height:100%;border:0;"$2', $embedCode);
                                }
                            @endphp
                            {!! $embedCode !!}
                        @elseif($hotel->latitude && $hotel->longitude)
                            <iframe
                                src="https://www.google.com/maps?q={{ $hotel->latitude }},{{ $hotel->longitude }}&hl=en&z=14&output=embed"
                                width="100%"
                                height="100%"
                                style="border:0;"
                                allowfullscreen>
                            </iframe>
                        @elseif($hotel->address || $hotel->location || $hotel->city)
                            @php
                                $address = $hotel->address ?? $hotel->location ?? $hotel->city;
                                $encodedAddress = urlencode($address);
                            @endphp
                            <iframe
                                src="https://www.google.com/maps?q={{ $encodedAddress }}&hl=en&z=14&output=embed"
                                width="100%"
                                height="100%"
                                style="border:0;"
                                allowfullscreen>
                            </iframe>
                        @endif
                    </div>
                    @if($hotel->address || $hotel->location || $hotel->city)
                        <p class="text-muted mt-3 mb-0" style="font-size: 13px;">
                            <i class="fas fa-map-marker-alt me-2"></i>{{ $hotel->address ?? $hotel->location ?? $hotel->city }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Right Column - Booking Box (Kigali Drive Rentals Wireframe) -->
        <div class="col-lg-4">
            <div class="sidebar-sticky">
                <!-- Booking Box -->
                <div class="reserve-box" id="reserveBox">
                    <h5 class="mb-3" style="font-weight: 700; color: #1a1a1a;">
                        <i class="fas fa-calendar-check me-2"></i>Booking Box
                    </h5>
                    <div class="reserve-box-header">
                        @php
                            $firstUnit = $rooms->isNotEmpty() ? $rooms->first() : null;
                            $defaultCurrency = $firstUnit ? ($firstUnit->currency ?? 'USD') : 'USD';
                            $defaultCurrencySymbol = getCurrencySymbol($defaultCurrency);
                            $defaultPt = $firstUnit ? ($firstUnit->price_display_type ?? 'per_night') : 'per_night';
                            $defaultPrice = $hotel->min_price ?? ($firstUnit ? (($defaultPt === 'per_month') ? ($firstUnit->base_price_per_month ?? 0) : ($firstUnit->base_price_per_night ?? 0)) : 0);
                        @endphp
                        <div class="reserve-box-price" id="reservePrice" data-currency="{{ $defaultCurrency }}" data-currency-symbol="{{ $defaultCurrencySymbol }}" data-price-type="{{ $defaultPt }}">
                            {{ $defaultCurrencySymbol }}{{ number_format($defaultPrice, 0) }}
                        </div>
                        <div class="reserve-box-price-label" id="reservePriceLabel">{{ $defaultPt === 'per_month' ? 'Price per month' : 'Price per night' }}</div>
                    </div>
                    
                    @php
                        $bookingChannels = app(\App\Services\SubmissionChannelService::class)->availableChannels($setting ?? null, 'booking');
                    @endphp
                    <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm">
                        @csrf
                        <input type="hidden" name="property_id" value="{{ $hotel->id }}" id="bookingPropertyId">
                        <input type="hidden" name="unit_id" value="" id="bookingUnitId">

                        @include('frontend.partials.kdr-submission-channels', ['channelContext' => 'booking'])
                        
                        <div class="reserve-form-group">
                            <label for="check_in">Check-in date</label>
                            <input type="date" name="check_in" id="check_in" class="form-control" required min="{{ date('Y-m-d') }}" onchange="calculateTotal()">
                        </div>
                        
                        <div class="reserve-form-group">
                            <label for="check_out">Check-out date</label>
                            <input type="date" name="check_out" id="check_out" class="form-control" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" onchange="calculateTotal()">
                        </div>
                        
                        <div class="reserve-form-group">
                            <label for="guests_count">Number of guests</label>
                            <select name="guests_count" id="guests_count" class="form-control" required>
                                @php $firstMax = max(1, (int)(optional($rooms->first())->max_occupancy ?? 10)); @endphp
                                @for($i = 1; $i <= $firstMax; $i++)
                                    <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'guest' : 'guests' }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="reserve-form-group">
                            <label for="guest_name">Guest full name</label>
                            <input type="text" name="guest_name" id="guest_name" class="form-control" required>
                        </div>

                        <div class="reserve-form-group">
                            <label for="guest_email">Guest email</label>
                            <input type="email" name="guest_email" id="guest_email" class="form-control" required>
                        </div>

                        <div class="reserve-form-group">
                            <label for="guest_country">Country</label>
                            <input type="text" name="guest_country" id="guest_country" class="form-control">
                        </div>

                        <div class="reserve-form-group">
                            <label for="guest_phone">Phone</label>
                            <input type="text" name="guest_phone" id="guest_phone" class="form-control">
                        </div>

                        <div class="reserve-form-group">
                            <label for="special_requests">Special requests (optional)</label>
                            <textarea name="special_requests" id="special_requests" rows="3" class="form-control" placeholder="E.g. late arrival, dietary needs, extra bed"></textarea>
                        </div>

                        <div class="reserve-form-group">
                            <label for="add_extras">Add Extras (optional)</label>
                            <div id="extrasContainer">
                                <p class="text-muted small mb-0" id="extrasPlaceholder">Select a room above to see available extras.</p>
                                <div id="extrasList"></div>
                            </div>
                        </div>
                        
                        <div class="reserve-form-group">
                            @php
                                $formCurrency = $rooms->isNotEmpty() ? ($rooms->first()->currency ?? 'USD') : 'USD';
                                $formCurrencySymbol = getCurrencySymbol($formCurrency);
                                $formPt = $rooms->isNotEmpty() ? ($rooms->first()->price_display_type ?? 'per_night') : 'per_night';
                                $formDefaultPrice = $hotel->min_price ?? ($rooms->isNotEmpty() ? (($formPt === 'per_month') ? ($rooms->first()->base_price_per_month ?? 0) : ($rooms->first()->base_price_per_night ?? 0)) : 0);
                            @endphp
                            <div style="background: #f8f9fa; padding: 15px; border-radius: 6px;">
                                <div class="d-flex justify-content-between mb-2">
                                    <span id="priceLabelText">{{ $formPt === 'per_month' ? 'Price per month:' : 'Price per night:' }}</span>
                                    <span id="pricePerNight" data-currency="{{ $formCurrency }}" data-currency-symbol="{{ $formCurrencySymbol }}">{{ $formCurrencySymbol }}{{ number_format($formDefaultPrice, 0) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Number of nights:</span>
                                    <span id="numberOfNights">-</span>
                                </div>
                                <hr style="margin: 10px 0;">
                                <div class="d-flex justify-content-between" style="font-weight: 700; font-size: 18px;">
                                    <span>Total:</span>
                                    <span id="totalAmount" data-currency="{{ $formCurrency }}" data-currency-symbol="{{ $formCurrencySymbol }}">{{ $formCurrencySymbol }}0</span>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-reserve" id="btnReserve" disabled @if(count($bookingChannels) === 0) title="No active contact channels" @endif>
                            <i class="fas fa-calendar-check me-2"></i>Book Now
                        </button>

                        <p class="text-center mt-2" style="font-size: 12px; color: #666;">
                            We will check availability and get back to you by email. <br>
                            <a href="{{ route('login') }}">Login</a> or <a href="{{ route('register') }}">create an account</a> to manage your bookings.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Properties -->
    @if(isset($relatedProperties) && $relatedProperties->isNotEmpty())
        <div class="mt-5 pt-4 border-top">
            <h2 class="section-title mb-4">Related properties</h2>
            <div class="row g-4">
                @foreach($relatedProperties as $prop)
                    @php
                        $propImage = $prop->featured_image ? asset('storage/images/properties/' . $prop->featured_image) : asset('assets/img/tour/tour_3_1.jpg');
                        if ($prop->images && $prop->images->isNotEmpty()) {
                            $primary = $prop->images->where('is_primary', true)->first() ?? $prop->images->first();
                            $propImage = asset('storage/images/properties/' . $primary->image_path);
                        }
                        $propMinPrice = $prop->min_price ?? null;
                        $propCurrency = 'USD';
                        if ($prop->units && $prop->units->isNotEmpty()) {
                            $cheapest = $prop->units->where('base_price_per_night', '>', 0)->sortBy('base_price_per_night')->first();
                            if ($cheapest) {
                                $propMinPrice = $propMinPrice ?? $cheapest->base_price_per_night;
                                $propCurrency = $cheapest->currency ?? 'USD';
                            }
                        }
                        $propCurrencySymbol = getCurrencySymbol($propCurrency);
                        $propStars = 0;
                        if (!empty($prop->stars)) {
                            preg_match('/\d+/', (string)$prop->stars, $m);
                            $propStars = !empty($m) ? max(0, min(5, (int)$m[0])) : 0;
                        }
                    @endphp
                    <div class="col-lg-3 col-md-6">
                        <div class="content-section h-100" style="padding: 0; overflow: hidden;">
                            <a href="{{ route('hotel', $prop->slug ?? $prop->id) }}" class="d-block">
                                <div style="height: 180px; overflow: hidden;">
                                    <img src="{{ $propImage }}" alt="{{ $prop->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div style="padding: 20px;">
                                    <h5 class="mb-2" style="font-size: 16px; font-weight: 600;">{{ $prop->name }}</h5>
                                    @if($propStars > 0)
                                        <div class="mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $propStars)
                                                    <i class="fa-solid fa-star text-warning" style="font-size: 12px;"></i>
                                                @else
                                                    <i class="fa-regular fa-star text-warning" style="font-size: 12px;"></i>
                                                @endif
                                            @endfor
                                            <span class="ms-1 small text-muted">{{ $propStars }} {{ $propStars == 1 ? 'Star' : 'Stars' }}</span>
                                        </div>
                                    @endif
                                    @if($prop->reviews_count > 0)
                                        <div class="mb-2 small text-muted">
                                            {{ number_format($prop->reviews_avg_rating ?? 0, 1) }} · {{ $prop->reviews_count }} {{ $prop->reviews_count == 1 ? 'review' : 'reviews' }}
                                        </div>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center">
                                        @if($propMinPrice)
                                            <span class="text-success fw-semibold">{{ $propCurrencySymbol }}{{ number_format($propMinPrice, 0) }}/night</span>
                                        @else
                                            <span class="text-muted small">Price on request</span>
                                        @endif
                                        <span class="btn btn-sm btn-outline-primary">View</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<!-- Gallery Modal -->
<div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white">Photo Gallery - {{ $hotel->name }}</h5>
                <span class="text-white me-3" id="galleryCounter">1 / {{ $allImages->count() }}</span>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 position-relative" style="display: flex; align-items: center; justify-content: center; min-height: 80vh;">
                <button class="btn btn-light position-absolute" style="left: 20px; top: 50%; transform: translateY(-50%); z-index: 10;" onclick="previousImage()" id="prevBtn">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <img id="galleryMainImage" src="" alt="Gallery Image" style="max-width: 90%; max-height: 80vh; object-fit: contain;">
                <button class="btn btn-light position-absolute" style="right: 20px; top: 50%; transform: translateY(-50%); z-index: 10;" onclick="nextImage()" id="nextBtn">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div class="modal-footer border-0 bg-dark">
                <div class="w-100">
                    <div class="d-flex gap-2 overflow-auto pb-2" style="max-height: 120px;">
                        @foreach($allImages as $index => $img)
                            <img src="{{ $img['url'] }}" 
                                 alt="Thumbnail" 
                                 class="gallery-thumbnail {{ $index == 0 ? 'active' : '' }}" 
                                 style="width: 100px; height: 80px; object-fit: cover; border-radius: 8px; cursor: pointer; border: 2px solid transparent;"
                                 onclick="showImage({{ $index }})"
                                 data-index="{{ $index }}">
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .gallery-thumbnail.active {
        border: 2px solid #0071c2 !important;
    }
    .gallery-thumbnail:hover {
        opacity: 0.8;
    }
    /* Star rating form - interactive */
    .star-rating-star {
        cursor: pointer;
        display: inline-flex;
        padding: 6px 4px;
        border-radius: 8px;
        transition: transform 0.15s ease, background 0.15s ease;
    }
    .star-rating-star:hover {
        transform: scale(1.2);
        background: rgba(255, 193, 7, 0.15);
    }
    .star-rating-star i {
        transition: color 0.2s ease;
    }
    .star-rating-star i.fa-regular {
        color: #ddd !important;
    }
    .star-rating-star i.fa-solid {
        color: #ffc107 !important;
    }
</style>

<script>
    let selectedUnitId = null;
    let selectedUnitPrice = {{ $rooms->isNotEmpty() ? (($rooms->first()->price_display_type ?? 'per_night') === 'per_month' ? ($rooms->first()->base_price_per_month ?? 0) : ($rooms->first()->base_price_per_night ?? 0)) : 0 }};
    let selectedUnitName = {!! json_encode($rooms->isNotEmpty() ? ($rooms->first()->name ?? 'Unit') : 'Unit') !!};
    let selectedUnitCurrency = '{{ $rooms->isNotEmpty() ? ($rooms->first()->currency ?? 'USD') : 'USD' }}';
    let selectedUnitCurrencySymbol = '{{ $rooms->isNotEmpty() ? getCurrencySymbol($rooms->first()->currency ?? 'USD') : '$' }}';
    let selectedUnitPriceType = '{{ $rooms->isNotEmpty() ? ($rooms->first()->price_display_type ?? 'per_night') : 'per_night' }}';

    // Unit extras: unitId => [{ id, name, price }]
    const unitsExtras = @json($rooms->keyBy('id')->map(function($u) { return $u->extraCharges->map(function($e) { return ['id' => $e->id, 'name' => $e->extraChargeType->name ?? 'Extra', 'price' => (float)$e->price]; })->values()->toArray(); })->toArray());

    const unitsMaxGuests = @json($rooms->keyBy('id')->map(function ($u) {
        return max(1, (int) ($u->max_occupancy ?? 10));
    })->toArray());

    function rebuildGuestSelect(maxG) {
        maxG = parseInt(maxG, 10) || 10;
        const sel = document.getElementById('guests_count');
        if (!sel) return;
        const prev = parseInt(sel.value, 10) || 1;
        const cur = Math.min(Math.max(1, prev), maxG);
        sel.innerHTML = '';
        for (let i = 1; i <= maxG; i++) {
            const opt = document.createElement('option');
            opt.value = i;
            opt.textContent = i + (i === 1 ? ' guest' : ' guests');
            if (i === cur) opt.selected = true;
            sel.appendChild(opt);
        }
    }
    
    // Gallery images array
    const galleryImages = @json($allImages->map(function($img) { return $img['url']; })->values());
    let currentImageIndex = 0;
    
    function changeMainImage(src, thumbElement, index = 0) {
        document.getElementById('mainGalleryImage').src = src;
        currentImageIndex = index;
        if (thumbElement) {
            document.querySelectorAll('.gallery-thumb').forEach(el => el.classList.remove('active'));
            thumbElement.classList.add('active');
        }
    }
    
    function openGalleryModal(startIndex = 0) {
        currentImageIndex = startIndex;
        showImage(startIndex);
        const modal = new bootstrap.Modal(document.getElementById('galleryModal'));
        modal.show();
    }
    
    function showImage(index) {
        if (index < 0 || index >= galleryImages.length) return;
        currentImageIndex = index;
        document.getElementById('galleryMainImage').src = galleryImages[index];
        document.getElementById('galleryCounter').textContent = (index + 1) + ' / ' + galleryImages.length;
        
        // Update thumbnails
        document.querySelectorAll('.gallery-thumbnail').forEach((thumb, i) => {
            if (i === index) {
                thumb.classList.add('active');
            } else {
                thumb.classList.remove('active');
            }
        });
    }
    
    function nextImage() {
        const next = (currentImageIndex + 1) % galleryImages.length;
        showImage(next);
    }
    
    function previousImage() {
        const prev = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
        showImage(prev);
    }
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('galleryModal');
        if (modal && modal.classList.contains('show')) {
            if (e.key === 'ArrowRight') {
                nextImage();
            } else if (e.key === 'ArrowLeft') {
                previousImage();
            } else if (e.key === 'Escape') {
                bootstrap.Modal.getInstance(modal).hide();
            }
        }
    });
    
    function selectRoom(unitId, price, name, currency, currencySymbol, priceType) {
        priceType = priceType || 'per_night';
        selectedUnitId = unitId;
        selectedUnitPrice = price;
        selectedUnitName = name;
        selectedUnitCurrency = currency || 'USD';
        selectedUnitCurrencySymbol = currencySymbol || '$';
        selectedUnitPriceType = priceType;
        
        document.getElementById('bookingUnitId').value = unitId;
        document.getElementById('reservePrice').textContent = selectedUnitCurrencySymbol + price.toLocaleString();
        document.getElementById('reservePrice').setAttribute('data-currency', selectedUnitCurrency);
        document.getElementById('reservePrice').setAttribute('data-currency-symbol', selectedUnitCurrencySymbol);
        document.getElementById('reservePrice').setAttribute('data-price-type', priceType);
        var priceLabelEl = document.getElementById('reservePriceLabel');
        if (priceLabelEl) priceLabelEl.textContent = priceType === 'per_month' ? 'Price per month' : 'Price per night';
        document.getElementById('pricePerNight').textContent = selectedUnitCurrencySymbol + price.toLocaleString();
        document.getElementById('pricePerNight').setAttribute('data-currency', selectedUnitCurrency);
        document.getElementById('pricePerNight').setAttribute('data-currency-symbol', selectedUnitCurrencySymbol);
        var priceLabelTextEl = document.getElementById('priceLabelText');
        if (priceLabelTextEl) priceLabelTextEl.textContent = priceType === 'per_month' ? 'Price per month:' : 'Price per night:';
        document.getElementById('totalAmount').setAttribute('data-currency', selectedUnitCurrency);
        document.getElementById('totalAmount').setAttribute('data-currency-symbol', selectedUnitCurrencySymbol);
        document.getElementById('btnReserve').disabled = false;

        rebuildGuestSelect(unitsMaxGuests[unitId] || 10);
        
        // Populate Add Extras for this unit
        var extrasList = document.getElementById('extrasList');
        var extrasPlaceholder = document.getElementById('extrasPlaceholder');
        extrasList.innerHTML = '';
        var extras = unitsExtras[unitId] || [];
        if (extras.length > 0) {
            extrasPlaceholder.style.display = 'none';
            extras.forEach(function(extra) {
                var div = document.createElement('div');
                div.className = 'form-check';
                div.innerHTML = '<input class="form-check-input extra-charge-cb" type="checkbox" name="extra_charges[]" value="' + extra.id + '" id="extra_' + extra.id + '" data-price="' + extra.price + '">' +
                    '<label class="form-check-label" for="extra_' + extra.id + '">' + extra.name + ' (+' + selectedUnitCurrencySymbol + extra.price.toLocaleString() + ')</label>';
                extrasList.appendChild(div);
            });
            extrasList.querySelectorAll('.extra-charge-cb').forEach(function(cb) {
                cb.addEventListener('change', calculateTotal);
            });
        } else {
            extrasPlaceholder.style.display = 'block';
            extrasPlaceholder.textContent = 'No additional extras for this room.';
        }
        
        // Scroll to reserve box
        document.getElementById('reserveBox').scrollIntoView({ behavior: 'smooth', block: 'start' });
        
        // Highlight selected room in table
        document.querySelectorAll('.rooms-table tr').forEach(tr => {
            tr.style.background = '';
        });
        if (event && event.target) {
            event.target.closest('tr').style.background = '#e3f2fd';
        }
        
        calculateTotal();
    }

    document.addEventListener('DOMContentLoaded', function() {
        var uid = document.getElementById('bookingUnitId') && document.getElementById('bookingUnitId').value;
        if (uid && unitsMaxGuests[uid]) {
            rebuildGuestSelect(unitsMaxGuests[uid]);
        }
    });
    
    function calculateTotal() {
        const checkIn = document.getElementById('check_in').value;
        const checkOut = document.getElementById('check_out').value;
        const pricePerNight = selectedUnitPrice;
        const priceType = (typeof selectedUnitPriceType !== 'undefined') ? selectedUnitPriceType : 'per_night';

        var extrasTotal = 0;
        document.querySelectorAll('#extrasList .extra-charge-cb:checked').forEach(function(cb) {
            extrasTotal += parseFloat(cb.getAttribute('data-price')) || 0;
        });
        
        if (priceType === 'per_month' && pricePerNight > 0) {
            document.getElementById('numberOfNights').textContent = '1 month';
            const currencySymbol = document.getElementById('totalAmount').getAttribute('data-currency-symbol') || selectedUnitCurrencySymbol || '$';
            const total = pricePerNight + extrasTotal;
            document.getElementById('totalAmount').textContent = currencySymbol + total.toLocaleString();
            if (selectedUnitId) document.getElementById('btnReserve').disabled = false;
            return;
        }
        
        if (checkIn && checkOut && pricePerNight > 0) {
            const date1 = new Date(checkIn);
            const date2 = new Date(checkOut);
            
            if (date2 > date1) {
                const diffTime = Math.abs(date2 - date1);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                document.getElementById('numberOfNights').textContent = diffDays + ' ' + (diffDays == 1 ? 'night' : 'nights');
                const currencySymbol = document.getElementById('totalAmount').getAttribute('data-currency-symbol') || selectedUnitCurrencySymbol || '$';
                const baseTotal = pricePerNight * diffDays;
                const total = baseTotal + extrasTotal;
                document.getElementById('totalAmount').textContent = currencySymbol + total.toLocaleString();
                
                if (selectedUnitId) {
                    document.getElementById('btnReserve').disabled = false;
                }
            } else {
                document.getElementById('numberOfNights').textContent = '-';
                const currencySymbol = document.getElementById('totalAmount').getAttribute('data-currency-symbol') || selectedUnitCurrencySymbol || '$';
                document.getElementById('totalAmount').textContent = currencySymbol + (extrasTotal || 0).toLocaleString();
                document.getElementById('btnReserve').disabled = true;
            }
        } else {
            document.getElementById('numberOfNights').textContent = '-';
            const currencySymbol = document.getElementById('totalAmount').getAttribute('data-currency-symbol') || selectedUnitCurrencySymbol || '$';
            document.getElementById('totalAmount').textContent = currencySymbol + (extrasTotal || 0).toLocaleString();
            document.getElementById('btnReserve').disabled = true;
        }
    }
    
    // Room data for auto-selection (from URL ?unit= or first available)
    const roomsData = @json($roomsDataJson ?? []);
    
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const unitIdParam = urlParams.get('unit') ? parseInt(urlParams.get('unit'), 10) : null;
        let roomToSelect = null;
        if (unitIdParam && roomsData.length) {
            roomToSelect = roomsData.find(r => r.id === unitIdParam && r.available);
        }
        if (!roomToSelect && roomsData.length) {
            roomToSelect = roomsData.find(r => r.available);
        }
        if (roomToSelect) {
            selectRoom(roomToSelect.id, roomToSelect.price, roomToSelect.name, roomToSelect.currency, roomToSelect.currencySymbol, roomToSelect.priceType);
            document.getElementById('reserveBox').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
    
    // Star rating - interactive hover and selection
    (function initStarRating() {
        const container = document.querySelector('.star-rating-input');
        if (!container) return;
        const stars = container.querySelectorAll('.star-rating-star');
        const ratingLabel = document.getElementById('ratingLabel');
        const labels = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
        
        function updateStars(upToValue) {
            stars.forEach((star, idx) => {
                const value = parseInt(star.getAttribute('data-value'), 10);
                const icon = star.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-regular', 'fa-solid');
                    icon.classList.add(value <= upToValue ? 'fa-solid' : 'fa-regular');
                    icon.classList.add('text-warning');
                }
            });
            if (ratingLabel && upToValue > 0) {
                ratingLabel.textContent = (labels.at ? labels.at(upToValue) : labels[upToValue]) + ' (' + upToValue + ' ' + (upToValue === 1 ? 'star' : 'stars') + ')';
            } else if (ratingLabel) {
                ratingLabel.textContent = 'Select your rating';
            }
        }
        
        function getSelectedValue() {
            const checked = container.querySelector('input[name="rating"]:checked');
            return checked ? parseInt(checked.value, 10) : 0;
        }
        
        stars.forEach((star) => {
            star.addEventListener('mouseenter', function() {
                const value = parseInt(this.getAttribute('data-value'), 10);
                updateStars(value);
            });
            star.addEventListener('mouseleave', function() {
                updateStars(getSelectedValue());
            });
            star.addEventListener('click', function() {
                const value = parseInt(this.getAttribute('data-value'), 10);
                this.querySelector('input[type="radio"]').checked = true;
                updateStars(value);
            });
        });
        
        updateStars(getSelectedValue());
    })();
    
    // Form validation: ensure a room/unit is selected before submit
    const bookingForm = document.getElementById('bookingForm');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
        if (!selectedUnitId) {
            e.preventDefault();
            alert('Please select a room first');
            return false;
        }
    });
    }
</script>

<!-- Booking Login/Register Modal -->
<div class="modal fade" id="bookingLoginModal" tabindex="-1" aria-labelledby="bookingLoginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #0071c2, #005a9e); color: white; border: none;">
                <h5 class="modal-title" id="bookingLoginModalLabel">
                    <i class="fas fa-lock me-2"></i>Login Required
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 30px;">
                <p class="text-center mb-4" style="color: #666;">
                    Please login or create an account to complete your booking
                </p>
                
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs mb-4" id="bookingAuthTabs" role="tablist" style="border-bottom: 2px solid #e0e0e0;">
                    <li class="nav-item" role="presentation" style="flex: 1;">
                        <button class="nav-link active w-100" id="booking-login-tab" data-bs-toggle="tab" data-bs-target="#booking-login" type="button" role="tab" aria-controls="booking-login" aria-selected="true" style="border: none; border-bottom: 3px solid #0071c2; color: #0071c2; font-weight: 600; padding: 12px;">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </button>
                    </li>
                    <li class="nav-item" role="presentation" style="flex: 1;">
                        <button class="nav-link w-100" id="booking-register-tab" data-bs-toggle="tab" data-bs-target="#booking-register" type="button" role="tab" aria-controls="booking-register" aria-selected="false" style="border: none; color: #666; font-weight: 600; padding: 12px;">
                            <i class="fas fa-user-plus me-2"></i>Create Account
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="bookingAuthTabContent">
                    <!-- Login Tab -->
                    <div class="tab-pane fade show active" id="booking-login" role="tabpanel" aria-labelledby="booking-login-tab">
                        <form id="booking-login-form" method="POST" action="{{ route('login') }}">
                            @csrf
                            <input type="hidden" name="redirect_after_login" value="{{ url()->current() }}">

                            <div class="mb-3">
                                <label for="booking-login-email" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Email Address
                                </label>
                                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                       id="booking-login-email" name="email" value="{{ old('email') }}" 
                                       placeholder="Enter your email" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="booking-login-password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Password
                                </label>
                                <div class="position-relative">
                                    <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                           id="booking-login-password" name="password" 
                                           placeholder="Enter your password" required>
                                    <button type="button" class="btn btn-sm position-absolute end-0 top-50 translate-middle-y me-2" 
                                            onclick="toggleBookingPassword('booking-login-password')" style="border: none; background: none; color: #666;">
                                        <i class="fas fa-eye" id="booking-login-password-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="booking-remember">
                                    <label class="form-check-label" for="booking-remember">
                                        Remember me
                                    </label>
                                </div>
                                <a href="{{ route('password.request') }}" class="text-decoration-none" style="color: #0071c2; font-size: 14px;">
                                    Forgot password?
                                </a>
                            </div>

                            <div id="booking-login-message" class="mb-3"></div>

                            <button type="submit" class="btn w-100" style="background: linear-gradient(135deg, #0071c2, #005a9e); color: white; padding: 12px; font-weight: 600; border-radius: 8px;">
                                <i class="fas fa-sign-in-alt me-2"></i>Sign In
                            </button>
                        </form>
                    </div>

                    <!-- Register Tab -->
                    <div class="tab-pane fade" id="booking-register" role="tabpanel" aria-labelledby="booking-register-tab">
                        <div class="alert alert-info mb-3" style="font-size: 13px; padding: 12px;">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Important:</strong> After creating your account, you must verify your email address before you can complete your booking. A verification link will be sent to your email.
                        </div>
                        <form id="booking-register-form" method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="booking-register-name" class="form-label">
                                    <i class="fas fa-user me-2"></i>Full Name
                                </label>
                                <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                       id="booking-register-name" name="name" value="{{ old('name') }}" 
                                       placeholder="Enter your full name" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="booking-register-email" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Email Address
                                </label>
                                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                       id="booking-register-email" name="email" value="{{ old('email') }}" 
                                       placeholder="Enter your email" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="booking-register-password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Password
                                </label>
                                <div class="position-relative">
                                    <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                           id="booking-register-password" name="password" 
                                           placeholder="Create a password (min. 8 characters)" required>
                                    <button type="button" class="btn btn-sm position-absolute end-0 top-50 translate-middle-y me-2" 
                                            onclick="toggleBookingPassword('booking-register-password')" style="border: none; background: none; color: #666;">
                                        <i class="fas fa-eye" id="booking-register-password-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Must be at least 8 characters</small>
                            </div>

                            <div class="mb-3">
                                <label for="booking-register-password-confirm" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Confirm Password
                                </label>
                                <div class="position-relative">
                                    <input type="password" class="form-control form-control-lg" 
                                           id="booking-register-password-confirm" name="password_confirmation" 
                                           placeholder="Confirm your password" required>
                                    <button type="button" class="btn btn-sm position-absolute end-0 top-50 translate-middle-y me-2" 
                                            onclick="toggleBookingPassword('booking-register-password-confirm')" style="border: none; background: none; color: #666;">
                                        <i class="fas fa-eye" id="booking-register-password-confirm-eye"></i>
                                    </button>
                                </div>
                            </div>

                            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="terms" id="booking-terms" required>
                                        <label class="form-check-label" for="booking-terms">
                                            I agree to the <a href="{{ route('terms.show') }}" target="_blank" style="color: #0071c2;">Terms of Service</a> and <a href="{{ route('policy.show') }}" target="_blank" style="color: #0071c2;">Privacy Policy</a>
                                        </label>
                                    </div>
                                </div>
                            @endif

                            <div id="booking-register-message" class="mb-3"></div>

                            <button type="submit" class="btn w-100" style="background: linear-gradient(135deg, #0071c2, #005a9e); color: white; padding: 12px; font-weight: 600; border-radius: 8px;">
                                <i class="fas fa-user-plus me-2"></i>Create Account
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
