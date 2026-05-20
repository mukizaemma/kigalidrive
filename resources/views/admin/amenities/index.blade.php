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
                <h6 class="mb-4">Amenities by Category</h6>
                <p class="text-muted small mb-4">Use the tabs below to view and manage amenities in each category. Add new amenities only to the selected category to avoid duplicates in the same category.</p>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($categories->isEmpty() && $uncategorized->isEmpty())
                    <div class="alert alert-info">
                        No facility categories found. Create categories first, then add amenities to each category.
                    </div>
                @else
                    {{-- Category tabs --}}
                    <ul class="nav nav-tabs mb-3" id="amenityCategoryTabs" role="tablist">
                        @foreach($categories as $index => $category)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $index === 0 ? 'active' : '' }}" id="tab-{{ $category->id }}" data-bs-toggle="tab" data-bs-target="#pane-{{ $category->id }}" type="button" role="tab">
                                    @if($category->icon)
                                        <i class="{{ $category->icon }} me-1"></i>
                                    @endif
                                    {{ $category->name }}
                                    <span class="badge bg-secondary ms-1">{{ $category->facilities->count() }}</span>
                                </button>
                            </li>
                        @endforeach
                        @if($uncategorized->isNotEmpty())
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $categories->isEmpty() ? 'active' : '' }}" id="tab-uncategorized" data-bs-toggle="tab" data-bs-target="#pane-uncategorized" type="button" role="tab">
                                    Uncategorized
                                    <span class="badge bg-secondary ms-1">{{ $uncategorized->count() }}</span>
                                </button>
                            </li>
                        @endif
                    </ul>

                    <div class="tab-content" id="amenityCategoryTabContent">
                        @foreach($categories as $index => $category)
                            <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="pane-{{ $category->id }}" role="tabpanel">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="mb-0 text-dark">{{ $category->name }} — Amenities</h6>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newAmenityModal" data-category-id="{{ $category->id }}" data-category-name="{{ $category->name }}">
                                        <i class="fa fa-plus me-1"></i>Add to this category
                                    </button>
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
                                                        <strong>{{ $amenity->title }}</strong>
                                                        @if($amenity->slug)
                                                            <br><small class="text-muted">Slug: {{ $amenity->slug }}</small>
                                                        @endif
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
                                                            <a href="{{ route('amenities.destroy', $amenity->id) }}" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this amenity?')"><i class="fa fa-trash"></i> Delete</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted py-4">
                                                        No amenities in this category yet. Click "Add to this category" to add one.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach

                        @if($uncategorized->isNotEmpty())
                            <div class="tab-pane fade {{ $categories->isEmpty() ? 'show active' : '' }}" id="pane-uncategorized" role="tabpanel">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="mb-0 text-dark">Uncategorized Amenities</h6>
                                    <span class="text-muted small">Assign a category via Edit.</span>
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
                                            @foreach($uncategorized as $amenity)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $amenity->title }}</strong>
                                                        @if($amenity->slug)
                                                            <br><small class="text-muted">Slug: {{ $amenity->slug }}</small>
                                                        @endif
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
                                                            <a href="{{ route('amenities.destroy', $amenity->id) }}" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this amenity?')"><i class="fa fa-trash"></i> Delete</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Content End -->

    <!-- New Amenity Modal (category set by tab) -->
    <div class="modal fade" id="newAmenityModal" tabindex="-1" aria-labelledby="newAmenityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newAmenityModalLabel">Add New Amenity — <span id="modalCategoryName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('amenities.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="facility_category_id" id="modalFacilityCategoryId" value="">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="title" class="form-label">Amenity Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="e.g., Free WiFi" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted small mb-0">You can add icon, description and sort order later when editing this amenity.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save me-2"></i>Save Amenity</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modalEl = document.getElementById('newAmenityModal');
            if (!modalEl) return;
            var categoryIdInput = document.getElementById('modalFacilityCategoryId');
            var categoryNameSpan = document.getElementById('modalCategoryName');
            var modal = typeof bootstrap !== 'undefined' && bootstrap.Modal ? new bootstrap.Modal(modalEl) : null;

            modalEl.addEventListener('show.bs.modal', function (e) {
                var btn = e.relatedTarget;
                if (btn && btn.getAttribute('data-category-id')) {
                    categoryIdInput.value = btn.getAttribute('data-category-id');
                    categoryNameSpan.textContent = btn.getAttribute('data-category-name') || '';
                }
            });

            @if($errors->any() && old('facility_category_id'))
            (function () {
                var catId = {{ json_encode(old('facility_category_id')) }};
                var categories = @json($categories->keyBy('id'));
                var cat = categories[catId];
                if (catId && categoryIdInput) {
                    categoryIdInput.value = catId;
                    if (categoryNameSpan && cat) categoryNameSpan.textContent = cat.name || '';
                    if (modal) modal.show();
                }
            })();
            @endif
        });
    </script>

    @include('admin.includes.footer')
@endsection
