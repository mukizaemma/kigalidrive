@php
    $whyItems = [
        ['icon' => 'fa-car', 'title' => 'Wide vehicle selection', 'text' => 'Sedans, SUVs, hybrids, and luxury options for every trip.'],
        ['icon' => 'fa-shield', 'title' => 'Safe & maintained fleet', 'text' => 'Regular servicing and verified listings you can trust.'],
        ['icon' => 'fa-tags', 'title' => 'Transparent pricing', 'text' => 'Clear daily, weekly, and monthly rates — no hidden surprises.'],
        ['icon' => 'fa-user-tie', 'title' => 'Driver or self-drive', 'text' => 'Choose chauffeur service or drive yourself with flexible terms.'],
        ['icon' => 'fa-headset', 'title' => 'Fast support', 'text' => 'Responsive team in Kigali — English, French, Kinyarwanda & Swahili.'],
        ['icon' => 'fa-calendar-check', 'title' => 'Easy booking', 'text' => 'Reserve online in minutes or message us on WhatsApp.'],
        ['icon' => 'fa-hand-holding-dollar', 'title' => 'Cars for sale', 'text' => 'Browse quality vehicles for purchase with clear pricing in USD.'],
        ['icon' => 'fa-map-location-dot', 'title' => 'Local expertise', 'text' => 'Based in Kisimenti — we know Kigali and Rwanda inside out.'],
    ];

    $whyBgFile = optional($setting)->why_trust_background_image ?? optional($setting)->home_background_image ?? null;
    $whyBgUrl = $whyBgFile
        ? asset('storage/images/site/' . ltrim($whyBgFile, '/'))
        : asset('assets/img/bg/breadcumb-bg-1.jpg');
@endphp

<section class="kdr-why-section kdr-why-section--parallax" aria-labelledby="kdr-why-heading">
    <div class="kdr-why-section__bg kdr-why-parallax-bg"
         style="background-image: url('{{ $whyBgUrl }}');"
         data-parallax-bg
         aria-hidden="true"></div>
    <div class="kdr-why-section__overlay" aria-hidden="true"></div>

    <div class="container kdr-why-section__content">
        <div class="text-center mb-5">
            <p class="kdr-why-section__eyebrow">Why choose us</p>
            <h2 id="kdr-why-heading" class="kdr-why-section__title">Why Clients Trust Kigali Drive Rentals</h2>
            <p class="kdr-why-section__lead">Reliable car rentals and sales in Rwanda — tailored for locals, tourists, NGOs, and corporates.</p>
        </div>
        <div class="row g-3 g-lg-4">
            @foreach($whyItems as $item)
            <div class="col-sm-6 col-lg-3">
                <article class="kdr-why-card h-100">
                    <div class="kdr-why-card__head">
                        <div class="kdr-why-card__icon" aria-hidden="true">
                            <i class="fas {{ $item['icon'] }}"></i>
                        </div>
                        <h3 class="kdr-why-card__title">{{ $item['title'] }}</h3>
                    </div>
                    <p class="kdr-why-card__text">{{ $item['text'] }}</p>
                </article>
            </div>
            @endforeach
        </div>
    </div>
</section>

@push('scripts')
<script>
(function () {
    var section = document.querySelector('.kdr-why-section--parallax');
    var bg = section && section.querySelector('[data-parallax-bg]');
    if (!bg || !section || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    var ticking = false;
    function updateParallax() {
        var box = section.getBoundingClientRect();
        var vh = window.innerHeight || 1;
        if (box.bottom < 0 || box.top > vh) {
            bg.style.transform = 'translate3d(0, 0, 0) scale(1.12)';
            ticking = false;
            return;
        }
        var progress = (vh - box.top) / (vh + box.height);
        var offset = (progress - 0.5) * 140;
        bg.style.transform = 'translate3d(0, ' + offset.toFixed(2) + 'px, 0) scale(1.12)';
        ticking = false;
    }

    window.addEventListener('scroll', function () {
        if (!ticking) {
            window.requestAnimationFrame(updateParallax);
            ticking = true;
        }
    }, { passive: true });
    window.addEventListener('resize', updateParallax, { passive: true });
    updateParallax();
})();
</script>
@endpush
