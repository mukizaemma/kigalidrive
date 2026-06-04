@extends('layouts.adminBase')

@section('content')
@include('admin.includes.sidebar')

<div class="content">
    @include('admin.includes.navbar')

    <div class="container-fluid pt-4 px-4">
        <ol class="breadcrumb mb-3">
            <li class="breadcrumb-item"><a href="{{ route('admin.services.index') }}">Services</a></li>
            <li class="breadcrumb-item active">{{ $service->exists ? 'Edit' : 'Add' }}</li>
        </ol>

        <div class="card">
            <div class="card-body">
                <form action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if($service->exists)
                        @method('PUT')
                    @endif

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $service->title) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sort order</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $service->sort_order ?? 0) }}" min="0">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Short excerpt</label>
                            <input type="text" name="excerpt" class="form-control" value="{{ old('excerpt', $service->excerpt) }}" maxlength="500" placeholder="Brief summary for cards (max 500 chars)">
                        </div>
                        <div class="col-12">
                            @include('admin.partials.rich-textarea', [
                                'name' => 'description',
                                'id' => 'serviceDescription',
                                'label' => 'Description',
                                'value' => $service->description,
                                'height' => 260,
                            ])
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Icon (Font Awesome class)</label>
                            <input type="text" name="icon" class="form-control" value="{{ old('icon', $service->icon) }}" placeholder="e.g. fa-car-side">
                            <small class="text-muted">Optional. Used when no cover image is set.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="Active" @selected(old('status', $service->status) === 'Active')>Active</option>
                                <option value="Inactive" @selected(old('status', $service->status) === 'Inactive')>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cover image</label>
                            @if($service->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/images/services/' . $service->image) }}" alt="" class="rounded" width="120">
                            </div>
                            @endif
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Save</button>
                        <a href="{{ route('admin.services.index') }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('admin.includes.footer')
@endsection
