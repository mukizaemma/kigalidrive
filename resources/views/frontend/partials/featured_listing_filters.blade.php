{{-- Featured Listings filters sidebar - used on homepage --}}
<div class="card border-0 shadow-sm" style="border-radius: 12px; position: sticky; top: 100px;">
    <div class="card-header bg-white border-0 py-3" style="border-radius: 12px 12px 0 0;">
        <h5 class="mb-0 fw-bold"><i class="fas fa-filter me-2"></i>Filters</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('home') }}" id="featuredFiltersForm">
            @foreach(request()->except('fl_page', 'fl_amenities', 'fl_location', 'fl_property_type', 'fl_star_rating', 'fl_orderby') as $key => $val)
                @if($val !== null && $val !== '' && !str_starts_with($key, 'fl_'))
                    @if(is_array($val))
                        @foreach($val as $v)
                            <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endif
                @endif
            @endforeach

            {{-- Location --}}
            @if(isset($featuredLocations) && $featuredLocations->isNotEmpty())
            <div class="mb-4">
                <h6 class="fw-bold mb-2">Location</h6>
                <select name="fl_location" class="form-select form-select-sm">
                    <option value="">All Locations</option>
                    @foreach($featuredLocations as $loc)
                        <option value="{{ $loc }}" {{ request('fl_location') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            {{-- Price --}}
            <div class="mb-4">
                <h6 class="fw-bold mb-2">Price</h6>
                <div class="row g-2">
                    <div class="col-6">
                        <input type="number" name="fl_price_min" class="form-control form-control-sm" placeholder="Min" value="{{ request('fl_price_min') }}" min="0" step="1">
                    </div>
                    <div class="col-6">
                        <input type="number" name="fl_price_max" class="form-control form-control-sm" placeholder="Max" value="{{ request('fl_price_max') }}" min="0" step="1">
                    </div>
                </div>
            </div>

            {{-- Star Rating --}}
            <div class="mb-4">
                <h6 class="fw-bold mb-2">Star Rating</h6>
                @foreach([5, 4, 3, 2, 1] as $star)
                <div class="form-check mb-1">
                    <input class="form-check-input" type="radio" name="fl_star_rating" id="fl_star{{ $star }}" value="{{ $star }}" {{ request('fl_star_rating') == $star ? 'checked' : '' }}>
                    <label class="form-check-label" for="fl_star{{ $star }}">
                        @for($i = 0; $i < $star; $i++)<i class="fa-solid fa-star text-warning" style="font-size: 12px;"></i>@endfor
                        {{ $star }} Star{{ $star > 1 ? 's' : '' }}
                    </label>
                </div>
                @endforeach
                <div class="form-check mb-1">
                    <input class="form-check-input" type="radio" name="fl_star_rating" id="fl_starAny" value="" {{ !request('fl_star_rating') ? 'checked' : '' }}>
                    <label class="form-check-label" for="fl_starAny">Any</label>
                </div>
            </div>

            {{-- Property Type --}}
            <div class="mb-4">
                <h6 class="fw-bold mb-2">Property Type</h6>
                @foreach($featuredPropertyTypes ?? [] as $typeKey => $typeLabel)
                <div class="form-check mb-1">
                    <input class="form-check-input" type="radio" name="fl_property_type" id="fl_pt{{ $typeKey }}" value="{{ $typeKey }}" {{ request('fl_property_type') == $typeKey ? 'checked' : '' }}>
                    <label class="form-check-label" for="fl_pt{{ $typeKey }}">{{ $typeLabel }}</label>
                </div>
                @endforeach
                <div class="form-check mb-1">
                    <input class="form-check-input" type="radio" name="fl_property_type" id="fl_ptAny" value="" {{ !request('fl_property_type') ? 'checked' : '' }}>
                    <label class="form-check-label" for="fl_ptAny">All Types</label>
                </div>
            </div>

            {{-- Amenities --}}
            @if(isset($featuredAmenities) && $featuredAmenities->isNotEmpty())
            <div class="mb-4">
                <h6 class="fw-bold mb-2">Amenities</h6>
                @foreach($featuredAmenities->take(8) as $amenity)
                <div class="form-check mb-1">
                    <input class="form-check-input" type="checkbox" name="fl_amenities[]" id="fl_am{{ $amenity->id }}" value="{{ $amenity->id }}" {{ in_array($amenity->id, (array)request('fl_amenities', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="fl_am{{ $amenity->id }}">{{ $amenity->title }}</label>
                </div>
                @endforeach
            </div>
            @endif

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Apply Filters
                </button>
                <a href="{{ route('home') }}#featured-listings-sec" class="btn btn-outline-secondary btn-sm">Clear All</a>
            </div>
        </form>
    </div>
</div>
