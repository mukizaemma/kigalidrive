{{--
  Expects: $heroSlides (collection of Slide), optional $defaultHeroTitle, $defaultHeroSubtitle
--}}
@php
    $heroSlides = ($heroSlides ?? collect())->filter(fn ($s) => filled($s->image));
    $defaultHeroTitle = $defaultHeroTitle ?? (optional($setting)->tagline ?? 'Premium Car Rentals & Sales in Kigali');
    $defaultHeroSubtitle = $defaultHeroSubtitle ?? 'Your trusted partner for self-drive and chauffeur rentals — plus quality vehicles for sale.';
    $compactCopy = $compactCopy ?? false;
@endphp

<section class="kdr-hero kdr-hero--slides {{ $compactCopy ? 'kdr-hero--compact-copy' : '' }}" aria-label="Homepage highlights">
    <div class="swiper kdr-hero-swiper" id="kdrHeroSlider">
        <div class="swiper-wrapper">
            @forelse($heroSlides as $slide)
                <div class="swiper-slide">
                    <div class="kdr-hero-slide-bg" style="background-image: url('{{ $slide->imageUrl() }}');" role="img" aria-label="{{ $slide->heading }}"></div>
                    <div class="kdr-hero-slide-overlay"></div>
                    <div class="container kdr-hero-slide-copy">
                        <div class="kdr-hero-slide-inner">
                            @if(filled($slide->caption))
                                <span class="kdr-hero-badge">{{ $slide->caption }}</span>
                            @endif
                            @if(filled($slide->heading))
                                <h1 class="kdr-hero-slide-title">{{ $slide->heading }}</h1>
                            @endif
                            @if(filled($slide->subheading))
                                <p class="kdr-hero-slide-sub">{{ $slide->subheading }}</p>
                            @endif
                            <div class="kdr-hero-actions">
                                <a href="{{ route('showCars') }}" class="kdr-hero-btn kdr-hero-btn--primary">
                                    <i class="fas fa-car" aria-hidden="true"></i>
                                    <span>Rent a Car</span>
                                </a>
                                <a href="{{ route('showCars', ['listing_type' => 'sale']) }}" class="kdr-hero-btn kdr-hero-btn--ghost">
                                    <span>Cars for Sale</span>
                                    <i class="fas fa-tags" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="swiper-slide">
                    <div class="kdr-hero-slide-bg kdr-hero-slide-bg--default"></div>
                    <div class="kdr-hero-slide-overlay"></div>
                    <div class="container kdr-hero-slide-copy">
                        <div class="kdr-hero-slide-inner">
                            <span class="kdr-hero-badge">Kigali Drive Rentals</span>
                            <h1 class="kdr-hero-slide-title">{{ $defaultHeroTitle }}</h1>
                            <p class="kdr-hero-slide-sub">{{ $defaultHeroSubtitle }}</p>
                            <div class="kdr-hero-actions">
                                <a href="{{ route('showCars') }}" class="kdr-hero-btn kdr-hero-btn--primary">
                                    <i class="fas fa-car" aria-hidden="true"></i>
                                    <span>Rent a Car</span>
                                </a>
                                <a href="{{ route('showCars', ['listing_type' => 'sale']) }}" class="kdr-hero-btn kdr-hero-btn--ghost">
                                    <span>Cars for Sale</span>
                                    <i class="fas fa-tags" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        @if($heroSlides->count() > 1)
            <div class="swiper-pagination kdr-hero-pagination"></div>
            <div class="kdr-hero-nav-wrap">
                <button type="button" class="kdr-hero-nav kdr-hero-nav--prev swiper-button-prev" aria-label="Previous slide">
                    <i class="fas fa-chevron-left" aria-hidden="true"></i>
                </button>
                <button type="button" class="kdr-hero-nav kdr-hero-nav--next swiper-button-next" aria-label="Next slide">
                    <i class="fas fa-chevron-right" aria-hidden="true"></i>
                </button>
            </div>
        @endif
    </div>
</section>
