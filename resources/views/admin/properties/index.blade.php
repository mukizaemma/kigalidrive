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
                    <div>
                        <h6 class="mb-0">
                            @if(!empty($mineOnly))
                                My properties
                            @else
                                Properties Management (Hotels & Apartments)
                            @endif
                        </h6>
                        @if(!empty($mineOnly) && !empty($isPropertySuperAdmin))
                            <p class="small text-muted mb-0 mt-1">
                                <a href="{{ route('admin.properties.index') }}">Show all properties</a>
                            </p>
                        @endif
                        <div class="d-flex gap-2 mt-2">
                            <span class="badge bg-warning">
                                <i class="fa fa-clock me-1"></i>Pending: {{ $pendingCount ?? 0 }}
                            </span>
                            <span class="badge bg-success">
                                <i class="fa fa-check-circle me-1"></i>Active: {{ $activeCount ?? 0 }}
                            </span>
                            <span class="badge bg-danger">
                                <i class="fa fa-times-circle me-1"></i>Inactive: {{ $inactiveCount ?? 0 }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('admin.properties.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus me-2"></i>Add New Property
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
                        <form method="GET" action="{{ route('admin.properties.index') }}" class="row g-3">
                            @if(request('mine'))
                                <input type="hidden" name="mine" value="1">
                            @endif
                            <div class="col-md-3">
                                <select name="type" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="hotel" {{ request('type') == 'hotel' ? 'selected' : '' }}>Hotels</option>
                                    <option value="apartment" {{ request('type') == 'apartment' ? 'selected' : '' }}>Apartments</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
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
                                <th scope="col">Property</th>
                                <th scope="col">Type</th>
                                <th scope="col">Owner</th>
                                <th scope="col">Location</th>
                                <th scope="col">Status</th>
                                <th scope="col">Rooms by type</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($properties as $property)
                                <tr>
                                    <td>
                                        <strong>{{ $property->name }}</strong>
                                        @if($property->is_featured)
                                            <span class="badge bg-warning">Featured</span>
                                        @endif
                                        @if($property->is_verified)
                                            <span class="badge bg-success">Verified</span>
                                        @endif
                                        <br><small class="text-muted">{{ $property->stars ? $property->stars . ' Stars' : '' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $property->property_type == 'hotel' ? 'primary' : 'info' }}">
                                            {{ ucfirst($property->property_type) }}
                                        </span>
                                    </td>
                                    <td>{{ $property->owner->name ?? 'N/A' }}</td>
                                    <td>
                                        {{ $property->city ?? 'N/A' }}
                                        @if($property->location)
                                            <br><small class="text-muted">{{ $property->location }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($property->status == 'Active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($property->status == 'Pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                            
                                            @if(!empty($isPropertySuperAdmin))
                                            <!-- Status Dropdown -->
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="statusDropdown{{ $property->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-cog"></i>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="statusDropdown{{ $property->id }}">
                                                    <li>
                                                        <a href="{{ route('admin.properties.updateStatus.get', ['id' => $property->id, 'status' => 'Active']) }}" 
                                                           class="dropdown-item status-update-btn {{ $property->status == 'Active' ? 'active' : '' }}" 
                                                           data-property-id="{{ $property->id }}" 
                                                           data-status="Active">
                                                            <i class="fa fa-check-circle text-success me-2"></i>Approve (Active)
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('admin.properties.updateStatus.get', ['id' => $property->id, 'status' => 'Pending']) }}" 
                                                           class="dropdown-item status-update-btn {{ $property->status == 'Pending' ? 'active' : '' }}" 
                                                           data-property-id="{{ $property->id }}" 
                                                           data-status="Pending">
                                                            <i class="fa fa-clock text-warning me-2"></i>Set to Pending
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('admin.properties.updateStatus.get', ['id' => $property->id, 'status' => 'Inactive']) }}" 
                                                           class="dropdown-item status-update-btn {{ $property->status == 'Inactive' ? 'active' : '' }}" 
                                                           data-property-id="{{ $property->id }}" 
                                                           data-status="Inactive">
                                                            <i class="fa fa-times-circle text-danger me-2"></i>Set to Inactive
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-start">
                                        <strong>{{ $property->total_room_inventory ?? 0 }}</strong> rooms total
                                        @if(empty($property->is_hotel_model))
                                            <br><a href="{{ route('admin.units.index', ['property_id' => $property->id]) }}" class="small">Manage units / types</a>
                                        @endif
                                        @if(!empty($property->room_type_summary))
                                            <br><small class="text-muted">{{ $property->room_type_summary }}</small>
                                        @elseif(($property->total_room_inventory ?? 0) === 0)
                                            <br><small class="text-muted">Add units with quantities per room type</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.properties.show', $property->id) }}" class="btn btn-info btn-sm">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.properties.edit', $property->id) }}" class="btn btn-warning btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.properties.destroy', $property->id) }}" 
                                               class="btn btn-danger btn-sm" 
                                               onclick="return confirm('Are you sure?')">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <p class="text-muted">No properties found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $properties->links() }}
                </div>
            </div>
        </div>
    </div>
    <!-- Content End -->

    @include('admin.includes.footer')

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Handle status update via AJAX - use event delegation for dynamically added elements
            $(document).on('click', '.status-update-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const propertyId = $(this).data('property-id');
                const status = $(this).data('status');
                const btn = $(this);
                const row = btn.closest('tr');
                const dropdown = btn.closest('.dropdown');
                const href = $(this).attr('href'); // Get href for GET route fallback
                
                if (!confirm(`Are you sure you want to set this property to ${status}?`)) {
                    return;
                }
                
                // If href exists and contains /status/, use GET route (fallback if AJAX fails)
                // Otherwise use AJAX POST for smoother UX
                
                // Close dropdown
                const bsDropdown = bootstrap.Dropdown.getInstance(dropdown.find('button')[0]);
                if (bsDropdown) {
                    bsDropdown.hide();
                }
                
                // Show loading state
                btn.html('<i class="fa fa-spinner fa-spin me-2"></i>Updating...');
                btn.prop('disabled', true);
                
                $.ajax({
                    url: `{{ url('/admin/properties') }}/${propertyId}/status`,
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: status
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Update status badge
                            const statusCell = row.find('td:eq(4)');
                            let badgeClass = 'bg-secondary';
                            let badgeText = status;
                            
                            if (status === 'Active') {
                                badgeClass = 'bg-success';
                            } else if (status === 'Pending') {
                                badgeClass = 'bg-warning';
                            } else if (status === 'Inactive') {
                                badgeClass = 'bg-danger';
                            }
                            
                            statusCell.find('.badge').removeClass('bg-success bg-warning bg-danger').addClass(badgeClass).text(status);
                            
                            // Update active state in dropdown
                            statusCell.find('.dropdown-item').removeClass('active');
                            btn.addClass('active');
                            
                            // Show success message
                            const alert = $('<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                                '<i class="fa fa-check-circle me-2"></i>' + response.message +
                                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                                '</div>');
                            $('.container-fluid').prepend(alert);
                            
                            // Auto-dismiss after 3 seconds
                            setTimeout(function() {
                                alert.alert('close');
                            }, 3000);
                            
                            // Reload page after 1.5 seconds to update counts
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        }
                    },
                    error: function(xhr) {
                        console.error('Status update error:', xhr);
                        let errorMsg = 'An error occurred while updating the status.';
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else if (xhr.responseText) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (response.message) {
                                    errorMsg = response.message;
                                }
                            } catch (e) {
                                // If not JSON, show generic error
                            }
                        }
                        
                        // Show error alert
                        const alert = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                            '<i class="fa fa-exclamation-circle me-2"></i>' + errorMsg +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                            '</div>');
                        $('.container-fluid').prepend(alert);
                        
                        setTimeout(function() {
                            alert.alert('close');
                        }, 5000);
                    }
                });
            });
        });
    </script>
    @endpush
@endsection


