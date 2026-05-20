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
                {{-- <h1 class="mt-4">Dashboard</h1> --}}
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Updates/ Partners</li>
                </ol>


                <div class="container-fluid px-4">

                    <div class="card mb-4">

                        <div class="card-body">
                        <form class="form" action="{{ url('updatePartner', $partner->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="modal-body">

                                <!-- Partner Name & Website -->
                                <div class="row mb-3">
                                    <div class="col-lg-6 col-sm-12">
                                        <label class="form-label">Partner / Company Name</label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ $partner->name }}" required>
                                    </div>

                                    <div class="col-lg-6 col-sm-12">
                                        <label class="form-label">Website</label>
                                        <input type="text" name="website" class="form-control"
                                            value="{{ $partner->website }}">
                                    </div>
                                </div>

                                <!-- Partner Type -->
                                <div class="row mb-3">
                                    <div class="col-lg-6 col-sm-12">
                                        <label class="form-label">Partner Type</label>
                                        <select name="type" class="form-control" required>
                                            <option value="">-- Select Type --</option>
                                            <option value="hotel" {{ $partner->type == 'hotel' ? 'selected' : '' }}>Hotel</option>
                                            <option value="apartment" {{ $partner->type == 'apartment' ? 'selected' : '' }}>Apartment</option>
                                            <option value="car_rental" {{ $partner->type == 'car_rental' ? 'selected' : '' }}>Car Rental</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-6 col-sm-12">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-control" required>
                                            <option value="Active" {{ $partner->status == 'Active' ? 'selected' : '' }}>Active</option>
                                            <option value="Inactive" {{ $partner->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Contact Info -->
                                <div class="row mb-3">
                                    <div class="col-lg-6 col-sm-12">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control"
                                            value="{{ $partner->email }}">
                                    </div>

                                    <div class="col-lg-6 col-sm-12">
                                        <label class="form-label">Phone</label>
                                        <input type="text" name="phone" class="form-control"
                                            value="{{ $partner->phone }}">
                                    </div>
                                </div>

                                <!-- Address & City -->
                                <div class="row mb-3">
                                    <div class="col-lg-6 col-sm-12">
                                        <label class="form-label">Address</label>
                                        <input type="text" name="address" class="form-control"
                                            value="{{ $partner->address }}">
                                    </div>

                                    <div class="col-lg-6 col-sm-12">
                                        <label class="form-label">City</label>
                                        <input type="text" name="city" class="form-control"
                                            value="{{ $partner->city }}">
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="row mb-3">
                                    <div class="col-lg-12">
                                        <label class="form-label">Partnership Description</label>
                                        <textarea rows="5" class="form-control" id="partnerDescription" name="description">{!! $partner->description !!}</textarea>
                                    </div>
                                </div>

                                <!-- Image Section -->
                                <div class="row mt-3">
                                    <div class="col-lg-4 col-sm-12">
                                        <label class="form-label">Current Cover Image</label><br>
                                        <img src="{{ asset('storage/images/partners/' . $partner->image) }}" 
                                            alt="Partner Logo" width="120px">
                                    </div>

                                    <div class="col-lg-4 col-sm-12">
                                        <label class="form-label">
                                            Change Cover Image<br>
                                        </label>
                                        <input type="file" name="image" class="form-control">
                                    </div>

                                    <div class="col-lg-4 col-sm-12">
                                        <label class="form-label">Confirm Status</label>
                                        <select name="status" class="form-control">
                                            <option value="Active" {{ $partner->status == 'Active' ? 'selected' : '' }}>Active</option>
                                            <option value="Inactive" {{ $partner->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <!-- Submit -->
                            <div class="form-actions mt-3">
                                <button type="submit" class="btn btn-primary text-black">
                                    <i class="fa fa-save"></i> Save Changes
                                </button>

                                <a href="{{ route('getPartners') }}" class="btn btn-dark">Back</a>
                            </div>

                        </form>

                        </div>
                    </div>


                </div>

            </div>

        </div>
        <!-- Content End -->

        @include('admin.includes.footer')
 @endsection