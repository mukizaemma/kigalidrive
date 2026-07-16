{{--
  Split hero: copy + CTAs left, image slideshow right.
  Copy from Home hire intro (admin) or site defaults.
--}}
@php
    $heroSlides = ($heroSlides ?? collect())->filter(fn ($s) => filled($s->image));
    $intro = $hireIntro ?? null;
    $useIntro = $intro && ($intro->is_active ?? false);

    $eyebrow = $useIntro && $intro->eyebrow ? $intro->eyebrow : 'Kigali Drive Rentals';
    $headline = $useIntro && $intro->headline
        ? $intro->headline
        : ($defaultHeroTitle ?? optional($setting)->tagline ?? 'Premium car rentals in Kigali');
    $subline = $useIntro && $intro->hook
        ? $intro->hook
        : ($defaultHeroSubtitle ?? 'Chauffeur car rentals — daily & monthly rates in USD across Rwanda.');
    $highlight = $useIntro ? $intro->hook_highlight : null;

    $ctaPrimaryLabel = $useIntro ? $intro->cta_primary_label : 'Browse our fleet';
    $ctaPrimaryUrl = $useIntro ? $intro->ctaPrimaryHref() : route('showCars');
    $ctaSecondaryLabel = $useIntro && $intro->cta_secondary_label
        ? $intro->cta_secondary_label
        : 'Tell us what you need';
    $ctaSecondaryUrl = $useIntro && $intro->ctaSecondaryHref()
        ? $intro->ctaSecondaryHref()
        : route('contact');

    $headlineHtml = e($headline);
    if (preg_match('/^(.*\byour\s+)(.+)$/iu', $headline, $m)) {
        $headlineHtml = e($m[1]) . '<span class="kdr-hero-split__title-accent">' . e($m[2]) . '</span>';
    } elseif (preg_match('/\s/u', trim($headline))) {
        $words = preg_split('/\s+/u', trim($headline));
        if (count($words) >= 3) {
            $accent = array_splice($words, -2);
            $headlineHtml = e(implode(' ', $words)) . ' <span class="kdr-hero-split__title-accent">' . e(implode(' ', $accent)) . '</span>';
        }
    }

    $fleetCount = (int) ($fleetCount ?? 0);
    $heroFromPrice = $heroFromPrice ?? null;
    $wa = $whatsappUrl ?? null;
@endphp

<section class="kdr-hero-split" aria-label="Welcome">
    <div class="kdr-hero-split__bg" aria-hidden="true">
        <span class="kdr-hero-split__orb kdr-hero-split__orb--1"></span>
        <span class="kdr-hero-split__orb kdr-hero-split__orb--2"></span>
    </div>
    <div class="container">
        <div class="row align-items-center g-4 g-lg-5">
            <div class="col-lg-7 kdr-hero-split__copy">
                <span class="kdr-hero-split__eyebrow">{{ $eyebrow }}</span>
                <h1 class="kdr-hero-split__title">{!! $headlineHtml !!}</h1>
                <p class="kdr-hero-split__sub">
                    @if($useIntro && $highlight)
                        {!! $intro->hookHtml() !!}
                    @else
                        {{ $subline }}
                    @endif
                </p>
                <div class="kdr-hero-split__actions">
                    <a href="{{ $ctaPrimaryUrl }}" class="kdr-hero-btn kdr-hero-btn--primary">
                        <i class="fas fa-car" aria-hidden="true"></i>
                        <span>{{ $ctaPrimaryLabel }}</span>
                        <i class="fas fa-arrow-right kdr-hero-btn__arrow" aria-hidden="true"></i>
                    </a>
                    <a href="{{ $ctaSecondaryUrl }}" class="kdr-hero-btn kdr-hero-btn--ghost">
                        <i class="fas fa-comment-dots" aria-hidden="true"></i>
                        <span>{{ $ctaSecondaryLabel }}</span>
                    </a>
                </div>
                <ul class="kdr-hero-split__trust" aria-label="Why book with us">
                    <li><i class="fas fa-dollar-sign" aria-hidden="true"></i> Clear USD pricing</li>
                    <li><i class="fas fa-plane-arrival" aria-hidden="true"></i> Airport &amp; city pickup</li>
                    <li><i class="fas fa-user-tie" aria-hidden="true"></i> Professional chauffeur</li>
                </ul>
            </div>
            <div class="col-lg-5 kdr-hero-split__media-col">
                @include('frontend.partials.kdr-hero-slider-visual', [
                    'heroSlides' => $heroSlides,
                    'fleetCount' => $fleetCount,
                    'heroFromPrice' => $heroFromPrice,
                ])
            </div>
        </div>
    </div>
</section>
