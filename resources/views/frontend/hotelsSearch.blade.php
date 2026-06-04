@extends('layouts.frontbase')

@section('content')
<section class="kdr-apartments-page py-4 py-lg-5">
    <div class="container">
        <div class="kdr-apartments-hero mb-4">
            <h1 class="kdr-apartments-hero__title">Apartments &amp; Villas</h1>
            <p class="kdr-apartments-hero__lead mb-0">
                @if(request('district'))
                    Listings in <strong>{{ request('district') }}</strong>
                @elseif(request('q'))
                    Results for <strong>"{{ request('q') }}"</strong>
                @elseif(request('listing_type') === 'rent')
                    Properties for rent
                @elseif(in_array(request('listing_type'), ['sale', 'sell'], true))
                    Properties for sale
                @else
                    Browse verified apartments and villas across Rwanda
                @endif
            </p>
        </div>

        <div class="kdr-cars-filters kdr-apartments-filters mb-4">
            <form id="apartmentsFilterForm" method="GET" action="{{ route('showCars') }}" class="kdr-cars-filters__form">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label" for="filter_q">Search</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" id="filter_q" name="q" class="form-control" value="{{ request('q') }}" placeholder="Name, address, district, description…" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label" for="filter_listing_type">Listing</label>
                        <select id="filter_listing_type" name="listing_type" class="form-select">
                            <option value="">Rent &amp; sale</option>
                            <option value="rent" @selected(request('listing_type') === 'rent')>For rent</option>
                            <option value="sale" @selected(in_array(request('listing_type'), ['sale', 'sell'], true))>For sale</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label" for="filter_district">District</label>
                        <select id="filter_district" name="district" class="form-select">
                            <option value="">All districts</option>
                            @foreach($districtGroups ?? [] as $province => $districts)
                                <optgroup label="{{ $province }}">
                                    @foreach($districts as $district)
                                        <option value="{{ $district }}" @selected(request('district', request('location')) === $district)>{{ $district }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label" for="filter_orderby">Sort</label>
                        <select id="filter_orderby" name="orderby" class="form-select">
                            <option value="" @selected(!request('orderby'))>Newest</option>
                            <option value="price" @selected(request('orderby') === 'price')>Lowest price</option>
                            <option value="price-desc" @selected(request('orderby') === 'price-desc')>Highest price</option>
                            <option value="rating" @selected(request('orderby') === 'rating')>Top rated</option>
                        </select>
                    </div>
                    <div class="col-lg-1 col-md-6 d-grid">
                        <a href="{{ route('showCars') }}" class="btn btn-outline-secondary" title="Clear filters">Clear</a>
                    </div>
                </div>
            </form>
        </div>

        <div id="accommodations-results">
            @include('frontend.partials.accommodations_results')
        </div>
    </div>
</section>

<script>
(function () {
    const resultsContainer = document.getElementById('accommodations-results');
    const filtersForm = document.getElementById('apartmentsFilterForm');
    const baseUrl = @json(route('showCars'));

    function getCurrentParams() {
        const params = {};
        if (!filtersForm) return params;
        new FormData(filtersForm).forEach((val, key) => {
            if (val !== null && val !== '') params[key] = val;
        });
        const url = new URL(window.location.href);
        const page = url.searchParams.get('page');
        if (page) params.page = page;
        return params;
    }

    function buildQueryString(params) {
        const usp = new URLSearchParams();
        Object.keys(params).forEach(k => {
            const v = params[k];
            if (v === null || v === undefined || v === '') return;
            usp.set(k, v);
        });
        return usp.toString();
    }

    async function fetchResults(params = {}) {
        if (!resultsContainer) return;
        const merged = { ...getCurrentParams(), ...params };
        if (!Object.prototype.hasOwnProperty.call(params, 'page')) {
            delete merged.page;
        }
        const qs = buildQueryString(merged);
        const url = qs ? baseUrl + '?' + qs : baseUrl;
        resultsContainer.style.opacity = '0.5';
        try {
            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const json = await res.json();
            if (json && json.html !== undefined) {
                resultsContainer.innerHTML = json.html;
                bindPaginationLinks();
            }
            window.history.pushState({}, '', url);
        } catch (err) {
            console.error('Apartments search error', err);
        } finally {
            resultsContainer.style.opacity = '1';
        }
    }

    function bindPaginationLinks() {
        if (!resultsContainer) return;
        resultsContainer.querySelectorAll('.th-pagination a, .pagination a').forEach(a => {
            const href = a.getAttribute('href');
            if (!href) return;
            const linkUrl = new URL(href, window.location.origin);
            const page = linkUrl.searchParams.get('page');
            if (!page) return;
            a.addEventListener('click', function (e) {
                e.preventDefault();
                fetchResults({ page });
            });
        });
    }

    let debounceTimer;
    if (filtersForm) {
        filtersForm.querySelectorAll('select').forEach(el => {
            el.addEventListener('change', () => fetchResults());
        });
        const searchInput = filtersForm.querySelector('#filter_q');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => fetchResults(), 400);
            });
        }
        filtersForm.addEventListener('submit', function (e) {
            e.preventDefault();
            fetchResults();
        });
    }

    window.addEventListener('popstate', () => fetchResults(getCurrentParams()));
    bindPaginationLinks();
})();
</script>
@endsection
