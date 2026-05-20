@extends('layouts.frontbase')

@section('content')
@php
    $cur = $room->currency ?? 'USD';
    $sym = getCurrencySymbol($cur);
    $pt = $room->price_display_type ?? 'per_night';
    $cover = $room->image && file_exists(storage_path('app/public/images/rooms/'.$room->image))
        ? asset('storage/images/rooms/'.$room->image)
        : asset('assets/img/tour/tour_3_1.jpg');
@endphp

<div class="container py-4" style="max-width: 960px;">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <p class="text-muted small mb-1"><a href="{{ route('my.properties.hotels.edit', $hotel) }}">{{ $hotel->name }}</a></p>
            <h2 class="box-title mb-0">{{ $room->room_type }}</h2>
            <div class="mt-2">
                @if($room->status == 'Available')
                    <span class="badge bg-success">Available</span>
                @else
                    <span class="badge bg-secondary">{{ $room->status ?? '—' }}</span>
                @endif
                @if(!($room->accepts_room_bookings ?? true))
                    <span class="badge bg-warning text-dark">Bookings closed (fully booked)</span>
                @else
                    <span class="badge bg-info text-dark">Accepting booking requests</span>
                @endif
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('my.properties.rooms.edit', $room) }}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit room</a>
            <a href="{{ route('my.properties.hotels.edit', $hotel) }}" class="btn btn-outline-secondary btn-sm">Property settings</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-md-5">
            <img src="{{ $cover }}" class="img-fluid rounded shadow-sm w-100" style="max-height: 320px; object-fit: cover;" alt="">
        </div>
        <div class="col-md-7">
            <h5 class="mb-3">Rates</h5>
            <table class="table table-sm">
                <tr>
                    <th>Display</th>
                    <td>{{ ucfirst(str_replace('_', ' ', $pt)) }}</td>
                </tr>
                <tr>
                    <th>Per night</th>
                    <td>{{ $sym }}{{ number_format($room->price_per_night ?? 0, 2) }}</td>
                </tr>
                @if($pt !== 'per_night' && $room->price_per_month)
                    <tr>
                        <th>Per month</th>
                        <td>{{ $sym }}{{ number_format($room->price_per_month, 2) }}</td>
                    </tr>
                @endif
                <tr>
                    <th>Currency</th>
                    <td>{{ $cur }}</td>
                </tr>
                <tr>
                    <th>Max occupancy</th>
                    <td>{{ $room->max_occupancy ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Inventory</th>
                    <td>{{ $room->available_rooms ?? 0 }} / {{ $room->total_rooms ?? 0 }} available</td>
                </tr>
            </table>
        </div>
    </div>

    @if($room->description)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-bold">Description</h6>
                <div class="text-muted property-description">{!! $room->description !!}</div>
            </div>
        </div>
    @endif

    @if($room->roomAmenities->isNotEmpty())
        <div class="mb-4">
            <h6 class="fw-bold">Amenities</h6>
            <div class="d-flex flex-wrap gap-2">
                @foreach($room->roomAmenities as $a)
                    <span class="badge bg-light text-dark border">{{ $a->title }}</span>
                @endforeach
            </div>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <strong>Room gallery</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('my.properties.rooms.images.store', $room) }}" method="POST" enctype="multipart/form-data" class="mb-4">
                @csrf
                <div class="row g-2 align-items-end">
                    <div class="col-md-8">
                        <label class="form-label">Upload more images</label>
                        <input type="file" name="image[]" class="form-control" multiple accept="image/*" required>
                        <small class="text-muted">You can select multiple files.</small>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">Upload</button>
                    </div>
                </div>
            </form>

            @if($room->images && $room->images->count())
                <div class="row g-2">
                    @foreach($room->images as $img)
                        <div class="col-6 col-md-3">
                            <div class="position-relative">
                                <img src="{{ asset('storage/images/rooms/'.$img->image) }}" class="img-fluid rounded w-100" style="height:120px;object-fit:cover;" alt="">
                                <form action="{{ route('my.properties.rooms.images.destroy', $img->id) }}" method="POST" class="position-absolute top-0 end-0 m-1"
                                      onsubmit="return confirm('Remove this image?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger py-0 px-1">&times;</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted mb-0">No extra images yet.</p>
            @endif
        </div>
    </div>

    <form action="{{ route('my.properties.rooms.destroy', $room) }}" method="POST"
          onsubmit="return confirm('Remove this room from your listing? It will be archived (not permanently deleted).');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger btn-sm">Remove room from listing</button>
    </form>
</div>
@endsection
