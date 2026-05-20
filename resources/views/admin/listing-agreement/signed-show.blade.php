@extends('layouts.adminBase')

@section('content')
<style>
    @media print {
        .no-print,
        .sidebar,
        .navbar,
        .back-to-top,
        #spinner {
            display: none !important;
        }
        .print-area {
            box-shadow: none !important;
            border: none !important;
        }
        body, .container-fluid {
            background: #fff !important;
        }
        .content {
            margin-left: 0 !important;
            width: 100% !important;
        }
    }
</style>

@include('admin.includes.sidebar')

<div class="content">
    @include('admin.includes.navbar')

    <div class="container-fluid pt-4 px-4">
        <div class="no-print d-flex flex-wrap gap-2 justify-content-between align-items-center mb-4">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.listing-agreement.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Back to signed agreements
                </a>
                <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">
                    <i class="fas fa-print me-1"></i>Print
                </button>
            </div>
        </div>

        <div class="no-print alert alert-light border mb-4">
            <div class="row g-2 small">
                <div class="col-md-3"><strong>Record #</strong> {{ $signature->id }}</div>
                <div class="col-md-3"><strong>Listing type</strong> {{ $typeLabel }}</div>
                <div class="col-md-3"><strong>Signed</strong> {{ $signature->signed_at?->format('M j, Y g:i A') ?? '—' }}</div>
                <div class="col-md-3"><strong>IP</strong> {{ $signature->signer_ip ?? '—' }}</div>
                <div class="col-md-6"><strong>Listing</strong> {{ $listingName }}</div>
                <div class="col-md-6"><strong>Host</strong>
                    @if($owner)
                        {{ $owner->name }} &lt;{{ $owner->email }}&gt;
                    @else
                        —
                    @endif
                </div>
                <div class="col-12">
                    <strong>Status</strong>
                    @if($matchesTemplate)
                        <span class="badge bg-success">Matches current template</span>
                    @else
                        <span class="badge bg-warning text-dark">Template changed since signing — host may need to re-sign</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="print-area bg-white rounded shadow-sm p-4 p-md-5 mx-auto" style="max-width: 900px;">
            <header class="text-center border-bottom pb-3 mb-4">
                <h1 class="h4 mb-1">Property listing agreement</h1>
                <p class="text-muted small mb-0">{{ $template->platform_name ?? 'Stay Nets' }}</p>
            </header>

            <section class="mb-4 small text-muted">
                <p class="mb-1"><strong>Listing:</strong> {{ $listingName }}</p>
                <p class="mb-1"><strong>Host:</strong>
                    @if($owner)
                        {{ $owner->name }} ({{ $owner->email }})
                    @else
                        —
                    @endif
                </p>
                <p class="mb-0"><strong>Signed on:</strong> {{ $signature->signed_at?->format('F j, Y \a\t g:i A') ?? '—' }}</p>
            </section>

            <p class="text-muted small mb-3">Between <strong>{{ $template->platform_name ?? 'Stay Nets' }}</strong> (“Platform”) and the Host named below.</p>

            @if($template->intro_text)
                <div class="mb-4" style="white-space: pre-wrap;">{{ $template->intro_text }}</div>
            @endif

            @foreach(($template->sections ?: \App\Models\ListingAgreementTemplate::defaultSections()) as $block)
                <div class="mb-4">
                    <h2 class="h6 text-dark fw-bold">{{ $block['heading'] ?? '' }}</h2>
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
                        <div class="border rounded p-2 bg-light mt-2 d-inline-block">
                            <img src="{{ asset('storage/'.$template->platform_signature_path) }}" alt="Platform signature" class="img-fluid" style="max-height: 100px;">
                        </div>
                    @else
                        <p class="text-muted small mb-0">—</p>
                    @endif
                </div>
                <div class="col-md-6">
                    <h3 class="h6 text-uppercase text-muted">Host</h3>
                    <p class="mb-1 fw-semibold">{{ $owner->name ?? '—' }}</p>
                    @if($owner && $owner->email)
                        <p class="small text-muted mb-1">{{ $owner->email }}</p>
                    @endif
                    <p class="small text-muted mb-2">Property: {{ $listingName }}</p>
                    @if($signature->owner_signature_path)
                        <div class="border rounded p-2 bg-light mt-2 d-inline-block">
                            <img src="{{ asset('storage/'.$signature->owner_signature_path) }}" alt="Host signature" class="img-fluid" style="max-height: 100px;">
                        </div>
                    @else
                        <p class="text-muted small mb-0">No signature file on record.</p>
                    @endif
                </div>
            </div>

            <footer class="mt-5 pt-3 border-top small text-muted text-center">
                <p class="mb-0">Printed from {{ config('app.name', 'Kigali Drive Rentals') }} admin · Record #{{ $signature->id }}</p>
            </footer>
        </div>
    </div>
</div>

@include('admin.includes.footer')
@endsection
