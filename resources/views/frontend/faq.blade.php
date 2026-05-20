@extends('layouts.frontbase')

@section('content')
<section class="py-5 mt-4">
    <div class="container">
        <h1 class="kdr-section-title mb-4">Frequently Asked Questions</h1>
        <div class="accordion" id="faqAccordion">
            @foreach([
                ['How do I book without an account?', 'Choose a car or apartment, fill the reservation form, and submit via email or WhatsApp. You receive a booking number instantly.'],
                ['What rental periods are available for cars?', 'Daily, weekly, monthly, annual, or custom durations — with or without a driver.'],
                ['Can I rent an apartment by day or night?', 'Yes. Select rent by day or night when reserving, or inquire about purchase.'],
                ['How do I leave a review?', 'Use your booking number from your reservation confirmation on the reviews page.'],
                ['How can owners list a car or apartment?', 'Use List with us and our team will review your submission before publishing.'],
            ] as $i => $faq)
            <div class="accordion-item kdr-card mb-2 border-0">
                <h2 class="accordion-header">
                    <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $i }}">{{ $faq[0] }}</button>
                </h2>
                <div id="faq{{ $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">{{ $faq[1] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
