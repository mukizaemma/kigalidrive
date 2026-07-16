@php
    $imgUrl = ($car->image && file_exists(storage_path('app/public/images/cars/' . $car->image)))
        ? asset('storage/images/cars/' . $car->image)
        : asset('assets/img/tour/tour_3_1.jpg');
    $hasDay = ($car->price_per_day ?? 0) > 0;
    $hasMonth = ($car->price_per_month ?? 0) > 0;
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
        <ul class="kdr-car-card__meta list-unstyled mb-3">
            @if($car->model)
            <li><i class="fas fa-car-side"></i> {{ $car->model }}</li>
            @endif
            @if($car->fuel_type)
            <li><i class="fas fa-gas-pump"></i> {{ Str::limit($car->fuel_type, 28) }}</li>
            @endif
            @if($car->transmission)
            <li><i class="fas fa-cogs"></i> {{ $car->transmission }}</li>
            @endif
            <li>
                <i class="fas fa-user-tie"></i>
                With driver
            </li>
        </ul>
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
