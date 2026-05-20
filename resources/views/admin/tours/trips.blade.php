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
                        <h6 class="mb-0">Trip Activities (Tours)</h6>
                        <div class="col-dm3">
                            <a href="{{ route('getTripDestinations') }}" class="btn btn-secondary float-left me-2">
                                <i class="fa fa-map-marked-alt"></i> Manage Destinations
                            </a>
                            <button type="button" class="btn btn-primary float-left" data-bs-toggle="modal" data-bs-target="#NewProduct">
                                <i class="fa fa-plus"></i> Add New Activity
                            </button>
                        </div>
                    </div>
                    @if(isset($selectedDestination) && $selectedDestination)
                        @php
                            $selectedDest = $tripDestinations->firstWhere('id', $selectedDestination);
                        @endphp
                        @if($selectedDest)
                            <div class="alert alert-info mb-3">
                                <i class="fa fa-filter"></i> Filtered by: <strong>{{ $selectedDest->name }}</strong> 
                                <a href="{{ route('getTrips') }}" class="btn btn-sm btn-light ms-2">Clear Filter</a>
                            </div>
                        @endif
                    @endif
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-dark">
                                    <th scope="col">Activity Name</th>
                                    <th scope="col">Trip Destination</th>
                                    <th scope="col">Cover Image</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($trips as $rs)
                                <tr>
                                    <td>
                                        <a href="{{ route('editTrip',['id'=>$rs->id]) }}">{{ $rs->title }}</a> 
                                        <br> 
                                        <small class="text-muted">
                                            <i class="fa fa-images"></i> {{$rs->images->count()}} Images
                                        </small>
                                    </td>
                                    <td>
                                        @if($rs->tripDestination)
                                            <a href="{{ route('editTripDestination', ['id' => $rs->tripDestination->id]) }}" class="badge bg-primary">
                                                {{ $rs->tripDestination->name }}
                                            </a>
                                        @elseif($rs->destination)
                                            <span class="badge bg-secondary">Legacy: {{ $rs->destination->name }}</span>
                                        @else
                                            <span class="badge bg-warning">No Destination</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($rs->image)
                                            <img src="{{ asset('storage/images/trips/' .$rs->image) }}" alt="" width="120px" style="object-fit: cover; border-radius: 4px;">
                                        @else
                                            <span class="text-muted">No image</span>
                                        @endif
                                    </td>
                                    <td>{!! Str::words($rs->description, 50, '...') !!}</td>
                                    <td>
                                        <span class="badge bg-{{ $rs->status == 'Active' ? 'success' : 'secondary' }}">
                                            {{ $rs->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="bg-light rounded ">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('editTrip',['id'=>$rs->id]) }}" class="btn btn-info" title="Edit & Manage Gallery"><i class="fa fa-images"></i></a>
                                                <a href="{{ route('deleteTrip',['id'=>$rs->id]) }}" class="btn btn-warning" onclick="return confirm('Are you sure to delete this activity?')" title="Delete"><i class="fa fa-trash"></i></a>
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
        <div class="modal fade" id="NewProduct">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
        
                <!-- Modal Header -->
                <div class="modal-header">
                <h4 class="modal-title">Adding New Activity</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
        
                <!-- Modal body -->
                <div class="modal-body">
                <form class="form" action="{{ route('storeTrip') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <div class="row mb-3">
                            <div class="col-lg-6 col-sm-12">
                                <label for="program_id" class="form-label">Service</label>

                                @if(isset($programs) && $programs->isNotEmpty())
                                    <select name="program_id" id="program_id" class="form-control">
                                        <option value="">-- Select Service --</option>
                                        @foreach($programs as $prog)
                                            <option value="{{ $prog->id }}" @if(old('program_id') == $prog->id) selected @endif>{{ $prog->name ?? $prog->title ?? $prog->id }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="text" name="program_id" class="form-control" id="program_id" placeholder="Program id or name" value="{{ old('program_id') }}">
                                @endif
                            </div>

                            <div class="col-lg-6 col-sm-12">
                                <label for="trip_destination_id" class="form-label">Trip Destination <span class="text-primary">(Recommended)</span></label>

                                @if(isset($tripDestinations) && $tripDestinations->isNotEmpty())
                                    <select name="trip_destination_id" id="trip_destination_id" class="form-control">
                                        <option value="">-- Select Trip Destination --</option>
                                        @foreach($tripDestinations as $dest)
                                            <option value="{{ $dest->id }}" @if(old('trip_destination_id') == $dest->id) selected @endif>{{ $dest->name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="text" name="trip_destination_id" class="form-control" id="trip_destination_id" placeholder="Trip Destination ID" value="{{ old('trip_destination_id') }}">
                                    <small class="text-muted">No trip destinations found. <a href="{{ route('getTripDestinations') }}">Create one first</a></small>
                                @endif
                            </div>



                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="title" class="form-label">Trip Title</label>
                                <input type="text" name="title" class="form-control" id="title" placeholder="Eg: Heading" value="{{ old('title') }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-8 col-sm-12">
                                <label for="image" class="form-label">Cover Image</label>
                                <input type="file" name="image" class="form-control" id="image" accept="image/*">
                            </div>

                            <div class="col-lg-4 col-sm-12">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" name="location" class="form-control" id="location" placeholder="Eg: Location" value="{{ old('location') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-4 col-sm-12">
                                <label for="duration" class="form-label">Duration</label>
                                <input type="text" name="duration" class="form-control" id="duration" placeholder="Eg: 7 Days" value="{{ old('duration') }}">
                            </div>

                            <div class="col-lg-4 col-sm-12">
                                <label for="languages" class="form-label">Languages</label>
                                <input type="text" name="languages" class="form-control" id="languages" placeholder="Eg: English, Spanish" value="{{ old('languages') }}">
                            </div>

                            <div class="col-lg-4 col-sm-12">
                                <label for="currency" class="form-label">Currency</label>
                                <input type="text" name="currency" class="form-control" id="currency" placeholder="Eg: USD" value="{{ old('currency') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-4 col-sm-12">
                                <label for="maxPeople" class="form-label">Max People</label>
                                <input type="text" name="maxPeople" class="form-control" id="maxPeople" placeholder="Eg: 50" value="{{ old('maxPeople') }}">
                            </div>

                            <div class="col-lg-4 col-sm-12">
                                <label for="minAge" class="form-label">Min Age</label>
                                <input type="text" name="minAge" class="form-control" id="minAge" placeholder="Eg: 18" value="{{ old('minAge') }}">
                            </div>

                            <div class="col-lg-4 col-sm-12">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" name="price" class="form-control" id="price" placeholder="Eg: 1000" value="{{ old('price', 0) }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-6 col-sm-12">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <label for="tripDescription" class="form-label">Trip Description</label>
                                <textarea id="tripDescription" rows="5" class="form-control" name="description" placeholder="Brief description of the trip">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        {{-- <div class="row mb-3">
                            <div class="col-lg-12">
                                <label for="itinerary" class="form-label">Itinerary</label>
                                <textarea id="itinerary" rows="5" class="form-control" name="itinerary">{{ old('itinerary') }}</textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <label for="expectations" class="form-label">Expectations</label>
                                <textarea id="expectations" rows="5" class="form-control" name="expectations">{{ old('expectations') }}</textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <label for="recommendations" class="form-label">Recommendations</label>
                                <textarea id="recommendations" rows="5" class="form-control" name="recommendations">{{ old('recommendations') }}</textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <label for="inclusions" class="form-label">Inclusions</label>
                                <textarea id="inclusions" rows="5" class="form-control" name="inclusions">{{ old('inclusions') }}</textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <label for="exclusions" class="form-label">Exclusions</label>
                                <textarea id="exclusions" rows="5" class="form-control" name="exclusions">{{ old('exclusions') }}</textarea>
                            </div>
                        </div> --}}

                        {{-- added_by (hidden if user logged in) --}}
                        @if(auth()->check())
                            <input type="hidden" name="added_by" value="{{ auth()->id() }}">
                        @else
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <label for="added_by" class="form-label">Added by (user id)</label>
                                    <input type="text" name="added_by" id="added_by" class="form-control" value="{{ old('added_by') }}" placeholder="User id who adds this trip">
                                </div>
                            </div>
                        @endif

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