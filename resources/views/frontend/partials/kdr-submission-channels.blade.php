@php
    $channelContext = $channelContext ?? 'contact';
    $channelService = app(\App\Services\SubmissionChannelService::class);
    $availableChannels = $channelService->availableChannels($setting ?? null, $channelContext);
    $selectedChannel = old('channel');
@endphp

@if(count($availableChannels) === 0)
    <div class="alert alert-warning mb-0">
        Online submissions are unavailable. Please use the phone number or email shown on this page.
    </div>
@else
    <div class="kdr-channel-picker mb-3">
        <label class="form-label fw-semibold">How would you like to send this? <span class="text-danger">*</span></label>
        <div class="kdr-channel-picker__options">
            @foreach($availableChannels as $value => $label)
            <label class="kdr-channel-option">
                <input type="radio" name="channel" value="{{ $value }}" @checked($selectedChannel === $value)>
                <span class="kdr-channel-option__box">
                    @if($value === 'whatsapp')
                        <i class="fab fa-whatsapp kdr-channel-option__icon text-success" aria-hidden="true"></i>
                    @elseif($value === 'email')
                        <i class="fas fa-envelope kdr-channel-option__icon" aria-hidden="true"></i>
                    @else
                        <i class="fas fa-paper-plane kdr-channel-option__icon" aria-hidden="true"></i>
                    @endif
                    <span class="kdr-channel-option__label">{{ $label }}</span>
                </span>
            </label>
            @endforeach
        </div>
        <div class="kdr-channel-picker__error text-danger small mt-1 d-none" role="alert"></div>
        @error('channel')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
        <p class="text-muted small mt-2 mb-0">
            Choose a method before submitting. WhatsApp opens with your details pre-filled; Email opens your mail app with a draft to us; Online form sends the request through our website.
        </p>
    </div>
@endif
