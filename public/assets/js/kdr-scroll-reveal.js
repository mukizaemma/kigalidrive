/**
 * Kigali Drive — scroll reveal (bottom-up fade)
 * Respects prefers-reduced-motion.
 */
(function () {
    'use strict';

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        document.documentElement.classList.add('kdr-reveal-ready');
        return;
    }

    var selectors = [
        '.container-fluid > section',
        '.kdr-home-search__form',
        '.kdr-why-section .kdr-why-card',
        '.kdr-why-section .text-center',
        '.kdr-car-card',
        '.kdr-card',
        '.kdr-service-card',
        '.kdr-service-detail-hero',
        '.kdr-service-detail .col-lg-8 > .kdr-card',
        '.kdr-service-detail-sidebar .kdr-card',
        '.kdr-company-intro__visual',
        '.kdr-company-intro__content',
        '.kdr-home-journey__panel',
        '.kdr-home-reviews',
        '.kdr-cars-hero',
        '.kdr-cars-filters',
        '.about-hero-text',
        '.accordion-item',
        '.about-contact-grid',
        '.contact-form2',
        '.title-area',
        '.kdr-empty-state',
        '.footer-widget',
        '.kdr-google-review-card',
        '.kdr-google-summary'
    ].join(',');

    var seen = new WeakSet();
    var elements = [];

    document.querySelectorAll(selectors).forEach(function (el) {
        if (seen.has(el) || el.closest('.kdr-hero, .kdr-hero--slides')) {
            return;
        }
        seen.add(el);
        el.classList.add('kdr-reveal');
        elements.push(el);
    });

    /* Stagger cards inside grid rows */
    document.querySelectorAll('.row.g-3, .row.g-4, .row.g-5').forEach(function (row) {
        var cards = row.querySelectorAll(':scope > [class*="col-"] .kdr-reveal, :scope > [class*="col-"].kdr-reveal');
        cards.forEach(function (card, index) {
            card.style.setProperty('--kdr-reveal-delay', (index * 0.08) + 's');
        });
    });

    if (!elements.length) {
        document.documentElement.classList.add('kdr-reveal-ready');
        return;
    }

    var observer = new IntersectionObserver(
        function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('kdr-reveal--visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        {
            root: null,
            rootMargin: '0px 0px -8% 0px',
            threshold: 0.12
        }
    );

    elements.forEach(function (el) {
        observer.observe(el);
    });

    document.documentElement.classList.add('kdr-reveal-ready');
})();
