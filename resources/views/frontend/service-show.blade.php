@extends('layouts.frontbase')

@section('title', $service->title . ' | ' . (optional($setting)->company ?? 'Kigali Drive Rentals'))

@section('content')
@php
    $cta = $service->primaryCta();
    $highlights = $service->highlights();
    $intro = $service->introText();
    $hasImage = $service->image && file_exists(storage_path('app/public/images/services/' . $service->image));
    $imgUrl = $hasImage ? asset('storage/images/services/' . $service->image) : null;
@endphp

<section class="kdr-service-detail-hero">
    <div class="container">
        <nav aria-label="breadcrumb" class="kdr-service-detail-hero__breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('services.index') }}">Services</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $service->title }}</li>
            </ol>
        </nav>
        <div class="row align-items-center g-4 py-4 py-lg-5">
            <div class="col-lg-7">
                <span class="kdr-service-detail-hero__type">{{ $service->serviceTypeLabel() }}</span>
                <h1 class="kdr-service-detail-hero__title">{{ $service->title }}</h1>
                <p class="kdr-service-detail-hero__lead mb-0">
                    {{ $service->excerpt ?: Str::limit($intro, 200) }}
                </p>
            </div>
            <div class="col-lg-5 text-lg-end">
                <div class="kdr-service-detail-hero__icon-wrap" aria-hidden="true">
                    @if($hasImage)
                        <img src="{{ $imgUrl }}" alt="" class="kdr-service-detail-hero__thumb">
                    @else
                        <i class="fas {{ $service->icon ?? 'fa-concierge-bell' }}"></i>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<section class="kdr-service-detail py-4 py-lg-5">
    <div class="container">
        <div class="row g-4 g-lg-5">
            <div class="col-lg-8">
                @if($hasImage)
                <img src="{{ $imgUrl }}" alt="{{ $service->title }}" class="kdr-service-detail__cover mb-4">
                @endif

                @if($intro)
                <div class="kdr-card p-4 p-lg-5 mb-4">
                    <h2 class="h5 mb-3">About this service</h2>
                    <div class="kdr-service-detail__content">
                        {!! nl2br(e($intro)) !!}
                    </div>
                </div>
                @endif

                @if(count($highlights) > 0)
                <div class="kdr-card p-4 p-lg-5 mb-4">
                    <h2 class="h5 mb-4">What&apos;s included</h2>
                    <ul class="kdr-service-highlights list-unstyled mb-0">
                        @foreach($highlights as $point)
                        <li>
                            <span class="kdr-service-highlights__icon"><i class="fas fa-check"></i></span>
                            <span>{{ $point }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @elseif($service->description && !$intro)
                <div class="kdr-card p-4 p-lg-5 mb-4">
                    <h2 class="h5 mb-3">Details</h2>
                    <div class="kdr-service-detail__content">
                        {!! nl2br(e($service->description)) !!}
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="kdr-service-detail-sidebar">
                    <div class="kdr-card p-4 mb-4">
                        <span class="kdr-service-detail-sidebar__badge">{{ $service->serviceTypeLabel() }}</span>
                        <h3 class="h5 mb-2 mt-2">{{ $service->title }}</h3>
                        @if($service->excerpt)
                        <p class="text-muted small mb-4">{{ $service->excerpt }}</p>
                        @endif
                        <a href="{{ $service->primaryCtaUrl() }}" class="th-btn btn-kdr-primary w-100 mb-2">
                            <i class="fas {{ $cta['icon'] }} me-2"></i>{{ $cta['label'] }}
                        </a>
                        <a href="{{ route('contact') }}" class="th-btn btn-kdr-outline-dark w-100 mb-2">Request a quote</a>
                        <a href="{{ route('services.index') }}" class="btn btn-link w-100 text-muted small">← All services</a>
                    </div>

                    @if($related->isNotEmpty())
                    <div class="kdr-card p-4">
                        <h4 class="h6 text-uppercase text-muted mb-3">Related services</h4>
                        <ul class="list-unstyled kdr-related-services mb-0">
                            @foreach($related as $item)
                            <li>
                                <a href="{{ route('services.show', $item->slug) }}" class="kdr-related-services__link">
                                    <span class="kdr-related-services__icon">
                                        <i class="fas {{ $item->icon ?? 'fa-angle-right' }}"></i>
                                    </span>
                                    <span>
                                        <strong class="d-block">{{ $item->title }}</strong>
                                        <small class="text-muted">{{ $item->serviceTypeLabel() }}</small>
                                    </span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
