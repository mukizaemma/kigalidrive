@extends('layouts.adminBase')

@section('content')

@include('admin.includes.sidebar')

<div class="content">
    @include('admin.includes.navbar')

    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">Car Rental Request from {{ $requestItem->name }}</h5>
                <a href="{{ route('admin.carRentalRequests.index') }}" class="btn btn-secondary btn-sm">Back to Requests</a>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h6>Request Details</h6>
                    <p><strong>Name:</strong> {{ $requestItem->name }}</p>
                    <p><strong>Email:</strong> {{ $requestItem->email }}</p>
                    <p><strong>Phone:</strong> {{ $requestItem->phone }}</p>
                    <p><strong>Car Type Needed:</strong> {{ $requestItem->car_type ?? '-' }}</p>
                    <p><strong>Number of People:</strong> {{ $requestItem->people ?? '-' }}</p>
                    <p><strong>Rental Date:</strong> {{ $requestItem->rental_date ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <h6>Additional Details</h6>
                    <p style="white-space: pre-line;">{{ $requestItem->message ?? '-' }}</p>
                </div>
            </div>

            <form action="{{ route('admin.carRentalRequests.update', $requestItem->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="pending" {{ $requestItem->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="responded" {{ $requestItem->status === 'responded' ? 'selected' : '' }}>Responded</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Admin Reply / Notes</label>
                    <textarea name="admin_reply" rows="5" class="form-control">{{ old('admin_reply', $requestItem->admin_reply) }}</textarea>
                    <small class="text-muted">You can paste email content or internal notes here.</small>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i>Save Changes
                </button>
            </form>
        </div>
    </div>
</div>

@endsection

