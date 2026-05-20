@extends('layouts.adminBase')

@section('content')
    @include('admin.includes.sidebar')
    <div class="content">
        @include('admin.includes.navbar')

        <div class="container-fluid pt-4 px-4">
            <div class="bg-light rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Trip Request Details</h6>
                    <a href="{{ route('admin.tripRequests.index') }}" class="btn btn-secondary">
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
                    <div class="col-md-6">
                        <h6>Requester</h6>
                        <p><strong>{{ $requestItem->names }}</strong><br>{{ $requestItem->email }}<br>{{ $requestItem->phone }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($requestItem->status) }}</p>
                        <p><strong>Destination:</strong> {{ $requestItem->tripDestination->name ?? 'N/A' }}</p>
                        <p><strong>Message:</strong><br>{{ $requestItem->message }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Selected Activities</h6>
                        <ul>
                            @forelse($selectedTrips as $trip)
                                <li>{{ $trip->title }}</li>
                            @empty
                                <li>No specific activities selected.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <hr>

                <form action="{{ route('admin.tripRequests.update', $requestItem->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="pending" {{ $requestItem->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $requestItem->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="cancelled" {{ $requestItem->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Quoted Cost</label>
                            <input type="number" step="0.01" name="quoted_cost" class="form-control" value="{{ $requestItem->quoted_cost }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Admin Response / Trip Plan</label>
                            <textarea name="admin_response" class="form-control" rows="4">{{ $requestItem->admin_response }}</textarea>
                        </div>
                    </div>

                    <div class="mt-3 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i>Save Response
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
