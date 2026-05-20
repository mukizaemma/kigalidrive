{{--
  Expects $listing (Property or Hotel) with optional cancellation_* fields.
--}}
@php
    $hideForProperty = $listing instanceof \App\Models\Property
        && trim($listing->resolvedListingTerms()) !== '';
    $free = trim((string) ($listing->cancellation_free_period ?? ''));
    $refund = trim((string) ($listing->cancellation_refund_conditions ?? ''));
    $noshow = trim((string) ($listing->cancellation_no_show_policy ?? ''));
@endphp
@if(!$hideForProperty)
<div class="property-cancellation-policy border rounded-3 p-3 p-md-4 bg-light">
    <h3 class="h5 mb-3">Cancellation policy</h3>
    <p class="text-muted small mb-3 mb-md-4">Set by the host for this listing. Also read our <a href="{{ route('terms') }}" target="_blank" rel="noopener">Terms &amp; Conditions</a>.</p>
    <div class="row g-4">
        <div class="col-12">
            <h4 class="h6 text-dark mb-2">Free cancellation period</h4>
            @if($free !== '')
                <div class="property-description small mb-0">{!! nl2br(e($free)) !!}</div>
            @else
                <p class="text-muted small mb-0">The host has not specified a free cancellation period for this listing.</p>
            @endif
        </div>
        <div class="col-12">
            <h4 class="h6 text-dark mb-2">Refund conditions</h4>
            @if($refund !== '')
                <div class="property-description small mb-0">{!! nl2br(e($refund)) !!}</div>
            @else
                <p class="text-muted small mb-0">The host has not specified refund conditions for this listing.</p>
            @endif
        </div>
        <div class="col-12">
            <h4 class="h6 text-dark mb-2">No-show policy</h4>
            @if($noshow !== '')
                <div class="property-description small mb-0">{!! nl2br(e($noshow)) !!}</div>
            @else
                <p class="text-muted small mb-0">The host has not specified a no-show policy for this listing.</p>
            @endif
        </div>
    </div>
</div>
@endif
