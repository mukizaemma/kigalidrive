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
                    <h6 class="mb-0">Property Details: {{ $property->name }}</h6>
                    <div>
                        <a href="{{ route('admin.booking-calendar.index', ['listing' => (!empty($isHotelModel) ? 'h-' : 'p-').$property->id, 'year' => date('Y')]) }}" class="btn btn-success">
                            <i class="fa fa-calendar-alt me-2"></i>Booking calendar
                        </a>
                        <a href="{{ route('admin.properties.edit', $property->id) }}" class="btn btn-info">
                            <i class="fa fa-edit me-2"></i>Edit
                        </a>
                        <a href="{{ route('admin.properties.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>

                <!-- Featured Image -->
                @if($property->featured_image)
                    <div class="mb-4 text-center">
                        <img src="{{ asset('storage/images/properties/' . $property->featured_image) }}" 
                             alt="{{ $property->name }}" class="img-fluid rounded" style="max-height: 400px;">
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-8 mx-auto">
                        <!-- Basic Information -->
                        <div class="card mb-3">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Basic Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Property Name:</strong></div>
                                    <div class="col-md-8">{{ $property->name }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Type:</strong></div>
                                    <div class="col-md-8">
                                        <span class="badge bg-{{ $property->property_type == 'hotel' ? 'primary' : 'info' }}">
                                            {{ ucfirst($property->property_type) }}
                                        </span>
                                        @if($property->stars)
                                            <span class="badge bg-warning ms-2">{{ $property->stars }} Stars</span>
                                        @endif
                                        @if($property->is_featured)
                                            <span class="badge bg-success ms-2">Featured</span>
                                        @endif
                                        @if($property->is_verified)
                                            <span class="badge bg-info ms-2">Verified</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Status:</strong></div>
                                    <div class="col-md-8">
                                        @if($property->status == 'Active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($property->status == 'Pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Owner:</strong></div>
                                    <div class="col-md-8">{{ $property->owner->name ?? 'N/A' }} ({{ $property->owner->email ?? 'N/A' }})</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Slug:</strong></div>
                                    <div class="col-md-8"><code>{{ $property->slug }}</code></div>
                                </div>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="card mb-3">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">Location</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Address:</strong></div>
                                    <div class="col-md-8">{{ $property->address ?? 'N/A' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>City:</strong></div>
                                    <div class="col-md-8">{{ $property->city ?? 'N/A' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Location:</strong></div>
                                    <div class="col-md-8">{{ $property->location ?? 'N/A' }}</div>
                                </div>
                                @if($property->latitude && $property->longitude)
                                    <div class="row mb-2">
                                        <div class="col-md-4"><strong>Coordinates:</strong></div>
                                        <div class="col-md-8">{{ $property->latitude }}, {{ $property->longitude }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="card mb-3">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">Contact person (guest-facing)</h6>
                            </div>
                            <div class="card-body">
                                @if(empty($isHotelModel) && data_get($property->meta_data, 'contact_person_name'))
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Contact name:</strong></div>
                                    <div class="col-md-8">{{ data_get($property->meta_data, 'contact_person_name') }}</div>
                                </div>
                                @endif
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Phone:</strong></div>
                                    <div class="col-md-8">{{ $property->phone ?? 'N/A' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Email:</strong></div>
                                    <div class="col-md-8">{{ $property->email ?? 'N/A' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Website:</strong></div>
                                    <div class="col-md-8">
                                        @if($property->website)
                                            <a href="{{ $property->website }}" target="_blank">{{ $property->website }}</a>
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(isset($roomInventory) && $roomInventory->count() > 0)
                        <div class="card mb-3">
                            <div class="card-header bg-dark text-white">
                                <h6 class="mb-0">Room inventory by type</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th class="ps-3">Room type</th>
                                                <th class="text-end pe-3">Rooms</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($roomInventory as $row)
                                                <tr>
                                                    <td class="ps-3">{{ $row['type_name'] }}</td>
                                                    <td class="text-end pe-3">{{ $row['count'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-light fw-semibold">
                                                <td class="ps-3">Total</td>
                                                <td class="text-end pe-3">{{ $roomInventory->sum('count') }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Relationships -->
                        <div class="card mb-3">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">Relationships</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Category:</strong></div>
                                    <div class="col-md-8">{{ $property->category->name ?? 'N/A' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Program:</strong></div>
                                    <div class="col-md-8">{{ $property->program->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        @if($property->description)
                            @php
                                $descRaw = $property->description;
                                $descLooksHtml = is_string($descRaw) && preg_match('/<[^>]+>/', $descRaw);
                            @endphp
                            <div class="card mb-3">
                                <div class="card-header bg-secondary text-white">
                                    <h6 class="mb-0">Description</h6>
                                </div>
                                <div class="card-body text-start property-description">
                                    @if($descLooksHtml)
                                        {!! $descRaw !!}
                                    @else
                                        {!! nl2br(e($descRaw)) !!}
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Facilities -->
                        @if($property->facilities->count() > 0)
                            <div class="card mb-3">
                                <div class="card-header bg-dark text-white">
                                    <h6 class="mb-0">Facilities/Amenities ({{ $property->facilities->count() }})</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($property->facilities as $facility)
                                            <div class="col-md-4 mb-2">
                                                @if($facility->icon)
                                                    <i class="{{ $facility->icon }} me-2"></i>
                                                @endif
                                                {{ $facility->title }}
                                                @if($facility->category)
                                                    <small class="text-muted">({{ $facility->category->name }})</small>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Units -->
                        @if($property->units->count() > 0)
                            <div class="card mb-3">
                                <div class="card-header bg-danger text-white">
                                    <h6 class="mb-0">Units ({{ $property->units->count() }})</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Type</th>
                                                    <th>Capacity</th>
                                                    <th>Price/Night</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($property->units as $unit)
                                                    <tr>
                                                        <td>{{ $unit->name ?? 'Unit #' . $unit->id }}</td>
                                                        <td>{{ $unit->unitType->name ?? 'N/A' }}</td>
                                                        <td>{{ $unit->max_occupancy }} guests</td>
                                                        <td>${{ number_format($unit->base_price_per_night ?? 0, 2) }}</td>
                                                        <td>
                                                            @if($unit->is_active)
                                                                <span class="badge bg-success">Active</span>
                                                            @else
                                                                <span class="badge bg-secondary">Inactive</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('admin.units.edit', $unit->id) }}" class="btn btn-sm btn-info">Edit</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('admin.units.create', ['property_id' => $property->id]) }}" class="btn btn-primary btn-sm">
                                            <i class="fa fa-plus me-2"></i>Add New Unit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="card mb-3">
                                <div class="card-body text-center">
                                    <p class="text-muted">No units found for this property.</p>
                                    <a href="{{ route('admin.units.create', ['property_id' => $property->id]) }}" class="btn btn-primary">
                                        <i class="fa fa-plus me-2"></i>Add First Unit
                                    </a>
                                </div>
                            </div>
                        @endif

                        <!-- Images -->
                        @if($property->images->count() > 0)
                            <div class="card mb-3">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">Images ({{ $property->images->count() }})</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($property->images as $image)
                                            <div class="col-md-3 mb-3">
                                                <img src="{{ asset('storage/images/properties/' . $image->image_path) }}" 
                                                     alt="Property Image" class="img-thumbnail" style="width: 100%; height: 200px; object-fit: cover;">
                                                @if($image->is_primary)
                                                    <span class="badge bg-success">Primary</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Content End -->

    @include('admin.includes.footer')
@endsection










