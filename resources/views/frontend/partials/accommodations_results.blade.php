<div class="tab-content" id="nav-tabContent">
    {{-- GRID VIEW --}}
    @php
        $propertyTypeLabels = ['hotel' => 'Hotel', 'apartment' => 'Apartment', 'villa' => 'Villa', 'guest_house' => 'Guest House', 'guesthouse' => 'Guest House', 'lodge' => 'Lodge'];
    @endphp
    <div class="tab-pane fade active show" id="tab-grid" role="tabpanel" aria-labelledby="tab-destination-grid">
        <div class="row gy-30">
            @forelse($rooms as $hotel)
                <div class="col-lg-4 col-md-6">
                    <div class="tour-box th-ani" style="height: 100%;">
                        <div class="tour-box_img global-img" style="position: relative;">
                            @php
                                $imagePath = $hotel->featured_image ? asset('storage/images/properties/' . $hotel->featured_image) : asset('assets/img/tour/tour_3_1.jpg');
                            @endphp
                            <img src="{{ $imagePath }}" alt="{{ $hotel->name }}">
                            @php
                                $minPrice = $hotel->min_price ?? null;
                                $currency = 'USD';
                                $priceLabel = '/night';
                                if (isset($hotel->property_type) && $hotel->units && $hotel->units->isNotEmpty()) {
                                    $cheapestUnit = $hotel->units->where('base_price_per_night', '>', 0)->sortBy('base_price_per_night')->first();
                                    if (!$cheapestUnit) {
                                        $cheapestUnit = $hotel->units->where('base_price_per_month', '>', 0)->sortBy('base_price_per_month')->first();
                                        if ($cheapestUnit) {
                                            $minPrice = $minPrice ?? $cheapestUnit->base_price_per_month;
                                            $currency = $cheapestUnit->currency ?? 'USD';
                                            $priceLabel = (($cheapestUnit->price_display_type ?? 'per_night') === 'per_month') ? '/month' : '/night';
                                        }
                                    } else {
                                        $minPrice = $minPrice ?? $cheapestUnit->base_price_per_night;
                                        $currency = $cheapestUnit->currency ?? 'USD';
                                        $priceLabel = (($cheapestUnit->price_display_type ?? 'per_night') === 'per_month') ? '/month' : '/night';
                                    }
                                }
                                $currencySymbol = getCurrencySymbol($currency);
                            @endphp
                        </div>
                        <div class="tour-content">
                            <h3 class="box-title">
                                <a href="{{ route('hotel', $hotel->slug ?? $hotel->id) }}">{{ $hotel->name }}</a>
                            </h3>
                            <p class="mb-1 d-flex flex-wrap gap-1" style="font-size: 13px;">
                                @if(!empty($hotel->property_type))
                                    <span class="badge bg-light text-dark border">{{ $propertyTypeLabels[$hotel->property_type] ?? ucfirst(str_replace('_', ' ', $hotel->property_type)) }}</span>
                                @endif
                                @if(!empty($hotel->listing_type))
                                    @php
                                        $listingLabel = match($hotel->listing_type) {
                                            'rent' => 'For rent',
                                            'sale' => 'For sale',
                                            'both' => 'Rent & sale',
                                            default => ucfirst($hotel->listing_type),
                                        };
                                    @endphp
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $listingLabel }}</span>
                                @endif
                            </p>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="tour-rating">
                                    @php
                                        $starsValue = $hotel->stars ?? 0;
                                        preg_match('/\d+/', (string)$starsValue, $matches);
                                        $starsDisplay = !empty($matches) ? max(0, min(5, (int)$matches[0])) : 0;
                                    @endphp
                                    <div class="star-rating" role="img" aria-label="Rated {{ $starsDisplay }} out of 5">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $starsDisplay)
                                                <i class="fa-solid fa-star text-warning" aria-hidden="true"></i>
                                            @else
                                                <i class="fa-regular fa-star text-warning" aria-hidden="true"></i>
                                            @endif
                                        @endfor
                                        <span class="ms-2" style="font-size: 14px; color: #666;">{{ $starsDisplay }} {{ $starsDisplay == 1 ? 'Star' : 'Stars' }}</span>
                                    </div>
                                </div>
                            </div>
                            @php
                                $address = $hotel->address ?? $hotel->location ?? $hotel->city ?? null;
                            @endphp
                            @if($address)
                                <p class="mb-2" style="font-size: 14px; color: #666;">
                                    <i class="fa-solid fa-location-dot text-primary me-2" aria-hidden="true"></i>{{ $address }}
                                </p>
                            @endif
                            @if(!empty($hotel->description))
                                <p class="mb-3" style="font-size: 13px; color: #666; line-height: 1.45;">{{ \Illuminate\Support\Str::limit(strip_tags($hotel->description), 100) }}</p>
                            @endif
                            <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
                                @if($minPrice)
                                    <span class="text-success fw-semibold" style="font-size: 1.05rem;">{{ $currencySymbol }}{{ number_format($minPrice, 0) }}{{ $priceLabel }}</span>
                                @else
                                    <span class="text-muted small">Price on request</span>
                                @endif
                                <a href="{{ route('hotel', $hotel->slug ?? $hotel->id) }}" class="th-btn style3 btn-sm">Book Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No properties found matching your search criteria.</p>
                        <a href="{{ route('apartments') }}" class="th-btn style3">View All Properties</a>
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
