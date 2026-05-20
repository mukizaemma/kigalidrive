@extends('layouts.frontbase')
@section('content')
<style>
    .rich-html-content { line-height: 1.65; }
    .rich-html-content p:last-child { margin-bottom: 0; }
    .rich-html-content ul, .rich-html-content ol { margin: 0.75rem 0; padding-left: 1.25rem; }
</style>

<div class="breadcumb-wrapper" data-bg-src="{{ asset('storage/images/rooms/' .$room->image) }}" style="height: 550px; width: 70%; margin: 20px auto; background-image: url('{{ asset('storage/images/trips/' .$room->image) }}'); background-size: cover; background-position: center;">
    <div class="container">
        <div class="breadcumb-content">
            <!-- Optional static content -->
        </div>
    </div>
</div>
{{-- @endif --}}


<!--==============================
Tour Area
==============================-->
<section class="tour-area3 position-relative bg-top-center overflow-hidden space" id="service-sec" style="width: 80%; margin: 20px auto;">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
        <div class="col-lg-8 col-sm-12">
            <div class="tour-page-single">

                <div class="row g-3 align-items-start">
                    <div class="col-md-8">
                        <div class="room-main-img mb-3">
                            @if($room->image && file_exists(storage_path('app/public/images/rooms/' . $room->image)))
                                <img src="{{ asset('storage/images/rooms/' . $room->image) }}" alt="{{ $room->title }}" class="img-fluid rounded" style="width:100%; height:auto; object-fit:cover;">
                            @else
                                <img src="{{ asset('assets/img/tour/tour_3_1.jpg') }}" alt="{{ $room->title }}" class="img-fluid rounded" style="width:100%; height:auto; object-fit:cover;">
                            @endif
                        </div>

                        @if($images->isNotEmpty())
                            <div class="room-thumbs d-flex flex-wrap gap-2 mb-4">
                                @foreach($images as $image)
                                    <a href="{{ asset('storage/images/rooms/' . $image->image) }}" class="d-block" style="width:120px; height:80px; overflow:hidden; border-radius:8px;">
                                        <img src="{{ asset('storage/images/rooms/' . $image->image) }}" alt="thumb" class="img-fluid" style="width:100%; height:100%; object-fit:cover;">
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        <div class="page-content mb-4">
                            <h3 class="box-title mb-3">About this room</h3>
                            <div class="box-text rich-html-content">
                                @if(filled($room->description))
                                    {!! $room->description !!}
                                @else
                                    <p class="text-muted mb-0">No description available.</p>
                                @endif
                            </div>
                        </div>

                        @if($amenities->isNotEmpty())
                            <div class="room-amenities mb-4">
                                <h3 class="box-title mb-3">Room Amenities</h3>
                                <div class="row">
                                    @foreach($amenities as $amenity)
                                        <div class="col-6 col-md-4 mb-3 d-flex align-items-start">
                                            <div style="width:34px; height:34px; display:flex; align-items:center; justify-content:center; margin-right:10px;">
                                                <i class="{{ $amenity->icon }}" style="color:#ecdd0c; font-size:18px;"></i>
                                            </div>
                                            <div>
                                                <div style="font-weight:600;">{{ $amenity->title }}</div>
                                                @if(!empty($amenity->description))
                                                    <small class="text-muted">{{ \Illuminate\Support\Str::limit($amenity->description, 70) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="tour-action mb-4">
                            <a href="{{ route('contact') }}" class="th-btn style4 th-icon me-2">Book Now</a>
                            <a href="{{ route('hotel', $room->hotel->slug ?? $room->hotel->id ?? '') }}" class="th-btn style3">View Hotel</a>
                        </div>

                        @if($images->isNotEmpty())
                            <div class="tour-gallery-wrapper mt-4">
                                <h3 class="page-title mb-3">Room's Gallery</h3>
                                <div class="row gy-3">
                                    @foreach($images as $image)
                                        <div class="col-6 col-md-4">
                                            <div class="tour-gallery-card">
                                                <div class="gallery-img global-img rounded">
                                                    <img src="{{ asset('storage/images/rooms/' . $image->image) }}" alt="gallery image" class="img-fluid" style="width:100%; height:200px; object-fit:cover;">
                                                    <a href="{{ asset('storage/images/rooms/' . $image->image) }}" class="icon-btn popup-image"><i class="fal fa-magnifying-glass-plus"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>

                    <div class="col-md-4">
                        <div class="card shadow-sm sticky-top" style="top:120px;">
                            <div class="card-body">
                                <h4 class="mb-2" style="font-weight:700;">{{ $room->title }}</h4>
                                <p class="mb-1 text-muted" style="font-size:14px;">{{ $room->hotel->name ?? 'Hotel' }}</p>
                                <p class="mb-2" style="font-size:18px; font-weight:700;">
                                    @php
                                        $roomCur = $room->currency ?? 'USD';
                                        $roomSym = getCurrencySymbol($roomCur);
                                        $roomPt = $room->price_display_type ?? 'per_night';
                                    @endphp
                                    @if($roomPt === 'per_month')
                                        {{ $roomSym }}{{ number_format($room->price_per_month ?? 0, 2) }} <small class="text-muted" style="font-weight:400;">/ month</small>
                                    @elseif($roomPt === 'both')
                                        {{ $roomSym }}{{ number_format($room->price_per_night ?? 0, 2) }} <small class="text-muted" style="font-weight:400;">/ night</small>
                                        @if(!empty($room->price_per_month))
                                            · {{ $roomSym }}{{ number_format($room->price_per_month, 2) }} <small class="text-muted" style="font-weight:400;">/ month</small>
                                        @endif
                                    @else
                                        {{ $roomSym }}{{ number_format($room->price_per_night ?? 0, 2) }} <small class="text-muted" style="font-weight:400;">/ night</small>
                                    @endif
                                </p>

                                <ul class="list-unstyled mb-3">
                                    <li class="mb-2"><i class="fa-solid fa-users me-2"></i> Max occupancy: <strong>{{ $room->max_occupancy }}</strong></li>
                                    <li class="mb-2"><i class="fa-solid fa-door-open me-2"></i> Available rooms: <strong>{{ $room->available_rooms ?? 0 }}</strong></li>
                                    <li class="mb-2"><i class="fa-solid fa-bed me-2"></i> Total rooms: <strong>{{ $room->total_rooms ?? 0 }}</strong></li>
                                </ul>

                                <div class="d-grid gap-2">
                                    <a href="{{ route('contact') }}" class="btn btn-primary">Book Now</a>
                                    <a href="{{ route('hotel', $room->hotel->slug ?? $room->hotel->id ?? '') }}" class="btn btn-outline-secondary">More rooms</a>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-none d-md-block">
                            <h5 class="mb-3">Quick Info</h5>
                            <table class="table table-borderless small">
                                <tr>
                                    <td>Room type</td>
                                    <td class="text-end">{{ $room->room_type ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Max guests</td>
                                    <td class="text-end">{{ $room->max_occupancy }}</td>
                                </tr>
                                <tr>
                                    <td>Category</td>
                                    <td class="text-end">{{ $room->hotel->category->name ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-4 col-sm-12">
            <aside class="sidebar-area">
                <div class="widget mb-4">
                    <h3 class="widget_title">Related Rooms</h3>
                    <div class="recent-post-wrap">
                        @foreach ($allRooms as $related)
                            <div class="recent-post d-flex align-items-center mb-3">
                                <div class="media-img me-3" style="width:78px; height:58px; overflow:hidden; border-radius:6px;">
                                    @if($related->image && file_exists(storage_path('app/public/images/rooms/' .$related->image)))
                                        <a href="{{ route('room',['slug'=>$related->slug]) }}"><img src="{{ asset('storage/images/rooms/' .$related->image) }}" alt="Image" class="img-fluid" style="width:100%; height:100%; object-fit:cover;"></a>
                                    @else
                                        <a href="{{ route('room',['slug'=>$related->slug]) }}"><img src="{{ asset('assets/img/tour/tour_3_1.jpg') }}" alt="Image" class="img-fluid" style="width:100%; height:100%; object-fit:cover;"></a>
                                    @endif
                                </div>
                                <div class="media-body">
                                    <h4 class="post-title mb-1"><a class="text-inherit" href="{{ route('room',['slug'=>$related->slug]) }}" style="font-weight:600;">{{ $related->title }}</a></h4>
                                    @php
                                        $relCur = $related->currency ?? 'USD';
                                        $relSym = getCurrencySymbol($relCur);
                                        $relPt = $related->price_display_type ?? 'per_night';
                                    @endphp
                                    <small class="text-muted">
                                        @if($relPt === 'per_month')
                                            {{ $relSym }}{{ number_format($related->price_per_month ?? 0, 2) }} / month
                                        @elseif($relPt === 'both')
                                            {{ $relSym }}{{ number_format($related->price_per_night ?? 0, 2) }}/night
                                            @if(!empty($related->price_per_month))
                                                · {{ $relSym }}{{ number_format($related->price_per_month, 2) }}/month
                                            @endif
                                        @else
                                            {{ $relSym }}{{ number_format($related->price_per_night ?? 0, 2) }} / night
                                        @endif
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="widget">
                    <h3 class="widget_title">Contact</h3>
                    <div class="p-3 bg-light rounded">
                        <p class="mb-1"><strong>{{ $room->hotel->name ?? 'Hotel' }}</strong></p>
                        <p class="mb-1"><i class="fa-solid fa-phone me-2"></i>{{ $room->hotel->phone ?? 'N/A' }}</p>
                        <p class="mb-1"><i class="fa-solid fa-envelope me-2"></i>{{ $room->hotel->email ?? 'N/A' }}</p>
                        <a href="{{ route('contact') }}" class="btn btn-outline-primary mt-2">Send Inquiry</a>
                    </div>
                </div>

            </aside>
        </div>

    </div>
</div>




            <!-- Sidebar Fixed Inside Row -->
            <div class="col-lg-4 col-sm-12">

                <aside class="sidebar-area">
                    <div class="widget">
                        <h3 class="widget_title">Related Rooms</h3>
                        <div class="recent-post-wrap">
                            @foreach ($allRooms as $room)
                            <div class="recent-post">
                                <div class="media-img">
                                    <a href="{{ route('room',['slug'=>$room->slug]) }}"><img src="{{ asset('storage/images/rooms/' .$room->image) }}" alt="Blog Image"></a>
                                </div>
                                <div class="media-body">
                                    <h4 class="post-title"><a class="text-inherit" href="{{ route('room',['slug'=>$room->slug]) }}">{{ $room->title }}</a></h4>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </aside>
            </div>
        </div>

    </div>
</section>



@endsection
