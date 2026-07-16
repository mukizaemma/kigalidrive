@extends('layouts.frontbase')

@section('content')
@php
    $galleryImages = collect();
    if ($car->image) {
        $galleryImages->push(asset('storage/images/cars/' . $car->image));
    }
    if ($images && $images->count() > 0) {
        foreach ($images as $carImage) {
            if ($carImage->image && ! $galleryImages->contains(asset('storage/images/cars/' . $carImage->image))) {
                $galleryImages->push(asset('storage/images/cars/' . $carImage->image));
            }
        }
    }
    $galleryUrls = $galleryImages->values()->all();
    $rentalPackages = $rentalPackages ?? app(\App\Services\CarRentalPackageService::class)->packagesFor($car);

    $metaLine = $car->relationLoaded('details')
        ? $car->details->pluck('name')->filter()->implode(' · ')
        : $car->details()->pluck('name')->filter()->implode(' · ');

    $dayPrice = ($car->price_per_day ?? 0) > 0 ? formatUsd($car->price_per_day) : null;
    $monthPrice = ($car->price_per_month ?? 0) > 0 ? formatUsd($car->price_per_month) : null;
    $assignedDetails = $car->relationLoaded('details') ? $car->details : $car->details()->get();
@endphp

<section class="kdr-car-detail py-4 py-lg-5">
    <div class="container">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please fix the following:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        {{-- Gallery + summary (same row) --}}
        <div class="row g-4 g-xl-5 align-items-start kdr-car-detail__hero">
            <div class="col-lg-7">
                @include('frontend.partials.kdr-car-detail-gallery', [
                    'galleryImages' => $galleryUrls,
                    'carName' => $car->name,
                ])
            </div>

            <div class="col-lg-5">
                <div class="kdr-car-detail__panel">
                    @if($car->brand)
                    <span class="kdr-car-detail__brand">{{ $car->brand }}</span>
                    @endif
                    <h1 class="kdr-car-detail__title">{{ $car->name }}</h1>
                    @if($metaLine)
                    <p class="kdr-car-detail__meta">{{ $metaLine }}</p>
                    @endif

                    @if($dayPrice || $monthPrice)
                    <div class="kdr-car-detail__price">
                        @if($dayPrice)
                        <div>
                            <span class="kdr-car-detail__price-value">{{ $dayPrice }}</span>
                            <span class="kdr-car-detail__price-unit">/ day</span>
                        </div>
                        @endif
                        @if($monthPrice)
                        <div class="{{ $dayPrice ? 'mt-1' : '' }}">
                            <span class="kdr-car-detail__price-value">{{ $monthPrice }}</span>
                            <span class="kdr-car-detail__price-unit">/ month</span>
                        </div>
                        @endif
                    </div>
                    @endif

                    @if(count($rentalPackages) > 0)
                    <ul class="kdr-car-detail__rates list-unstyled">
                        @foreach($rentalPackages as $package)
                        <li>
                            <span>{{ $package['label'] }}</span>
                            <strong>{{ $package['price_formatted'] }}</strong>
                        </li>
                        @endforeach
                    </ul>
                    @endif

                    <p class="kdr-car-detail__note text-muted small mb-3">
                        Other details will be discussed based on the client's needs.
                    </p>

                    <div class="kdr-car-detail__status">
                        <span class="kdr-car-detail__status-badge kdr-car-detail__status-badge--{{ $car->status }}">
                            {{ ucfirst($car->status) }}
                        </span>
                        @foreach($assignedDetails->take(2) as $detail)
                        <span class="kdr-car-detail__hire-type">{{ $detail->name }}</span>
                        @endforeach
                    </div>

                    <button type="button" class="th-btn btn-kdr-primary w-100 kdr-car-detail__book" data-bs-toggle="modal" data-bs-target="#carBookingModal">
                        <i class="fas fa-calendar-check me-2" aria-hidden="true"></i>Book now
                    </button>
                </div>
            </div>
        </div>

        {{-- Description & specifications --}}
        <div class="row g-4 mt-2">
            <div class="col-lg-8">
                @if(filled($car->description))
                <div class="kdr-car-detail__block">
                    <h2 class="kdr-car-detail__heading">About this vehicle</h2>
                    <div class="kdr-car-detail__prose kdr-rich-text">
                        {!! strip_tags($car->description, '<p><br><ul><ol><li><strong><em><b><i><a><h2><h3><h4><span>') !!}
                    </div>
                </div>
                @endif

                <div class="kdr-car-detail__block">
                    <h2 class="kdr-car-detail__heading">Details</h2>
                    @if($assignedDetails->isNotEmpty())
                    <div class="row g-3 kdr-car-detail__specs">
                        @foreach($assignedDetails as $detail)
                        <div class="col-sm-6 col-md-4">
                            <div class="kdr-car-detail__spec">
                                <i class="{{ $detail->iconClass() }}" aria-hidden="true"></i>
                                <div>
                                    <span class="kdr-car-detail__spec-value">{{ $detail->name }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted mb-0">Other details will be discussed based on the client's needs.</p>
                    @endif
                </div>
            </div>
        </div>

        @if($allCars->isNotEmpty())
        <div class="kdr-car-detail__related mt-5 pt-4">
            <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
                <h2 class="kdr-section-title mb-0">You may also like</h2>
                <a href="{{ route('showCars') }}" class="th-btn btn-kdr-outline-dark btn-sm">View all fleet</a>
            </div>
            <div class="row g-4">
                @foreach($allCars->take(3) as $r)
                <div class="col-md-6 col-lg-4">
                    @include('frontend.partials.car_card', ['car' => $r, 'rentalPeriod' => 'day'])
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

@include('frontend.partials.car-booking-form', ['rentalPackages' => $rentalPackages])
@endsection

@push('scripts')
<script src="{{ asset('assets/js/kdr-car-booking.js') }}" defer></script>
<script src="{{ asset('assets/js/kdr-car-gallery.js') }}" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    @if($errors->any() || (session('error') && old('car_id') == $car->id))
    const bookingModal = new bootstrap.Modal(document.getElementById('carBookingModal'));
    bookingModal.show();
    @endif
});
</script>
@endpush
