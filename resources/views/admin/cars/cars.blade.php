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
                        <h6 class="mb-0">Our Recent Published Cars</h6>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.carBookings.index') }}" class="btn btn-info">
                                <i class="fa fa-calendar-check me-2"></i>Car Bookings
                                @if(isset($pendingBookings) && $pendingBookings > 0)
                                    <span class="badge bg-danger ms-1">{{ $pendingBookings }}</span>
                                @endif
                            </a>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#NewProduct">
                                <i class="fa fa-plus me-2"></i>Add New Car
                            </button>
                        </div>
                        {{-- <a href="">Show All</a> --}}
                    </div>
                    <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Advert</th>
                                <th>Cover</th>
                                <th>Pricing</th>
                                <th>Description</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($cars as $rs)

                                <tr>
                                    {{-- Advert --}}
                                    <td>
                                        <strong>{{ $rs->name }}</strong>
                                    </td>

                                    {{-- Cover Image --}}
                                    <td>
                                        <a href="{{ route('editCar', $rs->id) }}">
                                            <img src="{{ asset('storage/images/cars/' . $rs->image) }}"
                                                alt="Hotel Image"
                                                width="80px"
                                                class="rounded shadow-sm">
                                        </a>
                                    </td>

                                    {{-- Price --}}
                                    <td>
                                        @if($rs->price_per_day)
                                            <div><strong>{{ number_format($rs->price_per_day) }}</strong> / day</div>
                                        @endif

                                        @if($rs->price_per_month)
                                            <div>{{ number_format($rs->price_per_month) }} / month</div>
                                        @endif

                                        @if($rs->price_to_buy)
                                            <div class="text-success">
                                                Buy: {{ number_format($rs->price_to_buy) }}
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Description --}}
                                    <td>
                                        {{ Str::limit(strip_tags($rs->description), 80) }}
                                    </td>

                                    {{-- Actions --}}
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('editCar', $rs->id) }}"
                                            class="btn btn-sm btn-info"
                                            title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <a href="{{ route('editCar', $rs->id) }}"
                                            class="btn btn-sm btn-primary"
                                            title="Manage Images">
                                                <i class="fa fa-images"></i>
                                            </a>

                                            <a href="{{ route('deleteCar', $rs->id) }}"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete this car?')"
                                            title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        No cars found
                                    </td>
                                </tr>
                            @endforelse
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
                <h4 class="modal-title">Adding New Car</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
        
                <!-- Modal body -->
                <div class="modal-body">
                    <form class="form" action="{{ route('storeCar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">

                            <div class="row mb-3">
                                <div class="col-lg-6 col-sm-12">
                                    <label class="form-label">Advert Type</label>
                                    <select name="advert_type" id="advert_type" class="form-control" required>
                                        <option value="rent">Rent</option>
                                        <option value="sell">Sell</option>
                                    </select>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <label class="form-label">Brand</label>
                                    <input type="text" name="brand" class="form-control" placeholder="Toyota, Mercedes-Benz…">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-6 col-sm-12">
                                    <label class="form-label">Car Name</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <label class="form-label">Model</label>
                                    <input type="text" name="model" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label class="form-label">Fuel Type</label>
                                        <select name="fuel_type" class="form-control" required>
                                            <option value="" disabled selected>Select Fuel Type</option>
                                            <option value="Petrol">Petrol</option>
                                            <option value="Diesel">Diesel</option>
                                            <option value="Hybrid">Hybrid</option>
                                            <option value="Electric">Electric</option>
                                        </select>
                                </div>

                                <div class="col-lg-4 col-sm-12">
                                    <label class="form-label">Transmission</label>

                                        <select name="transmission" class="form-control" required>
                                            <option value="">Select Transmission</option>
                                            <option value="Automatic">Automatic</option>
                                            <option value="Manual">Manual</option>
                                            <option value="Semi-Automatic">Semi-Automatic</option>
                                        </select>

                                </div>
                                    <div class="col-lg-4 col-sm-12">
                                    <label class="form-label">Seats</label>
                                    <input type="number" name="seats" class="form-control" min="1">
                                </div>
                            </div>


                            <div id="rent_fields">
                                <div class="row mb-3">
                                    <div class="col-lg-6 col-sm-12">
                                        <label class="form-label">Price Per Day</label>
                                        <input type="number" step="0.01" name="price_per_day" id="price_per_day" class="form-control">
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <label class="form-label">Price Per Month</label>
                                        <input type="number" step="0.01" name="price_per_month" id="price_per_month" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div id="sell_fields" style="display:none;">
                                <div class="row mb-3">
                                    <div class="col-lg-12">
                                        <label class="form-label">Buy Price</label>
                                        <input type="number" step="0.01" name="price_to_buy" id="price_to_buy" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <label class="form-label">Cover Image <span class="text-danger">*</span></label>
                                    <input type="file" name="cover_image" class="form-control" accept="image/*" required>
                                    <small class="text-muted">This will be the main image displayed for the car</small>
                                    @error('cover_image')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <label class="form-label">Additional Car Images</label>
                                    <input type="file" name="car_images[]" class="form-control" accept="image/*" multiple>
                                    <small class="text-muted">You can upload multiple images to show different angles</small>
                                    @error('car_images.*')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <label class="form-label">Description</label>
                                    <textarea rows="5" class="form-control" name="description"></textarea>
                                </div>
                            </div>

                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary text-black">
                                <i class="fa fa-save"></i> Save
                            </button>
                        </div>
                    </form>

                    <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const advertType = document.getElementById('advert_type');
                        const rentFields = document.getElementById('rent_fields');
                        const sellFields = document.getElementById('sell_fields');
                        const pricePerDay = document.getElementById('price_per_day');
                        const pricePerMonth = document.getElementById('price_per_month');
                        const priceToBuy = document.getElementById('price_to_buy');

                        function toggleFields() {
                            if (advertType.value === 'rent') {
                                rentFields.style.display = '';
                                sellFields.style.display = 'none';
                                pricePerDay.required = true;
                                pricePerMonth.required = true;
                                priceToBuy.required = false;
                                priceToBuy.value = '';
                            } else {
                                rentFields.style.display = 'none';
                                sellFields.style.display = '';
                                pricePerDay.required = false;
                                pricePerMonth.required = false;
                                pricePerDay.value = '';
                                pricePerMonth.value = '';
                                priceToBuy.required = true;
                            }
                        }

                        advertType.addEventListener('change', toggleFields);
                        toggleFields();
                    });
                    </script>

                    
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