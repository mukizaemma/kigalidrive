@extends('layouts.frontbase')

@section('content')
@php
    $channelService = app(\App\Services\SubmissionChannelService::class);
    $hasChannels = count($channelService->availableChannels($setting ?? null, 'contact')) > 0;
@endphp

<div class="space bg-smoke">
    <div class="container">
        <div class="title-area text-center">
            <h2 class="sec-title">Contact Kigali Drive Rentals</h2>
            <p class="sec-text">Choose how to reach us — WhatsApp or email — using our verified contact details only.</p>
        </div>
        <div class="row gy-4 justify-content-center">
            <div class="col-xl-4 col-lg-6">
                <div class="about-contact-grid">
                    <div class="about-contact-icon"><img src="{{ asset('assets/img/icon/call.svg') }}" alt=""></div>
                    <div class="about-contact-details">
                        <h6 class="box-title">Phone</h6>
                        @if(optional($setting)->phone)
                        <p class="about-contact-details-text"><a href="tel:{{ $setting->phone }}">{{ $setting->phone }}</a></p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6">
                <div class="about-contact-grid">
                    <div class="about-contact-icon"><img src="{{ asset('assets/img/icon/mail.svg') }}" alt=""></div>
                    <div class="about-contact-details">
                        <h6 class="box-title">Email</h6>
                        @if(optional($setting)->email)
                        <p class="about-contact-details-text"><a href="mailto:{{ $setting->email }}">{{ $setting->email }}</a></p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6">
                <div class="about-contact-grid style2">
                    <div class="about-contact-icon"><img src="{{ asset('assets/img/icon/location-dot2.svg') }}" alt=""></div>
                    <div class="about-contact-details">
                        <h6 class="box-title">Address</h6>
                        <p class="about-contact-details-text">{{ optional($setting)->address }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="space-extra2-top space-extra2-bottom">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                @if($hasChannels)
                <form action="{{ route('enquiries.store') }}" method="POST" class="contact-form2 bg-white rounded-3 p-4 shadow-sm kdr-enquiry-form kdr-channel-form">
                    @csrf
                    <input type="hidden" name="form_type" value="contact">
                    <div class="hp-field" aria-hidden="true" style="position:absolute;left:-9999px;height:0;overflow:hidden;">
                        <label for="website_url">Leave blank</label>
                        <input type="text" name="website_url" id="website_url" tabindex="-1" autocomplete="off">
                    </div>

                    <h5 class="sec-title mb-3">Send a message</h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full name *</label>
                            <input type="text" name="names" class="form-control" value="{{ old('names') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone *</label>
                            <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+250 7XX XXX XXX" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Topic *</label>
                            <select name="subject" class="form-select" required>
                                <option value="">Select topic</option>
                                <option value="Car rental enquiry" @selected(old('subject') == 'Car rental enquiry')>Car rental</option>
                                <option value="Car purchase enquiry" @selected(old('subject') == 'Car purchase enquiry')>Car purchase</option>
                                <option value="List my car" @selected(old('subject') == 'List my car' || old('subject') == 'List my property')>List my car</option>
                                <option value="General enquiry" @selected(old('subject') == 'General enquiry')>General enquiry</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Message *</label>
                            <textarea name="message" class="form-control" rows="5" required>{{ old('message') }}</textarea>
                        </div>
                        <div class="col-12">
                            @include('frontend.partials.kdr-submission-channels', ['channelContext' => 'contact'])
                        </div>
                        <div class="col-12">
                            <button type="submit" class="th-btn btn-kdr-primary">Submit</button>
                        </div>
                    </div>
                </form>
                @else
                <div class="alert alert-warning">Please contact us by phone or email using the details above.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
