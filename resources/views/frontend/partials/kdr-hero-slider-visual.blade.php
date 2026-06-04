@php
    $heroSlides = ($heroSlides ?? collect())->filter(fn ($s) => filled($s->image));
    $slideCount = max(1, $heroSlides->count());
    $fleetCount = (int) ($fleetCount ?? 0);
    $heroFromPrice = $heroFromPrice ?? null;
@endphp

<div class="kdr-hero-visual" aria-label="Fleet gallery">
    <div class="kdr-hero-visual__glow" aria-hidden="true"></div>
    <div class="swiper kdr-hero-visual-swiper" id="kdrHeroVisualSlider">
        <div class="swiper-wrapper">
            @forelse($heroSlides as $slide)
            <div class="swiper-slide">
                <div class="kdr-hero-visual__frame">
                    <img src="{{ $slide->imageUrl() }}" alt="{{ $slide->heading ?: 'Kigali Drive Rentals vehicle' }}" loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                    <div class="kdr-hero-visual__shade" aria-hidden="true"></div>
                </div>
            </div>
            @empty
            <div class="swiper-slide">
                <div class="kdr-hero-visual__frame kdr-hero-visual__frame--default" role="img" aria-label="Kigali Drive Rentals"></div>
            </div>
            @endforelse
        </div>
        @if($slideCount > 1)
        <div class="swiper-pagination kdr-hero-visual__pagination"></div>
        <button type="button" class="kdr-hero-visual__nav kdr-hero-visual__nav--prev swiper-button-prev" aria-label="Previous image">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button type="button" class="kdr-hero-visual__nav kdr-hero-visual__nav--next swiper-button-next" aria-label="Next image">
            <i class="fas fa-chevron-right"></i>
        </button>
        @endif
    </div>
    <div class="kdr-hero-visual__badges">
        @if($fleetCount > 0)
        <span class="kdr-hero-visual__badge kdr-hero-visual__badge--fleet">
            <i class="fas fa-check-circle" aria-hidden="true"></i>
            {{ $fleetCount }} {{ $fleetCount === 1 ? 'vehicle' : 'vehicles' }} ready to hire
        </span>
        @endif
        @if($heroFromPrice)
        <span class="kdr-hero-visual__badge kdr-hero-visual__badge--price">
            From {{ formatUsd($heroFromPrice) }}<span class="kdr-hero-visual__badge-unit">/day</span>
        </span>
        @endif
    </div>
</div>
