@extends('layouts.frontbase')

@section('content')
@php
    $hotels = $hotels ?? collect(isset($hotel) ? [$hotel] : []);
    $ownedProperties = $ownedProperties ?? collect();
    $hotelCompletions = $hotelCompletions ?? [];
    $propertyCompletions = $propertyCompletions ?? [];
    $anyIncomplete = false;
    foreach ($hotelCompletions as $hc) {
        if (!($hc['complete'] ?? true)) { $anyIncomplete = true; }
    }
    foreach ($propertyCompletions as $pc) {
        if (!($pc['complete'] ?? true)) { $anyIncomplete = true; }
    }
@endphp
<style>
    .owner-dash-wrap { max-width: 1200px; margin: 0 auto 48px; padding: 0 16px; }
    .owner-dash-hero {
        background: linear-gradient(135deg, #0d9488 0%, #115e59 45%, #134e4a 100%);
        border-radius: 16px;
        padding: 28px 32px;
        color: #fff;
        margin-bottom: 24px;
        box-shadow: 0 12px 40px rgba(13, 148, 136, 0.25);
    }
    .owner-dash-hero h1 { font-size: 1.65rem; font-weight: 700; margin: 0 0 6px; letter-spacing: -0.02em; }
    .owner-dash-hero p { margin: 0; opacity: 0.92; font-size: 0.95rem; }
    .owner-dash-tabs.nav-pills { gap: 8px; flex-wrap: wrap; border: none; }
    .owner-dash-tabs .nav-link {
        border-radius: 999px;
        padding: 10px 18px;
        font-weight: 600;
        font-size: 0.9rem;
        color: #475569;
        background: #f1f5f9;
        border: 1px solid transparent;
    }
    .owner-dash-tabs .nav-link:hover { background: #e2e8f0; color: #0f766e; }
    .owner-dash-tabs .nav-link.active {
        background: #fff;
        color: #0d9488;
        border-color: rgba(13, 148, 136, 0.35);
        box-shadow: 0 4px 14px rgba(15, 118, 110, 0.12);
    }
    .owner-stat-card {
        border-radius: 16px;
        border: 1px solid rgba(13, 148, 136, 0.12);
        background: linear-gradient(180deg, #fff 0%, #f8fafc 100%);
        overflow: hidden;
    }
    .owner-stat-card .stat-inner { padding: 2rem 2rem 2.25rem; position: relative; }
    .owner-stat-card .stat-inner::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 4px;
        background: linear-gradient(90deg, #14b8a6, #0d9488);
    }
    .owner-stat-icon {
        width: 52px; height: 52px; border-radius: 14px;
        background: rgba(20, 184, 166, 0.12);
        color: #0d9488;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.35rem; margin-bottom: 1rem;
    }
    .owner-empty-state {
        text-align: center;
        padding: 2.5rem 1.5rem;
        color: #64748b;
    }
    .owner-empty-state i { font-size: 2.5rem; opacity: 0.35; margin-bottom: 0.75rem; display: block; }
</style>

<div class="owner-dash-wrap">
    <div class="owner-dash-hero d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
            <h1>Owner Dashboard</h1>
            <p>Track earnings, bookings, and your listings in one place.</p>
        </div>
        <div class="d-flex gap-2 flex-shrink-0">
            <a href="{{ route('guide') }}" class="btn btn-light btn-sm px-3" style="border-radius: 999px;" title="How to add and manage properties">
                <i class="fas fa-book me-1"></i>Guide
            </a>
            <a href="{{ route('myPropertyCreate') }}" class="th-btn style4" style="white-space: nowrap;">Add New Hotel</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm" style="border-radius: 12px;">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm" style="border-radius: 12px;">{{ session('error') }}</div>
    @endif

    @if($anyIncomplete && (auth()->check()))
        <div class="alert alert-warning border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <strong>Please complete your listing(s).</strong> Finish all steps: add property details, rooms/units, gallery images, and sign the listing agreement.
            Open the <strong>My Properties</strong> tab to see progress and continue each step.
        </div>
    @endif

    <ul class="nav nav-pills owner-dash-tabs mb-4" id="ownerDashboardTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="earnings-tab" data-bs-toggle="tab" data-bs-target="#earnings" type="button" role="tab">
                <i class="fas fa-piggy-bank me-2"></i>Earnings
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="calendar-tab" data-bs-toggle="tab" data-bs-target="#calendar" type="button" role="tab">
                <i class="fas fa-calendar-alt me-2"></i>Booking Calendar
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">
                <i class="fas fa-history me-2"></i>Bookings History
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="properties-tab" data-bs-toggle="tab" data-bs-target="#properties" type="button" role="tab">
                <i class="fas fa-building me-2"></i>My Properties
            </button>
        </li>
    </ul>

    <div class="tab-content" id="ownerDashboardTabContent">
        {{-- Earnings Tab --}}
        <div class="tab-pane fade show active" id="earnings" role="tabpanel">
            <div class="owner-stat-card shadow-sm">
                <div class="stat-inner">
                    <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
                        <div>
                            <div class="owner-stat-icon"><i class="fas fa-coins"></i></div>
                            <h5 class="text-muted text-uppercase small fw-semibold mb-1" style="letter-spacing: 0.06em;">Total earnings</h5>
                            <p class="display-5 fw-bold mb-0" style="color: #0f766e;">${{ number_format($earnings ?? 0, 2) }}</p>
                            <p class="text-muted small mb-0 mt-2">From confirmed and completed bookings.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Booking Calendar: room × day grid + upcoming list --}}
        <div class="tab-pane fade" id="calendar" role="tabpanel" tabindex="0">
            <div class="card border-0 shadow-sm" style="border-radius: 14px;">
                <div class="card-body p-4">
                    @php
                        $cvUp = \App\Services\RoomBookingCalendarService::VIEW_UPCOMING;
                        $cvHist = \App\Services\RoomBookingCalendarService::VIEW_HISTORY;
                        $calendarViewOwner = $calendarView ?? $cvUp;
                    @endphp
                    <h5 class="card-title mb-2 fw-bold">Room booking dashboard</h5>
                    <p class="text-muted small mb-2">Same rules as admin: <strong>Upcoming</strong> shows from today forward; <strong>Past history</strong> shows earlier months and days.</p>

                    @if(isset($calendarListingOptions) && $calendarListingOptions->isNotEmpty())
                        <div class="btn-group btn-group-sm mb-3" role="group">
                            <a href="{{ route('myProperties', array_filter(['cal_listing' => $selectedCalendarListingKey, 'cal_year' => $bookingCalendarPayload['year'] ?? request('cal_year', date('Y')), 'cal_calendar_view' => $cvUp], fn ($v) => $v !== null && $v !== '')) }}#calendar"
                               class="btn {{ $calendarViewOwner === $cvUp ? 'btn-primary' : 'btn-outline-primary' }}">Upcoming</a>
                            <a href="{{ route('myProperties', array_filter(['cal_listing' => $selectedCalendarListingKey, 'cal_year' => $bookingCalendarPayload['year'] ?? request('cal_year', date('Y')), 'cal_calendar_view' => $cvHist], fn ($v) => $v !== null && $v !== '')) }}#calendar"
                               class="btn {{ $calendarViewOwner === $cvHist ? 'btn-primary' : 'btn-outline-primary' }}">Past history</a>
                        </div>

                        @if($calendarListingOptions->count() > 1)
                            <form method="get" action="{{ route('myProperties') }}" class="row g-2 align-items-end mb-4">
                                <input type="hidden" name="cal_calendar_view" value="{{ $calendarViewOwner }}">
                                <div class="col-auto">
                                    <label class="form-label small mb-0">Listing</label>
                                    <select name="cal_listing" class="form-select form-select-sm" style="min-width: 220px;" onchange="this.form.submit()">
                                        @foreach($calendarListingOptions as $opt)
                                            <option value="{{ $opt['key'] }}" @selected(($selectedCalendarListingKey ?? null) === $opt['key'])>{{ $opt['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <label class="form-label small mb-0">Year</label>
                                    <input type="number" name="cal_year" class="form-control form-control-sm" style="width: 100px;" value="{{ $bookingCalendarPayload['year'] ?? request('cal_year', date('Y')) }}" min="2020" max="2035">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Apply</button>
                                </div>
                            </form>
                        @endif

                        @if(isset($upcomingBookings) && $upcomingBookings->isNotEmpty())
                            <h6 class="fw-bold mb-2">Upcoming check-ins</h6>
                            <div class="table-responsive mb-4">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Guest</th>
                                            <th>Property</th>
                                            <th>Check-in</th>
                                            <th>Check-out</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($upcomingBookings->take(15) as $b)
                                        <tr>
                                            <td>{{ $b->guest_name ?? 'N/A' }}</td>
                                            <td>{{ $b->hotel->name ?? $b->property->name ?? 'N/A' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($b->check_in)->format('M d, Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($b->check_out)->format('M d, Y') }}</td>
                                            <td><span class="badge bg-info">{{ $b->booking_status ?? 'N/A' }}</span></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        @include('partials.booking-calendar-grid', [
                            'payload' => $bookingCalendarPayload,
                            'yearUrls' => $calendarYearUrls ?? [],
                            'canEditInventory' => true,
                            'inventoryUpdateUrl' => route('inventory-day-cap.update'),
                            'inventoryDetailUrl' => route('inventory-day-cap.show'),
                        ])
                    @else
                        <div class="owner-empty-state">
                            <i class="fas fa-calendar-alt"></i>
                            <p class="mb-0">Add a property to see the booking calendar.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Bookings History Tab --}}
        <div class="tab-pane fade" id="history" role="tabpanel">
            <div class="card border-0 shadow-sm" style="border-radius: 14px;">
                <div class="card-body p-4">
                    <h5 class="card-title mb-1 fw-bold">Bookings history</h5>
                    <p class="text-muted small mb-3">Your 50 most recent bookings (upcoming, in progress, and past), newest first.</p>
                    @if(isset($bookingsHistory) && $bookingsHistory->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Guest</th>
                                        <th>Property</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookingsHistory as $b)
                                    <tr>
                                        <td>{{ $b->guest_name ?? optional($b->user)->name ?? 'N/A' }}</td>
                                        <td>{{ $b->hotel->name ?? $b->property->name ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($b->check_in)->format('M d, Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($b->check_out)->format('M d, Y') }}</td>
                                        <td>${{ number_format($b->total_amount ?? 0, 2) }}</td>
                                        <td><span class="badge bg-secondary">{{ $b->booking_status ?? 'N/A' }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="owner-empty-state">
                            <i class="fas fa-receipt"></i>
                            <p class="mb-0">No bookings yet. When guests submit reservations, they will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- My Properties Tab --}}
        <div class="tab-pane fade" id="properties" role="tabpanel">
    @if($hotels->isEmpty() && $ownedProperties->isEmpty())
        <div class="card p-4 text-center">
            <h4>You don't have any properties yet</h4>
            <p class="text-muted mb-3">Read the guide to see the process and required details, then add your first property.</p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
                <a href="{{ route('guide') }}" class="btn btn-outline-primary">Guide</a>
                <a href="{{ route('myPropertyCreate') }}" class="th-btn style4">Add First Hotel</a>
            </div>
        </div>
    @else
        @if($hotels->isNotEmpty())
        <h5 class="mb-3 fw-bold text-secondary">Hotel listings (your submissions)</h5>
        <div class="row gy-4">
            @foreach($hotels as $hotel)
                <div class="col-md-6 col-lg-4">
                    <div class="tour-box">
                        <div class="tour-box_img position-relative">
                            @php
                                $img = $hotel->image && file_exists(storage_path('app/public/images/hotels/'.$hotel->image))
                                    ? asset('storage/images/hotels/'.$hotel->image)
                                    : asset('assets/img/tour/tour_3_1.jpg');
                                $st = $hotel->status ?? 'Pending';
                            @endphp
                            <span class="badge position-absolute top-0 end-0 m-2 shadow-sm
                                @if($st === 'Active') bg-success
                                @elseif($st === 'Pending') bg-warning text-dark
                                @else bg-secondary @endif">{{ $st }}</span>
                            <img src="{{ $img }}" alt="{{ $hotel->name }}" style="height:220px;object-fit:cover;width:100%;">
                        </div>

                        <div class="tour-content">
                            <h3 class="box-title">{{ $hotel->name }}</h3>
                            <p class="small text-muted">
                                {{ Str::limit(strip_tags($hotel->description),120) }}
                            </p>

                            @php $hc = $hotelCompletions[$hotel->id] ?? null; @endphp
                            @if($hc && !($hc['complete'] ?? false))
                                <div class="mb-2 p-2 rounded border border-warning border-opacity-50 bg-warning bg-opacity-10 small">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-semibold text-dark">Listing setup</span>
                                        <span class="badge bg-secondary">{{ $hc['percent'] ?? 0 }}%</span>
                                    </div>
                                    <div class="progress mb-2" style="height: 6px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $hc['percent'] ?? 0 }}%"></div>
                                    </div>
                                    <div class="text-muted mb-0">Next steps:
                                        @foreach($hc['steps'] ?? [] as $step)
                                            @if(empty($step['done']))
                                                <a href="{{ $step['url'] }}" class="text-decoration-none d-inline-block me-2">{{ $step['label'] }}</a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @elseif($hc && ($hc['complete'] ?? false))
                                <p class="small text-success mb-2"><i class="fas fa-check-circle me-1"></i>Listing setup complete</p>
                            @endif

                            <div class="d-flex flex-wrap gap-1" style="font-size: 0.85rem;">
                                <button type="button" class="btn btn-sm btn-info"
                                    data-bs-toggle="modal"
                                    data-bs-target="#viewHotelModal{{ $hotel->id }}">
                                    <i class="fa fa-eye"></i> Quick view
                                </button>

                                <a href="{{ route('my.properties.hotels.edit', $hotel) }}" class="btn btn-sm btn-warning">
                                    <i class="fa fa-edit"></i> Manage
                                </a>

                                <a href="{{ route('my.properties.rooms.create', $hotel) }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-plus"></i> Add room
                                </a>

                                <a href="{{ route('my.properties.listing-agreement.show', $hotel) }}" class="btn btn-sm btn-success">
                                    <i class="fa fa-file-signature"></i> Agreement
                                </a>

                                <form action="{{ route('my.properties.hotels.destroy', $hotel->id) }}" method="POST" onsubmit="return confirm('Remove this property from your dashboard? It will be archived (not permanently deleted).');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fa fa-archive"></i> Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- VIEW HOTEL MODAL --}}
                <div class="modal fade" id="viewHotelModal{{ $hotel->id }}">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ $hotel->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    {{-- Property Details --}}
                                    <div class="col-md-6">
                                        <h6 class="fw-bold mb-3">Property Details</h6>
                                        <table class="table table-sm">
                                            <tr>
                                                <td><strong>Name:</strong></td>
                                                <td>{{ $hotel->name }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Type:</strong></td>
                                                <td>{{ ucfirst(str_replace('_', ' ', $hotel->type ?? 'N/A')) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Stars:</strong></td>
                                                <td>{{ $hotel->stars ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Status:</strong></td>
                                                <td>
                                                    @if($hotel->status == 'Active')
                                                        <span class="badge bg-success">Active</span>
                                                    @elseif($hotel->status == 'Pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                    @else
                                                        <span class="badge bg-danger">Inactive</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Location:</strong></td>
                                                <td>{{ $hotel->location ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>City:</strong></td>
                                                <td>{{ $hotel->city ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Address:</strong></td>
                                                <td>{{ $hotel->address ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Phone:</strong></td>
                                                <td>{{ $hotel->phone ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Email:</strong></td>
                                                <td>{{ $hotel->email ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Website:</strong></td>
                                                <td>{{ $hotel->website ? '<a href="'.$hotel->website.'" target="_blank">'.$hotel->website.'</a>' : 'N/A' }}</td>
                                            </tr>
                                        </table>
                                        @if($hotel->description)
                                            <div class="mt-3">
                                                <strong>Description:</strong>
                                                <div class="text-muted small property-description">{!! $hotel->description !!}</div>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Property Images --}}
                                    <div class="col-md-6">
                                        <h6 class="fw-bold mb-3">Property Images</h6>
                                        <div class="row g-2">
                                            @php
                                                $coverImg = $hotel->image && file_exists(storage_path('app/public/images/hotels/'.$hotel->image))
                                                    ? asset('storage/images/hotels/'.$hotel->image)
                                                    : asset('assets/img/tour/tour_3_1.jpg');
                                            @endphp
                                            <div class="col-6">
                                                <div class="position-relative">
                                                    <img src="{{ $coverImg }}" alt="Cover" class="img-fluid rounded" style="height:150px;object-fit:cover;width:100%;">
                                                    <span class="badge bg-primary position-absolute top-0 start-0 m-2">Cover</span>
                                                </div>
                                            </div>
                                            @if($hotel->images && $hotel->images->count() > 0)
                                                @foreach($hotel->images->take(5) as $img)
                                                    <div class="col-6">
                                                        <div class="position-relative">
                                                            <img src="{{ asset('storage/images/hotels/'.$img->image) }}" alt="Image" class="img-fluid rounded" style="height:150px;object-fit:cover;width:100%;">
                                                            <form action="{{ route('my.properties.hotels.images.destroy', $img->id) }}" 
                                                                  method="POST" 
                                                                  onsubmit="return confirm('Are you sure you want to delete this image?');"
                                                                  style="position: absolute; top: 5px; right: 5px;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger" style="padding: 2px 6px;">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                @if($hotel->images->count() > 5)
                                                    <div class="col-12">
                                                        <p class="text-muted small">+ {{ $hotel->images->count() - 5 }} more images</p>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="col-12">
                                                    <p class="text-muted small">No additional images</p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="mt-3">
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addPropertyGalleryModal{{ $hotel->id }}">
                                                <i class="fa fa-plus"></i> Add Images
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Rooms Section --}}
                                <div class="mt-4">
                                    <h6 class="fw-bold mb-3">Rooms ({{ $hotel->rooms->count() }})</h6>
                                    @if($hotel->rooms && $hotel->rooms->count() > 0)
                                        <div class="row g-3">
                                            @foreach($hotel->rooms as $room)
                                                <div class="col-md-6">
                                                    <div class="card">
                                                        <div class="row g-0">
                                                            <div class="col-4">
                                                                @php
                                                                    $roomImg = $room->image && file_exists(storage_path('app/public/images/rooms/'.$room->image))
                                                                        ? asset('storage/images/rooms/'.$room->image)
                                                                        : asset('assets/img/tour/tour_3_1.jpg');
                                                                @endphp
                                                                <img src="{{ $roomImg }}" class="img-fluid rounded-start" style="height:100px;object-fit:cover;width:100%;" alt="{{ $room->room_type }}">
                                                            </div>
                                                            <div class="col-8">
                                                                <div class="card-body p-2">
                                                                    <h6 class="card-title mb-1" style="font-size:0.9rem;">{{ $room->room_type }}</h6>
                                                                    <p class="card-text small mb-1">
                                                                        @php
                                                                            $roomCur = $room->currency ?? 'USD';
                                                                            $roomSym = getCurrencySymbol($roomCur);
                                                                            $pt = $room->price_display_type ?? 'per_night';
                                                                        @endphp
                                                                        <strong>Price:</strong>
                                                                        @if($pt === 'per_month')
                                                                            {{ $roomSym }}{{ number_format($room->price_per_month ?? 0, 2) }}/month
                                                                        @elseif($pt === 'both')
                                                                            {{ $roomSym }}{{ number_format($room->price_per_night ?? 0, 2) }}/night
                                                                            @if(!empty($room->price_per_month))
                                                                                · {{ $roomSym }}{{ number_format($room->price_per_month, 2) }}/month
                                                                            @endif
                                                                        @else
                                                                            {{ $roomSym }}{{ number_format($room->price_per_night ?? 0, 2) }}/night
                                                                        @endif
                                                                        <br>
                                                                        <strong>Max Occupancy:</strong> {{ $room->max_occupancy ?? 'N/A' }}<br>
                                                                        <strong>Available:</strong> {{ $room->available_rooms ?? 0 }}/{{ $room->total_rooms ?? 0 }}
                                                                    </p>
                                                                    @if($room->status)
                                                                        <span class="badge bg-{{ $room->status == 'Available' ? 'success' : 'danger' }}">{{ $room->status }}</span>
                                                                    @endif
                                                                    <div class="mt-2 d-flex flex-wrap gap-1">
                                                                        <a href="{{ route('my.properties.rooms.show', [$hotel, $room]) }}" class="btn btn-sm btn-info">
                                                                            <i class="fa fa-eye"></i> Details
                                                                        </a>
                                                                        <a href="{{ route('my.properties.rooms.edit', $room) }}" class="btn btn-sm btn-warning">
                                                                            <i class="fa fa-edit"></i> Edit
                                                                        </a>
                                                                        <form action="{{ route('my.properties.rooms.destroy', $room->id) }}" 
                                                                            method="POST" 
                                                                            onsubmit="return confirm('Remove this room from your listing? It will be archived, not permanently deleted.');"
                                                                            style="display:inline;">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                                <i class="fa fa-archive"></i> Remove
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted">No rooms added yet. Click "Add Room" to add rooms to this property.</p>
                                    @endif
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ADD PROPERTY GALLERY MODAL --}}
                <div class="modal fade" id="addPropertyGalleryModal{{ $hotel->id }}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('my.properties.hotels.images.store', $hotel->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Add Images to {{ $hotel->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="propertyImages{{ $hotel->id }}" class="form-label">Select Images</label>
                                        <input type="file" 
                                               class="form-control" 
                                               id="propertyImages{{ $hotel->id }}" 
                                               name="image[]" 
                                               multiple 
                                               accept="image/*" 
                                               required>
                                        <small class="text-muted">You can select multiple images at once. Max size: 4MB per image.</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-upload"></i> Upload Images
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif

        @if($ownedProperties->isNotEmpty())
            <h5 class="mt-5 mb-3 fw-bold text-secondary">Properties (unified listings)</h5>
            <p class="text-muted small">These listings are managed in the admin area (units, gallery, and details).</p>
            <div class="row gy-4">
                @foreach($ownedProperties as $prop)
                    <div class="col-md-6 col-lg-4">
                        <div class="tour-box h-100">
                            <div class="tour-content">
                                <h3 class="box-title">{{ $prop->name }}</h3>
                                <p class="small text-muted mb-2">{{ ucfirst($prop->property_type ?? 'property') }} · {{ $prop->status ?? '—' }}</p>
                                @php $pc = $propertyCompletions[$prop->id] ?? null; @endphp
                                @if($pc && !($pc['complete'] ?? false))
                                    <div class="mb-2 p-2 rounded border border-warning border-opacity-50 bg-warning bg-opacity-10 small">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="fw-semibold text-dark">Listing setup</span>
                                            <span class="badge bg-secondary">{{ $pc['percent'] ?? 0 }}%</span>
                                        </div>
                                        <div class="progress mb-2" style="height: 6px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $pc['percent'] ?? 0 }}%"></div>
                                        </div>
                                        <div class="text-muted mb-0">Next steps:
                                            @foreach($pc['steps'] ?? [] as $step)
                                                @if(empty($step['done']))
                                                    <a href="{{ $step['url'] }}" class="text-decoration-none d-inline-block me-2">{{ $step['label'] }}</a>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @elseif($pc && ($pc['complete'] ?? false))
                                    <p class="small text-success mb-2"><i class="fas fa-check-circle me-1"></i>Listing setup complete</p>
                                @endif
                                <div class="d-flex flex-wrap gap-1">
                                    <a href="{{ route('admin.properties.edit', $prop->id) }}" class="btn btn-sm btn-warning">Manage in admin</a>
                                    <a href="{{ route('my.properties.property.listing-agreement.show', $prop) }}" class="btn btn-sm btn-success">Agreement</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif
        </div>{{-- end properties tab-pane --}}
    </div>{{-- end tab-content --}}
</div>

@endsection

@push('scripts')
<script>
(function () {
    function showCalendarTab() {
        var tabBtn = document.getElementById('calendar-tab');
        var panel = document.getElementById('calendar');
        if (!tabBtn || !panel) {
            return;
        }
        if (typeof bootstrap !== 'undefined' && bootstrap.Tab) {
            try {
                bootstrap.Tab.getOrCreateInstance(tabBtn).show();
            } catch (e) {
                tabBtn.click();
            }
        } else {
            tabBtn.click();
        }
        requestAnimationFrame(function () {
            panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    }

    function showPropertiesTab() {
        var tabBtn = document.getElementById('properties-tab');
        var panel = document.getElementById('properties');
        if (!tabBtn || !panel) {
            return;
        }
        if (typeof bootstrap !== 'undefined' && bootstrap.Tab) {
            try {
                bootstrap.Tab.getOrCreateInstance(tabBtn).show();
            } catch (e) {
                tabBtn.click();
            }
        } else {
            tabBtn.click();
        }
        requestAnimationFrame(function () {
            panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        var params = new URLSearchParams(window.location.search);
        var hash = window.location.hash;

        if (hash === '#properties') {
            showPropertiesTab();
            return;
        }

        var shouldShowCalendar = params.has('cal_year')
            || hash === '#calendar'
            || hash === '#booking-calendar-panel';

        if (!shouldShowCalendar) {
            return;
        }

        showCalendarTab();

        if (hash === '#booking-calendar-panel') {
            history.replaceState(null, '', window.location.pathname + window.location.search + '#calendar');
        }
    });
})();
</script>
@endpush
