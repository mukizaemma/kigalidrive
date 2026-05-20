@extends('layouts.frontbase')

@section('content')
@php
    $listingName = $hotel->name ?? $propertyModel->name ?? 'Listing';
    $signUrl = $hotel
        ? route('my.properties.listing-agreement.sign', $hotel)
        : route('my.properties.property.listing-agreement.sign', $propertyModel);
    $backUrl = route('myProperties').'#properties';
    $sig = $hotel ? $hotel->listingAgreementSignature : $propertyModel->listingAgreementSignature;
    $signedCurrent = $sig && $sig->isCurrentForTemplate($template);
@endphp
<div class="container py-4" style="max-width: 900px;">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h1 class="h3 mb-1">Property listing agreement</h1>
            <p class="text-muted small mb-0">{{ $listingName }}</p>
        </div>
        <a href="{{ $backUrl }}" class="btn btn-outline-secondary btn-sm">Back to dashboard</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($sig && ! $signedCurrent)
        <div class="alert alert-warning">
            The platform agreement was updated after you last signed. Please review and sign again to keep your listing compliant.
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4 p-md-5">
            <p class="text-muted small mb-3">Between <strong>{{ $template->platform_name ?? 'Stay Nets' }}</strong> (“Platform”) and you as Host.</p>

            @if($template->intro_text)
                <div class="mb-4 property-description" style="white-space: pre-wrap;">{{ $template->intro_text }}</div>
            @endif

            @foreach(($template->sections ?: \App\Models\ListingAgreementTemplate::defaultSections()) as $block)
                <div class="mb-4">
                    <h2 class="h5 text-dark">{{ $block['heading'] ?? '' }}</h2>
                    @if(!empty($block['items']) && is_array($block['items']))
                        <ul class="mb-0">
                            @foreach($block['items'] as $line)
                                <li>{{ $line }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach

            <hr class="my-4">

            <div class="row g-4 align-items-end">
                <div class="col-md-6">
                    <h3 class="h6 text-uppercase text-muted">Platform representative</h3>
                    <p class="mb-1 fw-semibold">{{ $template->platform_representative_name ?: '—' }}</p>
                    @if($template->platform_signature_path)
                        <div class="border rounded p-2 bg-light mt-2" style="max-width: 280px;">
                            <img src="{{ asset('storage/'.$template->platform_signature_path) }}" alt="Platform signature" class="img-fluid" style="max-height: 100px;">
                        </div>
                    @else
                        <p class="text-muted small mb-0">Signature on file will appear here once set by the platform.</p>
                    @endif
                </div>
                <div class="col-md-6">
                    <h3 class="h6 text-uppercase text-muted">Host</h3>
                    <p class="mb-1 fw-semibold">{{ auth()->user()->name }}</p>
                    <p class="small text-muted mb-2">{{ $listingName }}</p>
                    @if($signedCurrent && $sig && $sig->owner_signature_path)
                        <div class="border rounded p-2 bg-light mt-2" style="max-width: 280px;">
                            <img src="{{ asset('storage/'.$sig->owner_signature_path) }}" alt="Your signature" class="img-fluid" style="max-height: 100px;">
                        </div>
                        <p class="small text-success mt-2 mb-0">Signed {{ $sig->signed_at?->format('M j, Y g:i A') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(! $signedCurrent)
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Confirm &amp; sign</h2>
                <p class="text-muted small">Upload a clear image of your signature (PNG or JPG). This confirms you accept the listing agreement for this property.</p>
                <form action="{{ $signUrl }}" method="POST" enctype="multipart/form-data" class="mt-3">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Signature image <span class="text-danger">*</span></label>
                        <input type="file" name="signature_image" class="form-control" accept="image/*" required>
                        @error('signature_image')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="confirm_agreement" id="confirm_agreement" value="1" required>
                        <label class="form-check-label" for="confirm_agreement">
                            I have read and agree to the Property Listing Agreement above for <strong>{{ $listingName }}</strong>.
                        </label>
                    </div>
                    @error('confirm_agreement')<div class="text-danger small">{{ $message }}</div>@enderror
                    <button type="submit" class="btn btn-primary">Submit signature</button>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-success border-0 mb-0">
            <i class="fas fa-check-circle me-2"></i>Your agreement is on file and matches the current platform template.
        </div>
    @endif
</div>
@endsection
