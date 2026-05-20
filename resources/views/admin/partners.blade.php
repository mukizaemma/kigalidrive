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
                        <h6 class="mb-0">Our Partners</h6>
                        <div class="col-dm3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#slideImage">
                                Add New Partner
                              </button>
                        </div>
                        {{-- <a href="">Show All</a> --}}
                    </div>
                    <div class="table-responsive">

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Partner</th>
                                <th>Website</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Description</th>
                                <th>Cover Image</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($partners as $rs)
                            <tr>
                                <td>
                                    <a href="{{ route('editPartner', $rs->id) }}">{{ $rs->name }}</a>
                                </td>

                                <td>
                                    @if($rs->website)
                                    <a href="{{ $rs->website }}" target="_blank">{{ $rs->website }}</a>
                                    @else
                                    —
                                    @endif
                                </td>

                                <td>{{ $rs->phone ?? '—' }}</td>

                                <td>{{ $rs->email ?? '—' }}</td>

                                <td>{!! Str::limit($rs->description, 80) !!}</td>

                                <td>
                                    <a href="{{ route('editPartner', $rs->id) }}">
                                        <img src="{{ asset('storage/images/partners/' . $rs->image) }}"
                                            alt="Partner Logo"
                                            width="120px"
                                            style="border-radius:5px;">
                                    </a>
                                </td>

                                <td>
                                    @if($rs->status == 'Active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('editPartner', $rs->id) }}" 
                                        class="btn btn-primary btn-sm text-black me-2">
                                            Edit
                                        </a>

                                        <a href="{{ route('destroyPartner', $rs->id) }}"
                                        class="btn btn-danger btn-sm text-black"
                                        onclick="return confirm('Are you sure you want to delete this partner?')">
                                            Delete
                                        </a>
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
        <div class="modal fade" id="slideImage">
            <div class="modal-dialog modal-lg">
            <form class="form" action="{{ route('savePartner') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Adding New Partner</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="form-body">

                            <!-- Partner Logo -->
                            <div class="row mb-4">
                                <div class="col-lg-6 col-sm-12">
                                    <label>Partner Cover Image</label>
                                    <label id="projectinput7" class="file center-block">
                                        <input type="file" id="image" name="image" required>
                                        <span class="file-custom"></span>
                                    </label>
                                </div>
                            </div>

                            <!-- Name & Website -->
                            <div class="row mb-3">
                                <div class="col-lg-6 col-sm-12">
                                    <label>Company Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Company name" required>
                                </div>

                                <div class="col-lg-6 col-sm-12">
                                    <label>Website URL</label>
                                    <input type="text" class="form-control" name="website" placeholder="Eg: https://www.example.com">
                                </div>
                            </div>

                            <!-- Partner Type -->
                            <div class="row mb-3">
                                <div class="col-lg-6 col-sm-12">
                                    <label>Partner Type</label>
                                    <select class="form-control" name="type" required>
                                        <option value="">Select type</option>
                                        <option value="hotel">Hotel</option>
                                        <option value="apartment">Apartment</option>
                                        <option value="car_rental">Car Rental</option>
                                    </select>
                                </div>

                                <div class="col-lg-6 col-sm-12">
                                    <label>Status</label>
                                    <select class="form-control" name="status" required>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Email & Phone -->
                            <div class="row mb-3">
                                <div class="col-lg-6 col-sm-12">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="example@email.com">
                                </div>

                                <div class="col-lg-6 col-sm-12">
                                    <label>Phone</label>
                                    <input type="text" class="form-control" name="phone" placeholder="+250 7XX XXX XXX">
                                </div>
                            </div>

                            <!-- Address & City -->
                            <div class="row mb-3">
                                <div class="col-lg-6 col-sm-12">
                                    <label>Address</label>
                                    <input type="text" class="form-control" name="address" placeholder="Street, Building, etc">
                                </div>

                                <div class="col-lg-6 col-sm-12">
                                    <label>City</label>
                                    <input type="text" class="form-control" name="city" placeholder="Kigali, Musanze, Rubavu…">
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="form-label">Partnership Description</label>
                                    <textarea id="partnerDescription" rows="5" class="form-control" name="description"></textarea>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary text-black">
                            <i class="fa fa-save"></i> Add New
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>

                </div>
            </form>

            </div>
        </div>
        @include('admin.includes.footer')
 @endsection