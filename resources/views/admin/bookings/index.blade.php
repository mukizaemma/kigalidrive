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
            <div class="bg-light text-center rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Bookings Management</h6>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Filters -->
                <div class="row mb-3 text-start">
                    <div class="col-12">
                        <form method="GET" action="{{ route('admin.bookings.index') }}" class="row g-2 align-items-end">
                            <div class="col-lg-3 col-md-6">
                                <label class="form-label small mb-0 text-muted">Property / hotel</label>
                                <select name="listing" class="form-select form-select-sm">
                                    <option value="">All listings</option>
                                    @if(isset($hotelsForFilter) && $hotelsForFilter->isNotEmpty())
                                        <optgroup label="Hotels (owner listings)">
                                            @foreach($hotelsForFilter as $h)
                                                <option value="hotel:{{ $h->id }}" {{ request('listing') === 'hotel:'.$h->id ? 'selected' : '' }}>{{ $h->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                    @if(isset($properties) && $properties->isNotEmpty())
                                        <optgroup label="Properties (admin)">
                                            @foreach($properties as $property)
                                                <option value="property:{{ $property->id }}" {{ request('listing') === 'property:'.$property->id ? 'selected' : '' }}>{{ $property->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <label class="form-label small mb-0 text-muted">Stay from</label>
                                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <label class="form-label small mb-0 text-muted">Stay through</label>
                                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-lg-1 col-md-4">
                                <label class="form-label small mb-0 text-muted">Status</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">All</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="availability_requested" {{ request('status') == 'availability_requested' ? 'selected' : '' }}>Requested</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-lg-1 col-md-4">
                                <label class="form-label small mb-0 text-muted">Payment</label>
                                <select name="payment_status" class="form-select form-select-sm">
                                    <option value="">All</option>
                                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <label class="form-label small mb-0 text-muted">Search</label>
                                <input type="text" name="search" class="form-control form-control-sm" placeholder="Reference, guest…" value="{{ request('search') }}">
                            </div>
                            <div class="col-lg-1 col-md-4">
                                <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                            </div>
                            <div class="col-12">
                                <a href="{{ route('admin.bookings.index') }}" class="small">Clear filters</a>
                            </div>
                        </form>
                        <p class="text-muted small mt-2 mb-0 text-start">Date range: bookings that overlap the selected stay window (check-in before end date and check-out after start date).</p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table text-start align-middle table-bordered table-hover mb-0">
                        <thead>
                            <tr class="text-dark">
                                <th scope="col">Reference</th>
                                <th scope="col">Customer</th>
                                <th scope="col">Property</th>
                                <th scope="col">Unit</th>
                                <th scope="col">Check-in</th>
                                <th scope="col">Check-out</th>
                                <th scope="col">Guests</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Status</th>
                                <th scope="col">Payment</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                                <tr>
                                    <td>
                                        <strong>{{ $booking->reference_number ?? 'N/A' }}</strong>
                                    </td>
                                    <td>
                                        {{ $booking->guest_name ?? optional($booking->user)->name ?? 'N/A' }}
                                        <br><small class="text-muted">{{ $booking->guest_email ?? optional($booking->user)->email ?? '—' }}</small>
                                    </td>
                                    <td>
                                        @if($booking->property)
                                            <a href="{{ route('admin.properties.show', $booking->property_id) }}">
                                                {{ $booking->property->name }}
                                            </a>
                                        @elseif($booking->hotel)
                                            <a href="{{ route('admin.properties.show', $booking->hotel_id) }}">{{ $booking->hotel->name }}</a>
                                            <span class="badge bg-secondary ms-1">Hotel</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($booking->unit)
                                            <a href="{{ route('admin.units.edit', $booking->unit_id) }}">
                                                {{ $booking->unit->name ?? 'Unit #' . $booking->unit_id }}
                                            </a>
                                        @elseif($booking->room)
                                            <span>{{ $booking->room->room_type ?? 'Room #'.$booking->room_id }}</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $booking->check_in ? \Carbon\Carbon::parse($booking->check_in)->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $booking->check_out ? \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td>{{ $booking->guests_count ?? 'N/A' }}</td>
                                    <td>
                                        @if($booking->total_amount)
                                            <strong>${{ number_format($booking->total_amount, 2) }}</strong>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($booking->booking_status == 'confirmed')
                                            <span class="badge bg-success">Confirmed</span>
                                        @elseif($booking->booking_status == 'cancelled')
                                            <span class="badge bg-danger">Cancelled</span>
                                        @elseif($booking->booking_status == 'availability_requested')
                                            <span class="badge bg-info text-dark">Requested</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($booking->payment_status == 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($booking->payment_status == 'refunded')
                                            <span class="badge bg-info">Refunded</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-info btn-sm">
                                                <i class="fa fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('admin.bookings.destroy', $booking->id) }}" 
                                               class="btn btn-danger btn-sm" 
                                               onclick="return confirm('Are you sure you want to delete this booking?')">
                                                <i class="fa fa-trash"></i> Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">
                                        <p class="text-muted">No bookings found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
    </div>
    <!-- Content End -->

    @include('admin.includes.footer')
@endsection










