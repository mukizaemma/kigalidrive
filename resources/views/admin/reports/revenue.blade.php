@extends('layouts.adminBase')

@section('content')
    @include('admin.includes.sidebar')
    <div class="content">
        @include('admin.includes.navbar')

        <div class="container-fluid pt-4 px-4">
            <div class="bg-light rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Revenue &amp; Commission Report</h6>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary btn-sm">Back to Bookings</a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="GET" action="{{ route('admin.reports.revenue') }}" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">From (check-out)</label>
                        <input type="date" name="start" class="form-control" value="{{ $start }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To (check-out)</label>
                        <input type="date" name="end" class="form-control" value="{{ $end }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Property</label>
                        <select name="property_id" class="form-select">
                            <option value="">All properties</option>
                            @foreach($propertiesList as $p)
                                <option value="{{ $p->id }}" {{ $propertyId == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Apply</button>
                    </div>
                </form>

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">Totals (all bookings except cancelled, check-out in range)</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">Includes confirmed and pending/unconfirmed bookings so Kigali Drive Rentals can see expected sales.</p>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Total Revenue:</strong>
                                <span class="h5 ms-2">${{ number_format($totals->total_revenue ?? 0, 2) }}</span>
                            </div>
                            <div class="col-md-6">
                                <strong>Total Kigali Drive Rentals Commission:</strong>
                                <span class="h5 ms-2">${{ number_format($totals->total_commission ?? 0, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0">Per property (what each property owes Kigali Drive Rentals)</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>Property</th>
                                    <th>Revenue</th>
                                    <th>Commission to Pay</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($byProperty as $row)
                                    @php $prop = $properties->get($row->property_id); @endphp
                                    <tr>
                                        <td>{{ $prop ? $prop->name : 'Property #' . $row->property_id }}</td>
                                        <td>${{ number_format($row->revenue ?? 0, 2) }}</td>
                                        <td>${{ number_format($row->commission ?? 0, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No bookings in this period.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.includes.footer')
@endsection
