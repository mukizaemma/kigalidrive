@extends('layouts.adminBase')

@section('content')
@include('admin.includes.sidebar')

<div class="content">
    @include('admin.includes.navbar')

    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded p-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                <div>
                    <h5 class="mb-1">Homepage hero slides</h5>
                    <p class="text-muted small mb-0">Caption, heading, subheading, and image. Buttons on the site link to car listings.</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#slideImage">
                        Add slide
                    </button>
                    <a href="{{ route('aboutPage') }}" class="btn btn-secondary">Back to Story</a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle table-bordered table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:140px">Image</th>
                            <th>Caption</th>
                            <th>Heading</th>
                            <th>Subheading</th>
                            <th>Status</th>
                            <th style="width:120px">Order</th>
                            <th style="width:160px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($slides as $rs)
                        <tr>
                            <td>
                                <a href="{{ route('editSlide', $rs->id) }}">
                                    <img src="{{ $rs->imageUrl() }}" alt="" class="rounded" width="120" height="68" style="object-fit:cover">
                                </a>
                            </td>
                            <td class="small">{{ $rs->caption ?: '—' }}</td>
                            <td><a href="{{ route('editSlide', $rs->id) }}">{{ $rs->heading }}</a></td>
                            <td class="small text-muted">{{ \Illuminate\Support\Str::limit($rs->subheading, 80) ?: '—' }}</td>
                            <td>
                                <span class="badge {{ ($rs->status ?? 'Active') === 'Active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $rs->status ?? 'Active' }}
                                </span>
                            </td>
                            <td>{{ $rs->sort_order ?? 0 }}</td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    <a href="{{ route('editSlide', $rs->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="{{ route('destroySlide', $rs->id) }}" class="btn btn-sm btn-danger"
                                       onclick="return confirm('Delete this slide?')">Delete</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No slides yet. Add your first hero slide.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="slideImage" tabindex="-1" aria-labelledby="slideImageLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('saveSlide') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="slideImageLabel">Add hero slide</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Hero image <span class="text-danger">*</span></label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                            <small class="text-muted">Recommended 1900×800 px, under 650 KB.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Caption</label>
                            <input type="text" name="caption" class="form-control" maxlength="120"
                                   placeholder="e.g. Now available in Kigali">
                            <small class="text-muted">Small badge above the heading.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sort order</label>
                            <input type="number" name="sort_order" class="form-control" min="0" value="0">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Heading <span class="text-danger">*</span></label>
                            <input type="text" name="heading" class="form-control" required
                                   placeholder="Main headline on the slide">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Subheading</label>
                            <textarea name="subheading" class="form-control" rows="3" maxlength="500"
                                      placeholder="Supporting line under the heading"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="Active" selected>Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <p class="small text-muted mb-0">
                                On the homepage, buttons link to <strong>Rent a Car</strong> and <strong>Buy a Car</strong> listings.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save slide</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
