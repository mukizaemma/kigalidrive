@php
    $intro = $hireIntro ?? null;
    $pillars = ($hireScenarios ?? collect())->filter(fn ($s) => $s->is_active ?? true);
    $showSection = $intro && ($intro->is_active ?? true) && ($intro->show_on_hero ?? true) && $pillars->isNotEmpty();

    $sectionEyebrow = $intro->section_eyebrow ?? 'Why book with us';
    $sectionTitle = $intro->section_title ?? 'Rent with confidence. Drive Rwanda your way.';
    $sectionLead = $intro->section_lead ?? 'Transparent USD pricing, flexible terms, and real people in Kigali — on WhatsApp when you need us.';
@endphp

@if($showSection)
<section class="kdr-marketing-band" aria-labelledby="kdrMarketingBandTitle">
    <div class="container">
        <div class="kdr-marketing-band__intro">
            <h2 id="kdrMarketingBandTitle" class="kdr-marketing-band__title">{{ $sectionTitle }}</h2>
            <p class="kdr-marketing-band__lead">{{ $sectionLead }}</p>
        </div>

        <ul class="kdr-marketing-band__grid" role="list">
            @foreach($pillars as $pillar)
            <li class="kdr-marketing-band__item">
                <span class="kdr-marketing-band__icon" aria-hidden="true"><i class="{{ $pillar->iconClass() }}"></i></span>
                <div>
                    <h3 class="kdr-marketing-band__item-title">{{ $pillar->title }}</h3>
                    <p class="kdr-marketing-band__item-text">{{ $pillar->description }}</p>
                </div>
            </li>
            @endforeach
        </ul>

        @if(!empty($googleReviews['rating']))
        <p class="kdr-marketing-band__social-proof text-center mb-0">
            <i class="fab fa-google text-primary me-1"></i>
            Rated <strong>{{ number_format($googleReviews['rating'], 1) }}</strong> on Google
            @if(!empty($googleReviews['total']))
                · {{ number_format($googleReviews['total']) }}+ reviews
            @endif
            — <a href="{{ route('reviews.index') }}">See what clients say</a>
        </p>
        @endif
    </div>
</section>
@endif
