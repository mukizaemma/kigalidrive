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
                    <h6 class="mb-0">Edit Amenity</h6>
                    <a href="{{ route('amenities.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-8 mx-auto">
                        <form action="{{ route('amenities.update', $amenity->id) }}" method="POST">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="title" class="form-label">Amenity Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" value="{{ old('title', $amenity->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="facility_category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select name="facility_category_id" class="form-select @error('facility_category_id') is-invalid @enderror" 
                                            id="facility_category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ old('facility_category_id', $amenity->facility_category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('facility_category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="icon" class="form-label">Icon Class</label>
                                    <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror" 
                                           id="icon" value="{{ old('icon', $amenity->icon) }}" placeholder="e.g., fas fa-wifi">
                                    <small class="text-muted">Font Awesome icon class</small>
                                    @if($amenity->icon)
                                        <div class="mt-2">
                                            <i class="{{ $amenity->icon }} fa-2x text-primary"></i>
                                            <small class="text-muted ms-2">Preview</small>
                                        </div>
                                    @endif
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" value="{{ old('sort_order', $amenity->sort_order ?? 0) }}" min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                          id="description" rows="4">{{ old('description', $amenity->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" 
                                           {{ old('is_active', $amenity->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Slug</label>
                                <input type="text" class="form-control" value="{{ $amenity->slug }}" disabled>
                                <small class="text-muted">Slug is automatically generated from title</small>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('amenities.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save me-2"></i>Update Amenity
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Content End -->

    @include('admin.includes.footer')
@endsection

