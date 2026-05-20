@php
    $channelContext = $channelContext ?? 'contact';
    $channelService = app(\App\Services\SubmissionChannelService::class);
    $availableChannels = $channelService->availableChannels($setting ?? null, $channelContext);
    $selectedChannel = old('channel', array_key_first($availableChannels));
@endphp

@if(count($availableChannels) === 0)
    <div class="alert alert-warning mb-0">
        Online submissions are unavailable. Please use the phone number or email shown on this page.
    </div>
@else
    <div class="kdr-channel-picker mb-4">
        <label class="form-label fw-semibold">How would you like to send this? <span class="text-danger">*</span></label>
        <div class="kdr-channel-picker__options">
            @foreach($availableChannels as $value => $label)
            <label class="kdr-channel-option">
                <input type="radio" name="channel" value="{{ $value }}" @checked($selectedChannel === $value) required>
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
        @error('channel')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
        <p class="text-muted small mt-2 mb-0">
            WhatsApp opens with your message pre-filled to our verified business number. Email and online form go to our active address in Settings.
        </p>
    </div>
@endif
