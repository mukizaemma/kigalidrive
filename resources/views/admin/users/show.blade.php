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

        <!-- User Details Start -->
        <div class="container-fluid pt-4 px-4">
            <div class="bg-light rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">User Details</h6>
                    <a href="{{ route('users') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-2"></i>Back to Users
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0 ps-3">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- User Information Card -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-user me-2"></i>User Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Name:</strong> {{ $user->name }}</p>
                                        <p><strong>Email:</strong> {{ $user->email }}</p>
                                        <p><strong>Email Verified:</strong> 
                                            @if($user->hasVerifiedEmail())
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i>Verified
                                                </span>
                                            @else
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-exclamation-circle me-1"></i>Not Verified
                                                </span>
                                                <a href="{{ route('admin.users.verify', $user->id) }}" class="btn btn-success btn-sm ms-2" 
                                                   onclick="return confirm('Are you sure you want to verify this user\'s email?')">
                                                    <i class="fas fa-check-circle me-1"></i>Verify Email
                                                </a>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Registered:</strong> {{ $user->created_at->format('F d, Y h:i A') }}</p>
                                        <p><strong>Total Properties:</strong> <span class="badge bg-info">{{ $user->properties->count() }}</span></p>
                                        <p><strong>Total Bookings (as guest):</strong> <span class="badge bg-primary">{{ $user->hotelBookings->count() }}</span></p>
                                        <p><strong>Account status:</strong>
                                            @if(($user->status ?? 'Active') === 'Active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Suspended</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                @if(isset($isSuperAdmin) && $isSuperAdmin)
                                    @if($user->isPrimarySuperAdmin())
                                        <div class="mt-4 p-3 border rounded bg-white text-start">
                                            <p class="mb-0 text-muted small">This is the primary super admin account (<code>admin@iremetech.com</code>). Role and access cannot be changed here.</p>
                                        </div>
                                    @else
                                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="mt-4 p-3 border rounded bg-white text-start">
                                    @csrf
                                    <h6 class="mb-3 fw-semibold">Role &amp; access</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="edit_role" class="form-label">Role</label>
                                            <select name="role" id="edit_role" class="form-select" required>
                                                <option value="0" {{ (string) old('role', $user->role) === '0' || (int) old('role', $user->role) === 0 ? 'selected' : '' }}>User</option>
                                                <option value="2" {{ (string) old('role', $user->role) === '2' || (int) old('role', $user->role) === 2 ? 'selected' : '' }}>Admin</option>
                                            </select>
                                            <small class="text-muted d-block mt-1">Only <code>admin@iremetech.com</code> is the primary super admin.</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="edit_status" class="form-label">Account access</label>
                                            <select name="status" id="edit_status" class="form-select" required>
                                                <option value="Active" {{ old('status', $user->status ?? 'Active') === 'Active' ? 'selected' : '' }}>Active — can sign in</option>
                                                <option value="Inactive" {{ old('status', $user->status ?? 'Active') === 'Inactive' ? 'selected' : '' }}>Suspended — cannot sign in</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mt-3 d-flex flex-wrap gap-2">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-save me-1"></i>Save changes
                                        </button>
                                        @if(!$user->isAdmin())
                                            <a href="{{ route('makeAdmin', ['id' => $user->id]) }}" class="btn btn-outline-info btn-sm"
                                               onclick="return confirm('Grant this user admin panel access?');">
                                                <i class="fas fa-user-shield me-1"></i>Make Admin
                                            </a>
                                        @else
                                            <a href="{{ route('removeAdmin', ['id' => $user->id]) }}" class="btn btn-outline-warning btn-sm"
                                               onclick="return confirm('Remove admin access from this user?');">
                                                <i class="fas fa-user-slash me-1"></i>Remove Admin
                                            </a>
                                        @endif
                                        @if((int) $user->id !== (int) auth()->id())
                                        <a href="{{ route('deleteUser', ['id' => $user->id]) }}" class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Are you sure you want to delete this user? This cannot be undone.')">
                                            <i class="fas fa-trash me-1"></i>Delete User
                                        </a>
                                        @endif
                                    </div>
                                </form>
                                    @endif
                                @endif
                                <div class="mt-3 p-3 border rounded bg-light">
                                    <h6 class="fw-semibold mb-2">Password</h6>
                                    <form action="{{ route('admin.users.set-password', $user->id) }}" method="POST" class="mb-2"
                                          onsubmit="return confirm('Set a new password for {{ $user->email }}?');">
                                        @csrf
                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-5">
                                                <label class="form-label small mb-0">New password</label>
                                                <input type="password" name="new_password" class="form-control form-control-sm" required minlength="8" autocomplete="new-password">
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label small mb-0">Confirm</label>
                                                <input type="password" name="new_password_confirmation" class="form-control form-control-sm" required minlength="8" autocomplete="new-password">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-primary btn-sm w-100">Set password</button>
                                            </div>
                                        </div>
                                    </form>
                                    <form action="{{ route('admin.users.password-reset', $user->id) }}" method="POST"
                                          onsubmit="return confirm('Send a password reset link to {{ $user->email }}?');">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-envelope me-1"></i>Or send reset email
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Properties Section -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-building me-2"></i>Properties ({{ $user->properties->count() }})</h5>
                            </div>
                            <div class="card-body">
                                @if($user->properties->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Type</th>
                                                    <th>Location</th>
                                                    <th>Status</th>
                                                    <th>Created</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($user->properties as $property)
                                                <tr>
                                                    <td>
                                                        @if($property->featured_image)
                                                            <img src="{{ asset('storage/images/properties/' . $property->featured_image) }}" 
                                                                 alt="{{ $property->name }}" 
                                                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; margin-right: 10px;">
                                                        @endif
                                                        {{ $property->name }}
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary">{{ ucfirst($property->property_type) }}</span>
                                                    </td>
                                                    <td>{{ $property->location ?? $property->city ?? $property->address ?? 'N/A' }}</td>
                                                    <td>
                                                        @if($property->status == 'Active')
                                                            <span class="badge bg-success">{{ $property->status }}</span>
                                                        @elseif($property->status == 'Pending')
                                                            <span class="badge bg-warning">{{ $property->status }}</span>
                                                        @else
                                                            <span class="badge bg-danger">{{ $property->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $property->created_at->format('M d, Y') }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.properties.edit', $property->id) }}" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted text-center py-4">No properties found for this user.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bookings Section -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Bookings ({{ $user->hotelBookings->count() }})</h5>
                            </div>
                            <div class="card-body">
                                @if($user->hotelBookings->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Reference</th>
                                                    <th>Property</th>
                                                    <th>Unit/Room</th>
                                                    <th>Check-in</th>
                                                    <th>Check-out</th>
                                                    <th>Guests</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Payment</th>
                                                    <th>Created</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($user->hotelBookings as $booking)
                                                <tr>
                                                    <td><strong>{{ $booking->reference_number }}</strong></td>
                                                    <td>
                                                        @if($booking->property)
                                                            {{ $booking->property->name }}
                                                        @else
                                                            <span class="text-muted">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($booking->unit)
                                                            {{ $booking->unit->name }}
                                                        @else
                                                            <span class="text-muted">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($booking->check_in)->format('M d, Y') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}</td>
                                                    <td>{{ $booking->guests_count }}</td>
                                                    <td>${{ number_format($booking->total_amount, 2) }}</td>
                                                    <td>
                                                        @if($booking->booking_status == 'confirmed')
                                                            <span class="badge bg-success">{{ ucfirst($booking->booking_status) }}</span>
                                                        @elseif($booking->booking_status == 'pending')
                                                            <span class="badge bg-warning">{{ ucfirst($booking->booking_status) }}</span>
                                                        @else
                                                            <span class="badge bg-danger">{{ ucfirst($booking->booking_status) }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($booking->payment_status == 'paid')
                                                            <span class="badge bg-success">{{ ucfirst($booking->payment_status) }}</span>
                                                        @elseif($booking->payment_status == 'pending')
                                                            <span class="badge bg-warning">{{ ucfirst($booking->payment_status) }}</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ ucfirst($booking->payment_status) }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $booking->created_at->format('M d, Y') }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.bookings.show', $booking->id) ?? '#' }}" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-eye"></i> View
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted text-center py-4">No bookings found for this user.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- User Details End -->

        <!-- Footer Start -->
        <div class="container-fluid pt-4 px-4">
            <div class="bg-light rounded-top p-4">
                <div class="row">
                    <div class="col-12 col-sm-6 text-center text-sm-start">
                        &copy; <a href="#">Accommodation Booking Engine</a>, All Right Reserved. 
                    </div>
                    <div class="col-12 col-sm-6 text-center text-sm-end">
                        Designed By <a href="https://iremetech.com">Ireme Technologies</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->
    </div>
    <!-- Content End -->

    @include('admin.includes.footer')
@endsection

