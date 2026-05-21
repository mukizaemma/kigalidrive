@extends('layouts.frontbase')

@section('content')
@php
    $channelService = app(\App\Services\SubmissionChannelService::class);
    $hasChannels = count($channelService->availableChannels($setting ?? null, 'booking')) > 0;
@endphp

<section class="py-5 mt-4">
    <div class="container col-lg-8">
        <h1 class="kdr-section-title mb-2">List Your Car</h1>
        <p class="text-muted mb-4">Share a few details about your vehicle — our team in Kisimenti will review and contact you before anything goes live.</p>

        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(!$hasChannels)
        <div class="alert alert-warning">
            Online submissions are temporarily unavailable. Please call or email us using the contact details on this site.
        </div>
        @else
        <form action="{{ route('listYourProperty.store') }}" method="POST" class="kdr-card p-4 kdr-channel-form">
            @csrf
            <input type="hidden" name="product_type" value="car">
            <div class="hp-field" aria-hidden="true" style="position:absolute;left:-9999px;height:0;overflow:hidden;">
                <label for="listing_website_url">Leave blank</label>
                <input type="text" name="website_url" id="listing_website_url" tabindex="-1" autocomplete="off">
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Ad type</label>
                    <select name="ad_type" class="form-select" required>
                        <option value="rent" @selected(old('ad_type') === 'rent')>For rent</option>
                        <option value="sale" @selected(old('ad_type') === 'sale')>For sale</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Vehicle make / model</label>
                    <input type="text" name="vehicle_info" class="form-control" placeholder="e.g. Toyota RAV4 2020" value="{{ old('vehicle_info') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Your name <span class="text-danger">*</span></label>
                    <input type="text" name="contact_name" class="form-control" required value="{{ old('contact_name') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                    <input type="tel" name="phone" class="form-control" required value="{{ old('phone') }}" placeholder="+250 7XX XXX XXX">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger js-listing-email-required d-none">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="your@email.com">
                    <small class="text-muted js-listing-email-hint">Required when sending via Email or Online form.</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Asking price (RWF)</label>
                    <input type="number" name="amount" class="form-control" min="0" step="1" value="{{ old('amount') }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Location <span class="text-danger">*</span></label>
                    <input type="text" name="location" class="form-control" required value="{{ old('location') }}" placeholder="e.g. Kisimenti, Kigali">
                </div>
                <div class="col-12">
                    <label class="form-label">Additional details</label>
                    <textarea name="details" class="form-control" rows="4" placeholder="Condition, mileage, transmission, driver option, etc.">{{ old('details') }}</textarea>
                </div>
            </div>

            <hr class="my-4">

            @include('frontend.partials.kdr-submission-channels', ['channelContext' => 'booking'])

            <div class="mt-3">
                <button type="submit" class="th-btn btn-kdr-primary">Submit for review</button>
            </div>
        </form>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
(function () {
    var form = document.querySelector('form.kdr-channel-form');
    if (!form) return;

    var emailInput = form.querySelector('input[name="email"]');
    var emailRequiredMark = form.querySelector('.js-listing-email-required');

    function syncEmailRequired() {
        var channel = form.querySelector('input[name="channel"]:checked');
        var needsEmail = channel && (channel.value === 'email' || channel.value === 'form');
        if (emailInput) {
            emailInput.required = needsEmail;
        }
        if (emailRequiredMark) {
            emailRequiredMark.classList.toggle('d-none', !needsEmail);
        }
    }

    form.querySelectorAll('input[name="channel"]').forEach(function (radio) {
        radio.addEventListener('change', syncEmailRequired);
    });
    syncEmailRequired();
})();
</script>
@endpush
