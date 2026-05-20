@extends('layouts.adminBase')

@section('content')
    <!-- Sidebar Start -->
    @include('admin.includes.sidebar')
    <!-- Sidebar End -->

    <!-- Content Start -->
    <div class="content">
        <!-- Navbar Start -->
        @include('admin.includes.navbar')
        <!-- Navbar End -->

        <div class="container-fluid pt-4 px-4">
            <div class="bg-light text-center rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Edit Unit</h6>
                    <a href="{{ route('admin.units.index', ['property_id' => $unit->property_id]) }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('admin.units.update', $unit->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Basic Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3 border-bottom pb-2">Basic Information</h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="property_id" class="form-label">Property <span class="text-danger">*</span></label>
                            <select name="property_id" class="form-select @error('property_id') is-invalid @enderror" id="property_id" required>
                                <option value="">Select Property</option>
                                @foreach($properties as $property)
                                    <option value="{{ $property->id }}" {{ old('property_id', $unit->property_id) == $property->id ? 'selected' : '' }}>
                                        {{ $property->name }} ({{ ucfirst($property->property_type) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('property_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="unit_type_id" class="form-label">Unit Type</label>
                            <select name="unit_type_id" class="form-select @error('unit_type_id') is-invalid @enderror" id="unit_type_id">
                                <option value="">Select Type (Optional)</option>
                                @foreach($unitTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('unit_type_id', $unit->unit_type_id) == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('unit_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="name" class="form-label">Unit Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" value="{{ old('name', $unit->name) }}" placeholder="e.g., Deluxe Room">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Capacity & Features -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <h5 class="mb-2 border-bottom pb-2">Capacity & Features</h5>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="max_occupancy" class="form-label">Max Occupancy <span class="text-danger">*</span></label>
                            <input type="number" name="max_occupancy" class="form-control @error('max_occupancy') is-invalid @enderror" 
                                   id="max_occupancy" value="{{ old('max_occupancy', $unit->max_occupancy) }}" min="1" required>
                            @error('max_occupancy')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="bedrooms" class="form-label">Bedrooms</label>
                            <input type="number" name="bedrooms" class="form-control @error('bedrooms') is-invalid @enderror" 
                                   id="bedrooms" value="{{ old('bedrooms', $unit->bedrooms) }}" min="0">
                            @error('bedrooms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="bathrooms" class="form-label">Bathrooms <span class="text-danger">*</span></label>
                            <input type="number" name="bathrooms" class="form-control @error('bathrooms') is-invalid @enderror" 
                                   id="bathrooms" value="{{ old('bathrooms', $unit->bathrooms) }}" min="1" required>
                            @error('bathrooms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="beds" class="form-label">Beds</label>
                            <input type="number" name="beds" class="form-control @error('beds') is-invalid @enderror" 
                                   id="beds" value="{{ old('beds', $unit->beds) }}" min="1">
                            @error('beds')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="size_sqm" class="form-label">Size (sqm)</label>
                            <input type="number" name="size_sqm" class="form-control @error('size_sqm') is-invalid @enderror" 
                                   id="size_sqm" value="{{ old('size_sqm', $unit->size_sqm) }}" min="0" step="0.01">
                            @error('size_sqm')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Inventory & Pricing (one row) -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <h5 class="mb-2 border-bottom pb-2">Inventory &amp; Pricing</h5>
                        </div>
                        <div class="col-6 col-md-2 mb-2">
                            <label for="total_units" class="form-label">Total Units <span class="text-danger">*</span></label>
                            <input type="number" name="total_units" class="form-control @error('total_units') is-invalid @enderror" id="total_units" value="{{ old('total_units', $unit->total_units) }}" min="1" required>
                            @error('total_units')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6 col-md-2 mb-2">
                            <label for="available_units" class="form-label">Available Units <span class="text-danger">*</span></label>
                            <input type="number" name="available_units" class="form-control @error('available_units') is-invalid @enderror" id="available_units" value="{{ old('available_units', $unit->available_units) }}" min="0" required>
                            @error('available_units')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6 col-md-2 mb-2">
                            <label for="currency" class="form-label">Currency</label>
                            <select name="currency" class="form-select @error('currency') is-invalid @enderror" id="currency">
                                <option value="USD" {{ old('currency', $unit->currency ?? 'USD') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                <option value="EUR" {{ old('currency', $unit->currency ?? '') == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                <option value="GBP" {{ old('currency', $unit->currency ?? '') == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                                <option value="RWF" {{ old('currency', $unit->currency ?? '') == 'RWF' ? 'selected' : '' }}>RWF (Fr)</option>
                                <option value="KES" {{ old('currency', $unit->currency ?? '') == 'KES' ? 'selected' : '' }}>KES (KSh)</option>
                                <option value="UGX" {{ old('currency', $unit->currency ?? '') == 'UGX' ? 'selected' : '' }}>UGX (USh)</option>
                                <option value="TZS" {{ old('currency', $unit->currency ?? '') == 'TZS' ? 'selected' : '' }}>TZS (TSh)</option>
                            </select>
                            @error('currency')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6 col-md-2 mb-2">
                            <label for="price_display_type" class="form-label">Price is</label>
                            <select name="price_display_type" class="form-select @error('price_display_type') is-invalid @enderror" id="price_display_type">
                                <option value="per_night" {{ old('price_display_type', $unit->price_display_type ?? 'per_night') == 'per_night' ? 'selected' : '' }}>Per night</option>
                                <option value="per_month" {{ old('price_display_type', $unit->price_display_type ?? '') == 'per_month' ? 'selected' : '' }}>Per month</option>
                                <option value="both" {{ old('price_display_type', $unit->price_display_type ?? '') == 'both' ? 'selected' : '' }}>Both (night &amp; month)</option>
                            </select>
                            @error('price_display_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6 col-md-2 mb-2">
                            <label for="base_price_per_night" class="form-label">Price per night</label>
                            <input type="number" name="base_price_per_night" class="form-control @error('base_price_per_night') is-invalid @enderror" id="base_price_per_night" value="{{ old('base_price_per_night', $unit->base_price_per_night) }}" min="0" step="0.01">
                            @error('base_price_per_night')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6 col-md-2 mb-2">
                            <label for="base_price_per_month" class="form-label">Price per month</label>
                            <input type="number" name="base_price_per_month" class="form-control @error('base_price_per_month') is-invalid @enderror" id="base_price_per_month" value="{{ old('base_price_per_month', $unit->base_price_per_month) }}" min="0" step="0.01">
                            @error('base_price_per_month')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3 border-bottom pb-2">Description</h5>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="unitDescription" class="form-label">Unit Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                      id="unitDescription" rows="6">{!! old('description', $unit->description) !!}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Featured Image -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3 border-bottom pb-2">Featured Image</h5>
                            <p class="text-muted mb-3">Main featured image for the unit (displayed as the primary image).</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="featured_image" class="form-label">Upload Featured Image</label>
                            @if($unit->featured_image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/images/units/' . $unit->featured_image) }}" alt="Current featured image" class="img-thumbnail" style="max-width: 100%; height: auto; max-height: 300px;">
                                </div>
                            @endif
                            <input type="file" name="featured_image" class="form-control @error('featured_image') is-invalid @enderror" 
                                   id="featured_image" accept="image/*">
                            <small class="text-muted">Leave empty to keep current image</small>
                            @error('featured_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Status & Settings -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3 border-bottom pb-2">Status & Settings</h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" id="status" required>
                                <option value="Available" {{ old('status', $unit->status) == 'Available' ? 'selected' : '' }}>Available</option>
                                <option value="Unavailable" {{ old('status', $unit->status) == 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                                <option value="Maintenance" {{ old('status', $unit->status) == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label d-block">Settings</label>
                            <div class="form-check form-check-inline mt-2">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" 
                                       {{ old('is_active', $unit->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Facilities/Amenities -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3 border-bottom pb-2">Facilities/Amenities</h5>
                            <p class="text-muted mb-3">Select amenities for this unit, grouped by category:</p>
                        </div>
                        @php
                            $selectedFacilities = old('facilities', $unit->facilities->pluck('id')->toArray());
                        @endphp
                        @foreach($facilityCategories as $category)
                            @if($category->facilities->count() > 0)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card h-100">
                                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">
                                                @if($category->icon)
                                                    <i class="{{ $category->icon }} me-2"></i>
                                                @endif
                                                {{ $category->name }}
                                            </h6>
                                            <button type="button" class="btn btn-sm btn-light" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#addAmenityModal"
                                                    data-category-id="{{ $category->id }}"
                                                    data-category-name="{{ $category->name }}"
                                                    title="Add New Amenity">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                        <div class="card-body" style="max-height: 300px; overflow-y: auto; padding: 1rem;" id="amenities-list-{{ $category->id }}">
                                            @foreach($category->facilities as $amenity)
                                                <div class="d-flex align-items-center mb-2 amenity-item" data-amenity-id="{{ $amenity->id }}">
                                                    <div class="form-check me-3 flex-grow-1">
                                                        <input class="form-check-input" type="checkbox" name="facilities[]" 
                                                               value="{{ $amenity->id }}" id="facility_{{ $amenity->id }}"
                                                               {{ in_array($amenity->id, $selectedFacilities) ? 'checked' : '' }}>
                                                        <label class="form-check-label mb-0" for="facility_{{ $amenity->id }}">
                                                            {{ $amenity->title }}
                                                        </label>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-danger delete-amenity-btn" 
                                                            data-amenity-id="{{ $amenity->id }}"
                                                            data-amenity-title="{{ $amenity->title }}"
                                                            title="Delete Amenity">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Additional Charges (Extras) -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-2 border-bottom pb-2">Additional Charges (Extras)</h5>
                            <p class="text-muted small mb-3">Set a price for each extra to offer during booking. Leave 0 or empty to not offer that extra for this unit.</p>
                        </div>
                        @php
                            $extraChargesByType = $unit->extraChargesAll->keyBy('extra_charge_type_id');
                        @endphp
                        @foreach($extraChargeTypes as $chargeType)
                            @php
                                $currentPrice = $extraChargesByType->get($chargeType->id)?->price ?? 0;
                            @endphp
                            <div class="col-md-6 col-lg-4 mb-2">
                                <label for="extra_charge_{{ $chargeType->id }}" class="form-label">{{ $chargeType->name }}</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">Price</span>
                                    <input type="number" name="extra_charges[{{ $chargeType->id }}]" id="extra_charge_{{ $chargeType->id }}"
                                           class="form-control" min="0" step="0.01" value="{{ old('extra_charges.'.$chargeType->id, $currentPrice) }}" placeholder="0">
                                </div>
                                @if($chargeType->description)
                                    <small class="text-muted">{{ $chargeType->description }}</small>
                                @endif
                            </div>
                        @endforeach
                        @if($extraChargeTypes->isEmpty())
                            <div class="col-12">
                                <p class="text-muted mb-0">No extra charge types defined. Run <code>php artisan db:seed --class=ExtraChargeTypeSeeder</code> to seed defaults.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Submit Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 border-top pt-3">
                                <a href="{{ route('admin.units.index', ['property_id' => $unit->property_id]) }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save me-2"></i>Update Unit
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Image Gallery (Separate from main form) -->
                <div class="row mb-4 mt-4">
                    <div class="col-12">
                        <div class="bg-light rounded p-4">
                            <h5 class="mb-3 border-bottom pb-2">Image Gallery</h5>
                            <p class="text-muted mb-3">Upload multiple images to create a gallery for this unit. You can set one image as primary.</p>
                            
                            <!-- Upload New Images -->
                            <div class="mb-4">
                                <div class="card">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0"><i class="fas fa-upload me-2"></i>Upload Images</h6>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('admin.units.images.store', $unit->id) }}" method="POST" enctype="multipart/form-data" id="unitImagesForm">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="images" class="form-label">Select Images (Multiple)</label>
                                                <input type="file" name="images[]" class="form-control @error('images') is-invalid @enderror" 
                                                       id="images" accept="image/*" multiple>
                                                <small class="text-muted">You can select multiple images at once (Ctrl/Cmd + Click)</small>
                                                @error('images')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                @error('images.*')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="image_caption" class="form-label">Caption (Optional)</label>
                                                <input type="text" name="caption" class="form-control" id="image_caption" placeholder="Caption for all images">
                                            </div>
                                            <div class="mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="is_primary" id="set_primary" value="1">
                                                    <label class="form-check-label" for="set_primary">
                                                        Set first image as primary
                                                    </label>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-upload me-2"></i>Upload Images
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Existing Images -->
                            <div>
                                <div class="card">
                                    <div class="card-header bg-secondary text-white">
                                        <h6 class="mb-0"><i class="fas fa-images me-2"></i>Gallery Images ({{ $unit->images->count() }})</h6>
                                    </div>
                                    <div class="card-body">
                                        @if($unit->images->count() > 0)
                                            <div class="row">
                                                @foreach($unit->images as $image)
                                                    <div class="col-md-3 mb-3">
                                                        <div class="card h-100">
                                                            <div class="position-relative">
                                                                <img src="{{ asset('storage/images/units/' . $image->image_path) }}" 
                                                                     alt="{{ $image->caption ?? 'Unit Image' }}" 
                                                                     class="card-img-top" 
                                                                     style="height: 200px; object-fit: cover;">
                                                                @if($image->is_primary)
                                                                    <span class="badge bg-success position-absolute top-0 start-0 m-2">
                                                                        <i class="fas fa-star me-1"></i>Primary
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="card-body p-2">
                                                                <div class="mb-2">
                                                                    <input type="text" 
                                                                           class="form-control form-control-sm image-caption-input" 
                                                                           data-image-id="{{ $image->id }}"
                                                                           data-route="{{ route('admin.units.images.update', $image->id) }}"
                                                                           value="{{ $image->caption ?? '' }}" 
                                                                           placeholder="Image caption...">
                                                                    <small class="text-muted d-block mt-1">
                                                                        <i class="fas fa-info-circle"></i> Press Enter to save
                                                                    </small>
                                                                </div>
                                                                <div class="btn-group w-100" role="group">
                                                                    @if(!$image->is_primary)
                                                                        <a href="{{ route('admin.units.images.primary', $image->id) }}" 
                                                                           class="btn btn-sm btn-info" 
                                                                           title="Set as Primary">
                                                                            <i class="fas fa-star"></i>
                                                                        </a>
                                                                    @endif
                                                                    <a href="{{ route('admin.units.images.destroy', $image->id) }}" 
                                                                       class="btn btn-sm btn-danger" 
                                                                       onclick="return confirm('Are you sure you want to delete this image?')"
                                                                       title="Delete">
                                                                        <i class="fas fa-trash"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center py-4">
                                                <i class="fas fa-images fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">No images in gallery yet. Upload images above to get started.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Content End -->

    @include('admin.includes.footer')

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Handle inline image caption editing
            $('.image-caption-input').on('keypress', function(e) {
                if (e.which === 13) { // Enter key
                    e.preventDefault();
                    var $input = $(this);
                    var imageId = $input.data('image-id');
                    var route = $input.data('route');
                    var caption = $input.val();
                    var originalValue = $input.data('original-value') || $input.val();

                    // Disable input while saving
                    $input.prop('disabled', true);

                    $.ajax({
                        url: route,
                        method: 'PUT',
                        data: {
                            _token: '{{ csrf_token() }}',
                            caption: caption
                        },
                        success: function(response) {
                            $input.data('original-value', caption);
                            $input.prop('disabled', false);
                            
                            // Show success message
                            var $successMsg = $('<small class="text-success d-block mt-1"><i class="fas fa-check"></i> Saved</small>');
                            $input.siblings('small.text-muted').replaceWith($successMsg);
                            setTimeout(function() {
                                $successMsg.replaceWith('<small class="text-muted d-block mt-1"><i class="fas fa-info-circle"></i> Press Enter to save</small>');
                            }, 2000);
                        },
                        error: function(xhr) {
                            $input.prop('disabled', false);
                            $input.val(originalValue);
                            
                            // Show error message
                            var errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error saving caption';
                            var $errorMsg = $('<small class="text-danger d-block mt-1"><i class="fas fa-exclamation-circle"></i> ' + errorMsg + '</small>');
                            $input.siblings('small.text-muted, small.text-success').replaceWith($errorMsg);
                            setTimeout(function() {
                                $errorMsg.replaceWith('<small class="text-muted d-block mt-1"><i class="fas fa-info-circle"></i> Press Enter to save</small>');
                            }, 3000);
                        }
                    });
                }
            });

            // Store original value on focus
            $('.image-caption-input').on('focus', function() {
                $(this).data('original-value', $(this).val());
            });

            // Handle modal open - set category
            $('#addAmenityModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var categoryId = button.data('category-id');
                var categoryName = button.data('category-name');
                
                $('#amenity_category').val(categoryId);
                $('#addAmenityModalLabel').text('Add New Amenity to ' + categoryName);
            });

            // Handle form submission
            $('#addAmenityForm').on('submit', function(e) {
                e.preventDefault();
                
                var form = $(this);
                var submitBtn = form.find('button[type="submit"]');
                var originalText = submitBtn.html();
                
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i>Saving...');
                
                $.ajax({
                    url: '{{ route("amenities.store") }}',
                    method: 'POST',
                    data: form.serialize(),
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        console.log('Success response:', response);
                        if (response.success) {
                            // Add new amenity to the appropriate category card
                            var categoryId = response.amenity.facility_category_id;
                            var amenityList = $('#amenities-list-' + categoryId);
                            
                            // Remove "No amenities" message if exists
                            amenityList.find('.text-muted.text-center').remove();
                            
                            // Create new amenity row
                            var newAmenityHtml = '<div class="d-flex align-items-center mb-2 amenity-item" data-amenity-id="' + response.amenity.id + '">' +
                                '<div class="form-check me-3 flex-grow-1">' +
                                    '<input class="form-check-input" type="checkbox" name="facilities[]" value="' + response.amenity.id + '" id="facility_' + response.amenity.id + '" checked>' +
                                    '<label class="form-check-label mb-0" for="facility_' + response.amenity.id + '">' + response.amenity.title + '</label>' +
                                '</div>' +
                                '<button type="button" class="btn btn-sm btn-danger delete-amenity-btn" ' +
                                    'data-amenity-id="' + response.amenity.id + '" ' +
                                    'data-amenity-title="' + response.amenity.title + '" ' +
                                    'title="Delete Amenity">' +
                                    '<i class="fa fa-trash"></i>' +
                                '</button>' +
                            '</div>';
                            
                            amenityList.append(newAmenityHtml);
                            
                            // Close modal and reset form
                            $('#addAmenityModal').modal('hide');
                            form[0].reset();
                            
                            // Show success message
                            alert('Amenity added successfully!');
                        }
                    },
                    error: function(xhr) {
                        console.log('Error response:', xhr);
                        var errors = xhr.responseJSON?.errors || {};
                        var errorMsg = 'Error adding amenity. ';
                        
                        if (xhr.responseJSON?.message) {
                            errorMsg += xhr.responseJSON.message;
                        } else if (Object.keys(errors).length > 0) {
                            errorMsg += Object.values(errors).flat().join(', ');
                        } else if (xhr.status === 0) {
                            errorMsg += 'Network error. Please check your connection.';
                        } else {
                            errorMsg += 'Please try again. Status: ' + xhr.status;
                        }
                        
                        alert(errorMsg);
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });

            // Handle amenity deletion
            $(document).on('click', '.delete-amenity-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                var btn = $(this);
                var amenityId = btn.data('amenity-id');
                var amenityTitle = btn.data('amenity-title');
                var amenityItem = btn.closest('.amenity-item');
                
                console.log('Delete clicked for amenity:', amenityId, amenityTitle);
                
                // Show confirmation dialog
                if (confirm('Are you sure you want to delete "' + amenityTitle + '"?\n\nThis action cannot be undone.')) {
                    // Disable button during deletion
                    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
                    
                    $.ajax({
                        url: '{{ route("amenities.destroy", ":id") }}'.replace(':id', amenityId),
                        method: 'GET',
                        dataType: 'json',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        success: function(response) {
                            console.log('Delete success response:', response);
                            if (response.success) {
                                // Remove the amenity item from the DOM
                                amenityItem.fadeOut(300, function() {
                                    $(this).remove();
                                    
                                    // Check if category is now empty
                                    var categoryCard = amenityItem.closest('.card');
                                    var amenitiesList = categoryCard.find('.card-body');
                                    if (amenitiesList.find('.amenity-item').length === 0) {
                                        amenitiesList.html('<p class="text-muted text-center mb-0">No amenities in this category</p>');
                                    }
                                });
                            }
                        },
                        error: function(xhr) {
                            console.log('Delete error response:', xhr);
                            btn.prop('disabled', false).html('<i class="fa fa-trash"></i>');
                            var errorMsg = 'Error deleting amenity. ';
                            
                            if (xhr.responseJSON?.message) {
                                errorMsg += xhr.responseJSON.message;
                            } else if (xhr.status === 0) {
                                errorMsg += 'Network error. Please check your connection.';
                            } else {
                                errorMsg += 'Please try again. Status: ' + xhr.status;
                            }
                            
                            alert(errorMsg);
                        }
                    });
                }
            });
        });
    </script>
    @endpush

    <!-- Add Amenity Modal -->
    <div class="modal fade" id="addAmenityModal" tabindex="-1" aria-labelledby="addAmenityModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAmenityModalLabel">Add New Amenity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addAmenityForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="amenity_title" class="form-label">Amenity Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" id="amenity_title" required>
                        </div>
                        <div class="mb-3">
                            <label for="amenity_category" class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="facility_category_id" class="form-select" id="amenity_category" required>
                                <option value="">Select Category</option>
                                @foreach($facilityCategories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="amenity_icon" class="form-label">Icon Class (Optional)</label>
                            <input type="text" name="icon" class="form-control" id="amenity_icon" placeholder="e.g., fas fa-wifi">
                            <small class="text-muted">Font Awesome icon class</small>
                        </div>
                        <div class="mb-3">
                            <label for="amenity_description" class="form-label">Description (Optional)</label>
                            <textarea name="description" class="form-control" id="amenity_description" rows="2"></textarea>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="amenity_is_active" value="1" checked>
                            <label class="form-check-label" for="amenity_is_active">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i>Save Amenity
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
