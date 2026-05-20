@extends('layouts.adminBase')

@section('content')
    @include('admin.includes.sidebar')
    <div class="content">
        @include('admin.includes.navbar')

        <div class="container-fluid pt-4 px-4">
            <div class="bg-light rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Trip Requests</h6>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form class="row g-3 mb-3" method="GET" action="{{ route('admin.tripRequests.index') }}">
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100" type="submit">Filter</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table text-start align-middle table-bordered table-hover mb-0">
                        <thead>
                            <tr class="text-dark">
                                <th>Requester</th>
                                <th>Destination</th>
                                <th>Activities</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $requestItem)
                                @php
                                    $tripIds = $requestItem->selected_trip_ids ? json_decode($requestItem->selected_trip_ids, true) : [];
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $requestItem->names }}</strong><br>
                                        <small class="text-muted">{{ $requestItem->email }}</small>
                                    </td>
                                    <td>{{ $requestItem->tripDestination->name ?? 'N/A' }}</td>
                                    <td>{{ is_array($tripIds) ? count($tripIds) : 0 }}</td>
                                    <td>
                                        <span class="badge bg-{{ $requestItem->status == 'confirmed' ? 'success' : ($requestItem->status == 'cancelled' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($requestItem->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $requestItem->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.tripRequests.show', $requestItem->id) }}" class="btn btn-sm btn-info">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">No trip requests found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($requests->hasPages())
                    <div class="mt-4">
                        {{ $requests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
