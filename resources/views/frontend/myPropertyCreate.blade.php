@extends('layouts.frontbase')

@section('content')
<div class="container" style="width:70%; margin:20px auto;">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h2 class="box-title mb-0">Add New Hotel</h2>
        <a href="{{ route('guide') }}" class="btn btn-outline-primary btn-sm" title="Step-by-step guide and required details">
            <i class="fas fa-book me-1"></i>Guide
        </a>
    </div>

<form action="{{ route('storeHotel') }}" method="POST" enctype="multipart/form-data">
    @csrf



    <div class="row mb-3">
        <div class="col-lg-8 col-sm-12">
            <label>Property Name</label>
            <input type="text" class="form-control" name="name" placeholder="Business name" value="{{ old('name') }}" required>
            @error('name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>
        <div class="col-lg-4 col-sm-12">
            <label>Property's Cover Image</label>
            <input type="file" id="image" name="image" class="form-control" accept="image/*" required>
            @error('image') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

    </div>

    <div class="row mb-3">
        <div class="col-lg-4 col-sm-12">
            <label>Property Type</label>
            <select class="form-control" name="type" required>
                <option value="">Select Type</option>
                <option value="hotel" @if(old('type')=='hotel') selected @endif>Hotel</option>
                <option value="lodge" @if(old('type')=='lodge') selected @endif>Lodge</option>
                <option value="guest_house" @if(old('type')=='guest_house') selected @endif>Guest House</option>
                <option value="apartment" @if(old('type')=='apartment') selected @endif>Apartment</option>
                <option value="motel" @if(old('type')=='motel') selected @endif>Motel</option>
                <option value="resort" @if(old('type')=='resort') selected @endif>Resort</option>
            </select>
            @error('type') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="col-lg-4 col-sm-12">
            <label>Stars</label>
            <select class="form-control" name="stars" required>
                <option value="">Select Rating</option>
                <option value="0" @if(old('stars')==='0') selected @endif>Not Ranked</option>
                <option value="1" @if(old('stars')=='1') selected @endif>1 Star</option>
                <option value="2" @if(old('stars')=='2') selected @endif>2 Stars</option>
                <option value="3" @if(old('stars')=='3') selected @endif>3 Stars</option>
                <option value="4" @if(old('stars')=='4') selected @endif>4 Stars</option>
                <option value="5" @if(old('stars')=='5') selected @endif>5 Stars</option>
            </select>
            @error('stars') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

    </div>

    <div class="row mb-3">
        <div class="col-lg-4 col-sm-12">
            <label>Location (District)</label>
            <select class="form-control" name="location" required>
                <option value="">Select District</option>
                <optgroup label="Kigali City">
                    <option value="Gasabo" @if(old('location')=='Gasabo') selected @endif>Gasabo</option>
                    <option value="Kicukiro" @if(old('location')=='Kicukiro') selected @endif>Kicukiro</option>
                    <option value="Nyarugenge" @if(old('location')=='Nyarugenge') selected @endif>Nyarugenge</option>
                </optgroup>
                <optgroup label="Northern Province">
                    <option value="Burera" @if(old('location')=='Burera') selected @endif>Burera</option>
                    <option value="Gakenke" @if(old('location')=='Gakenke') selected @endif>Gakenke</option>
                    <option value="Gicumbi" @if(old('location')=='Gicumbi') selected @endif>Gicumbi</option>
                    <option value="Musanze" @if(old('location')=='Musanze') selected @endif>Musanze</option>
                    <option value="Rulindo" @if(old('location')=='Rulindo') selected @endif>Rulindo</option>
                </optgroup>
                <optgroup label="Southern Province">
                    <option value="Gisagara" @if(old('location')=='Gisagara') selected @endif>Gisagara</option>
                    <option value="Huye" @if(old('location')=='Huye') selected @endif>Huye</option>
                    <option value="Kamonyi" @if(old('location')=='Kamonyi') selected @endif>Kamonyi</option>
                    <option value="Muhanga" @if(old('location')=='Muhanga') selected @endif>Muhanga</option>
                    <option value="Nyamagabe" @if(old('location')=='Nyamagabe') selected @endif>Nyamagabe</option>
                    <option value="Nyanza" @if(old('location')=='Nyanza') selected @endif>Nyanza</option>
                    <option value="Nyaruguru" @if(old('location')=='Nyaruguru') selected @endif>Nyaruguru</option>
                    <option value="Ruhango" @if(old('location')=='Ruhango') selected @endif>Ruhango</option>
                </optgroup>
                <optgroup label="Eastern Province">
                    <option value="Bugesera" @if(old('location')=='Bugesera') selected @endif>Bugesera</option>
                    <option value="Gatsibo" @if(old('location')=='Gatsibo') selected @endif>Gatsibo</option>
                    <option value="Kayonza" @if(old('location')=='Kayonza') selected @endif>Kayonza</option>
                    <option value="Kirehe" @if(old('location')=='Kirehe') selected @endif>Kirehe</option>
                    <option value="Ngoma" @if(old('location')=='Ngoma') selected @endif>Ngoma</option>
                    <option value="Nyagatare" @if(old('location')=='Nyagatare') selected @endif>Nyagatare</option>
                    <option value="Rwamagana" @if(old('location')=='Rwamagana') selected @endif>Rwamagana</option>
                </optgroup>
                <optgroup label="Western Province">
                    <option value="Karongi" @if(old('location')=='Karongi') selected @endif>Karongi</option>
                    <option value="Ngororero" @if(old('location')=='Ngororero') selected @endif>Ngororero</option>
                    <option value="Nyabihu" @if(old('location')=='Nyabihu') selected @endif>Nyabihu</option>
                    <option value="Nyamasheke" @if(old('location')=='Nyamasheke') selected @endif>Nyamasheke</option>
                    <option value="Rubavu" @if(old('location')=='Rubavu') selected @endif>Rubavu</option>
                    <option value="Rutsiro" @if(old('location')=='Rutsiro') selected @endif>Rutsiro</option>
                    <option value="Rusizi" @if(old('location')=='Rusizi') selected @endif>Rusizi</option>
                </optgroup>
            </select>
            @error('location') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="col-lg-4 col-sm-12">
            <label>Address</label>
            <input type="text" class="form-control" name="address" value="{{ old('address') }}" placeholder="Street, Building, etc">
            @error('address') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="col-lg-4 col-sm-12">
            <label>City</label>
            <input type="text" class="form-control" name="city" value="{{ old('city') }}" placeholder="Kigali, Musanze, Rubavu…">
            @error('city') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>
    </div>


    <div class="row mb-3">
        <div class="col-lg-4 col-sm-12">
            <label>Contact Email</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="example@email.com">
            @error('email') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="col-lg-4 col-sm-12">
            <label>Contact Phone</label>
            <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="+250 7XX XXX XXX">
            @error('phone') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="col-lg-4 col-sm-12">
            <label>Website URL</label>
            <input type="text" class="form-control" name="website" value="{{ old('website') }}" placeholder="Eg: https://www.example.com">
            @error('website') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>
    </div>


    <div class="row mb-3">
        <div class="col-lg-12">
            <label class="form-label">Property Description</label>
            <textarea id="hotelDescription" rows="5" class="form-control" name="description">{!! old('description') !!}</textarea>
            @error('description') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <h5 class="border-bottom pb-2 mb-3">Cancellation policy</h5>
            <p class="text-muted small">Guests will see this on your listing. Describe free cancellation (if any), refunds, and no-shows.</p>
        </div>
        <div class="col-lg-12 mb-3">
            <label class="form-label">Free cancellation period</label>
            <textarea name="cancellation_free_period" rows="3" class="form-control" placeholder="e.g. Free cancellation until 48 hours before check-in">{{ old('cancellation_free_period') }}</textarea>
            @error('cancellation_free_period') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>
        <div class="col-lg-12 mb-3">
            <label class="form-label">Refund conditions</label>
            <textarea name="cancellation_refund_conditions" rows="3" class="form-control" placeholder="e.g. Partial refund if cancelled within 48 hours">{{ old('cancellation_refund_conditions') }}</textarea>
            @error('cancellation_refund_conditions') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>
        <div class="col-lg-12 mb-3">
            <label class="form-label">No-show policy</label>
            <textarea name="cancellation_no_show_policy" rows="3" class="form-control" placeholder="e.g. Full charge for no-show">{{ old('cancellation_no_show_policy') }}</textarea>
            @error('cancellation_no_show_policy') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <h5 class="border-bottom pb-2 mb-3">Listing terms &amp; conditions</h5>
            <p class="text-muted small">House rules and other conditions for guests. Shown on your public property page.</p>
        </div>
        <div class="col-lg-12 mb-3">
            <label class="form-label">Terms &amp; conditions</label>
            <textarea name="listing_terms" rows="6" class="form-control" placeholder="e.g. Quiet hours, smoking policy, check-in/out…">{{ old('listing_terms') }}</textarea>
            @error('listing_terms') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>
    </div>

    <!-- Facilities/Amenities Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="mb-3 border-bottom pb-2">Facilities & Amenities</h5>
            <p class="text-muted mb-3">Select amenities for your property. Categories will change based on property type.</p>
        </div>
        
        @php
            $selectedAmenities = old('amenities', []);
        @endphp
        
        <!-- Hotel Amenities (shown when type is hotel, lodge, guest_house, motel, resort) -->
        <div id="hotel-amenities" class="amenities-section" style="display:none;">
            @if(isset($hotelCategories) && $hotelCategories->count() > 0)
                @foreach($hotelCategories as $category)
                    <div class="col-12 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    @if($category->icon)
                                        <i class="{{ $category->icon }} me-2"></i>
                                    @endif
                                    {{ $category->name }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-2" id="amenities-list-{{ $category->id }}">
                                    @if($category->facilities && $category->facilities->count() > 0)
                                        @foreach($category->facilities as $amenity)
                                            <div class="col-md-6 col-lg-4">
                                                <label class="form-check-label d-flex align-items-center">
                                                    <input type="checkbox" 
                                                           name="amenities[]" 
                                                           value="{{ $amenity->id }}"
                                                           class="form-check-input me-2"
                                                           {{ in_array($amenity->id, $selectedAmenities) ? 'checked' : '' }}>
                                                    <span>{{ $amenity->title }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-12">
                                            <p class="text-muted text-center">No amenities in this category yet.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <p class="text-muted">Hotel amenities categories are not available yet. Please contact admin.</p>
                </div>
            @endif
        </div>
        
        <!-- Apartment Amenities (shown when type is apartment) -->
        <div id="apartment-amenities" class="amenities-section" style="display:none;">
            @if(isset($apartmentCategories) && $apartmentCategories->count() > 0)
                @foreach($apartmentCategories as $category)
                    <div class="col-12 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    @if($category->icon)
                                        <i class="{{ $category->icon }} me-2"></i>
                                    @endif
                                    {{ $category->name }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-2" id="amenities-list-{{ $category->id }}">
                                    @if($category->facilities && $category->facilities->count() > 0)
                                        @foreach($category->facilities as $amenity)
                                            <div class="col-md-6 col-lg-4">
                                                <label class="form-check-label d-flex align-items-center">
                                                    <input type="checkbox" 
                                                           name="amenities[]" 
                                                           value="{{ $amenity->id }}"
                                                           class="form-check-input me-2"
                                                           {{ in_array($amenity->id, $selectedAmenities) ? 'checked' : '' }}>
                                                    <span>{{ $amenity->title }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-12">
                                            <p class="text-muted text-center">No amenities in this category yet.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <p class="text-muted">Apartment amenities categories are not available yet. Please contact admin.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12 d-flex gap-2">
            <button class="btn btn-primary text-black" type="submit"><i class="fa fa-save"></i> Add New</button>
            <a class="btn btn-danger" href="{{ url()->previous() }}">Close</a>
        </div>
    </div>
</form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.querySelector('select[name="type"]');
    const hotelAmenities = document.getElementById('hotel-amenities');
    const apartmentAmenities = document.getElementById('apartment-amenities');
    
    function toggleAmenities() {
        const selectedType = typeSelect.value;
        // Hide all amenities sections first
        hotelAmenities.style.display = 'none';
        apartmentAmenities.style.display = 'none';
        
        // Show appropriate amenities based on type
        if (selectedType === 'apartment') {
            apartmentAmenities.style.display = 'block';
        } else if (['hotel', 'lodge', 'guest_house', 'motel', 'resort'].includes(selectedType)) {
            hotelAmenities.style.display = 'block';
        }
    }
    
    // Initial toggle based on old input or default
    toggleAmenities();
    
    // Toggle on change
    typeSelect.addEventListener('change', toggleAmenities);
});
</script>
@endsection
@include('layouts.includes.owner-summernote')
