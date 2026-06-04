@php
    $galleryImages = $galleryImages ?? [];
    $carName = $carName ?? 'Vehicle';
    $placeholder = asset('assets/img/tour/tour_3_1.jpg');

    if ($galleryImages === []) {
        $galleryImages = [$placeholder];
    }

    $firstSrc = $galleryImages[0];
    $hasMultiple = count($galleryImages) > 1;
@endphp

<div class="kdr-car-gallery" id="kdrCarGallery" data-image-count="{{ count($galleryImages) }}">
    <div class="kdr-car-gallery__main-wrap">
        <img
            src="{{ $firstSrc }}"
            alt="{{ $carName }}"
            class="kdr-car-gallery__main"
            id="kdrCarGalleryMain"
            loading="eager"
        >
        @if($hasMultiple)
        <button type="button" class="kdr-car-gallery__nav kdr-car-gallery__nav--prev" data-gallery-prev aria-label="Previous image">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button type="button" class="kdr-car-gallery__nav kdr-car-gallery__nav--next" data-gallery-next aria-label="Next image">
            <i class="fas fa-chevron-right"></i>
        </button>
        @endif
    </div>

    @if($hasMultiple)
    <div class="kdr-car-gallery__thumbs" role="tablist" aria-label="Vehicle photos">
        @foreach($galleryImages as $index => $src)
        <button
            type="button"
            class="kdr-car-gallery__thumb {{ $index === 0 ? 'is-active' : '' }}"
            data-gallery-index="{{ $index }}"
            data-gallery-src="{{ $src }}"
            role="tab"
            aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
            aria-label="Photo {{ $index + 1 }}"
        >
            <img src="{{ $src }}" alt="" loading="lazy">
        </button>
        @endforeach
    </div>
    @endif
</div>
