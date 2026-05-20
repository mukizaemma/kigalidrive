@extends('layouts.adminBase')

@section('content')

@include('admin.includes.sidebar')

<div class="content">
    @include('admin.includes.navbar')

    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Car Rental Requests</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Car Type</th>
                            <th>People</th>
                            <th>Rental Date</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                            <tr>
                                <td>
                                    <strong>{{ $req->name }}</strong><br>
                                    <small>{{ $req->email }}</small><br>
                                    <small>{{ $req->phone }}</small>
                                </td>
                                <td>{{ $req->car_type ?? '-' }}</td>
                                <td>{{ $req->people ?? '-' }}</td>
                                <td>{{ $req->rental_date ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $req->status === 'responded' ? 'success' : 'warning' }}">
                                        {{ ucfirst($req->status) }}
                                    </span>
                                </td>
                                <td>{{ $req->created_at?->format('Y-m-d H:i') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.carRentalRequests.edit', $req->id) }}" class="btn btn-sm btn-primary">
                                        View / Reply
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No car rental requests yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</div>

@endsection

