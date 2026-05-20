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
                    <h6 class="mb-0">Units Management (Rooms & Apartments)</h6>
                    <a href="{{ route('admin.units.create', request()->query()) }}" class="btn btn-primary">
                        <i class="fa fa-plus me-2"></i>Add New Unit
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <form method="GET" action="{{ route('admin.units.index') }}" class="row g-3">
                            <div class="col-md-4">
                                <select name="property_id" class="form-select">
                                    <option value="">All Properties</option>
                                    @foreach($properties as $property)
                                        <option value="{{ $property->id }}" {{ request('property_id') == $property->id ? 'selected' : '' }}>
                                            {{ $property->name }} ({{ ucfirst($property->property_type) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="search" class="form-control" placeholder="Search by name or slug..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table text-start align-middle table-bordered table-hover mb-0">
                        <thead>
                            <tr class="text-dark">
                                <th scope="col">Unit</th>
                                <th scope="col">Property</th>
                                <th scope="col">Type</th>
                                <th scope="col">Capacity</th>
                                <th scope="col">Price/Night</th>
                                <th scope="col">Availability</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($units as $unit)
                                <tr>
                                    <td>
                                        <strong>{{ $unit->name ?? 'Unit #' . $unit->id }}</strong>
                                        @if($unit->slug)
                                            <br><small class="text-muted">Slug: {{ $unit->slug }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.properties.show', $unit->property_id) }}">
                                            {{ $unit->property->name ?? 'N/A' }}
                                        </a>
                                        <br><small class="text-muted">{{ ucfirst($unit->property->property_type ?? '') }}</small>
                                    </td>
                                    <td>
                                        @if($unit->unitType)
                                            <span class="badge bg-info">{{ $unit->unitType->name }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>
                                            <i class="fas fa-users"></i> {{ $unit->max_occupancy }} guests<br>
                                            @if($unit->bedrooms > 0)
                                                <i class="fas fa-bed"></i> {{ $unit->bedrooms }} bedrooms<br>
                                            @endif
                                            <i class="fas fa-bath"></i> {{ $unit->bathrooms }} bathrooms
                                        </small>
                                    </td>
                                    <td>
                                        @if($unit->base_price_per_night)
                                            <strong>${{ number_format($unit->base_price_per_night, 2) }}</strong>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $unit->available_units }} / {{ $unit->total_units }}
                                        <br><small class="text-muted">units available</small>
                                    </td>
                                    <td>
                                        @if($unit->is_active)
                                            @if($unit->status == 'Available')
                                                <span class="badge bg-success">Available</span>
                                            @elseif($unit->status == 'Unavailable')
                                                <span class="badge bg-danger">Unavailable</span>
                                            @else
                                                <span class="badge bg-warning">Maintenance</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.units.edit', $unit->id) }}" class="btn btn-info btn-sm">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <a href="{{ route('admin.units.destroy', $unit->id) }}" 
                                               class="btn btn-danger btn-sm" 
                                               onclick="return confirm('Are you sure you want to delete this unit?')">
                                                <i class="fa fa-trash"></i> Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <p class="text-muted">No units found. Click "Add New Unit" to create one.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $units->links() }}
                </div>
            </div>
        </div>
    </div>
    <!-- Content End -->

    @include('admin.includes.footer')
@endsection










