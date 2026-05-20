@extends('layouts.adminBase')

@section('content')
    <style>
        .unit-form-page { max-width: 1140px; margin-left: auto; margin-right: auto; }
        .unit-form-card { border: 1px solid rgba(0,0,0,.06); border-radius: 12px; overflow: hidden; }
        .unit-stat-input .form-control { text-align: center; font-weight: 500; }
        .unit-file-drop {
            border: 2px dashed #dee2e6; border-radius: 10px; padding: 1rem;
            background: #fafafa; transition: border-color .2s, background .2s;
        }
        .unit-file-drop:hover { border-color: #0d6efd; background: #f8f9ff; }
    </style>
    <!-- Sidebar Start -->
    @include('admin.includes.sidebar')
    <!-- Sidebar End -->

    <!-- Content Start -->
    <div class="content">
        <!-- Navbar Start -->
        @include('admin.includes.navbar')
        <!-- Navbar End -->

        <div class="container-fluid pt-4 px-4">
            <div class="unit-form-page pb-5">
                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
                    <div>
                        <h4 class="mb-1 fw-semibold text-dark">Create New Unit</h4>
                        <p class="text-muted small mb-0">Define capacity, pricing, and details for a room or unit under a property.</p>
                    </div>
                    <a href="{{ route('admin.units.index', request()->query()) }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fa fa-arrow-left me-2"></i>Back to list
                    </a>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
                        <strong class="d-block mb-2"><i class="fa fa-exclamation-circle me-1"></i> Please fix the following:</strong>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('admin.units.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Basic Information -->
                    <div class="card unit-form-card shadow-sm mb-4">
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="property_id" class="form-label fw-medium">Property <span class="text-danger">*</span></label>
                                    <select name="property_id" class="form-select form-select-lg @error('property_id') is-invalid @enderror" id="property_id" required>
                                        <option value="">Select property</option>
                                        @foreach($properties as $property)
                                            <option value="{{ $property->id }}" {{ (old('property_id', $propertyId) == $property->id) ? 'selected' : '' }}>
                                                {{ $property->name }} ({{ ucfirst($property->property_type) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('property_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="unit_type_id" class="form-label fw-medium">Unit type</label>
                                    <select name="unit_type_id" class="form-select @error('unit_type_id') is-invalid @enderror" id="unit_type_id">
                                        <option value="">Optional</option>
                                        @foreach($unitTypes as $type)
                                            <option value="{{ $type->id }}" {{ old('unit_type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('unit_type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="name" class="form-label fw-medium">Unit name</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                           id="name" value="{{ old('name') }}" placeholder="e.g. Deluxe Room">
                                    <small class="text-muted">Optional — auto-generated if empty</small>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Capacity & Features -->
                    <div class="card unit-form-card shadow-sm mb-4">
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-6 col-md-4 col-xl-2 unit-stat-input">
                                    <label for="max_occupancy" class="form-label small text-muted mb-1">Max guests <span class="text-danger">*</span></label>
                                    <input type="number" name="max_occupancy" class="form-control @error('max_occupancy') is-invalid @enderror"
                                           id="max_occupancy" value="{{ old('max_occupancy', 2) }}" min="1" required>
                                    @error('max_occupancy')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-6 col-md-4 col-xl-2 unit-stat-input">
                                    <label for="bedrooms" class="form-label small text-muted mb-1">Bedrooms</label>
                                    <input type="number" name="bedrooms" class="form-control @error('bedrooms') is-invalid @enderror"
                                           id="bedrooms" value="{{ old('bedrooms', 0) }}" min="0">
                                    @error('bedrooms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-6 col-md-4 col-xl-2 unit-stat-input">
                                    <label for="bathrooms" class="form-label small text-muted mb-1">Bathrooms <span class="text-danger">*</span></label>
                                    <input type="number" name="bathrooms" class="form-control @error('bathrooms') is-invalid @enderror"
                                           id="bathrooms" value="{{ old('bathrooms', 1) }}" min="1" required>
                                    @error('bathrooms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-6 col-md-4 col-xl-2 unit-stat-input">
                                    <label for="beds" class="form-label small text-muted mb-1">Beds</label>
                                    <input type="number" name="beds" class="form-control @error('beds') is-invalid @enderror"
                                           id="beds" value="{{ old('beds', 1) }}" min="1">
                                    @error('beds')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-6 col-md-4 col-xl-2 unit-stat-input">
                                    <label for="size_sqm" class="form-label small text-muted mb-1">Size (m²)</label>
                                    <input type="number" name="size_sqm" class="form-control @error('size_sqm') is-invalid @enderror"
                                           id="size_sqm" value="{{ old('size_sqm') }}" min="0" step="0.01" placeholder="—">
                                    @error('size_sqm')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory & Pricing -->
                    <div class="card unit-form-card shadow-sm mb-4">
                        <div class="card-body p-4">
                            <div class="row g-3 mb-2">
                                <div class="col-6 col-md-4 col-xl-2">
                                    <label for="total_units" class="form-label fw-medium">Total units <span class="text-danger">*</span></label>
                                    <input type="number" name="total_units" class="form-control @error('total_units') is-invalid @enderror" id="total_units" value="{{ old('total_units', 1) }}" min="1" required>
                                    @error('total_units')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-6 col-md-4 col-xl-2">
                                    <label for="available_units" class="form-label fw-medium">Available <span class="text-danger">*</span></label>
                                    <input type="number" name="available_units" class="form-control @error('available_units') is-invalid @enderror" id="available_units" value="{{ old('available_units', 1) }}" min="0" required>
                                    @error('available_units')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-6 col-md-4 col-xl-2">
                                    <label for="currency" class="form-label fw-medium">Currency</label>
                                    <select name="currency" class="form-select @error('currency') is-invalid @enderror" id="currency">
                                        <option value="USD" {{ old('currency', 'USD') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                        <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                        <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                                        <option value="RWF" {{ old('currency') == 'RWF' ? 'selected' : '' }}>RWF (Fr)</option>
                                        <option value="KES" {{ old('currency') == 'KES' ? 'selected' : '' }}>KES (KSh)</option>
                                        <option value="UGX" {{ old('currency') == 'UGX' ? 'selected' : '' }}>UGX (USh)</option>
                                        <option value="TZS" {{ old('currency') == 'TZS' ? 'selected' : '' }}>TZS (TSh)</option>
                                    </select>
                                    @error('currency')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-6 col-md-4 col-xl-3">
                                    <label for="price_display_type" class="form-label fw-medium">Price is</label>
                                    <select name="price_display_type" class="form-select @error('price_display_type') is-invalid @enderror" id="price_display_type">
                                        <option value="per_night" {{ old('price_display_type', 'per_night') == 'per_night' ? 'selected' : '' }}>Per night</option>
                                        <option value="per_month" {{ old('price_display_type') == 'per_month' ? 'selected' : '' }}>Per month</option>
                                        <option value="both" {{ old('price_display_type') == 'both' ? 'selected' : '' }}>Both (night &amp; month)</option>
                                    </select>
                                    @error('price_display_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-6 col-md-4 col-xl-3">
                                    <label for="status" class="form-label fw-medium">Listing status</label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror" id="status">
                                        <option value="Available" {{ old('status', 'Available') == 'Available' ? 'selected' : '' }}>Available</option>
                                        <option value="Unavailable" {{ old('status') == 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                                        <option value="Maintenance" {{ old('status') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    </select>
                                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="row g-3 pt-2 border-top mt-1">
                                <div class="col-md-6">
                                    <label for="base_price_per_night" class="form-label fw-medium">Price per night</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fa fa-moon text-muted"></i></span>
                                        <input type="number" name="base_price_per_night" class="form-control @error('base_price_per_night') is-invalid @enderror" id="base_price_per_night" value="{{ old('base_price_per_night') }}" min="0" step="0.01" placeholder="0.00">
                                    </div>
                                    @error('base_price_per_night')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="base_price_per_month" class="form-label fw-medium">Price per month</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fa fa-calendar-alt text-muted"></i></span>
                                        <input type="number" name="base_price_per_month" class="form-control @error('base_price_per_month') is-invalid @enderror" id="base_price_per_month" value="{{ old('base_price_per_month') }}" min="0" step="0.01" placeholder="0.00">
                                    </div>
                                    @error('base_price_per_month')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description & Image -->
                    <div class="card unit-form-card shadow-sm mb-4">
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-lg-8">
                                    <label for="unitDescription" class="form-label fw-medium">Unit description</label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                              id="unitDescription" rows="6">{!! old('description') !!}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-4">
                                    <label for="featured_image" class="form-label fw-medium">Featured image</label>
                                    <div class="unit-file-drop">
                                        <input type="file" name="featured_image" class="form-control border-0 bg-transparent p-0 @error('featured_image') is-invalid @enderror"
                                               id="featured_image" accept="image/*">
                                        <small class="text-muted d-block mt-2">JPG, PNG or WebP — recommended 1200×800 or wider</small>
                                    </div>
                                    @error('featured_image')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Settings -->
                    <div class="card unit-form-card shadow-sm mb-4">
                        <div class="card-body p-4">
                            <div class="rounded-3 bg-light p-3 border border-light">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                           {{ old('is_active', true) ? 'checked' : '' }} role="switch">
                                    <label class="form-check-label fw-medium" for="is_active">Unit is active and bookable</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Facilities/Amenities -->
                    <div class="card unit-form-card shadow-sm mb-4">
                        <div class="card-body p-4">
                    <div class="row">
                        @php
                            $selectedFacilities = old('facilities', []);
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
                        </div>
                    </div>

                    <!-- Additional Charges (Extras) -->
                    <div class="card unit-form-card shadow-sm mb-4">
                        <div class="card-body p-4">
                    <div class="row g-3">
                        @foreach($extraChargeTypes as $chargeType)
                            <div class="col-md-6 col-lg-4 mb-2">
                                <label for="extra_charge_{{ $chargeType->id }}" class="form-label">{{ $chargeType->name }}</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">Price</span>
                                    <input type="number" name="extra_charges[{{ $chargeType->id }}]" id="extra_charge_{{ $chargeType->id }}"
                                           class="form-control" min="0" step="0.01" value="{{ old('extra_charges.'.$chargeType->id, 0) }}" placeholder="0">
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
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex flex-wrap gap-2 justify-content-end pt-2 pb-4">
                        <a href="{{ route('admin.units.index', request()->query()) }}" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <i class="fa fa-save me-2"></i>Create unit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Content End -->

    @include('admin.includes.footer')

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

    @push('scripts')
    <script>
        $(document).ready(function() {
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
@endsection
