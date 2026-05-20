{{--
  Expects $listing (Property or Hotel) with optional listing_terms (plain text from admin forms).
--}}
@php
    $raw = $listing instanceof \App\Models\Property
        ? $listing->resolvedListingTerms()
        : trim((string) ($listing->listing_terms ?? ''));
@endphp
<div class="property-listing-terms border rounded-3 p-3 p-md-4 bg-white">
    <h3 class="h5 mb-3">Terms &amp; conditions</h3>
    <p class="text-muted small mb-3">Listing-specific rules and conditions set by the host.</p>
    @if($raw !== '')
        <div class="property-description small mb-0">{!! $raw !!}</div>
    @else
        <p class="text-muted small mb-0">The host has not added listing terms and conditions for this property yet.</p>
    @endif
</div>
