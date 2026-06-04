@extends('layouts.adminBase')

@section('content')
@include('admin.includes.sidebar')

<div class="content">
    @include('admin.includes.navbar')

    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded p-4">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h5 class="mb-0">Edit hero slide</h5>
                <a href="{{ route('slides') }}" class="btn btn-secondary btn-sm">Back to slides</a>
            </div>

            <form action="{{ route('updateSlide', ['id' => $data->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    <div class="col-lg-5">
                        <label class="form-label">Current image</label>
                        <div class="border rounded overflow-hidden mb-3">
                            <img src="{{ $data->imageUrl() }}" alt="" class="w-100" style="max-height:220px;object-fit:cover">
                        </div>
                        <label class="form-label">Replace image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">Recommended 1900×800 px, under 650 KB.</small>
                    </div>
                    <div class="col-lg-7">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Caption</label>
                                <input type="text" name="caption" class="form-control" maxlength="120"
                                       value="{{ old('caption', $data->caption) }}"
                                       placeholder="Badge above heading">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sort order</label>
                                <input type="number" name="sort_order" class="form-control" min="0"
                                       value="{{ old('sort_order', $data->sort_order ?? 0) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Heading <span class="text-danger">*</span></label>
                                <input type="text" name="heading" class="form-control" required
                                       value="{{ old('heading', $data->heading) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Subheading</label>
                                <textarea name="subheading" class="form-control" rows="4" maxlength="500">{{ old('subheading', $data->subheading) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="Active" {{ old('status', $data->status ?? 'Active') === 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ old('status', $data->status ?? 'Active') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <p class="small text-muted mb-0">Homepage buttons: Rent a Car and Buy a Car link to the cars listings.</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
