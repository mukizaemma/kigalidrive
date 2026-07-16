@php
    $imgUrl = ($car->image && file_exists(storage_path('app/public/images/cars/' . $car->image)))
        ? asset('storage/images/cars/' . $car->image)
        : asset('assets/img/tour/tour_3_1.jpg');
    $hasDay = ($car->price_per_day ?? 0) > 0;
    $hasMonth = ($car->price_per_month ?? 0) > 0;
    $assignedDetails = $car->relationLoaded('details')
        ? $car->details
        : $car->details()->get();
@endphp
<div class="kdr-car-card h-100">
    <a href="{{ route('carDetails', $car->slug ?? $car->id) }}" class="kdr-car-card__media">
        <img src="{{ $imgUrl }}" alt="{{ $car->name }}" loading="lazy">
        @if($car->brand)
        <span class="kdr-car-card__badge">{{ $car->brand }}</span>
        @endif
    </a>
    <div class="kdr-car-card__body">
        <h3 class="kdr-car-card__title">
            <a href="{{ route('carDetails', $car->slug ?? $car->id) }}">{{ $car->name }}</a>
        </h3>
        @if($assignedDetails->isNotEmpty())
        <ul class="kdr-car-card__meta list-unstyled mb-3">
            @foreach($assignedDetails as $detail)
            <li><i class="{{ $detail->iconClass() }}"></i> {{ $detail->name }}</li>
            @endforeach
        </ul>
        @endif
        <div class="kdr-car-card__footer">
            @if($hasDay || $hasMonth)
            <div class="kdr-car-card__price mb-0">
                @if($hasDay)
                <p class="mb-0">{{ formatUsd($car->price_per_day) }} <span>/ day</span></p>
                @endif
                @if($hasMonth)
                <p class="mb-0 {{ $hasDay ? 'mt-1' : '' }}">{{ formatUsd($car->price_per_month) }} <span>/ month</span></p>
                @endif
            </div>
            @endif
            <a href="{{ route('carDetails', $car->slug ?? $car->id) }}" class="th-btn btn-kdr-primary btn-sm">View &amp; Book</a>
        </div>
    </div>
</div>
