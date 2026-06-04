@php
    $channelContext = $channelContext ?? 'contact';
    $channelService = app(\App\Services\SubmissionChannelService::class);
    $settingModel = $setting ?? null;
    $availableChannels = $settingModel
        ? $channelService->availableChannels($settingModel, $channelContext)
        : [];
    $channelKeys = array_keys($availableChannels);
    $selectedChannel = old('channel', $channelKeys[0] ?? null);

    $channelHints = [
        'whatsapp' => 'We save your request in our system and notify our team. WhatsApp then opens in a <strong>new tab</strong> with your details ready to send.',
        'email' => 'We save your request in our system and notify our team. Your email app then opens in a <strong>new tab</strong> with a draft addressed to us.',
    ];
@endphp

@if(count($availableChannels) === 0)
    <div class="alert alert-warning mb-0">
        Online submissions are unavailable. Please use the phone number or email shown on this page.
    </div>
@else
    <div class="kdr-channel-picker mb-3" data-kdr-channel-picker>
        <label class="form-label fw-semibold">How would you like to send this? <span class="text-danger">*</span></label>
        <div class="kdr-channel-picker__options">
            @foreach($availableChannels as $value => $label)
            <label class="kdr-channel-option">
                <input type="radio" name="channel" value="{{ $value }}" required @checked($selectedChannel === $value)>
                <span class="kdr-channel-option__box">
                    @if($value === 'whatsapp')
                        <i class="fab fa-whatsapp kdr-channel-option__icon text-success" aria-hidden="true"></i>
                    @else
                        <i class="fas fa-envelope kdr-channel-option__icon" aria-hidden="true"></i>
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
        <p class="text-muted small mt-2 mb-0 kdr-channel-picker__hint" data-kdr-channel-hint role="status">
            @foreach($channelHints as $key => $hint)
                @if(in_array($key, $channelKeys, true))
                <span data-hint-for="{{ $key }}" @if($selectedChannel !== $key) hidden @endif>{!! $hint !!}</span>
                @endif
            @endforeach
        </p>
    </div>
@endif
