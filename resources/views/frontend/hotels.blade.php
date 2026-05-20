@extends('layouts.frontbase')

@section('content')

<section class="space">
    <div class="container">
        <!-- Search Card with Auto-Search -->
        <div class="row mb-40">
            <div class="col-12">
                <div class="search-card" style="background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.98) 100%); border-radius: 20px; padding: 30px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); border: 1px solid rgba(0,0,0,0.05);">
                    <form id="hotelsSearchForm" method="get" action="{{ url()->current() }}">
                        <div class="row g-3 align-items-end">
                            <!-- Search Input with Auto-Search -->
                            <div class="col-lg-5 col-md-6">
                                <div class="form-item position-relative">
                                    <label class="form-label mb-2" style="font-weight: 600; color: #333; font-size: 14px;">
                                        <i class="fas fa-search me-2"></i>Search by Property Name or Location
                                    </label>
                                    <div class="position-relative">
                                        <i class="fas fa-search position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%); color: #999; z-index: 10; pointer-events: none;"></i>
                                        <input type="text" 
                                               name="q" 
                                               id="searchInput" 
                                               value="{{ request('q') }}" 
                                               placeholder="Type property name, city, or location..."
                                               class="form-control"
                                               style="padding-left: 45px; height: 50px; border: 2px solid #e0e0e0; border-radius: 12px; font-size: 15px; transition: all 0.3s ease;"
                                               autocomplete="off">
                                        <button type="button" id="clearSearch" aria-label="Clear search"
                                            style="position:absolute; right:15px; top:50%; transform:translateY(-50%); border:none; background:none; font-size:20px; cursor:pointer; color:#999; display: {{ request('q') ? 'inline-block' : 'none' }}; z-index: 10;">
                                            &times;
                                        </button>
                                    </div>
                                    <small class="text-muted mt-1 d-block" style="font-size: 12px;">
                                        <i class="fas fa-info-circle me-1"></i>Search automatically updates as you type
                                    </small>
                                </div>
                            </div>

                            <!-- Location Filter (Optional) -->
                            <div class="col-lg-3 col-md-6">
                                <div class="form-item">
                                    <label class="form-label mb-2" style="font-weight: 600; color: #333; font-size: 14px;">
                                        <i class="fas fa-map-marker-alt me-2"></i>Filter by Location
                                    </label>
                                    <select name="location" id="locationFilter" class="form-select" style="height: 50px; border: 2px solid #e0e0e0; border-radius: 12px; font-size: 15px;">
                                        <option value="">All Locations</option>
                                        @if(isset($allLocations) && is_array($allLocations))
                                            @foreach($allLocations as $loc)
                                                <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>
                                                    {{ $loc }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <!-- Sort Order -->
                            <div class="col-lg-2 col-md-6">
                                <div class="form-item">
                                    <label class="form-label mb-2" style="font-weight: 600; color: #333; font-size: 14px;">
                                        <i class="fas fa-sort me-2"></i>Sort By
                                    </label>
                                    <select name="orderby" id="orderBy" class="form-select" style="height: 50px; border: 2px solid #e0e0e0; border-radius: 12px; font-size: 15px;">
                                        <option value="menu_order" {{ request('orderby') == 'menu_order' ? 'selected' : '' }}>Default</option>
                                        <option value="popularity" {{ request('orderby') == 'popularity' ? 'selected' : '' }}>Popularity</option>
                                        <option value="rating" {{ request('orderby') == 'rating' ? 'selected' : '' }}>Rating</option>
                                        <option value="date" {{ request('orderby') == 'date' ? 'selected' : '' }}>Latest</option>
                                        <option value="price" {{ request('orderby') == 'price' ? 'selected' : '' }}>Price: Low to High</option>
                                        <option value="price-desc" {{ request('orderby') == 'price-desc' ? 'selected' : '' }}>Price: High to Low</option>
                                    </select>
                                </div>
                            </div>

                            <!-- View Toggle -->
                            <div class="col-lg-2 col-md-6">
                                <div class="form-item">
                                    <label class="form-label mb-2" style="font-weight: 600; color: #333; font-size: 14px;">
                                        <i class="fas fa-th me-2"></i>View
                                    </label>
                                    <div class="btn-group w-100" role="group" style="height: 50px;">
                                        <a href="#" id="tab-destination-grid" class="btn btn-outline-primary active" data-bs-toggle="tab" data-bs-target="#tab-grid" style="border-radius: 12px 0 0 12px; border: 2px solid #e0e0e0;">
                                            <i class="fa-light fa-grid-2"></i>
                                        </a>
                                        <a href="#" id="tab-destination-list" class="btn btn-outline-primary" data-bs-toggle="tab" data-bs-target="#tab-list" style="border-radius: 0 12px 12px 0; border: 2px solid #e0e0e0; border-left: none;">
                                            <i class="fa-solid fa-list"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Property type tabs --}}
        @php
            $currentType = request('type', 'all');
            $baseQuery = request()->except('page', 'type');
        @endphp
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <a href="{{ route('hotels', array_merge($baseQuery, ['type' => 'all'])) }}"
                       class="btn btn-sm {{ $currentType === 'all' ? 'btn-primary' : 'btn-outline-primary' }}"
                       style="border-radius: 999px; min-width: 110px;">
                        All
                    </a>
                    <a href="{{ route('hotels', array_merge($baseQuery, ['type' => 'hotel'])) }}"
                       class="btn btn-sm {{ $currentType === 'hotel' ? 'btn-primary' : 'btn-outline-primary' }}"
                       style="border-radius: 999px; min-width: 110px;">
                        Hotels
                    </a>
                    <a href="{{ route('hotels', array_merge($baseQuery, ['type' => 'apartment'])) }}"
                       class="btn btn-sm {{ $currentType === 'apartment' ? 'btn-primary' : 'btn-outline-primary' }}"
                       style="border-radius: 999px; min-width: 110px;">
                        Apartments
                    </a>
                    <a href="{{ route('hotels', array_merge($baseQuery, ['type' => 'guesthouse'])) }}"
                       class="btn btn-sm {{ $currentType === 'guesthouse' ? 'btn-primary' : 'btn-outline-primary' }}"
                       style="border-radius: 999px; min-width: 130px;">
                        Guest Houses
                    </a>
                    <a href="{{ route('hotels', array_merge($baseQuery, ['type' => 'lodge'])) }}"
                       class="btn btn-sm {{ $currentType === 'lodge' ? 'btn-primary' : 'btn-outline-primary' }}"
                       style="border-radius: 999px; min-width: 110px;">
                        Lodges
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">

                {{-- RESULTS: this container is replaced by AJAX responses --}}
                <div id="accommodations-results">
                    <div class="tab-content" id="nav-tabContent">

                        {{-- GRID VIEW --}}
                        <div class="tab-pane fade active show" id="tab-grid" role="tabpanel" aria-labelledby="tab-destination-grid">
                            <div class="row gy-30">
                                @forelse($rooms as $hotel)
                                    <div class="col-lg-4 col-md-6">
                                        <div class="tour-box th-ani" style="height: 100%;">
                                            <div class="tour-box_img global-img" style="position: relative;">
                                                @php
                                                    // Property model uses featured_image, stored in properties folder
                                                    $imagePath = $hotel->featured_image ? asset('storage/images/properties/' . $hotel->featured_image) : asset('assets/img/tour/tour_3_1.jpg');
                                                @endphp
                                                <img src="{{ $imagePath }}" alt="{{ $hotel->name }}">
                                                @php
                                                    // Get min price and currency from units relationship (Property model uses units, not rooms)
                                                    $minPrice = $hotel->min_price ?? null;
                                                    $currency = 'USD'; // Default currency
                                                    
                                                    if (isset($hotel->property_type)) {
                                                        // Property model - get from units
                                                        if (!$minPrice && $hotel->units && $hotel->units->isNotEmpty()) {
                                                            $cheapestUnit = $hotel->units->where('base_price_per_night', '>', 0)->sortBy('base_price_per_night')->first();
                                                            if ($cheapestUnit) {
                                                                $minPrice = $cheapestUnit->base_price_per_night;
                                                                $currency = $cheapestUnit->currency ?? 'USD';
                                                            }
                                                        } elseif ($hotel->units && $hotel->units->isNotEmpty()) {
                                                            $cheapestUnit = $hotel->units->where('base_price_per_night', '>', 0)->sortBy('base_price_per_night')->first();
                                                            if ($cheapestUnit) {
                                                                $currency = $cheapestUnit->currency ?? 'USD';
                                                            }
                                                        }
                                                    } else {
                                                        // Hotel model - get from rooms
                                                        if (!$minPrice && $hotel->rooms && $hotel->rooms->isNotEmpty()) {
                                                            $cheapestRoom = $hotel->rooms->where('price_per_night', '>', 0)->sortBy('price_per_night')->first();
                                                            if ($cheapestRoom) {
                                                                $minPrice = $cheapestRoom->price_per_night;
                                                                $currency = $cheapestRoom->currency ?? 'USD';
                                                            }
                                                        } elseif ($hotel->rooms && $hotel->rooms->isNotEmpty()) {
                                                            $cheapestRoom = $hotel->rooms->where('price_per_night', '>', 0)->sortBy('price_per_night')->first();
                                                            if ($cheapestRoom) {
                                                                $currency = $cheapestRoom->currency ?? 'USD';
                                                            }
                                                        }
                                                    }
                                                    $currencySymbol = getCurrencySymbol($currency);
                                                @endphp
                                                @if($minPrice)
                                                    <div style="position: absolute; top: 15px; right: 15px; background: rgba(37, 211, 102, 0.95); color: white; padding: 8px 15px; border-radius: 8px; font-weight: 600; font-size: 16px;">
                                                        {{ $currencySymbol }}{{ number_format($minPrice, 0) }}/night
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="tour-content">
                                                <h3 class="box-title">
                                                    <a href="{{ route('hotelRooms', $hotel->slug ?? $hotel->id) }}">{{ $hotel->name }}</a>
                                                </h3>

                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div class="tour-rating">
                                                    @php
                                                        // Parse stars - handle different formats like "5", "5 Star", "5-Star", etc.
                                                        $starsValue = $hotel->stars ?? 0;
                                                        if (is_string($starsValue)) {
                                                            // Extract number from string (e.g., "5 Star" -> 5)
                                                            preg_match('/\d+/', $starsValue, $matches);
                                                            $stars = !empty($matches) ? (int)$matches[0] : 0;
                                                        } else {
                                                            $stars = (int)$starsValue;
                                                        }
                                                        $stars = max(0, min(5, $stars)); // Ensure between 0 and 5
                                                        $avgRating = $hotel->average_rating ?? 0;
                                                        $totalReviews = $hotel->total_reviews ?? 0;
                                                    @endphp

                                                    <div class="star-rating" role="img" aria-label="Rated {{ $stars }} out of 5">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $stars)
                                                                <i class="fa-solid fa-star text-warning" aria-hidden="true"></i>
                                                            @else
                                                                <i class="fa-regular fa-star text-warning" aria-hidden="true"></i>
                                                            @endif
                                                        @endfor
                                                            @if($totalReviews > 0)
                                                                <span class="ms-2" style="font-size: 14px; color: #666;">
                                                                    {{ number_format($avgRating, 1) }} ({{ $totalReviews }} {{ $totalReviews == 1 ? 'review' : 'reviews' }})
                                                                </span>
                                                            @else
                                                                <span class="ms-2" style="font-size: 14px; color: #666;">No reviews yet</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <p class="mb-3" style="margin:6px 0; color: #666;">
                                                    <i class="fa-solid fa-location-dot text-primary me-2" aria-hidden="true"></i>
                                                    <strong>{{ $hotel->location ?? $hotel->city ?? 'Location not specified' }}</strong>
                                                </p>

                                                <div class="tour-action">
                                                    <a href="{{ route('hotel', $hotel->slug ?? $hotel->id) }}" class="th-btn style4 th-icon">View Rooms</a>
                                                    <a href="{{ route('hotel', $hotel->slug ?? $hotel->id) }}" class="th-btn style3">Book Now</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <p class="text-center">No hotels found.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- LIST VIEW --}}
                        <div class="tab-pane fade" id="tab-list" role="tabpanel" aria-labelledby="tab-destination-list">
                            <div class="row gy-30">
                                @forelse($rooms as $hotel)
                                    <div class="col-12">
                                        <div class="tour-box style-flex th-ani">
                                            <div class="tour-box_img global-img">
                                                @php
                                                    // Property model uses featured_image, stored in properties folder
                                                    $imagePath = $hotel->featured_image ? asset('storage/images/properties/' . $hotel->featured_image) : asset('assets/img/tour/tour_3_1.jpg');
                                                @endphp
                                                <img src="{{ $imagePath }}" alt="{{ $hotel->name }}">
                                            </div>

                                            <div class="tour-content">
                                                <h3 class="box-title">
                                                    <a href="{{ route('accommodations', $hotel->slug ?? $hotel->id) }}">{{ $hotel->name }}</a>
                                                </h3>

                                                <div class="tour-rating">
                                                    @php
                                                        $stars = (int) filter_var($hotel->stars, FILTER_SANITIZE_NUMBER_INT);
                                                        $stars = max(0, min(5, $stars));
                                                    @endphp
                                                    <div class="star-rating" role="img" aria-label="Rated {{ $stars }} out of 5">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $stars)
                                                                <i class="fa-solid fa-star" aria-hidden="true"></i>
                                                            @else
                                                                <i class="fa-regular fa-star" aria-hidden="true"></i>
                                                            @endif
                                                        @endfor
                                                        <a href="{{ route('accommodations', $hotel->slug ?? $hotel->id) }}" class="woocommerce-review-link">({{ $stars }} Rating)</a>
                                                    </div>
                                                </div>

                                                <p class="mb-2" style="margin:6px 0;">
                                                    <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                                                    {{ $hotel->location ?? $hotel->city ?? 'Location not specified' }}
                                                </p>

                                                <div class="tour-action">
                                                    <a href="{{ route('accommodations', $hotel->slug ?? $hotel->id) }}" class="th-btn style4">View Rooms</a>
                                                    <a href="{{ route('accommodations', $hotel->slug ?? $hotel->id) }}" class="th-btn style3">Book Now</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <p class="text-center">No hotels found.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- pagination --}}
                        <div class="th-pagination text-center mt-60 mb-0">
                            @if(method_exists($rooms, 'links'))
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-center">
                                        {!! $rooms->appends(request()->query())->links() !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- end #accommodations-results --}}

            </div>
        </div>
    </div>
</section>

<script>
(function () {
    // Elements - Updated to use new form IDs
    const resultsContainer = document.getElementById('accommodations-results');
    const searchInput = document.getElementById('searchInput');
    const locationFilter = document.getElementById('locationFilter');
    const orderSelect = document.getElementById('orderBy');
    const clearBtn = document.getElementById('clearSearch');
    const searchForm = document.getElementById('hotelsSearchForm');

    // Debounce utility
    function debounce(fn, wait = 400) {
        let t;
        return function(...args) {
            clearTimeout(t);
            t = setTimeout(() => fn.apply(this, args), wait);
        };
    }

    // AJAX fetch function
    async function fetchResults(params = {}) {
        if (!resultsContainer) return;

        // Build URL with current querystring + params
        const url = new URL(window.location.href);
        Object.keys(params).forEach(k => {
            if (params[k] === null || params[k] === undefined || params[k] === '') {
                url.searchParams.delete(k);
            } else {
                url.searchParams.set(k, params[k]);
            }
        });

        // Loading indication
        resultsContainer.style.opacity = '0.5';
        resultsContainer.style.pointerEvents = 'none';

        try {
            const res = await fetch(url.toString(), {
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const json = await res.json();

            if (json && json.html !== undefined) {
                resultsContainer.innerHTML = json.html;
                bindPaginationLinks();
            } else if (typeof json === 'string') {
                resultsContainer.innerHTML = json;
                bindPaginationLinks();
            } else {
                console.warn('Unexpected AJAX response', json);
            }

            // Update URL without reload
            window.history.pushState({}, '', url.toString());
        } catch (err) {
            console.error('Search fetch error', err);
        } finally {
            resultsContainer.style.opacity = '1';
            resultsContainer.style.pointerEvents = 'auto';
        }
    }

    // Expose globally
    window.fetchAccommodationsResults = fetchResults;

    // Bind pagination links
    function bindPaginationLinks() {
        if (!resultsContainer) return;
        const pagerLinks = resultsContainer.querySelectorAll('.th-pagination a, .pagination a');
        pagerLinks.forEach(a => {
            const newA = a.cloneNode(true);
            a.parentNode.replaceChild(newA, a);

            newA.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (!href) return;
                const linkUrl = new URL(href, window.location.origin);
                const page = linkUrl.searchParams.get('page');
                if (page) {
                    e.preventDefault();
                    const q = searchInput ? searchInput.value : '';
                    const location = locationFilter ? locationFilter.value : '';
                    const orderby = orderSelect ? orderSelect.value : '';
                    fetchResults({ q, location, orderby, page });
                }
            });
        });
    }

    // Auto-search on input (debounced) - searches by property name OR location
    if (searchInput) {
        const onInput = debounce(function() {
            const q = this.value.trim();
            const location = locationFilter ? locationFilter.value : '';
            const orderby = orderSelect ? orderSelect.value : '';
            fetchResults({ q, location, orderby, page: 1 });
        }, 400);
        
        searchInput.addEventListener('input', function() {
            toggleClearBtn();
            onInput.call(this);
        });

        // Add focus effect
        searchInput.addEventListener('focus', function() {
            this.style.borderColor = '#25D366';
            this.style.boxShadow = '0 0 0 0.2rem rgba(37, 211, 102, 0.1)';
        });

        searchInput.addEventListener('blur', function() {
            this.style.borderColor = '#e0e0e0';
            this.style.boxShadow = 'none';
        });
    }

    // Location filter change
    if (locationFilter) {
        locationFilter.addEventListener('change', function() {
            const q = searchInput ? searchInput.value : '';
            const location = this.value;
            const orderby = orderSelect ? orderSelect.value : '';
            fetchResults({ q, location, orderby, page: 1 });
        });
    }

    // Order select change
    if (orderSelect) {
        orderSelect.addEventListener('change', function() {
            const q = searchInput ? searchInput.value : '';
            const location = locationFilter ? locationFilter.value : '';
            const orderby = this.value;
            fetchResults({ q, location, orderby, page: 1 });
        });
    }

    // Clear button
    if (clearBtn) {
        clearBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (searchInput) {
                searchInput.value = '';
                searchInput.focus();
                toggleClearBtn();
                const location = locationFilter ? locationFilter.value : '';
                const orderby = orderSelect ? orderSelect.value : '';
                fetchResults({ q: '', location, orderby, page: 1 });
            }
        });
    }

    // Toggle clear button visibility
    function toggleClearBtn() {
        if (!clearBtn || !searchInput) return;
        if (searchInput.value && searchInput.value.trim().length > 0) {
            clearBtn.style.display = 'inline-block';
        } else {
            clearBtn.style.display = 'none';
        }
    }

    // Prevent form submission (we use AJAX)
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const q = searchInput ? searchInput.value : '';
            const location = locationFilter ? locationFilter.value : '';
            const orderby = orderSelect ? orderSelect.value : '';
            fetchResults({ q, location, orderby, page: 1 });
        });
    }

    // Handle browser back/forward
    window.addEventListener('popstate', function() {
        const url = new URL(window.location.href);
        const q = url.searchParams.get('q') || '';
        const location = url.searchParams.get('location') || '';
        const orderby = url.searchParams.get('orderby') || '';
        const page = url.searchParams.get('page') || 1;

        if (searchInput) searchInput.value = q;
        if (locationFilter) locationFilter.value = location;
        if (orderSelect) orderSelect.value = orderby;
        toggleClearBtn();

        fetchResults({ q, location, orderby, page });
    });

    // Initial bindings
    bindPaginationLinks();
    toggleClearBtn();

})();
</script>

<style>
.search-card .form-control:focus,
.search-card .form-select:focus {
    border-color: #25D366 !important;
    box-shadow: 0 0 0 0.2rem rgba(37, 211, 102, 0.1) !important;
    outline: none;
}

.search-card .btn-outline-primary.active {
    background-color: #25D366;
    border-color: #25D366;
    color: white;
}

.search-card .btn-outline-primary:not(.active) {
    color: #25D366;
}

.search-card .btn-outline-primary:hover {
    background-color: #25D366;
    border-color: #25D366;
    color: white;
}
</style>

@endsection
