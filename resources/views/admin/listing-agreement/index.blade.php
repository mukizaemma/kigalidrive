@extends('layouts.adminBase')

@section('content')
@include('admin.includes.sidebar')

<div class="content">
    @include('admin.includes.navbar')

    <div class="container-fluid pt-4 px-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
            <div>
                <h4 class="mb-1">Listing agreement</h4>
                <p class="text-muted small mb-0">Signed agreements from hosts. Update the platform template only when you need to change terms or the representative signature.</p>
            </div>
            <a href="{{ route('admin.listing-agreement.edit') }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Update listing agreement
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @php
            $templateModel = $template;
        @endphp

        <div class="bg-light rounded p-4 mb-4">
            <div class="row g-3 align-items-center">
                <div class="col-md-8">
                    <h6 class="text-muted text-uppercase small mb-1">Current template</h6>
                    <p class="mb-0">
                        <strong>{{ $templateModel->platform_name ?? '—' }}</strong>
                        @if($templateModel?->platform_representative_name)
                            · Representative: {{ $templateModel->platform_representative_name }}
                        @endif
                        @if($templateModel)
                            <span class="text-muted small">· Last updated {{ $templateModel->updated_at?->format('M j, Y g:i A') }}</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('admin.listing-agreement.edit') }}" class="btn btn-sm btn-outline-primary">Edit template</a>
                </div>
            </div>
        </div>

        <div class="bg-light text-center rounded p-4">
            <h6 class="text-start mb-3">Signed agreements ({{ $signatures->total() }})</h6>

            @if($signatures->isEmpty())
                <p class="text-muted mb-0 py-4">No signatures yet. Hosts will appear here after they sign the listing agreement for a property.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm text-start align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Listing type</th>
                                <th>Listing name</th>
                                <th>Host / owner</th>
                                <th>Signed</th>
                                <th>Status</th>
                                <th>Agreement</th>
                                <th>IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($signatures as $sig)
                                @php
                                    $listing = $sig->signable;
                                    $typeLabel = class_basename($sig->signable_type);
                                    $listingName = $listing ? ($listing->name ?? '—') : '(listing removed)';
                                    $owner = $listing ? ($listing->owner ?? null) : null;
                                    $current = $templateModel ? $sig->isCurrentForTemplate($templateModel) : false;
                                @endphp
                                <tr>
                                    <td>{{ $sig->id }}</td>
                                    <td><span class="badge bg-{{ $typeLabel === 'Hotel' ? 'info' : 'primary' }}">{{ $typeLabel }}</span></td>
                                    <td class="fw-medium">{{ $listingName }}</td>
                                    <td class="small">
                                        @if($owner)
                                            <div class="fw-medium">{{ $owner->name }}</div>
                                            <div class="text-muted">{{ $owner->email }}</div>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="small text-nowrap">{{ $sig->signed_at?->format('M j, Y g:i A') ?? '—' }}</td>
                                    <td>
                                        @if(! $templateModel)
                                            <span class="badge bg-secondary">—</span>
                                        @elseif($current)
                                            <span class="badge bg-success">Matches template</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Re-sign needed</span>
                                        @endif
                                    </td>
                                    <td class="text-nowrap">
                                        <a href="{{ route('admin.listing-agreement.signed.show', $sig) }}" class="btn btn-sm btn-primary py-0">
                                            View / print
                                        </a>
                                        @if($sig->owner_signature_path)
                                            <a href="{{ asset('storage/'.$sig->owner_signature_path) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary py-0 ms-1" title="Signature image only">Image</a>
                                        @endif
                                    </td>
                                    <td class="small text-muted">{{ $sig->signer_ip ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 d-flex justify-content-center">
                    {{ $signatures->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@include('admin.includes.footer')
@endsection
