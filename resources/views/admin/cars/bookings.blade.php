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

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

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
                                            @if($booking->rental_package)
                                                <strong>Package:</strong> {{ $booking->rental_package }}<br>
                                            @endif
                                            @if($booking->pickup_date)
                                                <strong>Pickup:</strong> {{ \Carbon\Carbon::parse($booking->pickup_date)->format('M d, Y') }}
                                                @if($booking->pickup_time)
                                                    {{ \Carbon\Carbon::parse($booking->pickup_time)->format('g:i A') }}
                                                @endif
                                                <br>
                                            @endif
                                            @if($booking->dropoff_date)
                                                <strong>Return:</strong> {{ \Carbon\Carbon::parse($booking->dropoff_date)->format('M d, Y') }}
                                                @if($booking->dropoff_time)
                                                    {{ \Carbon\Carbon::parse($booking->dropoff_time)->format('g:i A') }}
                                                @endif
                                            @endif
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($booking->total_amount)
                                            <strong>{{ formatUsd($booking->total_amount) }}</strong>
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
                                        <div class="btn-group flex-wrap">
                                            <button type="button" class="btn btn-sm btn-info" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#viewBookingModal{{ $booking->id }}"
                                                    title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </button>

                                            @if($booking->email)
                                            <form action="{{ route('admin.carBookings.resendEmail', $booking->id) }}"
                                                  method="POST"
                                                  style="display:inline;"
                                                  onsubmit="return confirm('Resend confirmation email to {{ $booking->email }}?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-primary" title="Resend confirmation email">
                                                    <i class="fa fa-envelope"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#updateEmailModal{{ $booking->id }}"
                                                    title="Send update email">
                                                <i class="fa fa-paper-plane"></i>
                                            </button>
                                            @endif
                                            
                                            @if($booking->rental_status == 'pending')
                                                <form action="{{ route('admin.carBookings.updateStatus', $booking->id) }}" 
                                                      method="POST" 
                                                      style="display:inline;"
                                                      onsubmit="return confirm('Confirm this booking and email the client?');">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="confirmed">
                                                    <input type="hidden" name="notify_client" value="1">
                                                    <button type="submit" class="btn btn-sm btn-success" title="Confirm &amp; notify">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                </form>
                                                
                                                <form action="{{ route('admin.carBookings.updateStatus', $booking->id) }}" 
                                                      method="POST" 
                                                      style="display:inline;"
                                                      onsubmit="return confirm('Cancel this booking and email the client?');">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <input type="hidden" name="notify_client" value="1">
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Cancel &amp; notify">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Send update email modal -->
                                @if($booking->email)
                                <div class="modal fade" id="updateEmailModal{{ $booking->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content text-start">
                                            <form action="{{ route('admin.carBookings.sendUpdate', $booking->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Email update — #{{ $booking->booking_number ?? $booking->id }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="small text-muted">Sending to <strong>{{ $booking->email }}</strong></p>
                                                    <div class="mb-3">
                                                        <label class="form-label">Optionally update status</label>
                                                        <select name="status" class="form-select">
                                                            <option value="">Keep current ({{ $booking->rental_status }})</option>
                                                            <option value="pending">Pending</option>
                                                            <option value="confirmed">Confirmed</option>
                                                            <option value="cancelled">Cancelled</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="form-label">Message to client <span class="text-danger">*</span></label>
                                                        <textarea name="admin_message" class="form-control" rows="4" required
                                                                  placeholder="e.g. Driver will meet you at the airport at 10:00. Please bring your passport."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fa fa-paper-plane me-1"></i>Send email
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif

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
                                                        @if($booking->rental_package && $booking->car)
                                                        <div class="col-md-12 mb-3">
                                                            <strong>Package:</strong><br>
                                                            {{ app(\App\Services\CarRentalPackageService::class)->labelFor($booking->car, $booking->rental_package) ?? $booking->rental_package }}
                                                        </div>
                                                        @endif
                                                        <div class="col-md-6 mb-3">
                                                            <strong>Pickup Location:</strong><br>
                                                            {{ $booking->pickup_location ?? 'N/A' }}
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <strong>Return Location:</strong><br>
                                                            {{ $booking->dropoff_location ?? 'N/A' }}
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <strong>Pickup:</strong><br>
                                                            {{ $booking->pickup_date ? \Carbon\Carbon::parse($booking->pickup_date)->format('M d, Y') : 'N/A' }}
                                                            @if($booking->pickup_time)
                                                                {{ \Carbon\Carbon::parse($booking->pickup_time)->format('g:i A') }}
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <strong>Return:</strong><br>
                                                            {{ $booking->dropoff_date ? \Carbon\Carbon::parse($booking->dropoff_date)->format('M d, Y') : 'N/A' }}
                                                            @if($booking->dropoff_time)
                                                                {{ \Carbon\Carbon::parse($booking->dropoff_time)->format('g:i A') }}
                                                            @endif
                                                        </div>
                                                    @endif
                                                    @if($booking->total_amount)
                                                        <div class="col-md-6 mb-3">
                                                            <strong>Total Amount:</strong><br>
                                                            <span style="font-size:18px;font-weight:700;">{{ formatUsd($booking->total_amount) }}</span>
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
                                                @if($booking->email)
                                                <form action="{{ route('admin.carBookings.resendEmail', $booking->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-primary">
                                                        <i class="fa fa-envelope me-2"></i>Resend confirmation
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#updateEmailModal{{ $booking->id }}">
                                                    <i class="fa fa-paper-plane me-2"></i>Send update
                                                </button>
                                                @endif
                                                @if($booking->rental_status == 'pending')
                                                    <form action="{{ route('admin.carBookings.updateStatus', $booking->id) }}" 
                                                          method="POST" 
                                                          style="display:inline;">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="confirmed">
                                                        <input type="hidden" name="notify_client" value="1">
                                                        <button type="submit" class="btn btn-success">
                                                            <i class="fa fa-check me-2"></i>Confirm &amp; notify
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




