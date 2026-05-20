@extends('layouts.frontbase')

@section('content')
<section class="kdr-services-page py-4 py-lg-5">
    <div class="container">
        <div class="kdr-cars-hero mb-5">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <span class="kdr-cars-hero__eyebrow"><i class="fas fa-concierge-bell me-2"></i>Our services</span>
                    <h1 class="kdr-cars-hero__title">Everything you need in Kigali</h1>
                    <p class="kdr-cars-hero__lead mb-0">Car rentals, apartment stays, chauffeur services, and property solutions — one trusted team for locals, visitors, and businesses.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('contact') }}" class="th-btn btn-kdr-primary">Talk to our team</a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @forelse($services as $service)
            <div class="col-md-6 col-lg-4">
                <article class="kdr-service-card h-100">
                    <a href="{{ route('services.show', $service->slug) }}" class="kdr-service-card__media">
                        @if($service->image && file_exists(storage_path('app/public/images/services/' . $service->image)))
                            <img src="{{ asset('storage/images/services/' . $service->image) }}" alt="{{ $service->title }}" loading="lazy">
                        @else
                            <div class="kdr-service-card__icon-fallback">
                                <i class="fas {{ $service->icon ?? 'fa-star' }}"></i>
                            </div>
                        @endif
                    </a>
                    <div class="kdr-service-card__body">
                        <h2 class="h5 kdr-service-card__title">
                            <a href="{{ route('services.show', $service->slug) }}">{{ $service->title }}</a>
                        </h2>
                        <p class="text-muted small mb-3">{{ $service->excerpt ?: Str::limit(strip_tags($service->description), 120) }}</p>
                        <a href="{{ route('services.show', $service->slug) }}" class="th-btn btn-kdr-primary btn-sm">Learn more</a>
                    </div>
                </article>
            </div>
            @empty
            <div class="col-12">
                <div class="kdr-empty-state text-center py-5">
                    <p class="text-muted mb-0">Services will be listed here soon.</p>
                </div>
            </div>
            @endforelse
        </div>

        @if($services->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $services->links('vendor.pagination.kdr') }}
        </div>
        @endif
    </div>
</section>
@endsection
