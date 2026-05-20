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
                    <h6 class="mb-0">Booking Details: {{ $booking->reference_number ?? 'Booking #' . $booking->id }}</h6>
                    <div>
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-8 mx-auto">
                        <!-- Booking Information -->
                        <div class="card mb-3">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Booking Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Reference Number:</strong></div>
                                    <div class="col-md-8"><code>{{ $booking->reference_number ?? 'N/A' }}</code></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Status:</strong></div>
                                    <div class="col-md-8">
                                        @if($booking->booking_status == 'confirmed')
                                            <span class="badge bg-success">Confirmed</span>
                                        @elseif($booking->booking_status == 'cancelled')
                                            <span class="badge bg-danger">Cancelled</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Payment Status:</strong></div>
                                    <div class="col-md-8">
                                        @if($booking->payment_status == 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($booking->payment_status == 'refunded')
                                            <span class="badge bg-info">Refunded</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Check-in Date:</strong></div>
                                    <div class="col-md-8">
                                        {{ $booking->check_in ? \Carbon\Carbon::parse($booking->check_in)->format('F d, Y') : 'N/A' }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Check-out Date:</strong></div>
                                    <div class="col-md-8">
                                        {{ $booking->check_out ? \Carbon\Carbon::parse($booking->check_out)->format('F d, Y') : 'N/A' }}
                                    </div>
                                </div>
                                @if($booking->check_in && $booking->check_out)
                                    <div class="row mb-2">
                                        <div class="col-md-4"><strong>Duration:</strong></div>
                                        <div class="col-md-8">
                                            {{ \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out)) }} nights
                                        </div>
                                    </div>
                                @endif
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Number of Guests:</strong></div>
                                    <div class="col-md-8">{{ $booking->guests_count ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <div class="card mb-3">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">Customer Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Name:</strong></div>
                                    <div class="col-md-8">{{ $booking->guest_name ?? $booking->user->name ?? 'N/A' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Email:</strong></div>
                                    <div class="col-md-8">{{ $booking->guest_email ?? $booking->user->email ?? 'N/A' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Phone:</strong></div>
                                    <div class="col-md-8">{{ $booking->guest_phone ?? $booking->user->phone ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Property & Unit Information -->
                        <div class="card mb-3">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">Property & Unit Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Property:</strong></div>
                                    <div class="col-md-8">
                                        @if($booking->property)
                                            <a href="{{ route('admin.properties.show', $booking->property_id) }}">
                                                {{ $booking->property->name }}
                                            </a>
                                            <br><small class="text-muted">{{ ucfirst($booking->property->property_type) }}</small>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Unit:</strong></div>
                                    <div class="col-md-8">
                                        @if($booking->unit)
                                            <a href="{{ route('admin.units.edit', $booking->unit_id) }}">
                                                {{ $booking->unit->name ?? 'Unit #' . $booking->unit_id }}
                                            </a>
                                            @if($booking->unit->unitType)
                                                <br><small class="text-muted">{{ $booking->unit->unitType->name }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pricing Information -->
                        <div class="card mb-3">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">Pricing Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Total Amount:</strong></div>
                                    <div class="col-md-8">
                                        @if($booking->total_amount !== null)
                                            <strong class="h5">${{ number_format($booking->total_amount, 2) }}</strong>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </div>
                                </div>
                                @if(isset($booking->commission_amount) && $booking->commission_amount > 0)
                                    <div class="row mb-2">
                                        <div class="col-md-4"><strong>Kigali Drive Rentals Commission ({{ number_format($booking->commission_rate ?? 10, 0) }}%):</strong></div>
                                        <div class="col-md-8">${{ number_format($booking->commission_amount, 2) }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4"><strong>Amount to Property:</strong></div>
                                        <div class="col-md-8"><strong>${{ number_format($booking->total_amount - $booking->commission_amount, 2) }}</strong></div>
                                    </div>
                                @endif
                                @if($booking->tax_amount ?? null)
                                    <div class="row mb-2">
                                        <div class="col-md-4"><strong>Tax Amount:</strong></div>
                                        <div class="col-md-8">${{ number_format($booking->tax_amount, 2) }}</div>
                                    </div>
                                @endif
                                @if($booking->discount_amount ?? null)
                                    <div class="row mb-2">
                                        <div class="col-md-4"><strong>Discount:</strong></div>
                                        <div class="col-md-8">-${{ number_format($booking->discount_amount, 2) }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Additional Information -->
                        @if($booking->special_requests || $booking->description)
                            <div class="card mb-3">
                                <div class="card-header bg-secondary text-white">
                                    <h6 class="mb-0">Additional Information</h6>
                                </div>
                                <div class="card-body">
                                    @if($booking->special_requests)
                                        <div class="mb-3">
                                            <strong>Special Requests:</strong>
                                            <p class="mt-2">{{ $booking->special_requests }}</p>
                                        </div>
                                    @endif
                                    @if($booking->description)
                                        <div>
                                            <strong>Notes:</strong>
                                            <p class="mt-2">{{ $booking->description }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Status Update Form -->
                        <div class="card mb-3">
                            <div class="card-header bg-dark text-white">
                                <h6 class="mb-0">Update Status</h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.bookings.updateStatus', $booking->id) }}" method="POST">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="booking_status" class="form-label">Booking Status <span class="text-danger">*</span></label>
                                            <select name="booking_status" class="form-select" id="booking_status" required>
                                                <option value="pending" {{ $booking->booking_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="confirmed" {{ $booking->booking_status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                                <option value="cancelled" {{ $booking->booking_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="payment_status" class="form-label">Payment Status</label>
                                            <select name="payment_status" class="form-select" id="payment_status">
                                                <option value="pending" {{ $booking->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="paid" {{ $booking->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                                <option value="refunded" {{ $booking->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save me-2"></i>Update Status
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Comments / Feedback -->
                        <div class="card mb-3">
                            <div class="card-header bg-secondary text-white">
                                <h6 class="mb-0"><i class="fas fa-comments me-2"></i>Comments &amp; Feedback ({{ $booking->comments->count() }})</h6>
                            </div>
                            <div class="card-body">
                                @if($booking->comments->count() > 0)
                                    <div class="mb-4">
                                        @foreach($booking->comments as $c)
                                            <div class="border-start border-3 {{ $c->author_type === 'staff' ? 'border-primary' : 'border-info' }} ps-3 mb-3 py-2">
                                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-1">
                                                    <strong class="text-dark">
                                                        @if($c->author_type === 'staff')
                                                            <i class="fas fa-headset me-1 text-primary"></i> Kigali Drive Rentals staff
                                                        @else
                                                            <i class="fas fa-building me-1 text-info"></i> Property
                                                        @endif
                                                        · {{ $c->user->name ?? 'Team' }}
                                                    </strong>
                                                    <small class="text-muted">{{ $c->created_at->format('M j, Y g:i A') }}</small>
                                                </div>
                                                <p class="mb-0 mt-1">{{ $c->comment }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted mb-0">No comments yet. Add a comment to communicate with the guest.</p>
                                @endif
                                <hr>
                                <form action="{{ route('admin.bookings.comment.store', $booking->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-2">
                                        <label for="comment" class="form-label">Add comment / feedback</label>
                                        <textarea name="comment" id="comment" class="form-control @error('comment') is-invalid @enderror" rows="3" placeholder="Your message to the guest..." required maxlength="5000"></textarea>
                                        @error('comment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        <small class="text-muted">The guest will receive an email notification with this message.</small>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-1"></i>Send &amp; notify guest
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Stay modification (early checkout) -->
                        @if(($isOwner ?? false) || ($isAdmin ?? false))
                            <div class="card mb-3">
                                <div class="card-header {{ ($isAdmin ?? false) ? 'bg-dark' : 'bg-info' }} text-white">
                                    <h6 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Stay Modification (early checkout)</h6>
                                </div>
                                <div class="card-body">
                                    @if($booking->stayModifications && $booking->stayModifications->count() > 0)
                                        <div class="mb-3">
                                            @foreach($booking->stayModifications as $mod)
                                                <div class="border rounded p-3 mb-2">
                                                    <div class="d-flex justify-content-between flex-wrap">
                                                        <strong>Actual check-out: {{ $mod->actual_check_out ? \Carbon\Carbon::parse($mod->actual_check_out)->format('M j, Y') : 'N/A' }}</strong>
                                                        @if($mod->status === 'pending')
                                                            <span class="badge bg-warning">Pending</span>
                                                            @if($isAdmin ?? false)
                                                                <div class="mt-2 w-100">
                                                                    <form action="{{ route('admin.bookings.stay-modification.approve', [$booking->id, $mod->id]) }}" method="POST" class="d-inline">
                                                                        @csrf
                                                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                                                    </form>
                                                                    <form action="{{ route('admin.bookings.stay-modification.reject', [$booking->id, $mod->id]) }}" method="POST" class="d-inline">
                                                                        @csrf
                                                                        <input type="text" name="admin_notes" class="form-control form-control-sm d-inline-block w-auto ms-1" placeholder="Rejection reason (optional)">
                                                                        <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                                                    </form>
                                                                </div>
                                                            @endif
                                                        @elseif($mod->status === 'approved')
                                                            <span class="badge bg-success">Approved</span>
                                                            @if($mod->adjusted_total_amount !== null)
                                                                <small class="d-block mt-1">Adjusted total: ${{ number_format($mod->adjusted_total_amount, 2) }} · Commission: ${{ number_format($mod->adjusted_commission_amount ?? 0, 2) }}</small>
                                                            @endif
                                                        @else
                                                            <span class="badge bg-danger">Rejected</span>
                                                            @if($mod->admin_notes)
                                                                <small class="d-block mt-1 text-muted">{{ $mod->admin_notes }}</small>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <p class="mb-0 mt-2"><strong>Reason:</strong> {{ $mod->reason }}</p>
                                                    <small class="text-muted">Requested {{ $mod->created_at->format('M j, Y g:i A') }}@if($mod->reviewed_at) · Reviewed {{ $mod->reviewed_at->format('M j, Y') }}@endif</small>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if(($isOwner ?? false) && (!($booking->stayModifications ?? collect())->where('status', 'pending')->count()))
                                        <form action="{{ route('admin.bookings.stay-modification.store', $booking->id) }}" method="POST">
                                            @csrf
                                            <p class="text-muted small">If the guest left earlier than the booked check-out date, request a stay modification so Kigali Drive Rentals can adjust the commission.</p>
                                            <div class="row mb-2">
                                                <div class="col-md-4">
                                                    <label for="actual_check_out" class="form-label">Actual check-out date</label>
                                                    <input type="date" name="actual_check_out" id="actual_check_out" class="form-control" value="{{ old('actual_check_out', $booking->check_out ? \Carbon\Carbon::parse($booking->check_out)->format('Y-m-d') : '') }}" required>
                                                </div>
                                                <div class="col-md-8">
                                                    <label for="reason" class="form-label">Reason</label>
                                                    <input type="text" name="reason" id="reason" class="form-control" placeholder="e.g. Guest left early" value="{{ old('reason') }}" required maxlength="500">
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-sm">Request stay modification</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Timestamps -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <strong>Created:</strong> {{ $booking->created_at->format('F d, Y h:i A') }}
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <strong>Last Updated:</strong> {{ $booking->updated_at->format('F d, Y h:i A') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Content End -->

    @include('admin.includes.footer')
@endsection










