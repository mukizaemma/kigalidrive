@extends('layouts.adminBase')

@section('content')
    @include('admin.includes.sidebar')
    <div class="content">
        @include('admin.includes.navbar')

        <div class="container-fluid pt-4 px-4">
            <div class="bg-light rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Add Facility Category</h6>
                    <a href="{{ route('admin.facility-categories.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left me-2"></i>Back to list
                    </a>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('admin.facility-categories.store') }}" method="POST" class="mb-0">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="e.g. Room Amenities" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror"
                                   value="{{ old('slug') }}" placeholder="Leave blank to auto-generate from name">
                            @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="icon" class="form-label">Icon class</label>
                            <input type="text" name="icon" id="icon" class="form-control @error('icon') is-invalid @enderror"
                                   value="{{ old('icon') }}" placeholder="e.g. fas fa-bed">
                            @error('icon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="property_type" class="form-label">Property type</label>
                            <select name="property_type" id="property_type" class="form-select @error('property_type') is-invalid @enderror">
                                <option value="">All / Both</option>
                                <option value="hotel" {{ old('property_type') === 'hotel' ? 'selected' : '' }}>Hotel</option>
                                <option value="apartment" {{ old('property_type') === 'apartment' ? 'selected' : '' }}>Apartment</option>
                            </select>
                            @error('property_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="sort_order" class="form-label">Sort order</label>
                            <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror"
                                   value="{{ old('sort_order', 0) }}" min="0">
                            @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save me-2"></i>Create category</button>
                        <a href="{{ route('admin.facility-categories.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('admin.includes.footer')
@endsection
