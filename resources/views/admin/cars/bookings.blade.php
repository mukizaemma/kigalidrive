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
            <div class="bg-light rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Car Booking Requests</h6>
                    <a href="{{ route('getCars') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left me-2"></i>Back to Cars
                    </a>
                </div>

                <!-- Filter Tabs -->
                <ul class="nav nav-tabs mb-4" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ $filters['status'] == 'all' ? 'active' : '' }}" 
                           href="{{ route('admin.carBookings.index', ['booking_type' => $filters['booking_type']]) }}">
                            All <span class="badge bg-secondary">{{ $counts['all'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $filters['status'] == 'pending' ? 'active' : '' }}" 
                           href="{{ route('admin.carBookings.index', ['status' => 'pending', 'booking_type' => $filters['booking_type']]) }}">
                            Pending <span class="badge bg-warning">{{ $counts['pending'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $filters['status'] == 'confirmed' ? 'active' : '' }}" 
                           href="{{ route('admin.carBookings.index', ['status' => 'confirmed', 'booking_type' => $filters['booking_type']]) }}">
                            Confirmed <span class="badge bg-success">{{ $counts['confirmed'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $filters['status'] == 'cancelled' ? 'active' : '' }}" 
                           href="{{ route('admin.carBookings.index', ['status' => 'cancelled', 'booking_type' => $filters['booking_type']]) }}">
                            Cancelled <span class="badge bg-danger">{{ $counts['cancelled'] }}</span>
                        </a>
                    </li>
                </ul>

                <!-- Booking Type Filter -->
                <div class="mb-3">
                    <label class="form-label">Filter by Booking Type:</label>
                    <select class="form-select w-auto d-inline-block" id="bookingTypeFilter" onchange="filterByType(this.value)">
                        <option value="all" {{ $filters['booking_type'] == 'all' ? 'selected' : '' }}>All Types</option>
                        <option value="view_car" {{ $filters['booking_type'] == 'view_car' ? 'selected' : '' }}>View Car</option>
                        <option value="rent" {{ $filters['booking_type'] == 'rent' ? 'selected' : '' }}>Rent</option>
                        <option value="buy" {{ $filters['booking_type'] == 'buy' ? 'selected' : '' }}>Buy</option>
                    </select>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Car</th>
                                <th>Customer</th>
                                <th>Type</th>
                                <th>Date/Time</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                                <tr>
                                    <td>#{{ $booking->id }}</td>
                                    <td>
                                        <strong>{{ $booking->car->name ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $booking->car->model ?? '' }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $booking->name }}</strong><br>
                                        <small class="text-muted">
                                            {{ $booking->email }}<br>
                                            {{ $booking->phone }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($booking->booking_type == 'view_car')
                                            <span class="badge bg-info">View Car</span>
                                        @elseif($booking->booking_type == 'rent')
                                            <span class="badge bg-primary">Rent</span>
                                        @elseif($booking->booking_type == 'buy')
                                            <span class="badge bg-success">Buy</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($booking->booking_type == 'view_car')
                                            @if($booking->preferred_date)
                                                <strong>Date:</strong> {{ \Carbon\Carbon::parse($booking->preferred_date)->format('M d, Y') }}<br>
                                            @endif
                                            @if($booking->preferred_time)
                                                <strong>Time:</strong> {{ \Carbon\Carbon::parse($booking->preferred_time)->format('h:i A') }}
                                            @endif
                                        @elseif($booking->booking_type == 'rent')
                                            @if($booking->pickup_date)
                                                <strong>Pickup:</strong> {{ \Carbon\Carbon::parse($booking->pickup_date)->format('M d, Y') }}<br>
                                            @endif
                                            @if($booking->dropoff_date)
                                                <strong>Drop-off:</strong> {{ \Carbon\Carbon::parse($booking->dropoff_date)->format('M d, Y') }}
                                            @endif
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($booking->total_amount)
                                            <strong>{{ number_format($booking->total_amount) }} RWF</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($booking->rental_status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($booking->rental_status == 'confirmed')
                                            <span class="badge bg-success">Confirmed</span>
                                        @elseif($booking->rental_status == 'cancelled')
                                            <span class="badge bg-danger">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($booking->payment_status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($booking->payment_status == 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($booking->payment_status == 'refunded')
                                            <span class="badge bg-info">Refunded</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-info" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#viewBookingModal{{ $booking->id }}"
                                                    title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            
                                            @if($booking->rental_status == 'pending')
                                                <form action="{{ route('admin.carBookings.updateStatus', $booking->id) }}" 
                                                      method="POST" 
                                                      style="display:inline;"
                                                      onsubmit="return confirm('Are you sure you want to confirm this booking?');">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="confirmed">
                                                    <button type="submit" class="btn btn-sm btn-success" title="Confirm">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                </form>
                                                
                                                <form action="{{ route('admin.carBookings.updateStatus', $booking->id) }}" 
                                                      method="POST" 
                                                      style="display:inline;"
                                                      onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Cancel">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- View Booking Modal -->
                                <div class="modal fade" id="viewBookingModal{{ $booking->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Booking Details #{{ $booking->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <strong>Car:</strong><br>
                                                        {{ $booking->car->name ?? 'N/A' }}<br>
                                                        <small class="text-muted">{{ $booking->car->model ?? '' }}</small>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <strong>Booking Type:</strong><br>
                                                        @if($booking->booking_type == 'view_car')
                                                            <span class="badge bg-info">View Car</span>
                                                        @elseif($booking->booking_type == 'rent')
                                                            <span class="badge bg-primary">Rent</span>
                                                        @elseif($booking->booking_type == 'buy')
                                                            <span class="badge bg-success">Buy</span>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <strong>Customer Name:</strong><br>
                                                        {{ $booking->name }}
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <strong>Email:</strong><br>
                                                        {{ $booking->email }}
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <strong>Phone:</strong><br>
                                                        {{ $booking->phone }}
                                                    </div>
                                                    @if($booking->booking_type == 'view_car')
                                                        <div class="col-md-6 mb-3">
                                                            <strong>Preferred Date:</strong><br>
                                                            {{ $booking->preferred_date ? \Carbon\Carbon::parse($booking->preferred_date)->format('M d, Y') : 'N/A' }}
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <strong>Preferred Time:</strong><br>
                                                            {{ $booking->preferred_time ? \Carbon\Carbon::parse($booking->preferred_time)->format('h:i A') : 'N/A' }}
                                                        </div>
                                                    @elseif($booking->booking_type == 'rent')
                                                        <div class="col-md-6 mb-3">
                                                            <strong>Pickup Location:</strong><br>
                                                            {{ $booking->pickup_location ?? 'N/A' }}
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <strong>Drop-off Location:</strong><br>
                                                            {{ $booking->dropoff_location ?? 'N/A' }}
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <strong>Pickup Date:</strong><br>
                                                            {{ $booking->pickup_date ? \Carbon\Carbon::parse($booking->pickup_date)->format('M d, Y') : 'N/A' }}
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <strong>Drop-off Date:</strong><br>
                                                            {{ $booking->dropoff_date ? \Carbon\Carbon::parse($booking->dropoff_date)->format('M d, Y') : 'N/A' }}
                                                        </div>
                                                    @endif
                                                    @if($booking->total_amount)
                                                        <div class="col-md-6 mb-3">
                                                            <strong>Total Amount:</strong><br>
                                                            <span style="font-size:18px;font-weight:700;">{{ number_format($booking->total_amount) }} RWF</span>
                                                        </div>
                                                    @endif
                                                    <div class="col-md-6 mb-3">
                                                        <strong>Status:</strong><br>
                                                        @if($booking->rental_status == 'pending')
                                                            <span class="badge bg-warning">Pending</span>
                                                        @elseif($booking->rental_status == 'confirmed')
                                                            <span class="badge bg-success">Confirmed</span>
                                                        @elseif($booking->rental_status == 'cancelled')
                                                            <span class="badge bg-danger">Cancelled</span>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <strong>Payment Status:</strong><br>
                                                        @if($booking->payment_status == 'pending')
                                                            <span class="badge bg-warning">Pending</span>
                                                        @elseif($booking->payment_status == 'paid')
                                                            <span class="badge bg-success">Paid</span>
                                                        @elseif($booking->payment_status == 'refunded')
                                                            <span class="badge bg-info">Refunded</span>
                                                        @endif
                                                    </div>
                                                    @if($booking->message)
                                                        <div class="col-12 mb-3">
                                                            <strong>Message:</strong><br>
                                                            {{ $booking->message }}
                                                        </div>
                                                    @endif
                                                    <div class="col-12 mb-3">
                                                        <strong>Submitted:</strong><br>
                                                        {{ $booking->created_at->format('M d, Y h:i A') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                @if($booking->rental_status == 'pending')
                                                    <form action="{{ route('admin.carBookings.updateStatus', $booking->id) }}" 
                                                          method="POST" 
                                                          style="display:inline;">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="confirmed">
                                                        <button type="submit" class="btn btn-success">
                                                            <i class="fa fa-check me-2"></i>Confirm Booking
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">
                                        No booking requests found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4 d-flex justify-content-center">
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
    </div>
    <!-- Content End -->

    @include('admin.includes.footer')

    <script>
        function filterByType(type) {
            const url = new URL(window.location.href);
            if (type === 'all') {
                url.searchParams.delete('booking_type');
            } else {
                url.searchParams.set('booking_type', type);
            }
            window.location.href = url.toString();
        }
    </script>
@endsection




