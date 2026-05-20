@extends('layouts.adminBase')

@section('content')
    <style>
        .unit-form-page { max-width: 1140px; margin-left: auto; margin-right: auto; }
        .unit-form-card { border: 1px solid rgba(0,0,0,.06); border-radius: 12px; overflow: hidden; }
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
                        <h4 class="mb-1 fw-semibold text-dark">Create New Property</h4>
                        <p class="text-muted small mb-0">Add listing details, location, media, and amenities.</p>
                    </div>
                    <a href="{{ route('admin.properties.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fa fa-arrow-left me-2"></i>Back to list
                    </a>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form id="adminPropertyForm" action="{{ route('admin.properties.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="card unit-form-card shadow-sm mb-4">
                        <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Property Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="property_type" class="form-label">Property Type <span class="text-danger">*</span></label>
                            <select name="property_type" class="form-select @error('property_type') is-invalid @enderror" id="property_type" required>
                                <option value="hotel" {{ old('property_type') == 'hotel' ? 'selected' : '' }}>Hotel</option>
                                <option value="apartment" {{ old('property_type') == 'apartment' ? 'selected' : '' }}>Apartment</option>
                                <option value="guesthouse" {{ old('property_type') == 'guesthouse' ? 'selected' : '' }}>Guest House</option>
                                <option value="lodge" {{ old('property_type') == 'lodge' ? 'selected' : '' }}>Lodge</option>
                            </select>
                            @error('property_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="stars" class="form-label">Stars (Hotels)</label>
                            <input type="text" name="stars" class="form-control @error('stars') is-invalid @enderror" 
                                   id="stars" value="{{ old('stars') }}" placeholder="e.g., 3, 4, 5">
                            @error('stars')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @if(!empty($isPropertySuperAdmin))
                        <div class="col-md-4 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" id="status">
                                <option value="Pending" {{ old('status', 'Pending') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label d-block">Settings</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" 
                                       {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">Featured</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="is_verified" id="is_verified" value="1" 
                                       {{ old('is_verified') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_verified">Verified</label>
                            </div>
                        </div>
                        @endif
                    </div>
                        </div>
                    </div>

                    <div class="card unit-form-card shadow-sm mb-4">
                        <div class="card-body p-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-4 mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" 
                                   id="address" value="{{ old('address') }}">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <label for="city" class="form-label">City / Town</label>
                            <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" 
                                   id="city" value="{{ old('city') }}">
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <label for="location" class="form-label">Location (District)</label>
                            <select name="location" id="location" class="form-select @error('location') is-invalid @enderror" required>
                                <option value="">Select District</option>
                                <optgroup label="Kigali City">
                                    <option value="Gasabo" {{ old('location')=='Gasabo' ? 'selected' : '' }}>Gasabo</option>
                                    <option value="Kicukiro" {{ old('location')=='Kicukiro' ? 'selected' : '' }}>Kicukiro</option>
                                    <option value="Nyarugenge" {{ old('location')=='Nyarugenge' ? 'selected' : '' }}>Nyarugenge</option>
                                </optgroup>
                                <optgroup label="Northern Province">
                                    <option value="Burera" {{ old('location')=='Burera' ? 'selected' : '' }}>Burera</option>
                                    <option value="Gakenke" {{ old('location')=='Gakenke' ? 'selected' : '' }}>Gakenke</option>
                                    <option value="Gicumbi" {{ old('location')=='Gicumbi' ? 'selected' : '' }}>Gicumbi</option>
                                    <option value="Musanze" {{ old('location')=='Musanze' ? 'selected' : '' }}>Musanze</option>
                                    <option value="Rulindo" {{ old('location')=='Rulindo' ? 'selected' : '' }}>Rulindo</option>
                                </optgroup>
                                <optgroup label="Southern Province">
                                    <option value="Gisagara" {{ old('location')=='Gisagara' ? 'selected' : '' }}>Gisagara</option>
                                    <option value="Huye" {{ old('location')=='Huye' ? 'selected' : '' }}>Huye</option>
                                    <option value="Kamonyi" {{ old('location')=='Kamonyi' ? 'selected' : '' }}>Kamonyi</option>
                                    <option value="Muhanga" {{ old('location')=='Muhanga' ? 'selected' : '' }}>Muhanga</option>
                                    <option value="Nyamagabe" {{ old('location')=='Nyamagabe' ? 'selected' : '' }}>Nyamagabe</option>
                                    <option value="Nyanza" {{ old('location')=='Nyanza' ? 'selected' : '' }}>Nyanza</option>
                                    <option value="Nyaruguru" {{ old('location')=='Nyaruguru' ? 'selected' : '' }}>Nyaruguru</option>
                                    <option value="Ruhango" {{ old('location')=='Ruhango' ? 'selected' : '' }}>Ruhango</option>
                                </optgroup>
                                <optgroup label="Eastern Province">
                                    <option value="Bugesera" {{ old('location')=='Bugesera' ? 'selected' : '' }}>Bugesera</option>
                                    <option value="Gatsibo" {{ old('location')=='Gatsibo' ? 'selected' : '' }}>Gatsibo</option>
                                    <option value="Kayonza" {{ old('location')=='Kayonza' ? 'selected' : '' }}>Kayonza</option>
                                    <option value="Kirehe" {{ old('location')=='Kirehe' ? 'selected' : '' }}>Kirehe</option>
                                    <option value="Ngoma" {{ old('location')=='Ngoma' ? 'selected' : '' }}>Ngoma</option>
                                    <option value="Nyagatare" {{ old('location')=='Nyagatare' ? 'selected' : '' }}>Nyagatare</option>
                                    <option value="Rwamagana" {{ old('location')=='Rwamagana' ? 'selected' : '' }}>Rwamagana</option>
                                </optgroup>
                                <optgroup label="Western Province">
                                    <option value="Karongi" {{ old('location')=='Karongi' ? 'selected' : '' }}>Karongi</option>
                                    <option value="Ngororero" {{ old('location')=='Ngororero' ? 'selected' : '' }}>Ngororero</option>
                                    <option value="Nyabihu" {{ old('location')=='Nyabihu' ? 'selected' : '' }}>Nyabihu</option>
                                    <option value="Nyamasheke" {{ old('location')=='Nyamasheke' ? 'selected' : '' }}>Nyamasheke</option>
                                    <option value="Rubavu" {{ old('location')=='Rubavu' ? 'selected' : '' }}>Rubavu</option>
                                    <option value="Rutsiro" {{ old('location')=='Rutsiro' ? 'selected' : '' }}>Rutsiro</option>
                                    <option value="Rusizi" {{ old('location')=='Rusizi' ? 'selected' : '' }}>Rusizi</option>
                                </optgroup>
                            </select>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                        </div>
                    </div>

                    <div class="card unit-form-card shadow-sm mb-4">
                        <div class="card-body p-4">
                            <p class="text-muted small mb-3">Paste an embedded Google Maps iframe code to display the location on the property page.</p>
                    <div class="row g-3">
                        <div class="col-12 mb-3">
                            <label for="map_embed_code" class="form-label">Google Maps Embed Code (Optional)</label>
                            <textarea name="map_embed_code" class="form-control @error('map_embed_code') is-invalid @enderror" 
                                      id="map_embed_code" rows="4" 
                                      placeholder='<iframe src="https://www.google.com/maps/embed?pb=..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'>{{ old('map_embed_code') }}</textarea>
                            <small class="text-muted">Paste the full iframe code from Google Maps embed.</small>
                            @error('map_embed_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                        </div>
                    </div>

                    <div class="card unit-form-card shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h6 class="fw-semibold mb-3">Contact person <span class="text-muted fw-normal">(shown to guests)</span></h6>
                    <div class="row g-3">
                        <div class="col-md-4 mb-3">
                            <label for="contact_person_name" class="form-label">Contact person name</label>
                            <input type="text" name="contact_person_name" class="form-control @error('contact_person_name') is-invalid @enderror" 
                                   id="contact_person_name" value="{{ old('contact_person_name') }}" placeholder="e.g. Front desk / Manager">
                            @error('contact_person_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="phone" class="form-label">Contact phone</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="email" class="form-label">Contact email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="website" class="form-label">Website</label>
                            <input type="url" name="website" class="form-control @error('website') is-invalid @enderror" 
                                   id="website" value="{{ old('website') }}">
                            @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                        </div>
                    </div>

                    <div class="card unit-form-card shadow-sm mb-4">
                        <div class="card-body p-4">
                            <div class="row g-4">
                        <div class="col-lg-8 mb-3">
                            <label for="propertyDescription" class="form-label fw-medium">Property <span class="text-muted">(rich text)</span></label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                      id="propertyDescription" rows="6">{!! old('description') !!}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label for="featured_image" class="form-label fw-medium">Featured image</label>
                            <div class="unit-file-drop">
                            <input type="file" name="featured_image" class="form-control border-0 bg-transparent p-0 @error('featured_image') is-invalid @enderror"
                                   id="featured_image" accept="image/*">
                            <small class="text-muted d-block mt-2">JPG, PNG or WebP — recommended wide hero</small>
                            </div>
                            @error('featured_image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                            </div>
                        </div>
                    </div>

                    <div class="card unit-form-card shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="fw-semibold mb-3">Terms and conditions</h5>
                            <p class="text-muted small mb-3">Cancellation policy, refund rules, no-show policy, house rules, and check-in expectations — shown to guests on the public property page.</p>
                            <div class="col-12">
                                <label for="propertyListingTerms" class="form-label">Terms and conditions</label>
                                <textarea name="listing_terms" class="form-control @error('listing_terms') is-invalid @enderror" id="propertyListingTerms" rows="10">{!! old('listing_terms') !!}</textarea>
                                @error('listing_terms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card unit-form-card shadow-sm mb-4">
                        <div class="card-body p-4">
                            <p class="text-muted small mb-3">Select amenities for this property, grouped by category.</p>
                    <div class="row g-3">
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

                    <div class="d-flex flex-wrap gap-2 justify-content-end pt-2 pb-4">
                        <a href="{{ route('admin.properties.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <i class="fa fa-save me-2"></i>Create property
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
            $('#adminPropertyForm').on('submit', function () {
                ['#propertyDescription', '#propertyListingTerms'].forEach(function (sel) {
                    var $ta = $(sel);
                    if ($ta.length && $ta.next('.note-editor').length) {
                        $ta.val($ta.summernote('code'));
                    }
                });
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
@endsection
