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
            <div class="bg-light rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Add New Review</h6>
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>

                <form action="{{ route('admin.reviews.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="names" class="form-label">Reviewer Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="names" name="names" value="{{ old('names') }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="rating" class="form-label">Rating <span class="text-danger">*</span></label>
                                        <select class="form-select" id="rating" name="rating" required>
                                            <option value="">Select Rating</option>
                                            @for($i = 5; $i >= 1; $i--)
                                                <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="testimony" class="form-label">Review Content <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="testimony" name="testimony" rows="6" required>{{ old('testimony') }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="website" class="form-label">Website (Optional)</label>
                                        <input type="url" class="form-control" id="website" name="website" value="{{ old('website') }}" placeholder="https://example.com">
                                    </div>
                                    <div class="mb-3">
                                        <label for="images" class="form-label">Images (Optional)</label>
                                        <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                                        <small class="text-muted">You can upload multiple images (max 5MB each)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="mb-3">Settings</h6>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_approved" name="is_approved" value="1" {{ old('is_approved', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_approved">
                                                Approved (Visible on public pages)
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">
                                                Featured Review
                                            </label>
                                        </div>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save me-2"></i>Save Review
                                        </button>
                                        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection



