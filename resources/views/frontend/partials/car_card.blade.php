@php
    $period = $rentalPeriod ?? request('rental_period', 'day');
    $price = match ($period) {
        'week' => $car->price_per_week,
        'month' => $car->price_per_month,
        default => $car->price_per_day,
    };
    $periodLabel = match ($period) {
        'week' => 'week',
        'month' => 'month',
        default => 'day',
    };
    if (!$price || $price <= 0) {
        $price = $car->price_per_day ?: $car->price_per_month;
        $periodLabel = $car->price_per_day ? 'day' : 'month';
    }
    $imgUrl = ($car->image && file_exists(storage_path('app/public/images/cars/' . $car->image)))
        ? asset('storage/images/cars/' . $car->image)
        : asset('assets/img/tour/tour_3_1.jpg');
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
                <i class="fas fa-user"></i>
                @if($car->driver_available && !$car->self_drive)
                    With driver
                @elseif($car->self_drive && !$car->driver_available)
                    Self-drive
                @else
                    Driver or self-drive
                @endif
            </li>
        </ul>
        <div class="kdr-car-card__footer">
            @if($price)
            <p class="kdr-car-card__price mb-0">
                {{ formatUsd($price) }} <span>/ {{ $periodLabel }}</span>
            </p>
            @endif
            <a href="{{ route('carDetails', $car->slug ?? $car->id) }}" class="th-btn btn-kdr-primary btn-sm">View &amp; Book</a>
        </div>
    </div>
</div>
