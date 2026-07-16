@extends('layouts.adminBase')

@section('content')
@include('admin.includes.sidebar')
<div class="content">
    @include('admin.includes.navbar')
    <div class="container-fluid pt-4 px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Car details</h4>
                <p class="text-muted small mb-0">Manage tags admins can assign to cars (driver, fuel, condition, etc.).</p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCarDetailModal">
                <i class="fa fa-plus me-1"></i> Add detail
            </button>
        </div>

        <div class="bg-light rounded p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:60px">Order</th>
                            <th style="width:60px">Icon</th>
                            <th>Name</th>
                            <th style="width:100px">Status</th>
                            <th style="width:160px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($details as $detail)
                        <tr>
                            <td>{{ $detail->sort_order }}</td>
                            <td><i class="{{ $detail->iconClass() }}"></i></td>
                            <td>
                                <strong>{{ $detail->name }}</strong>
                                <div class="text-muted small">{{ $detail->slug }}</div>
                            </td>
                            <td>
                                @if($detail->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Hidden</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editCarDetail{{ $detail->id }}">Edit</button>
                                <form action="{{ route('admin.car-details.destroy', $detail) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this detail? It will be removed from all cars.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>

                        <div class="modal fade" id="editCarDetail{{ $detail->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.car-details.update', $detail) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit detail</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control" value="{{ old('name', $detail->name) }}" required maxlength="120">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Icon class</label>
                                                <input type="text" name="icon" class="form-control" value="{{ old('icon', $detail->icon) }}" placeholder="fa-user-tie">
                                                <small class="text-muted">Font Awesome class without the <code>fas</code> prefix is fine.</small>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Sort order</label>
                                                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $detail->sort_order) }}" min="0">
                                            </div>
                                            <div class="form-check">
                                                <input type="hidden" name="is_active" value="0">
                                                <input class="form-check-input" type="checkbox" name="is_active" id="editActive{{ $detail->id }}" value="1" @checked(old('is_active', $detail->is_active))>
                                                <label class="form-check-label" for="editActive{{ $detail->id }}">Active (shown on car forms)</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No details yet. Add your first tag above.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addCarDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.car-details.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required maxlength="120" placeholder="With driver">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icon class</label>
                        <input type="text" name="icon" class="form-control" value="{{ old('icon') }}" placeholder="fa-user-tie">
                        <small class="text-muted">Font Awesome class, e.g. <code>fa-gas-pump</code>.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sort order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                    </div>
                    <div class="form-check">
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-check-input" type="checkbox" name="is_active" id="addActive" value="1" @checked(old('is_active', true))>
                        <label class="form-check-label" for="addActive">Active (shown on car forms)</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add detail</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('admin.includes.footer')
@endsection
