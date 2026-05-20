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
                                $priceLabel = '/night';
                                
                                if (isset($hotel->property_type)) {
                                    // Property model - get from units
                                    if (!$minPrice && $hotel->units && $hotel->units->isNotEmpty()) {
                                        $cheapestUnit = $hotel->units->where('base_price_per_night', '>', 0)->sortBy('base_price_per_night')->first();
                                        if ($cheapestUnit) {
                                            $minPrice = $cheapestUnit->base_price_per_night;
                                            $currency = $cheapestUnit->currency ?? 'USD';
                                            $priceLabel = ($cheapestUnit->price_display_type ?? 'per_night') === 'per_month' ? '/month' : '/night';
                                        } else {
                                            $cheapestUnit = $hotel->units->where('base_price_per_month', '>', 0)->sortBy('base_price_per_month')->first();
                                            if ($cheapestUnit) {
                                                $minPrice = $cheapestUnit->base_price_per_month;
                                                $currency = $cheapestUnit->currency ?? 'USD';
                                                $priceLabel = ($cheapestUnit->price_display_type ?? 'per_night') === 'per_month' ? '/month' : '/night';
                                            }
                                        }
                                    } elseif ($hotel->units && $hotel->units->isNotEmpty()) {
                                        $cheapestUnit = $hotel->units->where('base_price_per_night', '>', 0)->sortBy('base_price_per_night')->first();
                                        if (!$cheapestUnit) {
                                            $cheapestUnit = $hotel->units->where('base_price_per_month', '>', 0)->sortBy('base_price_per_month')->first();
                                        }
                                        if ($cheapestUnit) {
                                            $currency = $cheapestUnit->currency ?? 'USD';
                                            $priceLabel = ($cheapestUnit->price_display_type ?? 'per_night') === 'per_month' ? '/month' : '/night';
                                        }
                                    }
                                } else {
                                    // Hotel model - get from rooms
                                    if (!$minPrice && $hotel->rooms && $hotel->rooms->isNotEmpty()) {
                                        $cheapestRoom = $hotel->rooms->where('price_per_night', '>', 0)->sortBy('price_per_night')->first();
                                        if ($cheapestRoom) {
                                            $minPrice = $cheapestRoom->price_per_night;
                                            $currency = $cheapestRoom->currency ?? 'USD';
                                            $priceLabel = ($cheapestRoom->price_display_type ?? 'per_night') === 'per_month' ? '/month' : '/night';
                                        } else {
                                            $cheapestRoom = $hotel->rooms->where('price_per_month', '>', 0)->sortBy('price_per_month')->first();
                                            if ($cheapestRoom) {
                                                $minPrice = $cheapestRoom->price_per_month;
                                                $currency = $cheapestRoom->currency ?? 'USD';
                                                $priceLabel = ($cheapestRoom->price_display_type ?? 'per_night') === 'per_month' ? '/month' : '/night';
                                            }
                                        }
                                    } elseif ($hotel->rooms && $hotel->rooms->isNotEmpty()) {
                                        $cheapestRoom = $hotel->rooms->where('price_per_night', '>', 0)->sortBy('price_per_night')->first();
                                        if (!$cheapestRoom) {
                                            $cheapestRoom = $hotel->rooms->where('price_per_month', '>', 0)->sortBy('price_per_month')->first();
                                        }
                                        if ($cheapestRoom) {
                                            $currency = $cheapestRoom->currency ?? 'USD';
                                            $priceLabel = ($cheapestRoom->price_display_type ?? 'per_night') === 'per_month' ? '/month' : '/night';
                                        }
                                    }
                                }
                                $currencySymbol = getCurrencySymbol($currency);
                            @endphp
                            @if($minPrice)
                                <div style="position: absolute; top: 15px; right: 15px; background: rgba(37, 211, 102, 0.95); color: white; padding: 8px 15px; border-radius: 8px; font-weight: 600; font-size: 16px;">
                                    {{ $currencySymbol }}{{ number_format($minPrice, 0) }}{{ $priceLabel }}
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
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hotels found matching your search criteria.</p>
                        <a href="{{ route('hotels') }}" class="th-btn style3">View All Hotels</a>
                    </div>
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
                                @endphp
                                <div class="star-rating" role="img" aria-label="Rated {{ $stars }} out of 5">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $stars)
                                            <i class="fa-solid fa-star text-warning" aria-hidden="true"></i>
                                        @else
                                            <i class="fa-regular fa-star text-warning" aria-hidden="true"></i>
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
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hotels found matching your search criteria.</p>
                        <a href="{{ route('hotels') }}" class="th-btn style3">View All Hotels</a>
                    </div>
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

