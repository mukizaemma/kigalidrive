@extends('layouts.frontbase')

@section('title', 'FAQ | ' . (optional($setting)->company ?? 'Kigali Drive Rentals'))

@section('content')
<section class="py-5 mt-4">
    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8 text-center">
                <h1 class="kdr-section-title mb-2">Frequently Asked Questions</h1>
                <p class="text-muted mb-0">Answers about renting, buying, listings, reviews, and bookings.</p>
            </div>
        </div>

        @if($faqs->isNotEmpty())
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="accordion" id="faqAccordion">
                    @foreach($faqs as $i => $faq)
                    <div class="accordion-item kdr-card mb-2 border-0">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq{{ $faq->id }}">
                                {{ $faq->question }}
                            </button>
                        </h2>
                        <div id="faq{{ $faq->id }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}"
                             data-bs-parent="#faqAccordion">
                            <div class="accordion-body">{!! nl2br(e($faq->answer)) !!}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @else
        <div class="kdr-empty-state text-center py-5">
            <p class="text-muted mb-0">FAQ content is being updated. Please <a href="{{ route('contact') }}">contact us</a> for help.</p>
        </div>
        @endif
    </div>
</section>

@include('frontend.partials.google-reviews-cta', ['setting' => $setting ?? null])
@endsection
