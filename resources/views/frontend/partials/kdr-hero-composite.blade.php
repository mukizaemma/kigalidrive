@include('frontend.partials.kdr-hero-split', [
    'heroSlides' => $heroSlides ?? collect(),
    'defaultHeroTitle' => $defaultHeroTitle ?? null,
    'defaultHeroSubtitle' => $defaultHeroSubtitle ?? null,
    'hireIntro' => $hireIntro ?? null,
    'setting' => $setting ?? null,
    'fleetCount' => $fleetCount ?? 0,
    'heroFromPrice' => $heroFromPrice ?? null,
    'whatsappUrl' => $whatsappUrl ?? null,
])
