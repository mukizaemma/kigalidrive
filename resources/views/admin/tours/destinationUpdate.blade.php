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
                        <a href="{{ route('getTripDestinations') }}" class="btn btn-dark">Back</a>
                    </li>
                    <li class="nav-item ">
                        <li class="breadcrumb-item active">Updating <strong> {{$destination->name}}</strong></li>
                    </li>
                    <li class="nav-item ms-auto">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newImage">
                            Add New Image to Gallery
                        </button>
                    </li>
                </ul>

                <div class="container-fluid px-4">

                    <div class="card mb-4">
                        <div class="card-body">
                            <form class="form" action="{{ route('updateTripDestination',$destination->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <div class="col-lg-12">
                                            <label for="name" class="form-label">Destination Name</label>
                                            <input type="text" name="name" class="form-control" id="name" value="{{$destination->name}}">
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-lg-4 col-sm-12">
                                            <label for="image" class="form-label">Current Cover Image</label>
                                            @if($destination->image)
                                                <img src="{{ asset('storage/images/trip-destinations/' . $destination->image) }}" alt="" width="120px" class="d-block">
                                            @else
                                                <span class="text-muted">No image</span>
                                            @endif
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <label for="image" class="form-label">Change Cover Image</label>
                                            <input type="file" name="image" class="form-control" id="image">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-lg-6 col-sm-12">
                                            <label for="location" class="form-label">Location</label>
                                            <input type="text" name="location" class="form-control" id="location" value="{{ $destination->location }}">
                                        </div>
                                        <div class="col-lg-6 col-sm-12">
                                            <label for="status" class="form-label">Status</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="Active" {{ $destination->status == 'Active' ? 'selected' : '' }}>Active</option>
                                                <option value="Inactive" {{ $destination->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                            
                                    <div class="row">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea id="description" rows="5" class="form-control" name="description">{!!$destination->description!!}</textarea>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-actions mt-5">
                                    <button type="submit" class="btn btn-primary text-black">
                                        <i class="fa fa-save"></i> Update Destination
                                    </button>
                                    <a href="{{ route('getTripDestinations') }}" class="btn btn-light">Back to Destinations</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Image Gallery Section -->
                    <div class="card mt-5">
                        <div class="card-header bg-dark text-white d-flex align-items-center">
                            <h5 class="mb-0">
                                <span style="color: yellow">{{ $totalImages }}</span> Gallery Images
                            </h5>
                            <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#newImage">
                                Add New Image
                            </button>
                        </div>
                        
                        <div class="card-body">
                            @if($images->count() == 0)
                                <p class="text-muted">No images in gallery yet. Add some images to showcase this destination.</p>
                            @else
                                <div class="row">
                                    @foreach($images as $image)
                                    <div class="col-md-4 col-sm-6 mb-4">
                                        <div class="card shadow" style="position: relative; overflow: hidden; transition: 0.3s;">
                                            <img src="{{ asset('storage/images/trip-destinations/' . $image->image) }}" 
                                                class="card-img-top rounded" alt="Destination Image" style="width: 100%; height: 200px; object-fit: cover;">
                                            <button onclick="confirmDelete('{{ route('deleteTripDestinationImage', $image->id) }}')" 
                                                    style="position: absolute; top: 10px; right: 10px; background: rgba(255, 0, 0, 0.8); color: white; border: none; padding: 6px 10px; border-radius: 50%; cursor: pointer; transition: 0.3s;">
                                                ×
                                            </button>
                                            <div class="card-body text-center">
                                                @if($image->caption)
                                                    <p class="card-text text-truncate" style="max-width: 100%;">{{ $image->caption }}</p>
                                                @endif
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

        <!-- Modal for Adding New Image -->
        <div class="modal fade" id="newImage">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add New Image to Gallery</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('addTripDestinationImage') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="trip_destination_id" value="{{ $destination->id }}">
                            
                            <div class="mb-3">
                                <label for="image" class="form-label">Select Images</label>
                                <input type="file" name="image[]" class="form-control" id="image" accept="image/*" multiple required>
                                <small class="text-muted">You can select multiple images at once.</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="caption" class="form-label">Caption (Optional)</label>
                                <input type="text" name="caption" class="form-control" id="caption" placeholder="Image caption">
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-upload"></i> Upload Images
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function confirmDelete(url) {
            if (confirm('Are you sure you want to delete this image?')) {
                window.location.href = url;
            }
        }
        </script>

        @include('admin.includes.footer')
@endsection








