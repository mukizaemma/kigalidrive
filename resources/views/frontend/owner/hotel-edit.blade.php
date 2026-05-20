@extends('layouts.frontbase')

@section('content')
<div class="container py-4" style="max-width: 960px;">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h2 class="box-title mb-0">Edit property</h2>
            <p class="text-muted small mb-0">{{ $hotel->name }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('myProperties') }}#properties" class="btn btn-outline-secondary btn-sm">Back to dashboard</a>
            <a href="{{ route('guide') }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-book me-1"></i>Guide</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @php
        $selectedAmenities = old('amenities', $selectedAmenities ?? []);
    @endphp

    <form action="{{ route('my.properties.hotels.update', $hotel) }}" method="POST" enctype="multipart/form-data" class="card border-0 shadow-sm mb-4">
        @csrf
        @method('PUT')

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-lg-8">
                    <label class="form-label">Property name</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name', $hotel->name) }}" required>
                </div>
                <div class="col-lg-4">
                    <label class="form-label">Change cover image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small class="text-muted">Leave empty to keep current image.</small>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Property type</label>
                    <select class="form-control" name="type" required>
                        <option value="hotel" @selected(old('type', $hotel->type)=='hotel')>Hotel</option>
                        <option value="lodge" @selected(old('type', $hotel->type)=='lodge')>Lodge</option>
                        <option value="guest_house" @selected(old('type', $hotel->type)=='guest_house')>Guest House</option>
                        <option value="apartment" @selected(old('type', $hotel->type)=='apartment')>Apartment</option>
                        <option value="motel" @selected(old('type', $hotel->type)=='motel')>Motel</option>
                        <option value="resort" @selected(old('type', $hotel->type)=='resort')>Resort</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Stars</label>
                    <select class="form-control" name="stars" required>
                        <option value="0" @selected(old('stars', $hotel->stars) === '0' || old('stars', $hotel->stars) === 0)>Not ranked</option>
                        <option value="1" @selected(old('stars', $hotel->stars) == '1')>1 Star</option>
                        <option value="2" @selected(old('stars', $hotel->stars) == '2')>2 Stars</option>
                        <option value="3" @selected(old('stars', $hotel->stars) == '3')>3 Stars</option>
                        <option value="4" @selected(old('stars', $hotel->stars) == '4')>4 Stars</option>
                        <option value="5" @selected(old('stars', $hotel->stars) == '5')>5 Stars</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Approval status</label>
                    <input type="text" class="form-control" value="{{ $hotel->status ?? 'Pending' }}" disabled readonly>
                    <small class="text-muted">Only administrators can change this.</small>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">District</label>
                    <select class="form-control" name="location" required>
                        <option value="">Select district</option>
                        <optgroup label="Kigali City">
                            @foreach(['Gasabo','Kicukiro','Nyarugenge'] as $d)
                                <option value="{{ $d }}" @selected(old('location', $hotel->location)==$d)>{{ $d }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Northern Province">
                            @foreach(['Burera','Gakenke','Gicumbi','Musanze','Rulindo'] as $d)
                                <option value="{{ $d }}" @selected(old('location', $hotel->location)==$d)>{{ $d }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Southern Province">
                            @foreach(['Gisagara','Huye','Kamonyi','Muhanga','Nyamagabe','Nyanza','Nyaruguru','Ruhango'] as $d)
                                <option value="{{ $d }}" @selected(old('location', $hotel->location)==$d)>{{ $d }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Eastern Province">
                            @foreach(['Bugesera','Gatsibo','Kayonza','Kirehe','Ngoma','Nyagatare','Rwamagana'] as $d)
                                <option value="{{ $d }}" @selected(old('location', $hotel->location)==$d)>{{ $d }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Western Province">
                            @foreach(['Karongi','Ngororero','Nyabihu','Nyamasheke','Rubavu','Rutsiro','Rusizi'] as $d)
                                <option value="{{ $d }}" @selected(old('location', $hotel->location)==$d)>{{ $d }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">City / sector</label>
                    <input type="text" class="form-control" name="city" value="{{ old('city', $hotel->city) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Address</label>
                    <input type="text" class="form-control" name="address" value="{{ old('address', $hotel->address) }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Phone</label>
                    <input type="text" class="form-control" name="phone" value="{{ old('phone', $hotel->phone) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="{{ old('email', $hotel->email) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Website</label>
                    <input type="text" class="form-control" name="website" value="{{ old('website', $hotel->website ?? '') }}" placeholder="https://">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Latitude</label>
                    <input type="text" class="form-control" name="latitude" value="{{ old('latitude', $hotel->latitude) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Longitude</label>
                    <input type="text" class="form-control" name="longitude" value="{{ old('longitude', $hotel->longitude) }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea id="hotelDescription" rows="5" class="form-control" name="description">{!! old('description', $hotel->description ?? '') !!}</textarea>
            </div>

            <h5 class="border-bottom pb-2 mb-3">Cancellation policy</h5>
            <p class="text-muted small">Shown on your public listing. Describe free cancellation (if any), refunds, and no-shows.</p>
            <div class="mb-3">
                <label class="form-label">Free cancellation period</label>
                <textarea name="cancellation_free_period" rows="3" class="form-control" placeholder="e.g. Free cancellation until 48 hours before check-in">{{ old('cancellation_free_period', $hotel->cancellation_free_period) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Refund conditions</label>
                <textarea name="cancellation_refund_conditions" rows="3" class="form-control" placeholder="e.g. Partial refund if cancelled within 48 hours">{{ old('cancellation_refund_conditions', $hotel->cancellation_refund_conditions) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">No-show policy</label>
                <textarea name="cancellation_no_show_policy" rows="3" class="form-control" placeholder="e.g. Full charge for no-show">{{ old('cancellation_no_show_policy', $hotel->cancellation_no_show_policy) }}</textarea>
            </div>

            <h5 class="border-bottom pb-2 mb-3">Listing terms &amp; conditions</h5>
            <p class="text-muted small">Shown on your public property page.</p>
            <div class="mb-3">
                <label class="form-label">Terms &amp; conditions</label>
                <textarea name="listing_terms" rows="6" class="form-control" placeholder="e.g. Quiet hours, smoking policy, check-in/out…">{{ old('listing_terms', $hotel->listing_terms) }}</textarea>
            </div>

            <h5 class="border-bottom pb-2 mb-3">Facilities &amp; amenities</h5>
            <p class="text-muted small">Same categories as the admin panel. Choose based on property type.</p>

            <div id="hotel-amenities" class="amenities-section" style="display:none;">
                @foreach($hotelCategories as $category)
                    @if($category->facilities && $category->facilities->count())
                        <div class="card mb-3 shadow-sm">
                            <div class="card-header bg-primary text-white py-2"><strong>{{ $category->name }}</strong></div>
                            <div class="card-body">
                                <div class="row g-2">
                                    @foreach($category->facilities as $amenity)
                                        <div class="col-md-6 col-lg-4">
                                            <label class="form-check-label d-flex align-items-center gap-2">
                                                <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}" class="form-check-input"
                                                    {{ in_array($amenity->id, $selectedAmenities) ? 'checked' : '' }}>
                                                {{ $amenity->title }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div id="apartment-amenities" class="amenities-section" style="display:none;">
                @foreach($apartmentCategories as $category)
                    @if($category->facilities && $category->facilities->count())
                        <div class="card mb-3 shadow-sm">
                            <div class="card-header bg-success text-white py-2"><strong>{{ $category->name }}</strong></div>
                            <div class="card-body">
                                <div class="row g-2">
                                    @foreach($category->facilities as $amenity)
                                        <div class="col-md-6 col-lg-4">
                                            <label class="form-check-label d-flex align-items-center gap-2">
                                                <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}" class="form-check-input"
                                                    {{ in_array($amenity->id, $selectedAmenities) ? 'checked' : '' }}>
                                                {{ $amenity->title }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save property</button>
                <a href="{{ route('my.properties.rooms.create', $hotel) }}" class="btn btn-success"><i class="fa fa-plus"></i> Add room</a>
            </div>
        </div>
    </form>

    <div class="card border-0 shadow-sm mb-4" id="property-gallery">
        <div class="card-header bg-light"><strong>Property gallery</strong></div>
        <div class="card-body">
            <p class="text-muted small">Upload several images at once (same as admin workflow).</p>
            <form action="{{ route('my.properties.hotels.images.store', $hotel) }}" method="POST" enctype="multipart/form-data" class="mb-4">
                @csrf
                <div class="row g-2 align-items-end">
                    <div class="col-md-8">
                        <label class="form-label">Add images</label>
                        <input type="file" name="image[]" class="form-control" multiple accept="image/*" required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">Upload</button>
                    </div>
                </div>
            </form>
            @if($hotel->images && $hotel->images->count())
                <div class="row g-2">
                    @foreach($hotel->images as $galleryImage)
                        <div class="col-6 col-md-3">
                            <div class="position-relative">
                                <img src="{{ asset('storage/images/hotels/'.$galleryImage->image) }}" class="img-fluid rounded w-100" style="height:120px;object-fit:cover;" alt="">
                                <form action="{{ route('my.properties.hotels.images.destroy', $galleryImage->id) }}" method="POST" class="position-absolute top-0 end-0 m-1"
                                      onsubmit="return confirm('Remove this image from the gallery?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger py-0 px-1">&times;</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted mb-0">No extra gallery images yet.</p>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.querySelector('select[name="type"]');
    const hotelAmenities = document.getElementById('hotel-amenities');
    const apartmentAmenities = document.getElementById('apartment-amenities');
    function toggleAmenities() {
        const t = typeSelect.value;
        hotelAmenities.style.display = 'none';
        apartmentAmenities.style.display = 'none';
        if (t === 'apartment') apartmentAmenities.style.display = 'block';
        else if (['hotel','lodge','guest_house','motel','resort'].includes(t)) hotelAmenities.style.display = 'block';
    }
    toggleAmenities();
    typeSelect.addEventListener('change', toggleAmenities);
});
</script>
@endsection
@include('layouts.includes.owner-summernote')
