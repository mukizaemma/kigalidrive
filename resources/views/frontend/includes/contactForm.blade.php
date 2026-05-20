@php
    $contactChannels = app(\App\Services\SubmissionChannelService::class)->availableChannels($setting ?? null, 'contact');
@endphp
<div class="space-extra2-top space" data-bg-src="{{ asset('storage/images/about') . $about->image2 }}">
    <div class="container">
        <div class="row flex-row-reverse justify-content-center align-items-center">
            <div class="col-lg-6">
                <div class="video-box1">
                    <a href="https://youtube.com/shorts/ks01kSxAmi4" class="play-btn style2 popup-video" aria-label="Watch short video about our stay">
                        <i class="fa-sharp fa-solid fa-play"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-6">
                <div>
                    @if(count($contactChannels) > 0)
                    <form class="contact-form2 ajax-contacts kdr-enquiry-form" action="{{ route('enquiries.store') }}" method="POST" novalidate>
                        @csrf
                        <input type="hidden" name="form_type" value="contact">
                        <input type="hidden" name="subject" value="General enquiry">
                        <div class="hp-field" aria-hidden="true" style="position:absolute;left:-9999px;height:0;overflow:hidden;">
                            <input type="text" name="website_url" tabindex="-1" autocomplete="off">
                        </div>

                        <h5 class="sec-title mb-30 text-capitalize">
                            Request Your Stay — we'll confirm availability shortly
                        </h5>

                        <p class="mb-20" style="margin-top:-10px;">
                            Tell us how we can help and we'll reply within <strong>24 hours</strong>.
                            @if(optional($setting)->phone)
                            Or call/WhatsApp us at <a href="tel:{{ $setting->phone }}">{{ $setting->phone }}</a>.
                            @endif
                        </p>

                        @include('frontend.partials.kdr-submission-channels', ['channelContext' => 'contact'])

                        <div class="row">
                            <div class="form-group col-12">
                                <textarea name="message" id="message" cols="30" rows="3" class="form-control" placeholder="How would you like us to serve you?" aria-label="Booking message" required>{{ old('message') }}</textarea>
                                <img src="{{ asset('assets/img/icon/chat.svg') }}" alt="">
                            </div>

                            <div class="col-12 form-group">
                                <input type="text" class="form-control" name="names" id="name3" placeholder="Full name (e.g., John Doe)" aria-label="Full name" value="{{ old('names') }}" required>
                                <img src="{{ asset('assets/img/icon/user.svg') }}" alt="">
                            </div>

                            <div class="col-lg-6 col-sm-12 form-group">
                                <input type="email" class="form-control" name="email" id="email3" placeholder="Email (we'll send confirmation)" aria-label="Email" value="{{ old('email') }}" required>
                                <img src="{{ asset('assets/img/icon/mail.svg') }}" alt="">
                            </div>

                            <div class="col-lg-6 col-sm-12 form-group">
                                <input type="tel" class="form-control" name="phone" id="phone" placeholder="Phone (WhatsApp number preferred)" aria-label="Phone" value="{{ old('phone') }}" inputmode="tel" required>
                                <img src="{{ asset('assets/img/icon/phone.svg') }}" alt="">
                            </div>

                            <div class="form-btn col-12 mt-24">
                                <button type="submit" class="th-btn style3" aria-label="Submit booking request">
                                    Request Booking <img src="{{ asset('assets/img/icon/plane.svg') }}" alt="">
                                </button>
                                <small class="d-block mt-2">No payment required to request — we'll confirm availability first.</small>
                            </div>
                        </div>

                        <p class="form-messages mb-0 mt-3" aria-live="polite" role="status"></p>
                        <p class="mt-2 mb-0" style="font-size:0.9em; opacity:0.9;">
                            We respect your privacy. Your details are used only to process this request.
                        </p>
                    </form>
                    @else
                    <div class="alert alert-warning">
                        Online submissions are unavailable. Please use the contact details on this page.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
