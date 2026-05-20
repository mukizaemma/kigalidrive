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
                        <a href="{{ route('getTrips') }}" class="btn btn-dark">Back</a>
                    </li>
                    <li class="nav-item ">
                        
                    <li class="breadcrumb-item active">Updating <strong> {{$trip->title}}</strong></li>

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
                            <form class="form" action="{{ route('updateTrip',$trip->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <div class="col-lg-12">
                                            <label for="title" class="form-label">Title</label>
                                            <input type="text" name="title" class="form-control"
                                                id="title" value="{{$trip->title}}">
                                        </div>

                                    </div>

                                    <div  class="row mt-3">
                                        <div class="col-lg-4 col-sm-12">
                                            <label for="image" class="form-label">Cover Image</label>
                                            @if($trip->image)
                                                <img src="{{ asset('storage/images/trips/' . $trip->image) }}" alt="" width="120px" style="object-fit: cover; border-radius: 4px;">
                                            @else
                                                <span class="text-muted">No image</span>
                                            @endif
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <label for="image" class="form-label">Change Cover Image</label>
                                            <div class="input-group">

                                                <input type="file" name="image" class="form-control" id="image">

                                            </div>
                                        </div>

                                    </div>



                                    <div class="row">
                                        <div class="mb-3">
                                            <label for="summernote" class="form-label">Description</label>
                                            <textarea id="tripDescription" rows="5" class="form-control" name="description">{!!$trip->description!!}</textarea>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-lg-4 col-sm-12">
                                            <label for="location" class="form-label">Location</label>
                                            <input type="text" name="location" class="form-control" id="location" value="{{ $trip->location }}">
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <label for="duration" class="form-label">Duration</label>
                                            <input type="text" name="duration" class="form-control" id="duration"  value="{{ $trip->duration }}">
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <label for="languages" class="form-label">Languages</label>
                                            <input type="text" name="languages" class="form-control" id="languages"  value="{{ $trip->languages }}">
                                        </div>
                                    </div>
                            
                                    <div class="row mb-3">
                                        <div class="col-lg-6 col-sm-12">
                                            <label for="trip_destination_id" class="form-label">Trip Destination <span class="text-primary">(Recommended)</span></label>
                                            @if(isset($tripDestinations) && $tripDestinations->isNotEmpty())
                                                <select name="trip_destination_id" id="trip_destination_id" class="form-control">
                                                    <option value="">-- Select Trip Destination --</option>
                                                    @foreach($tripDestinations as $dest)
                                                        <option value="{{ $dest->id }}" {{ $trip->trip_destination_id == $dest->id ? 'selected' : '' }}>{{ $dest->name }}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <input type="text" name="trip_destination_id" class="form-control" id="trip_destination_id" value="{{ $trip->trip_destination_id }}" placeholder="Trip Destination ID">
                                                <small class="text-muted">No trip destinations found. <a href="{{ route('getTripDestinations') }}">Create one first</a></small>
                                            @endif
                                        </div>
                                        <div class="col-lg-2 col-sm-12">
                                            <label for="currency" class="form-label">Currency</label>
                                            <input type="text" name="currency" class="form-control" id="currency"  value="{{ $trip->currency }}">
                                        </div>
                                        <div class="col-lg-2 col-sm-12">
                                            <label for="maxPeople" class="form-label">Max People</label>
                                            <input type="text" name="maxPeople" class="form-control" id="maxPeople"  value="{{ $trip->maxPeople }}">
                                        </div>
                                        <div class="col-lg-2 col-sm-12">
                                            <label for="minAge" class="form-label">Min Age</label>
                                            <input type="text" name="minAge" class="form-control" id="minAge"  value="{{ $trip->minAge }}">
                                        </div>
                                    </div>
                            
                                    <div class="row mb-3">
                                        <div class="col-lg-4 col-sm-12">
                                            <label for="price" class="form-label">Price</label>
                                            <input type="number" name="price" class="form-control" id="price"  value="{{ $trip->price }}">
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <label for="couplePrice" class="form-label">Couple Price</label>
                                            <input type="number" name="couplePrice" class="form-control" id="couplePrice"  value="{{ $trip->couplePrice }}">
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <label for="status" class="form-label">Status</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="Active" {{ $trip->status == 'Active' ? 'selected' : '' }}>Active</option>
                                                <option value="Inactive" {{ $trip->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>


                                </div>

                                <div class="form-actions mt-5">
                                    <button type="submit" class="btn btn-primary text-black">
                                        <i class="fa fa-save"></i> Update Facility Details
                                    </button>

                                    <a href="{{ route('getFacilities') }}" class="btn btn-light">Back to Facilities</a>


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
                                                <img src="{{ asset('storage/images/trips/' . $image->image) }}" 
                                                    class="card-img-top rounded" alt="Blog Image" style="width: 100%; height: auto;">
                                                <button onclick="confirmDelete('{{ route('deleteTripImage', $image->id) }}')" 
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
        <h4 class="modal-title">Adding New Image to {{ $trip->title ?? '' }}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <form class="form" action="{{ route('addTripImage',['id'=>$trip->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-lg-6 col-sm-12">
                            <label for="image" class="form-label">Upload Images</label>
                            <div class="input-group">
                                <input type="hidden" name="trip_id" value="{{ $trip->id }}">
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