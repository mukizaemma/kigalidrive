@extends('layouts.frontbase')

@php
    $isEdit = isset($room);
@endphp

@section('content')
<div class="container py-4" style="max-width: 900px;">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h2 class="box-title mb-0">{{ $isEdit ? 'Edit room' : 'Add room' }}</h2>
            <p class="text-muted small mb-0">{{ $hotel->name }}</p>
        </div>
        <a href="{{ $isEdit ? route('my.properties.rooms.show', [$hotel, $room]) : route('my.properties.hotels.edit', $hotel) }}" class="btn btn-outline-secondary btn-sm">Back</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" enctype="multipart/form-data" action="{{ $isEdit ? route('my.properties.rooms.update', $room) : route('my.properties.rooms.store', $hotel) }}" class="card border-0 shadow-sm">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-12">
                    <label class="form-label">Room type</label>
                    <input type="text" name="room_type" class="form-control" required
                           value="{{ old('room_type', $room->room_type ?? '') }}">
                </div>
            </div>

            <h6 class="text-uppercase text-muted small mb-2">Pricing</h6>
            <div class="row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="form-label">Currency</label>
                    <select name="currency" class="form-control">
                        @foreach(['USD','EUR','GBP','RWF','KES','UGX','TZS'] as $c)
                            <option value="{{ $c }}" @selected(old('currency', $room->currency ?? 'USD') == $c)>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label">Price display</label>
                    <select name="price_display_type" class="form-control">
                        <option value="per_night" @selected(old('price_display_type', $room->price_display_type ?? 'per_night') == 'per_night')>Per night</option>
                        <option value="per_month" @selected(old('price_display_type', $room->price_display_type ?? '') == 'per_month')>Per month</option>
                        <option value="both" @selected(old('price_display_type', $room->price_display_type ?? '') == 'both')>Both</option>
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label">Price per night</label>
                    <input type="number" step="0.01" name="price_per_night" class="form-control" required
                           value="{{ old('price_per_night', $room->price_per_night ?? '') }}">
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label">Price per month <span class="text-muted">(optional)</span></label>
                    <input type="number" step="0.01" name="price_per_month" class="form-control"
                           value="{{ old('price_per_month', $room->price_per_month ?? '') }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Max occupancy</label>
                    <input type="number" name="max_occupancy" class="form-control" required
                           value="{{ old('max_occupancy', $room->max_occupancy ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Total units</label>
                    <input type="number" name="total_rooms" class="form-control" required
                           value="{{ old('total_rooms', $room->total_rooms ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Available units</label>
                    <input type="number" name="available_rooms" class="form-control" required
                           value="{{ old('available_rooms', $room->available_rooms ?? '') }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="Available" @selected(old('status', $room->status ?? 'Available') == 'Available')>Available</option>
                        <option value="Unavailable" @selected(old('status', $room->status ?? '') == 'Unavailable')>Unavailable</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Cover image {{ $isEdit ? '(optional)' : '' }}</label>
                    <input type="file" name="image" class="form-control" accept="image/*" @if(!$isEdit) required @endif>
                </div>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="accepts_room_bookings" value="1" class="form-check-input" id="accepts_room_bookings"
                    @checked(old('accepts_room_bookings', $room->accepts_room_bookings ?? true))>
                <label class="form-check-label" for="accepts_room_bookings">Open for guest bookings (uncheck to mark as fully booked / closed to new requests)</label>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea id="roomDescription" name="description" rows="4" class="form-control">{!! old('description', $room->description ?? '') !!}</textarea>
            </div>

            <h6 class="text-uppercase text-muted small mb-2">Amenities <span class="fw-normal">(grouped like admin)</span></h6>
            <p class="text-muted small">Tick the facilities that apply to this room type.</p>

            @foreach($facilityCategories as $category)
                @if($category->facilities && $category->facilities->count())
                    <div class="card mb-3">
                        <div class="card-header py-2 bg-primary text-white"><strong>{{ $category->name }}</strong></div>
                        <div class="card-body" style="max-height: 280px; overflow-y: auto;">
                            <div class="row">
                                @foreach($category->facilities as $amenity)
                                    <div class="col-md-6 col-lg-4 mb-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="amenities[]" value="{{ $amenity->id }}" id="am{{ $amenity->id }}"
                                                {{ in_array($amenity->id, old('amenities', $selectedAmenities ?? [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="am{{ $amenity->id }}">{{ $amenity->title }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

            <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Save changes' : 'Create room' }}</button>
        </div>
    </form>
</div>
@endsection
@include('layouts.includes.owner-summernote')
