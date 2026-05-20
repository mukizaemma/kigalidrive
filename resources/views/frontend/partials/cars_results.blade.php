<div class="kdr-cars-results">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <p class="mb-0 text-muted">
            <strong>{{ $cars->total() }}</strong> {{ Str::plural('vehicle', $cars->total()) }} found
        </p>
    </div>

    @if($cars->count() > 0)
        <div class="row g-4">
            @foreach($cars as $car)
            <div class="col-md-6 col-xl-4">
                @include('frontend.partials.car_card', ['car' => $car, 'rentalPeriod' => $rentalPeriod ?? request('rental_period', 'day')])
            </div>
            @endforeach
        </div>

        @if($cars->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $cars->links('vendor.pagination.kdr') }}
        </div>
        @endif
    @else
        <div class="kdr-empty-state text-center py-5">
            <i class="fas fa-car-side fa-3x mb-3 text-muted"></i>
            <h4 class="h5">No vehicles match your filters</h4>
            <p class="text-muted mb-3">Try adjusting your search or <a href="{{ route('showCars') }}">clear all filters</a>.</p>
        </div>
    @endif
</div>
