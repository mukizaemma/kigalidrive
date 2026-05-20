@extends('layouts.adminBase')

@section('content')
    @include('admin.includes.sidebar')
    <div class="content">
        @include('admin.includes.navbar')

        <div class="container-fluid pt-4 px-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Edit category form --}}
            <div class="bg-light rounded p-4 mb-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Edit category: {{ $category->name }}</h6>
                    <div class="d-flex gap-2">
                        <a href="{{ route('amenities.index') }}" class="btn btn-outline-primary btn-sm" title="Manage all amenities by category">
                            <i class="fa fa-list me-1"></i>Amenities by category
                        </a>
                        <a href="{{ route('admin.facility-categories.index') }}" class="btn btn-secondary btn-sm">Back to categories</a>
                    </div>
                </div>

                @if($errors->any() && !$errors->has('title'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            @foreach($errors->all() as $e)
                                @if(!str_contains($e, 'title') && !str_contains($e, 'Amenity'))
                                    <li>{{ $e }}</li>
                                @endif
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('admin.facility-categories.update', $category->id) }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $category->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror"
                                   value="{{ old('slug', $category->slug) }}">
                            @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="icon" class="form-label">Icon class</label>
                            <input type="text" name="icon" id="icon" class="form-control @error('icon') is-invalid @enderror"
                                   value="{{ old('icon', $category->icon) }}">
                            @error('icon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="property_type" class="form-label">Property type</label>
                            <select name="property_type" id="property_type" class="form-select @error('property_type') is-invalid @enderror">
                                <option value="">All / Both</option>
                                <option value="hotel" {{ old('property_type', $category->property_type) === 'hotel' ? 'selected' : '' }}>Hotel</option>
                                <option value="apartment" {{ old('property_type', $category->property_type) === 'apartment' ? 'selected' : '' }}>Apartment</option>
                            </select>
                            @error('property_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="sort_order" class="form-label">Sort order</label>
                            <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror"
                                   value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0">
                            @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $category->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save me-2"></i>Update category</button>
                </form>
            </div>

            {{-- Related amenities --}}
            <div class="bg-light rounded p-4">
                <h6 class="mb-3">Amenities in this category</h6>
                <p class="text-muted small mb-3">Add new amenities below; they will be assigned to "{{ $category->name }}". Duplicate titles in the same category are not allowed.</p>

                @if($errors->has('title') || $errors->has('facility_category_id'))
                    <div class="alert alert-danger alert-dismissible fade show mb-3">
                        @foreach($errors->get('title') as $e)<div>{{ $e }}</div>@endforeach
                        @foreach($errors->get('facility_category_id') as $e)<div>{{ $e }}</div>@endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Add amenity form (scoped to this category) --}}
                <div class="card mb-4">
                    <div class="card-header py-2"><strong>Add amenity to this category</strong></div>
                    <div class="card-body">
                        <form action="{{ route('amenities.store') }}" method="POST" class="row g-3 align-items-end">
                            @csrf
                            <input type="hidden" name="facility_category_id" value="{{ $category->id }}">
                            <input type="hidden" name="redirect_category_id" value="{{ $category->id }}">
                            <div class="col-md-6">
                                <label for="amenity_title" class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="amenity_title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="e.g. Free WiFi" required>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="amenity_is_active" value="1" checked>
                                    <label class="form-check-label" for="amenity_is_active">Active</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100"><i class="fa fa-plus me-1"></i>Add</button>
                            </div>
                        </form>
                        <p class="text-muted small mt-2 mb-0">Icon, description and sort order can be added when editing the amenity.</p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead>
                            <tr class="text-dark">
                                <th>Title</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($category->facilities as $amenity)
                                <tr>
                                    <td>
                                        @if($amenity->icon)<i class="{{ $amenity->icon }} me-2"></i>@endif
                                        <strong>{{ $amenity->title }}</strong>
                                        @if($amenity->slug)<br><small class="text-muted">{{ $amenity->slug }}</small>@endif
                                    </td>
                                    <td>
                                        @if($amenity->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('amenities.edit', $amenity->id) }}" class="btn btn-info"><i class="fa fa-edit"></i> Edit</a>
                                            <a href="{{ route('amenities.destroy', $amenity->id) }}" class="btn btn-danger" onclick="return confirm('Delete this amenity?');"><i class="fa fa-trash"></i> Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">No amenities in this category yet. Use the form above to add one.</td>
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
