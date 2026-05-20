@extends('layouts.adminBase')

@section('content')
    @include('admin.includes.sidebar')
    <div class="content">
        @include('admin.includes.navbar')

        <div class="container-fluid pt-4 px-4">
            <div class="bg-light rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Facility Categories</h6>
                    <a href="{{ route('admin.facility-categories.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus me-2"></i>Add Category
                    </a>
                </div>
                <p class="text-muted small mb-4">Manage categories for amenities (e.g. Room Amenities, Hotel Facilities). Edit a category to add or manage its amenities.</p>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead>
                            <tr class="text-dark">
                                <th>Name</th>
                                <th>Property type</th>
                                <th>Amenities</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td>
                                        @if($category->icon)
                                            <i class="{{ $category->icon }} me-2"></i>
                                        @endif
                                        <strong>{{ $category->name }}</strong>
                                        @if($category->slug)
                                            <br><small class="text-muted">Slug: {{ $category->slug }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($category->property_type)
                                            <span class="badge bg-info">{{ ucfirst($category->property_type) }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $category->facilities_count ?? 0 }}</td>
                                    <td>
                                        @if($category->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.facility-categories.edit', $category->id) }}" class="btn btn-info" title="Edit and manage amenities">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <a href="{{ route('admin.facility-categories.destroy', $category->id) }}" class="btn btn-danger" title="Delete category (amenities become uncategorized)"
                                               onclick="return confirm('Delete this category? Its amenities will become uncategorized.');">
                                                <i class="fa fa-trash"></i> Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        No categories yet. <a href="{{ route('admin.facility-categories.create') }}">Create one</a> to group amenities.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('admin.includes.footer')
@endsection
