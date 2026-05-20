@extends('layouts.frontbase')

@section('content')
<style>
    .unit-header-section { margin-bottom: 16px; }
    .unit-title { font-size: 24px; font-weight: 700; color: #1a1a1a; margin: 0; }
    .breadcrumb-link { color: #0071c2; text-decoration: none; }
    .breadcrumb-link:hover { text-decoration: underline; }
    .image-gallery-main {
        position: relative; width: 100%; height: 500px; border-radius: 12px; overflow: hidden;
        margin-bottom: 10px; background: #f0f0f0; cursor: pointer;
    }
    .image-gallery-main img { width: 100%; height: 100%; object-fit: cover; }
    .image-gallery-thumbs { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
    .gallery-thumb {
        position: relative; height: 120px; border-radius: 8px; overflow: hidden;
        cursor: pointer; opacity: 0.8; transition: opacity 0.3s;
    }
    .gallery-thumb:hover { opacity: 1; }
    .gallery-thumb.active { opacity: 1; border: 3px solid #0071c2; }
    .gallery-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .gallery-overlay {
        position: absolute; bottom: 15px; right: 15px;
        background: rgba(0,0,0,0.7); color: white; padding: 8px 15px;
        border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer;
    }
    .content-section {
        background: white; border-radius: 12px; padding: 30px; margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .section-title { font-size: 22px; font-weight: 700; color: #1a1a1a; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #f0f0f0; }
    .property-gallery-link {
        display: inline-flex; align-items: center; gap: 8px; color: #0071c2; text-decoration: none;
        font-weight: 600; padding: 10px 16px; background: #e7f3ff; border-radius: 8px; margin-bottom: 20px;
    }
    .property-gallery-link:hover { background: #cce5ff; color: #005a9e; }
    .room-snapshot { display: flex; flex-wrap: wrap; gap: 20px; margin: 20px 0; }
    .room-snapshot-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; background: #f8f9fa; border-radius: 8px; }
    .room-snapshot-item i { color: #0071c2; font-size: 18px; width: 24px; }
    .btn-book-room {
        background: #0071c2; color: white; border: none; padding: 12px 24px;
        border-radius: 6px; font-weight: 600; cursor: pointer; transition: background 0.3s;
    }
    .btn-book-room:hover { background: #005a9e; }
    .gallery-thumbnail.active { border: 2px solid #0071c2 !important; }
    @media (max-width: 991px) { .image-gallery-main { height: 300px; } .image-gallery-thumbs { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 767px) { .image-gallery-thumbs { grid-template-columns: repeat(2, 1fr); } }
</style>

@php
    $allImages = $roomImages ?? collect();
    $mainImage = $allImages->isNotEmpty() ? $allImages->first()['url'] : asset('assets/img/tour/tour_3_1.jpg');
    $unit = $unit ?? null;
    $property = $property ?? null;
    $uPt = $unit ? ($unit->price_display_type ?? 'per_night') : 'per_night';
    $currency = $unit ? ($unit->currency ?? 'USD') : 'USD';
    $currencySymbol = getCurrencySymbol($currency);
    $unitDisplayName = $unit ? ($unit->unitType && $unit->unitType->name !== $unit->name ? $unit->unitType->name . ' – ' . $unit->name : $unit->name) : 'Room';
@endphp

<div class="container-fluid py-3" style="max-width: 1400px;">
    <!-- Breadcrumb & Header -->
    <div class="unit-header-section">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('apartments') }}" class="breadcrumb-link">Apartments</a></li>
                <li class="breadcrumb-item"><a href="{{ route('hotel', $property->slug ?? $property->id) }}" class="breadcrumb-link">{{ $property->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $unitDisplayName }}</li>
            </ol>
        </nav>
        <h1 class="unit-title">{{ $unitDisplayName }}</h1>
        <p class="text-muted mb-0">
            <a href="{{ route('hotel', $property->slug ?? $property->id) }}" class="breadcrumb-link">
                <i class="fas fa-building me-1"></i>{{ $property->name }}
            </a>
        </p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Link to view property gallery -->
            <a href="{{ route('hotel', $property->slug ?? $property->id) }}#property-gallery" class="property-gallery-link">
                <i class="fas fa-images"></i> View property gallery
            </a>

            <!-- Room Gallery -->
            <div class="content-section" id="room-gallery">
                <h3 class="section-title">Room Gallery</h3>
                <div class="image-gallery-main">
                    <img src="{{ $mainImage }}" alt="{{ $unitDisplayName }}" id="mainGalleryImage" onclick="openGalleryModal(0)">
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

            <!-- Room Details -->
            <div class="content-section">
                <h3 class="section-title">Room Details</h3>
                @if($unit->description)
                    <div class="mb-4">{!! $unit->description !!}</div>
                @else
                    <p class="text-muted">No description available.</p>
                @endif

                <h4 class="mb-3" style="font-size: 18px;">Room Snapshot</h4>
                <div class="room-snapshot">
                    <div class="room-snapshot-item">
                        <i class="fas fa-users"></i>
                        <div><span class="text-muted d-block small">Max Occupancy</span><strong>{{ $unit->max_occupancy ?? 0 }}</strong> guests</div>
                    </div>
                    @if($unit->bedrooms)
                    <div class="room-snapshot-item">
                        <i class="fas fa-bed"></i>
                        <div><span class="text-muted d-block small">Bedrooms</span><strong>{{ $unit->bedrooms }}</strong></div>
                    </div>
                    @endif
                    @if($unit->bathrooms)
                    <div class="room-snapshot-item">
                        <i class="fas fa-bath"></i>
                        <div><span class="text-muted d-block small">Bathrooms</span><strong>{{ $unit->bathrooms }}</strong></div>
                    </div>
                    @endif
                    @if($unit->size_sqm)
                    <div class="room-snapshot-item">
                        <i class="fas fa-ruler-combined"></i>
                        <div><span class="text-muted d-block small">Size</span><strong>{{ $unit->size_sqm }}</strong> m²</div>
                    </div>
                    @endif
                    <div class="room-snapshot-item">
                        <i class="fas fa-door-open"></i>
                        <div><span class="text-muted d-block small">Availability</span><strong>{{ $unit->available_units ?? 0 }}</strong> of {{ $unit->total_units ?? 0 }} available</div>
                    </div>
                </div>

                @if($unit->facilities && $unit->facilities->isNotEmpty())
                    <h4 class="mb-3 mt-4" style="font-size: 18px;">Room Amenities</h4>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($unit->facilities as $facility)
                            <span class="badge bg-light text-dark" style="padding: 8px 12px;">
                                <i class="{{ $facility->icon ?? 'fas fa-check' }} me-1"></i>{{ $facility->title }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar - Booking -->
        <div class="col-lg-4">
            <div class="content-section" style="position: sticky; top: 20px;">
                <h4 class="section-title mb-3">Book this room</h4>
                <div class="mb-4">
                    @if($uPt === 'per_month')
                        <div style="font-size: 28px; font-weight: 700;">{{ $currencySymbol }}{{ number_format($unit->base_price_per_month ?? 0, 0) }}</div>
                        <div class="text-muted small">per month</div>
                    @elseif($uPt === 'both')
                        <div style="font-size: 28px; font-weight: 700;">{{ $currencySymbol }}{{ number_format($unit->base_price_per_night ?? 0, 0) }}</div>
                        <div class="text-muted small">per night</div>
                        @if(!empty($unit->base_price_per_month))
                            <div class="mt-2" style="font-size: 20px; font-weight: 600;">{{ $currencySymbol }}{{ number_format($unit->base_price_per_month, 0) }}</div>
                            <div class="text-muted small">per month</div>
                        @endif
                    @else
                        <div style="font-size: 28px; font-weight: 700;">{{ $currencySymbol }}{{ number_format($unit->base_price_per_night ?? 0, 0) }}</div>
                        <div class="text-muted small">per night</div>
                    @endif
                </div>

                @if($unit->available_units > 0)
                    <a href="{{ route('hotel', $property->slug ?? $property->id) }}?unit={{ $unit->id }}#reserveBox" class="btn-book-room w-100 text-center d-block text-decoration-none" style="padding: 14px;">
                        <i class="fas fa-calendar-check me-2"></i>Book this room
                    </a>
                    <p class="text-muted small mt-3 mb-0 text-center">
                        You'll be redirected to the property page to complete your booking.
                    </p>
                @else
                    <button class="btn-book-room w-100" disabled style="opacity: 0.6; cursor: not-allowed;">Currently unavailable</button>
                @endif

                <hr class="my-4">
                <a href="{{ route('hotel', $property->slug ?? $property->id) }}" class="breadcrumb-link d-block text-center">
                    <i class="fas fa-arrow-left me-1"></i>Back to {{ $property->name }}
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Gallery Modal -->
<div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white">Room Gallery - {{ $unitDisplayName }}</h5>
                <span class="text-white me-3" id="galleryCounter">1 / {{ $allImages->count() }}</span>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 position-relative" style="display: flex; align-items: center; justify-content: center; min-height: 80vh;">
                <button class="btn btn-light position-absolute" style="left: 20px; top: 50%; transform: translateY(-50%); z-index: 10;" onclick="previousImage()">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <img id="galleryMainImage" src="" alt="Gallery Image" style="max-width: 90%; max-height: 80vh; object-fit: contain;">
                <button class="btn btn-light position-absolute" style="right: 20px; top: 50%; transform: translateY(-50%); z-index: 10;" onclick="nextImage()">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div class="modal-footer border-0 bg-dark">
                <div class="w-100">
                    <div class="d-flex gap-2 overflow-auto pb-2" style="max-height: 120px;">
                        @foreach($allImages as $index => $img)
                            <img src="{{ $img['url'] }}" alt="Thumbnail" class="gallery-thumbnail {{ $index == 0 ? 'active' : '' }}"
                                 style="width: 100px; height: 80px; object-fit: cover; border-radius: 8px; cursor: pointer; border: 2px solid transparent;"
                                 onclick="showImage({{ $index }})" data-index="{{ $index }}">
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const galleryImages = @json($allImages->map(function($img) { return $img['url']; })->values());
    let currentImageIndex = 0;
    function changeMainImage(src, thumbElement, index) {
        document.getElementById('mainGalleryImage').src = src;
        currentImageIndex = index;
        if (thumbElement) {
            document.querySelectorAll('.gallery-thumb').forEach(el => el.classList.remove('active'));
            thumbElement.classList.add('active');
        }
    }
    function openGalleryModal(startIndex) {
        currentImageIndex = startIndex;
        showImage(startIndex);
        new bootstrap.Modal(document.getElementById('galleryModal')).show();
    }
    function showImage(index) {
        if (index < 0 || index >= galleryImages.length) return;
        currentImageIndex = index;
        document.getElementById('galleryMainImage').src = galleryImages[index];
        document.getElementById('galleryCounter').textContent = (index + 1) + ' / ' + galleryImages.length;
        document.querySelectorAll('.gallery-thumbnail').forEach((thumb, i) => {
            thumb.classList.toggle('active', i === index);
        });
    }
    function nextImage() { showImage((currentImageIndex + 1) % galleryImages.length); }
    function previousImage() { showImage((currentImageIndex - 1 + galleryImages.length) % galleryImages.length); }
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('galleryModal');
        if (modal && modal.classList.contains('show')) {
            if (e.key === 'ArrowRight') nextImage();
            else if (e.key === 'ArrowLeft') previousImage();
            else if (e.key === 'Escape') bootstrap.Modal.getInstance(modal).hide();
        }
    });
</script>
@endsection
