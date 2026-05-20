@extends('layouts.adminBase')


@section('content')


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
                        <h6 class="mb-0">Our Rooms</h6>

                        <div class="d-flex gap-2">

                            <!-- Search Bar -->
                            <form method="GET" action="{{ route('getRooms') }}" class="d-flex">
                                <div class="input-group">
                                    <input type="text" id="roomSearch" name="search"
                                        placeholder="Search rooms by type, price, amenities..."
                                        value="{{ request('search') }}"
                                        class="form-control">
                                    
                                    @if(request('search'))
                                        <a href="{{ route('getRooms') }}" 
                                        class="btn btn-outline-secondary">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    @endif

                                    <button class="btn btn-outline-primary" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </form>

                            <!-- Add New Room Button -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#NewProduct">
                                Add New Room
                            </button>

                        </div>
                    </div>

                    <div class="table-responsive">

                        <table class="table text-start align-middle table-bordered table-hover mb-0" id="roomTable">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">Room Details</th>
                                    <th scope="col">Cover Image</th>
                                    <th scope="col">Price (USD)</th>
                                    <th scope="col">Description</th>
                                    <th scope="col" style="width:120px">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($rooms as $rs)
                                <tr>

                                    <!-- Room Type -->
                                    <td>
                                        <a href="{{ route('editRoom', ['id' => $rs->id]) }}">
                                            <strong>{{ $rs->room_type }}</strong>
                                        </a>
                                        <br>
                                        <span class="text-muted small">
                                            {{ $rs->max_occupancy }} Persons • 
                                            {{ $rs->available_rooms }}/{{ $rs->total_rooms }} Available<br>
                                            <strong>Property:</strong> {{ $rs->hotel->name ?? 'N/A' }}<br>
                                            @if(isset($isAdmin) && $isAdmin && $rs->hotel && $rs->hotel->owner)
                                                <strong>Owner:</strong> {{ $rs->hotel->owner->name ?? 'N/A' }} ({{ $rs->hotel->owner->email ?? 'N/A' }})
                                            @endif
                                        </span>
                                    </td>

                                    <!-- Cover Image -->
                                    <td>
                                        <img src="{{ asset('storage/images/rooms/' . $rs->image) }}" 
                                            alt="Room Image" 
                                            width="120px" 
                                            class="rounded">
                                    </td>

                                    <!-- Price -->
                                    <td>
                                        ${{ number_format($rs->price_per_night, 2) }}
                                    </td>

                                    <!-- Description -->
                                    <td>{!! Str::words($rs->description, 40, '...') !!}</td>

                                    <!-- Action -->
                                    <td>
                                        <div class="btn-group" role="group">
                                                <a href="{{ route('editRoom',['id'=>$rs->id]) }}" class="btn btn-info"><i class="fa fa-images"></i></a>

                                            <a href="{{ route('deleteRoom', ['id' => $rs->id]) }}" 
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure to delete this room?')">
                                                <i class="fa fa-trash"></i>
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
        <div class="modal fade" id="NewProduct">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
        
                <!-- Modal Header -->
                <div class="modal-header">
                <h4 class="modal-title">Adding New Room</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
        
                <!-- Modal body -->
                <div class="modal-body" style="max-height: 75vh; overflow-y: auto;">
                    <form class="form" action="{{ route('storeRoom') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="modal-body">

                            <!-- Select Hotel -->
                            <div class="row mb-3">
                                <div class="col-lg-12 col-sm-12">
                                    <label class="form-label">Select Hotel</label>
                                    <select name="hotel_id" class="form-control" required>
                                        <option value="">-- Select Hotel --</option>
                                        @foreach($hotels as $hotel)
                                            <option value="{{ $hotel->id }}">
                                                {{ $hotel->name }} ({{ $hotel->location }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Room Type, Price, Image -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label class="form-label">Room Type / Room Name</label>
                                    <input type="text" name="room_type" class="form-control"
                                        placeholder="Eg: Double Room" required>
                                </div>

                                <div class="col-lg-4 col-sm-12">
                                    <label class="form-label">Cover Image</label>
                                    <input type="file" name="image" class="form-control" required>
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
                                        <option value="USD" selected>USD ($)</option>
                                        <option value="EUR">EUR (€)</option>
                                        <option value="GBP">GBP (£)</option>
                                        <option value="RWF">RWF (Fr)</option>
                                        <option value="KES">KES (KSh)</option>
                                        <option value="UGX">UGX (USh)</option>
                                        <option value="TZS">TZS (TSh)</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-sm-12 mb-3">
                                    <label class="form-label">Price Display Type</label>
                                    <select name="price_display_type" class="form-control">
                                        <option value="per_night" selected>Per Night</option>
                                    </select>
                                    <small class="text-muted">Prices are displayed per night</small>
                                </div>
                                <div class="col-lg-3 col-sm-12 mb-3">
                                    <label class="form-label">Price per Night</label>
                                    <input type="number" step="0.01" name="price_per_night" class="form-control"
                                        placeholder="Eg: 50" required>
                                </div>
                            </div>

                            <!-- Occupancy + Rooms Count -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label class="form-label">Max Occupancy</label>
                                    <input type="number" name="max_occupancy" class="form-control"
                                        placeholder="Eg: 2" required>
                                </div>

                                <div class="col-lg-4 col-sm-12">
                                    <label class="form-label">Total Rooms</label>
                                    <input type="number" name="total_rooms" class="form-control"
                                        placeholder="Eg: 10" required>
                                </div>

                                <div class="col-lg-4 col-sm-12">
                                    <label class="form-label">Available Rooms</label>
                                    <input type="number" name="available_rooms" class="form-control"
                                        placeholder="Eg: 8" required>
                                </div>
                            </div>

                            <!-- Room Description -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="form-label">Room Description</label>
                                    <textarea id="roomDescriptionModal" rows="5" class="form-control"
                                        name="description"></textarea>
                                </div>
                            </div>

                            <!-- Amenities (grouped by category) -->
                            <div class="row mt-3">
                                <div class="col-lg-12">
                                    <label class="form-label">Select Room Amenities</label>
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
                                                            <input class="form-check-input" type="checkbox"
                                                                   name="amenities[]" 
                                                                   value="{{ $amenity->id }}" 
                                                                   id="amenity{{ $amenity->id }}">
                                                            <label class="form-check-label" for="amenity{{ $amenity->id }}">
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

                        <!-- Submit -->
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

        <script>
            document.getElementById('roomSearch').addEventListener('keyup', function () {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#roomTable tbody tr');

                rows.forEach(row => {
                    let text = row.innerText.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none';
                });
            });

        </script>


        @include('admin.includes.footer')
@push('scripts')
<script>
$(function () {
    $('#NewProduct').on('shown.bs.modal', function () {
        var $t = $('#roomDescriptionModal');
        if (!$t.length || $t.next('.note-editor').length) return;
        $t.summernote({
            placeholder: 'Room Description',
            tabsize: 2,
            height: 220,
            toolbar: window._summernoteToolbar || []
        });
    });
});
</script>
@endpush
 @endsection