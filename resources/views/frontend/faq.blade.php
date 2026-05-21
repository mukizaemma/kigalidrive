@extends('layouts.frontbase')

@section('content')
<section class="py-5 mt-4">
    <div class="container">
        <h1 class="kdr-section-title mb-4">Frequently Asked Questions</h1>
        <div class="accordion" id="faqAccordion">
            @foreach([
                ['How do I book a car without an account?', 'Choose a vehicle, fill in the reservation form, and submit via email or WhatsApp. You receive a booking reference instantly.'],
                ['What rental periods are available?', 'Daily, weekly, monthly, annual, or custom durations — with or without a driver.'],
                ['Can I buy a car through Kigali Drive Rentals?', 'Yes. Browse our Cars for Sale section or contact us for vehicles listed for purchase.'],
                ['Do you offer self-drive and chauffeur options?', 'Most vehicles support self-drive, with-driver, or both. Check each listing for availability.'],
                ['How do I leave a review?', 'Use your booking number from your reservation confirmation on the reviews page.'],
                ['How can I list my car for rent or sale?', 'Use List your car and our team will review your submission before publishing.'],
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
