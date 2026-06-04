@extends('layouts.frontbase')

@section('title', 'Terms & Conditions | ' . (optional($setting)->company ?? 'Kigali Drive Rentals'))

@section('content')
<section class="kdr-car-detail py-4 py-lg-5">
    <div class="container">
        <div class="kdr-cars-hero mb-4 mb-lg-5">
            <h1 class="kdr-cars-hero__title mb-1">Terms &amp; Conditions</h1>
            <p class="kdr-cars-hero__lead mb-0">Rental policies for Kigali Drive Rentals.</p>
        </div>

        <div class="row g-4 g-lg-5 align-items-start">
            <div class="col-lg-8">
                <div class="kdr-car-detail__block">
                    @if(filled(optional($terms)->terms))
                    <div class="kdr-car-detail__prose kdr-rich-text">
                        {!! $terms->terms !!}
                    </div>
                    @else
                    <p class="text-muted mb-0">Terms and conditions are being updated. Please <a href="{{ route('contact') }}">contact us</a> if you have questions.</p>
                    @endif
                </div>
            </div>

            <div class="col-lg-4">
                <div class="kdr-car-detail__panel">
                    <h2 class="h5 mb-3" style="font-family: var(--kdr-display); font-weight: 800; color: var(--kdr-navy);">Latest from our fleet</h2>
                    <ul class="list-unstyled mb-3">
                        @forelse($featuredCars as $car)
                        <li class="mb-3">
                            @php
                                $thumb = ($car->image && file_exists(storage_path('app/public/images/cars/' . $car->image)))
                                    ? asset('storage/images/cars/' . $car->image)
                                    : asset('assets/img/tour/tour_3_1.jpg');
                            @endphp
                            <a href="{{ route('carDetails', $car->slug ?? $car->id) }}" class="kdr-terms-fleet-card text-decoration-none">
                                <span class="kdr-terms-fleet-card__img">
                                    <img src="{{ $thumb }}" alt="{{ $car->name }}" loading="lazy">
                                </span>
                                <span class="kdr-terms-fleet-card__name">{{ $car->name }}</span>
                            </a>
                        </li>
                        @empty
                        <li class="text-muted small">No vehicles listed yet.</li>
                        @endforelse
                    </ul>
                    <a href="{{ route('showCars') }}" class="th-btn btn-kdr-primary btn-sm w-100 text-center">View all fleet</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
