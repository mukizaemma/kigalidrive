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


            <!-- Sale & Revenue Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-sm-6 col-xl-3">
                        <a href="{{ route('admin.properties.index') }}" class="text-decoration-none">
                            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 h-100 hover-shadow">
                                <i class="fa fa-building fa-3x text-primary"></i>
                                <div class="ms-3 text-end">
                                    <p class="mb-2 text-secondary">Total Properties</p>
                                    <h6 class="mb-0 text-dark">{{ $totalProperties ?? 0 }}</h6>
                                    <small class="text-muted">View all</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <a href="{{ route('admin.properties.index', ['mine' => 1]) }}" class="text-decoration-none">
                            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 h-100 hover-shadow">
                                <i class="fa fa-user-check fa-3x text-primary"></i>
                                <div class="ms-3 text-end">
                                    <p class="mb-2 text-secondary">My properties</p>
                                    <h6 class="mb-0 text-dark">{{ $myPropertiesCount ?? 0 }}</h6>
                                    <small class="text-muted">Listings you added</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <a href="{{ route('admin.units.index') }}" class="text-decoration-none">
                            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 h-100 hover-shadow">
                                <i class="fa fa-door-open fa-3x text-primary"></i>
                                <div class="ms-3 text-end">
                                    <p class="mb-2 text-secondary">Total Rooms</p>
                                    <h6 class="mb-0 text-dark">{{ $totalRooms ?? 0 }}</h6>
                                    <small class="text-muted">View all</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <a href="{{ route('admin.bookings.index') }}" class="text-decoration-none">
                            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 h-100 hover-shadow">
                                <i class="fa fa-calendar-check fa-3x text-primary"></i>
                                <div class="ms-3 text-end">
                                    <p class="mb-2 text-secondary">Total Reservations</p>
                                    <h6 class="mb-0 text-dark">{{ $totalReservations ?? 0 }}</h6>
                                    <small class="text-muted">View all</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <a href="{{ route('getCars') }}" class="text-decoration-none">
                            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 h-100 hover-shadow">
                                <i class="fa fa-car fa-3x text-primary"></i>
                                <div class="ms-3 text-end">
                                    <p class="mb-2 text-secondary">Cars</p>
                                    <h6 class="mb-0 text-dark">Fleet</h6>
                                    <small class="text-muted">Manage listings</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <a href="{{ route('admin.listing-requests.index') }}" class="text-decoration-none">
                            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 h-100 hover-shadow">
                                <i class="fa fa-inbox fa-3x text-primary"></i>
                                <div class="ms-3 text-end">
                                    <p class="mb-2 text-secondary">Listing requests</p>
                                    <h6 class="mb-0 text-dark">Owner leads</h6>
                                    <small class="text-muted">Review submissions</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-bar fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Site Visitors</p>
                                <h6 class="mb-0"><a href="https://analytics.google.com/analytics/web/#/p468682803/reports/intelligenthome" class="btn btn-dark btn-sm" target="_blank">Google Analytics</a></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sale & Revenue End -->

            <style>
                .hover-shadow:hover { box-shadow: 0 .5rem 1rem rgba(0,0,0,.12); transition: box-shadow .2s ease; }
            </style>

            <!-- Sales Chart Start -->
     


            <!-- Recent Sales Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Latest 10 Reservations</h6>
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-primary btn-sm">View all bookings</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-dark">
                                    <th scope="col">Reference</th>
                                    <th scope="col">Guest</th>
                                    <th scope="col">Property</th>
                                    <th scope="col">Check-in</th>
                                    <th scope="col">Check-out</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestReservations as $booking)
                                <tr>
                                    <td><code>{{ $booking->reference_number ?? '—' }}</code></td>
                                    <td>{{ $booking->guest_name ?? $booking->guest_email ?? '—' }}</td>
                                    <td>{{ $booking->property ? $booking->property->name : '—' }}</td>
                                    <td>{{ $booking->check_in ? \Carbon\Carbon::parse($booking->check_in)->format('M j, Y') : '—' }}</td>
                                    <td>{{ $booking->check_out ? \Carbon\Carbon::parse($booking->check_out)->format('M j, Y') : '—' }}</td>
                                    <td>${{ number_format($booking->total_amount ?? 0, 2) }}</td>
                                    <td>
                                        @if($booking->booking_status == 'confirmed')
                                            <span class="badge bg-success">Confirmed</span>
                                        @elseif($booking->booking_status == 'cancelled')
                                            <span class="badge bg-danger">Cancelled</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">No reservations yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Recent Sales End -->



            <!-- Footer Start -->
            @include('admin.includes.footer')
            <!-- Footer End -->
        </div>
        <!-- Content End -->



 @endsection