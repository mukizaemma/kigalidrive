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
                        <a href="{{ route('getCars') }}" class="btn btn-dark">Back</a>
                    </li>
                    <li class="nav-item ">
                        <li class="breadcrumb-item active">Updating <strong> {{$car->name}}</strong></li>
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
                        <form action="{{ route('updateCar', $car->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="modal-body">

                                {{-- Advert Type & Service --}}
                                <div class="row mb-3">
                                    <div class="col-lg-6 col-sm-12">
                                        <label class="form-label">Advert Type <span class="text-danger">*</span></label>
                                        <select name="advert_type" id="advert_type" class="form-control" required>
                                            <option value="rent" {{ old('advert_type', $advertType ?? 'rent') == 'rent' ? 'selected' : '' }}>Rent</option>
                                            <option value="sell" {{ old('advert_type', $advertType ?? 'rent') == 'sell' ? 'selected' : '' }}>Sell</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <label class="form-label">Brand</label>
                                        <input type="text" name="brand" class="form-control" value="{{ old('brand', $car->brand) }}" placeholder="Toyota, BMW…">
                                    </div>
                                </div>

                                {{-- Advert title --}}
                                <div class="row mb-3">
                                    <div class="col-lg-6 col-sm-12">
                                        <label class="form-label">Car Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $car->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <label class="form-label">Model</label>
                                        <input type="text" name="model" class="form-control"
                                            value="{{ old('model', $car->model) }}">
                                    </div>
                                </div>

                                {{-- Cover Image --}}
                                <div class="row mb-3">
                                    <div class="col-lg-4">
                                        <label class="form-label">Current Cover Image</label><br>
                                        @if($car->image)
                                            <img src="{{ asset('storage/images/cars/' . $car->image) }}" width="150" class="rounded shadow-sm">
                                        @else
                                            <p class="text-muted">No cover image</p>
                                        @endif
                                    </div>

                                    <div class="col-lg-8">
                                        <label class="form-label">Change Cover Image</label>
                                        <input type="file" name="cover_image" class="form-control" accept="image/*">
                                        <small class="text-muted">Leave empty to keep current image</small>
                                    </div>
                                </div>

                                {{-- Fuel / Transmission / Seats --}}
                                <div class="row mb-3 mt-3">
                                    <div class="col-lg-4 col-sm-12">
                                        <label class="form-label">Fuel Type</label>
                                        <select name="fuel_type" class="form-control">
                                            <option value="">Select</option>
                                            @foreach(['Petrol','Diesel','Hybrid','Electric'] as $fuel)
                                                <option value="{{ $fuel }}" {{ $car->fuel_type === $fuel ? 'selected' : '' }}>
                                                    {{ $fuel }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-4 col-sm-12">
                                        <label class="form-label">Transmission</label>
                                        <select name="transmission" class="form-control">
                                            <option value="">Select</option>
                                            @foreach(['Automatic','Manual','Semi-Automatic'] as $trans)
                                                <option value="{{ $trans }}" {{ $car->transmission === $trans ? 'selected' : '' }}>
                                                    {{ $trans }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-4 col-sm-12">
                                        <label class="form-label">Seats</label>
                                        <input type="number" name="seats" class="form-control" min="1"
                                            value="{{ old('seats', $car->seats) }}">
                                    </div>
                                </div>

                                {{-- Status --}}
                                <div class="row mb-3">
                                    <div class="col-lg-6 col-sm-12">
                                        <label class="form-label">Status <span class="text-danger">*</span></label>
                                        <select name="status" class="form-control" required>
                                            <option value="available" {{ old('status', $car->status) == 'available' ? 'selected' : '' }}>Available</option>
                                            <option value="rented" {{ old('status', $car->status) == 'rented' ? 'selected' : '' }}>Rented</option>
                                            <option value="maintenance" {{ old('status', $car->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Pricing (Rent) --}}
                                <div id="rent_fields">
                                    <div class="row mb-3">
                                        <div class="col-lg-6 col-sm-12">
                                            <label class="form-label">Price Per Day</label>
                                            <input type="number" step="0.01" name="price_per_day" id="price_per_day"
                                                class="form-control"
                                                value="{{ old('price_per_day', $car->price_per_day) }}">
                                        </div>

                                        <div class="col-lg-6 col-sm-12">
                                            <label class="form-label">Price Per Month</label>
                                            <input type="number" step="0.01" name="price_per_month" id="price_per_month"
                                                class="form-control"
                                                value="{{ old('price_per_month', $car->price_per_month) }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- Pricing (Sell) --}}
                                <div id="sell_fields" style="display:none;">
                                    <div class="row mb-3">
                                        <div class="col-lg-12">
                                            <label class="form-label">Buy Price</label>
                                            <input type="number" step="0.01" name="price_to_buy" id="price_to_buy"
                                                class="form-control"
                                                value="{{ old('price_to_buy', $car->price_to_buy) }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- Additional Images --}}
                                <div class="row mb-3">
                                    <div class="col-lg-12">
                                        <label class="form-label">Add Additional Images</label>
                                        <input type="file" name="car_images[]" class="form-control" accept="image/*" multiple>
                                        <small class="text-muted">You can upload multiple images at once</small>
                                    </div>
                                </div>

                                {{-- Description --}}
                                <div class="row mb-3">
                                    <div class="col-lg-12">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" rows="5"
                                                name="description">{{ old('description', $car->description) }}</textarea>
                                    </div>
                                </div>

                            </div>

                            {{-- Actions --}}
                            <div class="form-actions mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Update Car
                                </button>

                                <a href="{{ route('getCars') }}" class="btn btn-light">
                                    Back
                                </a>
                            </div>
                        </form>

                        </div>
                    </div>

                        <!-- Image Gallery Section -->
                        <div class="card mt-5">
                            <div class="card-header bg-dark text-white d-flex align-items-center">
                                <h5 class="mb-0">
                                    Additional Images ({{ $carImages->count() }})
                                </h5>
                                <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#newImage">
                                    Add New Image
                                </button>
                            </div>
                            
                            <div class="card-body">
                                @if($carImages->count() == 0)
                                    <p class="text-muted">No additional images yet. Add images to show different angles of the car.</p>
                                @else
                                    <div class="row">
                                        @foreach($carImages as $carImage)
                                        <div class="col-md-4 col-sm-6 mb-4">
                                            <div class="card shadow" style="position: relative; overflow: hidden;">
                                                <img src="{{ asset('storage/images/cars/' . $carImage->image) }}" 
                                                    class="card-img-top rounded" alt="Car Image" style="width: 100%; height: 200px; object-fit: cover;">
                                                <button onclick="confirmDelete('{{ route('deleteCarImage', $carImage->id) }}')" 
                                                        style="position: absolute; top: 10px; right: 10px; background: rgba(255, 0, 0, 0.8); color: white; border: none; padding: 6px 10px; border-radius: 50%; cursor: pointer; font-size: 18px;">
                                                    ×
                                                </button>
                                                @if($carImage->caption)
                                                <div class="card-body text-center">
                                                    <h6 class="card-title text-truncate">{{ $carImage->caption }}</h6>
                                                </div>
                                                @endif
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
        <h4 class="modal-title">Adding New Image to {{ $car->name ?? '' }}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <form class="form" action="{{ route('addCarImage', ['id' => $car->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <label for="image" class="form-label">Upload Images</label>
                            <input type="file" name="images[]" class="form-control" id="image" multiple accept="image/*" required>
                            <small class="text-muted">You can upload one or multiple images at once.</small>
                        </div>
                    </div>
                </div>
            
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary text-black">
                        <i class="fa fa-save"></i> Upload
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
            priceToBuy.value = '';
        } else {
            rentFields.style.display = 'none';
            sellFields.style.display = '';
            pricePerDay.value = '';
            pricePerMonth.value = '';
        }
    }

    advertType.addEventListener('change', toggleFields);
    toggleFields(); // Initial toggle
});
</script>

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