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
                        <h6 class="mb-0">Trip Destinations</h6>
                        <div class="col-dm3">
                            <a href="{{ route('getTrips') }}" class="btn btn-secondary float-left me-2">
                                <i class="fa fa-route"></i> View Activities
                            </a>
                            <button type="button" class="btn btn-primary float-left" data-bs-toggle="modal" data-bs-target="#NewDestination">
                                <i class="fa fa-plus"></i> Add New Destination
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-dark">
                                    <th scope="col">Destination Name</th>
                                    <th scope="col">Cover Image</th>
                                    <th scope="col">Location</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Activities</th>
                                    <th scope="col">Gallery</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($destinations as $destination)
                                <tr>
                                    <td><a href="{{ route('editTripDestination',['id'=>$destination->id]) }}">{{ $destination->name }}</a> 
                                    <br> <span class="badge bg-{{ $destination->status == 'Active' ? 'success' : 'secondary' }}">{{ $destination->status }}</span>
                                    </td>
                                    <td>
                                        @if($destination->image)
                                            <img src="{{ asset('storage/images/trip-destinations/' .$destination->image) }}" alt="" width="120px">
                                        @else
                                            <span class="text-muted">No image</span>
                                        @endif
                                    </td>
                                    <td>{{ $destination->location ?? 'N/A' }}</td>
                                    <td>{!! Str::words($destination->description, 20, '...') !!}</td>
                                    <td>
                                        @if($destination->trips->count() > 0)
                                            <a href="{{ route('getTrips') }}?destination={{ $destination->id }}" class="badge bg-info text-white">
                                                {{ $destination->trips->count() }} Activities
                                            </a>
                                        @else
                                            <span class="badge bg-secondary">0 Activities</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $destination->images->count() }} Images</span>
                                    </td>
                                    <td>
                                        <div class="bg-light rounded ">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('editTripDestination',['id'=>$destination->id]) }}" class="btn btn-info" title="Edit & Manage Gallery"><i class="fa fa-images"></i></a>
                                                <a href="{{ route('deleteTripDestination',['id'=>$destination->id]) }}" class="btn btn-warning" onclick="return confirm('Are you sure to delete this destination? All activities under it will also be affected.')" title="Delete"><i class="fa fa-trash"></i></a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <!-- Content End -->

        <!-- The Modal -->
        <div class="modal fade" id="NewDestination">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
        
                <!-- Modal Header -->
                <div class="modal-header">
                <h4 class="modal-title">Adding New Trip Destination</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
        
                <!-- Modal body -->
                <div class="modal-body">
                <form class="form" action="{{ route('storeTripDestination') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="name" class="form-label">Destination Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Eg: Kigali City Tours" value="{{ old('name') }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-8 col-sm-12">
                                <label for="image" class="form-label">Cover Image</label>
                                <input type="file" name="image" class="form-control" id="image" accept="image/*">
                            </div>

                            <div class="col-lg-4 col-sm-12">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" name="location" class="form-control" id="location" placeholder="Eg: Kigali, Rwanda" value="{{ old('location') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-6 col-sm-12">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea id="description" rows="5" class="form-control" name="description" placeholder="Brief description of the destination">{{ old('description') }}</textarea>
                            </div>
                        </div>

                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary text-black">
                            <i class="fa fa-save"></i> Save
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
        @include('admin.includes.footer')
@endsection

