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
                    <h6 class="mb-0">Edit Review</h6>
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>

                <form action="{{ route('admin.reviews.update', $review->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="names" class="form-label">Reviewer Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="names" name="names" value="{{ old('names', $review->names) }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $review->email) }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="rating" class="form-label">Rating <span class="text-danger">*</span></label>
                                        <select class="form-select" id="rating" name="rating" required>
                                            @for($i = 5; $i >= 1; $i--)
                                                <option value="{{ $i }}" {{ old('rating', $review->rating) == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="testimony" class="form-label">Review Content <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="testimony" name="testimony" rows="6" required>{{ old('testimony', $review->testimony) }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="website" class="form-label">Website (Optional)</label>
                                        <input type="text" class="form-control" id="website" name="website" value="{{ old('website', $review->website) }}" placeholder="example.com">
                                    </div>
                                    <div class="mb-3">
                                        <label for="admin_response" class="form-label">Admin Response (Optional)</label>
                                        <textarea class="form-control" id="admin_response" name="admin_response" rows="4">{{ old('admin_response', $review->admin_response) }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="images" class="form-label">Add More Images (Optional)</label>
                                        <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                                        <small class="text-muted">You can upload multiple images (max 5MB each)</small>
                                    </div>
                                    
                                    @if($review->images->count() > 0)
                                        <div class="mb-3">
                                            <label class="form-label">Current Images</label>
                                            <div class="row g-2">
                                                @foreach($review->images as $image)
                                                    <div class="col-md-3">
                                                        <div class="position-relative">
                                                            <img src="{{ asset('storage/' . $image->image) }}" alt="Review Image" class="img-fluid rounded">
                                                            <form action="{{ route('admin.reviews.deleteImage', ['reviewId' => $review->id, 'imageId' => $image->id]) }}" method="POST" class="position-absolute top-0 end-0 m-1">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this image?')">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="mb-3">Settings</h6>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_approved" name="is_approved" value="1" {{ old('is_approved', $review->is_approved) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_approved">
                                                Approved (Visible on public pages)
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $review->is_featured) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">
                                                Featured Review
                                            </label>
                                        </div>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save me-2"></i>Update Review
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

