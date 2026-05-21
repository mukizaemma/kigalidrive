<section class="kdr-home-search" aria-label="Search cars">
    <div class="container">
        <form action="{{ route('showCars') }}" method="GET" class="kdr-home-search__form kdr-card">
            <div class="kdr-home-search__head">
                <h2 class="kdr-home-search__title"><i class="fas fa-search me-2" aria-hidden="true"></i>Find your vehicle</h2>
                <p class="kdr-home-search__sub mb-0">Search our fleet for rent or sale</p>
            </div>
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label" for="home_search_q">Keyword</label>
                    <input type="text" id="home_search_q" name="q" class="form-control" placeholder="Brand, model, name…">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="home_search_type">Listing type</label>
                    <select id="home_search_type" name="listing_type" class="form-select">
                        <option value="">Rent or sale</option>
                        <option value="rent">For rent</option>
                        <option value="sale">For sale</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="home_search_brand">Brand</label>
                    <select id="home_search_brand" name="brand" class="form-select">
                        <option value="">All brands</option>
                        @foreach($carBrands ?? [] as $brand)
                        <option value="{{ $brand }}">{{ $brand }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="th-btn btn-kdr-primary w-100">
                        Search <i class="fas fa-arrow-right ms-1" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>
