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
                        <a href="{{ route('getCars') }}" class="text-decoration-none">
                            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 h-100 hover-shadow">
                                <i class="fa fa-car fa-3x text-primary"></i>
                                <div class="ms-3 text-end">
                                    <p class="mb-2 text-secondary">Cars in fleet</p>
                                    <h6 class="mb-0 text-dark">{{ $totalCars ?? 0 }}</h6>
                                    <small class="text-muted">Manage listings</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <a href="{{ route('admin.carBookings.index') }}" class="text-decoration-none">
                            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 h-100 hover-shadow">
                                <i class="fa fa-calendar-check fa-3x text-primary"></i>
                                <div class="ms-3 text-end">
                                    <p class="mb-2 text-secondary">Car bookings</p>
                                    <h6 class="mb-0 text-dark">{{ $totalCarBookings ?? 0 }}</h6>
                                    <small class="text-muted">View all</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 h-100">
                            <i class="fa fa-dollar-sign fa-3x text-primary"></i>
                            <div class="ms-3 text-end">
                                <p class="mb-2 text-secondary">Booking revenue</p>
                                <h6 class="mb-0 text-dark">{{ formatUsd($totalCarBookingRevenue ?? 0) }}</h6>
                                <small class="text-muted">Active bookings (USD)</small>
                            </div>
                        </div>
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
     


            <!-- Recent car bookings -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Latest 10 Car Bookings</h6>
                        <a href="{{ route('admin.carBookings.index') }}" class="btn btn-primary btn-sm">View all car bookings</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-dark">
                                    <th scope="col">Reference</th>
                                    <th scope="col">Customer</th>
                                    <th scope="col">Car</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestCarBookings as $booking)
                                <tr>
                                    <td><code>{{ $booking->booking_number ?? '—' }}</code></td>
                                    <td>{{ $booking->name ?? $booking->email ?? '—' }}</td>
                                    <td>{{ $booking->car?->name ?? '—' }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $booking->booking_type ?? '—')) }}</td>
                                    <td>{{ $booking->total_amount ? formatUsd($booking->total_amount) : '—' }}</td>
                                    <td>
                                        @if($booking->rental_status == 'confirmed')
                                            <span class="badge bg-success">Confirmed</span>
                                        @elseif($booking->rental_status == 'cancelled')
                                            <span class="badge bg-danger">Cancelled</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No car bookings yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



            <!-- Footer Start -->
            @include('admin.includes.footer')
            <!-- Footer End -->
        </div>
        <!-- Content End -->



 @endsection