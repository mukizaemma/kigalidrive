@extends('layouts.adminBase')

@section('content')
    @include('admin.includes.sidebar')

    <div class="content">
        @include('admin.includes.navbar')

    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded p-4">
            @php
                $cvUpcoming = \App\Services\RoomBookingCalendarService::VIEW_UPCOMING;
                $cvHistory = \App\Services\RoomBookingCalendarService::VIEW_HISTORY;
                $calendarView = $calendarView ?? $cvUpcoming;
            @endphp
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="mb-2">Room booking dashboard</h4>
                    <div class="btn-group btn-group-sm" role="group" aria-label="Calendar view">
                        <a href="{{ route('admin.booking-calendar.index', array_filter(['year' => $year, 'listing' => ($listing ?? 'all') !== 'all' ? ($listing ?? 'all') : null, 'calendar_view' => $cvUpcoming], fn ($v) => $v !== null && $v !== '')) }}"
                           class="btn {{ $calendarView === $cvUpcoming ? 'btn-primary' : 'btn-outline-primary' }}">Upcoming</a>
                        <a href="{{ route('admin.booking-calendar.index', array_filter(['year' => $year, 'listing' => ($listing ?? 'all') !== 'all' ? ($listing ?? 'all') : null, 'calendar_view' => $cvHistory], fn ($v) => $v !== null && $v !== '')) }}"
                           class="btn {{ $calendarView === $cvHistory ? 'btn-primary' : 'btn-outline-primary' }}">Past history</a>
                    </div>
                    <p class="text-muted small mb-0 mt-2">Upcoming shows from today through year-end (full months for future years). Past history shows earlier months and days.</p>
                </div>
                <form method="get" action="{{ route('admin.booking-calendar.index') }}" class="d-flex flex-wrap gap-2 align-items-end">
                    <input type="hidden" name="calendar_view" value="{{ $calendarView }}">
                    <div>
                        <label class="form-label small mb-0">Listing</label>
                        <select name="listing" class="form-select form-select-sm" style="min-width: 260px;">
                            <option value="all" {{ ($listing ?? 'all') === 'all' ? 'selected' : '' }}>All listings</option>
                            @if(isset($hotelsList) && $hotelsList->isNotEmpty())
                                <optgroup label="Hotels">
                                    @foreach($hotelsList as $h)
                                        <option value="h-{{ $h->id }}" {{ ($listing ?? '') === 'h-'.$h->id ? 'selected' : '' }}>{{ $h->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endif
                            @if(isset($propertiesList) && $propertiesList->isNotEmpty())
                                <optgroup label="Properties">
                                    @foreach($propertiesList as $p)
                                        <option value="p-{{ $p->id }}" {{ ($listing ?? '') === 'p-'.$p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endif
                        </select>
                    </div>
                    <div>
                        <label class="form-label small mb-0">Year</label>
                        <input type="number" name="year" class="form-control form-control-sm" value="{{ $year }}" min="2020" max="2035" style="width: 100px;">
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">Apply</button>
                </form>
            </div>

            @php
                $yearUrls = [];
                foreach ([$year - 1, $year, $year + 1] as $y) {
                    if ($y < 2020 || $y > 2035) continue;
                    $yearUrls[$y] = route('admin.booking-calendar.index', array_filter([
                        'year' => $y,
                        'listing' => ($listing ?? 'all') !== 'all' ? ($listing ?? 'all') : null,
                        'calendar_view' => $calendarView ?? $cvUpcoming,
                    ], static fn ($v) => $v !== null && $v !== ''));
                }
            @endphp

            @php $calFirst = true; @endphp
            @forelse($calendars as $payload)
                <div class="mb-5 pb-4 border-bottom">
                    <h5 class="fw-bold mb-3">{{ $payload['hotel_name'] ?? 'Property' }} <span class="text-muted small">#{{ $payload['hotel_id'] ?? $payload['property_id'] ?? '' }}</span></h5>
                    @include('partials.booking-calendar-grid', [
                        'payload' => $payload,
                        'yearUrls' => $calFirst ? $yearUrls : [],
                        'canEditInventory' => true,
                        'inventoryUpdateUrl' => route('inventory-day-cap.update'),
                        'inventoryDetailUrl' => route('inventory-day-cap.show'),
                    ])
                    @php $calFirst = false; @endphp
                </div>
            @empty
                <p class="text-muted">No properties to show.</p>
            @endforelse
        </div>
    </div>
    </div>
@endsection
