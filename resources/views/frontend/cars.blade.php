@extends('layouts.frontbase')

@section('content')
@php
    $rentalPeriod = $rentalPeriod ?? request('rental_period', 'day');
    $filters = request()->only(['q', 'listing_type', 'brand', 'driver', 'fuel_type', 'transmission', 'rental_period', 'model', 'orderby']);
@endphp

<section class="kdr-cars-page py-4 py-lg-5">
    <div class="container">
        {{-- Hero --}}
        <div class="kdr-cars-hero mb-4 mb-lg-5">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <span class="kdr-cars-hero__eyebrow"><i class="fas fa-car me-2"></i>Car rental</span>
                    <h1 class="kdr-cars-hero__title">Our rental fleet in Kigali</h1>
                    <p class="kdr-cars-hero__lead mb-0">With professional driver · Daily &amp; monthly rates in USD · Airport &amp; city hire. Other details discussed based on your needs.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <button type="button" class="th-btn btn-kdr-primary" data-bs-toggle="modal" data-bs-target="#carRentalRequestModal">
                        <i class="fas fa-paper-plane me-2"></i>Request a quote
                    </button>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="kdr-cars-filters mb-4">
            <form id="carsFilterForm" method="get" action="{{ route('showCars') }}" class="kdr-cars-filters__form">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label" for="filter_q">Search</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" id="filter_q" name="q" class="form-control" value="{{ request('q') }}" placeholder="Name, brand, keyword…">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label" for="filter_brand">Brand</label>
                        <select id="filter_brand" name="brand" class="form-select">
                            <option value="">All brands</option>
                            @foreach($filterOptions['brands'] ?? [] as $b)
                            <option value="{{ $b }}" @selected(request('brand') === $b)>{{ $b }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label" for="filter_model">Model</label>
                        <select id="filter_model" name="model" class="form-select">
                            <option value="">All models</option>
                            @foreach($filterOptions['models'] ?? [] as $m)
                            <option value="{{ $m }}" @selected(request('model') === $m)>{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label" for="filter_fuel_type">Fuel</label>
                        <select id="filter_fuel_type" name="fuel_type" class="form-select">
                            <option value="">Any</option>
                            <option value="included" @selected(request('fuel_type') === 'included')>Fuel included</option>
                            <option value="not_included" @selected(request('fuel_type') === 'not_included')>Fuel not included</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label" for="filter_transmission">Transmission</label>
                        <select id="filter_transmission" name="transmission" class="form-select">
                            <option value="">Any</option>
                            @foreach($filterOptions['transmissions'] ?? [] as $t)
                            <option value="{{ $t }}" @selected(request('transmission') === $t)>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label" for="filter_rental_period">Rate period</label>
                        <select id="filter_rental_period" name="rental_period" class="form-select">
                            <option value="day" @selected($rentalPeriod === 'day')>Per day</option>
                            <option value="month" @selected($rentalPeriod === 'month')>Per month</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label" for="filter_orderby">Sort by</label>
                        <select id="filter_orderby" name="orderby" class="form-select">
                            <option value="date" @selected(request('orderby', 'date') === 'date')>Newest first</option>
                            <option value="price" @selected(request('orderby') === 'price')>Price: low to high</option>
                            <option value="price-desc" @selected(request('orderby') === 'price-desc')>Price: high to low</option>
                            <option value="name" @selected(request('orderby') === 'name')>Name A–Z</option>
                        </select>
                    </div>
                    <div class="col-lg-auto col-md-6 d-flex gap-2 flex-wrap">
                        <button type="submit" class="th-btn btn-kdr-primary">
                            <i class="fas fa-filter me-1"></i> Apply
                        </button>
                        <a href="{{ route('showCars') }}" class="th-btn btn-kdr-outline-dark">Clear</a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Results --}}
        <div id="cars-results">
            @include('frontend.partials.cars_results', ['cars' => $cars, 'rentalPeriod' => $rentalPeriod])
        </div>
    </div>
</section>

@push('modals')
<div class="modal fade" id="carRentalRequestModal" tabindex="-1" aria-labelledby="carRentalRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered kdr-modal-landscape">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="carRentalRequestModalLabel">Request a Car Rental</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('enquiries.store') }}" method="POST" class="kdr-channel-form d-flex flex-column flex-grow-1 overflow-hidden">
                @csrf
                <input type="hidden" name="form_type" value="car_enquiry">
                <input type="hidden" name="subject" value="Car rental enquiry">
                <div class="hp-field" aria-hidden="true" style="position:absolute;left:-9999px;height:0;overflow:hidden;">
                    <input type="text" name="website_url" tabindex="-1" autocomplete="off">
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="names" class="form-control" value="{{ auth()->check() ? auth()->user()->name : old('names') }}" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ auth()->check() ? auth()->user()->email : old('email') }}" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+250 7XX XXX XXX" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Type of Car Needed <span class="text-danger">*</span></label>
                            <select name="car_type" class="form-select" required>
                                <option value="">Select type</option>
                                @foreach($filterOptions['models'] ?? [] as $m)
                                <option value="{{ $m }}" @selected(old('car_type') === $m)>{{ $m }}</option>
                                @endforeach
                                <option value="Not sure yet" @selected(old('car_type') === 'Not sure yet')>Not sure yet</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Number of People</label>
                            <input type="number" name="people" class="form-control" value="{{ old('people') }}" min="1" placeholder="Optional">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Preferred Date</label>
                            <input type="date" name="rental_date" class="form-control" value="{{ old('rental_date') }}" min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Additional Details <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control" rows="3" placeholder="Route, pickup location, timings…" required>{{ old('message') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer kdr-modal-footer--stacked">
                    @include('frontend.partials.kdr-submission-channels', ['channelContext' => 'booking'])
                    <div class="kdr-modal-footer__actions">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-kdr-primary th-btn">Submit Request</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
(function () {
    const form = document.getElementById('carsFilterForm');
    const results = document.getElementById('cars-results');
    if (!form || !results) return;

    let debounceTimer;

    function buildUrl() {
        return new URL(form.action, window.location.origin);
    }

    function getFormParams() {
        const params = new URLSearchParams(new FormData(form));
        return params;
    }

    async function fetchResults(pushState = true) {
        const url = buildUrl();
        const params = getFormParams();
        params.forEach((v, k) => {
            if (v) url.searchParams.set(k, v);
            else url.searchParams.delete(k);
        });
        url.searchParams.delete('page');

        results.style.opacity = '0.55';
        try {
            const res = await fetch(url.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const json = await res.json();
            if (json.html) {
                results.innerHTML = json.html;
                bindPagination();
            }
            if (pushState) {
                window.history.pushState({}, '', url.toString());
            }
        } catch (e) {
            console.error(e);
        } finally {
            results.style.opacity = '1';
        }
    }

    function bindPagination() {
        results.querySelectorAll('.kdr-pagination a').forEach(function (a) {
            a.addEventListener('click', function (e) {
                e.preventDefault();
                const linkUrl = new URL(this.href);
                const page = linkUrl.searchParams.get('page');
                const url = buildUrl();
                getFormParams().forEach((v, k) => {
                    if (v) url.searchParams.set(k, v);
                });
                if (page) url.searchParams.set('page', page);
                results.style.opacity = '0.55';
                fetch(linkUrl.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                })
                .then(r => r.json())
                .then(json => {
                    if (json.html) results.innerHTML = json.html;
                    bindPagination();
                    window.history.pushState({}, '', linkUrl.toString());
                })
                .finally(() => { results.style.opacity = '1'; });
            });
        });
    }

    form.querySelectorAll('select').forEach(function (el) {
        el.addEventListener('change', function () {
            fetchResults();
        });
    });

    const searchInput = form.querySelector('[name="q"]');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => fetchResults(), 400);
        });
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        fetchResults();
    });

    window.addEventListener('popstate', function () {
        window.location.reload();
    });

    bindPagination();
})();
</script>
@endpush
@endsection
