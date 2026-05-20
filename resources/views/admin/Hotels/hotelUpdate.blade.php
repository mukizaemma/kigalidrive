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
                        
                    <li class="breadcrumb-item active"> Updating <strong>  {{ $hotel->room_type}}</strong></li>

                    </li>
                    <li class="nav-item ms-auto">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newImage">
                            Add New Image
                        </button>
                    </li>
                </ul>

                <div class="container-fluid px-4">

                <div class="card mb-4">
                    <div class="card-body">
                        <form class="form" action="{{ route('updateHotel', $hotel->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="modal-body">

                                <!-- Hotel Name -->
                                <div class="row mb-3">
                                    <div class="col-lg-12">
                                        <label class="form-label">Hotel Name</label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ $hotel->name }}" required>
                                    </div>
                                </div>

                                <!-- Stars + Status -->
                                <div class="row mb-3">

                                    <div class="col-lg-4 col-sm-12">
                                        <label class="form-label">Property Type</label>
                                        <select name="type" class="form-control">
                                            <option value="">Not Ranked</option>
                                            <option value="hotel" {{ $hotel->stars == 'hotel' ? 'selected' : '' }}>Hotel</option>
                                            <option value="apartment" {{ $hotel->stars == 'apartment' ? 'selected' : '' }}>Apartment</option>
                                            <option value="guest_house" {{ $hotel->stars == 'guest_house' ? 'selected' : '' }}>Guest House</option>
                                            <option value="motel" {{ $hotel->stars == 'motel' ? 'selected' : '' }}>Motel</option>
                                            <option value="resort" {{ $hotel->stars == 'resort' ? 'selected' : '' }}>Resort</option>
                                            <option value="resort" {{ $hotel->stars == 'lodge' ? 'selected' : '' }}>Lodge</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-sm-12">
                                        <label class="form-label">Hotel Rating (Stars)</label>
                                        <select name="stars" class="form-control">
                                            <option value="">Not Ranked</option>
                                            <option value="1" {{ $hotel->stars == '1' ? 'selected' : '' }}>1 Star</option>
                                            <option value="2" {{ $hotel->stars == '2' ? 'selected' : '' }}>2 Stars</option>
                                            <option value="3" {{ $hotel->stars == '3' ? 'selected' : '' }}>3 Stars</option>
                                            <option value="4" {{ $hotel->stars == '4' ? 'selected' : '' }}>4 Stars</option>
                                            <option value="5" {{ $hotel->stars == '5' ? 'selected' : '' }}>5 Stars</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-sm-12">
                                        <label class="form-label">District</label>
                                        <select name="location" class="form-control" required>
                                            <option value="">Select District</option>

                                            <!-- Kigali -->
                                            <optgroup label="Kigali City">
                                                <option value="Gasabo" {{ $hotel->location == 'Gasabo' ? 'selected' : '' }}>Gasabo</option>
                                                <option value="Kicukiro" {{ $hotel->location == 'Kicukiro' ? 'selected' : '' }}>Kicukiro</option>
                                                <option value="Nyarugenge" {{ $hotel->location == 'Nyarugenge' ? 'selected' : '' }}>Nyarugenge</option>
                                            </optgroup>

                                            <!-- Northern -->
                                            <optgroup label="Northern Province">
                                                @foreach(['Burera','Gakenke','Gicumbi','Musanze','Rulindo'] as $district)
                                                    <option value="{{ $district }}" {{ $hotel->location == $district ? 'selected' : '' }}>
                                                        {{ $district }}
                                                    </option>
                                                @endforeach
                                            </optgroup>

                                            <!-- Southern -->
                                            <optgroup label="Southern Province">
                                                @foreach(['Gisagara','Huye','Kamonyi','Muhanga','Nyamagabe','Nyanza','Nyaruguru','Ruhango'] as $district)
                                                    <option value="{{ $district }}" {{ $hotel->location == $district ? 'selected' : '' }}>
                                                        {{ $district }}
                                                    </option>
                                                @endforeach
                                            </optgroup>

                                            <!-- Eastern -->
                                            <optgroup label="Eastern Province">
                                                @foreach(['Bugesera','Gatsibo','Kayonza','Kirehe','Ngoma','Nyagatare','Rwamagana'] as $district)
                                                    <option value="{{ $district }}" {{ $hotel->location == $district ? 'selected' : '' }}>
                                                        {{ $district }}
                                                    </option>
                                                @endforeach
                                            </optgroup>

                                            <!-- Western -->
                                            <optgroup label="Western Province">
                                                @foreach(['Karongi','Ngororero','Nyabihu','Nyamasheke','Rubavu','Rutsiro','Rusizi'] as $district)
                                                    <option value="{{ $district }}" {{ $hotel->location == $district ? 'selected' : '' }}>
                                                        {{ $district }}
                                                    </option>
                                                @endforeach
                                            </optgroup>

                                        </select>
                                    </div>


                                </div>

                                <!-- Contact -->
                                <div class="row mb-3">
                                    <div class="col-lg-4 col-sm-12">
                                        <label class="form-label">City / Sector</label>
                                        <input type="text" name="city" class="form-control"
                                            value="{{ $hotel->city }}">
                                    </div>
                                    <div class="col-lg-4 col-sm-12">
                                        <label class="form-label">Phone Number</label>
                                        <input type="text" name="phone" class="form-control"
                                            value="{{ $hotel->phone }}">
                                    </div>

                                    <div class="col-lg-4 col-sm-12">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" name="email" class="form-control"
                                            value="{{ $hotel->email }}">
                                    </div>
                                </div>

                                <!-- Map Coordinates -->
                                <div class="row mb-3">
                                    <div class="col-lg-4 col-sm-12">
                                        <label class="form-label">Latitude</label>
                                        <input type="text" name="latitude" class="form-control"
                                            value="{{ $hotel->latitude }}">
                                    </div>

                                    <div class="col-lg-4 col-sm-12">
                                        <label class="form-label">Longitude</label>
                                        <input type="text" name="longitude" class="form-control"
                                            value="{{ $hotel->longitude }}">
                                    </div>
                                    <div class="col-lg-4 col-sm-12">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-control">
                                            <option value="Active" {{ $hotel->status == 'Active' ? 'selected' : '' }}>Active</option>
                                            <option value="Inactive" {{ $hotel->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="row mb-3">
                                    <div class="col-lg-12">
                                        <label class="form-label">Hotel Description</label>
                                        <textarea rows="5" class="form-control" name="description" id="hotelDescription">{!! $hotel->description !!}</textarea>
                                    </div>
                                </div>

                                <!-- Cover Image -->
                                <div class="row mb-3">
                                    <div class="col-lg-4">
                                        <label class="form-label">Current Cover Image</label><br>
                                        <img src="{{ asset('storage/images/hotels/' . $hotel->image) }}" width="150" class="rounded">
                                    </div>

                                    <div class="col-lg-4">
                                        <label class="form-label">Change Cover Image</label>
                                        <input type="file" name="image" class="form-control">
                                    </div>
                                </div>

                            </div>

                            <!-- Submit -->
                            <div class="form-actions mt-4">
                                <button type="submit" class="btn btn-primary text-black">
                                    <i class="fa fa-save"></i> Save Changes
                                </button>
                                <a href="{{ route('getHotels') }}" class="btn btn-light">Back to Hotels</a>
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
                                                <img src="{{ asset('storage/images/hotels/' . $image->image) }}" 
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
        <h4 class="modal-title">Adding New Image to {{ $hotel->name ?? '' }}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <form class="form" action="{{ route('addRoomImage',['id'=>$hotel->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-lg-6 col-sm-12">
                            <label for="image" class="form-label">Upload Images</label>
                            <div class="input-group">
                                <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                                <input type="file" name="image[]" class="form-control" id="image" multiple>
                            </div>
                            <small class="text-muted">You can upload one or multiple images.</small>
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