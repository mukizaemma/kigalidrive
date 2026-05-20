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

            <div class="container-fluid px-4">
 

                <ul class="nav mt-10">
                    <li class="nav-item mr-20 ">
                        <a href="{{ route('getRooms') }}" class="btn btn-dark">Back</a>
                    </li>
                    <li class="nav-item ">
                        
                    <li class="breadcrumb-item active"> Updating <strong>  {{ $room->room_type}}</strong></li>

                    </li>
                    <li class="nav-item ms-auto">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newImage">
                            Add New Image
                        </button>
                    </li>
                </ul>

                <div class="container-fluid px-4">

                <div class="card mb-4">
                    <div class="card-body" style="max-height: 75vh; overflow-y: auto;">
                        <form class="form" action="{{ route('updateRoom', $room->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="modal-body">

                                <!-- Select Hotel -->
                                <div class="row mb-3">
                                    <div class="col-lg-12">
                                        <label class="form-label">Select Hotel</label>
                                        <select name="hotel_id" class="form-control" required>
                                            @foreach($hotels as $hotel)
                                                <option value="{{ $hotel->id }}" 
                                                    {{ $hotel->id == $room->hotel_id ? 'selected' : '' }}>
                                                    {{ $hotel->name }} — {{ $hotel->location }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Room Type -->
                                <div class="row mb-3">
                                    <div class="col-lg-12 col-sm-12">
                                        <label class="form-label">Room Type</label>
                                        <input type="text" name="room_type" class="form-control"
                                            value="{{ $room->room_type }}" required>
                                    </div>
                                </div>

                                <!-- Pricing Section -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <h6 class="mb-2">Pricing Information</h6>
                                    </div>
                                    <div class="col-lg-3 col-sm-12 mb-3">
                                        <label class="form-label">Currency</label>
                                        <select name="currency" class="form-control">
                                            <option value="USD" {{ old('currency', $room->currency ?? 'USD') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                            <option value="EUR" {{ old('currency', $room->currency ?? '') == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                            <option value="GBP" {{ old('currency', $room->currency ?? '') == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                                            <option value="RWF" {{ old('currency', $room->currency ?? '') == 'RWF' ? 'selected' : '' }}>RWF (Fr)</option>
                                            <option value="KES" {{ old('currency', $room->currency ?? '') == 'KES' ? 'selected' : '' }}>KES (KSh)</option>
                                            <option value="UGX" {{ old('currency', $room->currency ?? '') == 'UGX' ? 'selected' : '' }}>UGX (USh)</option>
                                            <option value="TZS" {{ old('currency', $room->currency ?? '') == 'TZS' ? 'selected' : '' }}>TZS (TSh)</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-sm-12 mb-3">
                                        <label class="form-label">Price Display Type</label>
                                        <select name="price_display_type" class="form-control">
                                            <option value="per_night" {{ old('price_display_type', $room->price_display_type ?? 'per_night') == 'per_night' ? 'selected' : '' }}>Per Night</option>
                                        </select>
                                        <small class="text-muted">Prices are displayed per night</small>
                                    </div>
                                    <div class="col-lg-3 col-sm-12 mb-3">
                                        <label class="form-label">Price per Night</label>
                                        <input type="number" step="0.01" name="price_per_night" 
                                            class="form-control"
                                            value="{{ $room->price_per_night }}" required>
                                    </div>
                                </div>

                                <!-- Occupancy + Rooms Count -->
                                <div class="row mb-3">

                                    <div class="col-lg-4 col-sm-12">
                                        <label class="form-label">Max Occupancy</label>
                                        <input type="number" name="max_occupancy" class="form-control"
                                            value="{{ $room->max_occupancy }}" required>
                                    </div>

                                    <div class="col-lg-4 col-sm-12">
                                        <label class="form-label">Total Rooms</label>
                                        <input type="number" name="total_rooms" class="form-control"
                                            value="{{ $room->total_rooms }}" required>
                                    </div>

                                    <div class="col-lg-4 col-sm-12">
                                        <label class="form-label">Available Rooms</label>
                                        <input type="number" name="available_rooms" class="form-control"
                                            value="{{ $room->available_rooms }}" required>
                                    </div>

                                </div>

                                <!-- Description -->
                                <div class="row mb-3">
                                    <div class="col-lg-12">
                                        <label class="form-label">Room Description</label>
                                        <textarea rows="5" class="form-control"
                                                name="description" id="roomDescription">{!! $room->description !!}</textarea>
                                    </div>
                                </div>

                                <!-- Cover Image -->
                                <div class="row mb-3">
                                    <div class="col-lg-4 col-sm-12">
                                        <label class="form-label">Current Cover Image</label><br>
                                        <img src="{{ asset('storage/images/rooms/' . $room->image) }}" 
                                            width="140" class="rounded">
                                    </div>

                                    <div class="col-lg-4 col-sm-12">
                                        <label class="form-label">Change Cover Image</label>
                                        <input type="file" name="image" class="form-control">
                                    </div>
                                </div>

                                <!-- Amenities (grouped by category) -->
                                <div class="row mt-3">
                                    <div class="col-lg-12">
                                        <label class="form-label">Select Amenities</label>
                                        <p class="text-muted mb-2">Amenities are grouped by category for easier selection.</p>
                                    </div>
                                    @foreach($facilityCategories ?? [] as $category)
                                        @if($category->facilities->count())
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-header bg-primary text-white">
                                                        <h6 class="mb-0">
                                                            @if($category->icon)
                                                                <i class="{{ $category->icon }} me-2"></i>
                                                            @endif
                                                            {{ $category->name }}
                                                        </h6>
                                                    </div>
                                                    <div class="card-body" style="max-height: 260px; overflow-y: auto;">
                                                        @foreach($category->facilities as $amenity)
                                                            <div class="form-check mb-1">
                                                                <input class="form-check-input"
                                                                       type="checkbox"
                                                                       name="amenities[]" 
                                                                       value="{{ $amenity->id }}"
                                                                       id="amenity{{ $amenity->id }}"
                                                                       {{ in_array($amenity->id, $selectedAmenities ?? []) ? 'checked' : '' }}>
                                                                <label class="form-check-label" 
                                                                       for="amenity{{ $amenity->id }}">
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

                            </div>

                            <!-- Submit Section -->
                            <div class="form-actions mt-4">
                                <button type="submit" class="btn btn-primary text-black">
                                    <i class="fa fa-save"></i> Update Room
                                </button>

                                <a href="{{ route('getRooms') }}" class="btn btn-light">Back to Rooms</a>
                            </div>

                        </form>
                    </div>
                </div>


                        <!-- Image Gallery Section -->
                        <div class="card mt-5">
                            <div class="card-header bg-dark text-white d-flex align-items-center">
                                <h5 class="mb-0">
                                    <span style="color: yellow">{{ $totalImages }}</span> Images
                                </h5>
                                <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#newImage">
                                    Add New Image
                                </button>
                            </div>
                            
                            <div class="card-body">
                                @if($images->count() == 0)
                                    <p class="text-muted">No images yet.</p>
                                @else
                                    <div class="row">
                                        @foreach($images as $image)
                                        <div class="col-md-4 col-sm-6 mb-4">
                                            <div class="card shadow" style="position: relative; overflow: hidden; transition: 0.3s;">
                                                <img src="{{ asset('storage/images/rooms/' . $image->image) }}" 
                                                    class="card-img-top rounded" alt="Blog Image" style="width: 100%; height: auto;">
                                                <button onclick="confirmDelete('{{ route('deleteRoomImage', $image->id) }}')" 
                                                        style="position: absolute; top: 10px; right: 10px; background: rgba(255, 0, 0, 0.8); color: white; border: none; padding: 6px 10px; border-radius: 50%; cursor: pointer; transition: 0.3s;">
                                                    ×
                                                </button>
                                                <div class="card-body text-center">
                                                    <h6 class="card-title text-truncate" style="max-width: 100%;">{{ $image->title }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                
                </div>



            </div>

        </div>
        <!-- Content End -->

        @include('admin.includes.footer')

        <!-- Add Image Modal -->
<div class="modal fade" id="newImage">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
        <h4 class="modal-title">Adding New Image to {{ $room->room_type ?? '' }}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <form class="form" action="{{ route('addRoomImage') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-lg-6 col-sm-12">
                            <label for="image" class="form-label">Upload Images</label>
                            <div class="input-group">
                                <input type="hidden" name="hotel_room_id" value="{{ $room->id }}">
                                <input type="file" name="image[]" class="form-control" id="image" multiple>
                            </div>
                            <small class="text-muted">You can upload one or multiple images. Max size: 2MB per image.</small>
                        </div>
                    </div>
                </div>
            
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary text-black">
                        <i class="fa fa-save"></i> Upload
                    </button>
                </div>
            </form>
            
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        </div>

    </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this image? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="" id="deleteConfirmBtn" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(deleteUrl) {
        document.getElementById('deleteConfirmBtn').setAttribute('href', deleteUrl);
        var confirmModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        confirmModal.show();
    }
</script>

 @endsection