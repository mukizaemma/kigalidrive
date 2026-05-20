{{-- Shared marketing footer + WhatsApp float. $setting is shared globally (AppServiceProvider). --}}
@php
    $googleReviewData = app(\App\Services\GoogleBusinessReviewService::class)->getData($setting ?? null);
    $reviewsCount = $googleReviewData['total'] ?? 0;
    $reviewsAvg = $googleReviewData['rating'] ?? 0;
    $googleWriteUrl = $googleReviewData['write_review_url'] ?? null;
    $socialFacebook = filled($setting->facebook ?? null) ? $setting->facebook : 'https://www.facebook.com/';
    $socialInstagram = filled($setting->instagram ?? null) ? $setting->instagram : 'https://instagram.com/';
    $socialYoutube = filled($setting->youtube ?? null) ? $setting->youtube : null;
    $socialLinkedin = filled($setting->linkedin ?? null) ? $setting->linkedin : null;
@endphp
<footer class="footer-wrapper bg-title footer-layout2 site-footer-enhanced">
    <div class="widget-area">
        <div class="container">
            <div class="row g-4 g-lg-5 align-items-start">
                {{-- Column 1: Brand + Google reviews --}}
                <div class="col-12 col-lg-4">
                    <div class="widget footer-widget footer-widget--brand mb-0">
                        <div class="th-widget-about">
                            <div class="about-logo">
                                <a href="{{ route('home') }}">
                                    <img src="{{ ($setting->logo ?? '') ? asset('storage/images/' . $setting->logo) : asset('assets/img/kdr-logo.png') }}" width="140" alt="{{ $setting->company ?? 'Kigali Drive Rentals' }}">
                                </a>
                            </div>
                            <p class="about-text">{{ $setting->tagline ?? 'Drive Better. Stay Smarter.' }}</p>

                            <div class="footer-reviews-card">
                                <div class="footer-reviews-card__head">
                                    <span class="footer-reviews-card__icon" aria-hidden="true"><i class="fab fa-google"></i></span>
                                    <span class="footer-reviews-card__label">Google reviews</span>
                                </div>
                                @if($reviewsAvg && $reviewsCount)
                                <div class="footer-reviews-card__stats">
                                    <span class="footer-reviews-card__rating">{{ number_format($reviewsAvg, 1) }}</span>
                                    <span class="footer-reviews-card__outof">/ 5</span>
                                    <span class="footer-reviews-card__dot" aria-hidden="true">·</span>
                                    <span class="footer-reviews-card__count">{{ number_format($reviewsCount) }} on Google</span>
                                </div>
                                @else
                                <p class="footer-reviews-card__empty small mb-2">Trusted feedback from our guests on Google.</p>
                                @endif
                                <a href="{{ route('reviews.index') }}" class="footer-reviews-card__link">See all reviews</a>
                                @if($googleWriteUrl)
                                <a href="{{ $googleWriteUrl }}" target="_blank" rel="noopener noreferrer" class="footer-reviews-card__link d-block mt-2">Write a review on Google</a>
                                @endif
                            </div>

                            <div class="footer-social-block">
                                <div class="th-social th-social--footer">
                                    <a href="{{ $socialFacebook }}" rel="noopener noreferrer" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
                                    <a href="{{ $socialInstagram }}" rel="noopener noreferrer" target="_blank" aria-label="Instagram"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                                    @if($socialYoutube)
                                    <a href="{{ $socialYoutube }}" rel="noopener noreferrer" target="_blank" aria-label="YouTube"><i class="fab fa-youtube" aria-hidden="true"></i></a>
                                    @endif
                                    @if($socialLinkedin)
                                    <a href="{{ $socialLinkedin }}" rel="noopener noreferrer" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Column 2: Quick links --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="widget widget_nav_menu footer-widget mb-0">
                        <h3 class="widget_title">Quick Links</h3>
                        <div class="menu-all-pages-container">
                            <ul class="menu footer-quick-links">
                                <li><a href="{{ route('showCars') }}">Cars for rent &amp; sale</a></li>
                                <li><a href="{{ route('apartments') }}">Apartments</a></li>
                                <li><a href="{{ route('services.index') }}">Services</a></li>
                                <li><a href="{{ route('about') }}">About Us</a></li>
                                <li><a href="{{ route('faq') }}">FAQ</a></li>
                                <li><a href="{{ route('listYourProperty') }}">List with us</a></li>
                                <li><a href="{{ route('contact') }}">Contact</a></li>
                                <li><a href="{{ route('terms') }}">Terms &amp; Conditions</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Column 3: Contact + CTAs --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="widget footer-widget footer-widget--contact mb-0">
                        <h3 class="widget_title">Get In Touch</h3>
                        <div class="th-widget-contact">
                            <div class="info-box_text">
                                <div class="icon" aria-hidden="true">
                                    <img src="{{ asset('assets/img/icon/phone.svg') }}" alt="">
                                </div>
                                <div class="details">
                                    <p class="mb-0"><a href="tel:{{ optional($setting)->phone ?? '' }}" class="info-box_link">{{ optional($setting)->phone ?? '' }}</a></p>
                                </div>
                            </div>
                            <div class="info-box_text">
                                <div class="icon" aria-hidden="true">
                                    <img src="{{ asset('assets/img/icon/envelope.svg') }}" alt="">
                                </div>
                                <div class="details">
                                    <p class="mb-0"><a href="mailto:{{ optional($setting)->email ?? '' }}" class="info-box_link">{{ optional($setting)->email ?? '' }}</a></p>
                                </div>
                            </div>
                            <div class="info-box_text">
                                <div class="icon" aria-hidden="true"><img src="{{ asset('assets/img/icon/location-dot.svg') }}" alt=""></div>
                                <div class="details">
                                    <p class="info-box_label mb-0">Location</p>
                                    <p class="mb-0">{{ $setting->address ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="footer-book-cta">
                            <p class="footer-book-cta__label">Ready to book?</p>
                            <div class="footer-book-cta__buttons">
                                <a href="{{ route('showCars') }}" class="th-btn style3 footer-book-btn">Book a Car</a>
                                <a href="{{ route('apartments') }}" class="th-btn style3 footer-book-btn footer-book-btn--outline">Find Apartment</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright-wrap">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <p class="copyright-text mb-0">
                        &copy; {{ date('Y') }} <a href="{{ route('home') }}">{{ optional($setting)->company ?? 'Kigali Drive Rentals' }}</a>. All rights reserved.
                        <span class="copyright-meta">Crafted by <a href="https://www.iremetech.com" target="_blank" rel="noopener noreferrer">Ireme Technologies</a></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>

@php
    $rawPhone = optional($setting)->phone ?: optional($setting)->phone1 ?: '250788316330';
    $whatsappNumber = preg_replace('/\D/', '', $rawPhone) ?: '250788316330';
@endphp
<a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" class="whatsapp-float" rel="noopener noreferrer" aria-label="Contact us on WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>
