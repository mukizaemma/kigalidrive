
@extends('layouts.frontbase')

@section('content')
<section class="space">
    <div class="container">
        <div class="row">
            <div class="col-xxl-8 col-lg-7">
                <div class="row gy-24 gx-24">
                    @forelse($rooms as $room)
                        <div class="col-md-6">
                            <div class="tour-box th-ani">
                                <div class="tour-box_img global-img">
                                    @if(!empty($room->image) && file_exists(storage_path('app/public/images/rooms/' . $room->image)))
                                        <img src="{{ asset('storage/images/rooms/' . $room->image) }}" alt="{{ $room->room_type }}">
                                    @else
                                        <img src="{{ asset('assets/img/tour/tour_5_1.jpg') }}" alt="{{ $room->room_type }}">
                                    @endif
                                </div>

                                <div class="tour-content">
                                    <h3 class="box-title">
                                        <a href="{{ route('roomDetails', ['hotel' => $hotel->slug, 'room' => $room->slug]) }}">{{ $room->room_type }}</a>
                                    </h3>

                                    <p style="margin:6px 0;">
                                        @php
                                            $roomCur = $room->currency ?? 'USD';
                                            $roomSym = getCurrencySymbol($roomCur);
                                            $roomPt = $room->price_display_type ?? 'per_night';
                                        @endphp
                                        <i class="fa-solid fa-user" aria-hidden="true"></i>
                                        Max: <strong>{{ $room->max_occupancy }}</strong> &nbsp;•&nbsp;
                                        <i class="fa-solid fa-money-bill" aria-hidden="true"></i>
                                        @if($roomPt === 'per_month')
                                            <strong>{{ $roomSym }}{{ number_format($room->price_per_month ?? 0, 2) }}</strong> / month
                                        @elseif($roomPt === 'both')
                                            <strong>{{ $roomSym }}{{ number_format($room->price_per_night ?? 0, 2) }}</strong> / night
                                            @if(!empty($room->price_per_month))
                                                · <strong>{{ $roomSym }}{{ number_format($room->price_per_month, 2) }}</strong> / month
                                            @endif
                                        @else
                                            <strong>{{ $roomSym }}{{ number_format($room->price_per_night ?? 0, 2) }}</strong> / night
                                        @endif
                                    </p>

                                    <p style="margin:6px 0;">
                                        <i class="fa-solid fa-door-open" aria-hidden="true"></i>
                                        Available: <strong>{{ $room->available_rooms }}</strong> / {{ $room->total_rooms }}
                                        &nbsp;•&nbsp;
                                        @if($room->status === 'Available')
                                            <span style="display:inline-block;padding:4px 8px;border-radius:12px;background:#e6f9ee;color:#0b7a3a;font-weight:600;font-size:.85em;">Available</span>
                                        @else
                                            <span style="display:inline-block;padding:4px 8px;border-radius:12px;background:#fff1f0;color:#a30000;font-weight:600;font-size:.85em;">Unavailable</span>
                                        @endif
                                    </p>

                                    @if(!empty($room->description))
                                        <p style="margin:6px 0;">{{ \Illuminate\Support\Str::limit(strip_tags($room->description), 100) }}</p>
                                    @endif

                                    <div class="tour-action">
                                        <a href="{{ route('roomDetails', ['hotel' => $hotel->slug, 'room' => $room->slug]) }}" class="th-btn style4">View More</a>

                                        @if($room->status === 'Available' && $room->available_rooms > 0)
                                            <a href="{{ route('roomDetails', ['hotel' => $hotel->slug, 'room' => $room->slug]) }}" class="th-btn style3">Book Now</a>
                                        @else
                                            <a href="{{ route('contact') }}" class="th-btn style3">Contact Us</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-center">No rooms found for this hotel yet.</p>
                        </div>
                    @endforelse
                </div>

                @if(method_exists($rooms, 'links'))
                    <div class="row mt-24">
                        <div class="col-12 d-flex justify-content-center">
                            {{ $rooms->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-xxl-4 col-lg-5">
                <aside class="sidebar-area">
                    @if($hotel->amenities->isNotEmpty())
                        <div class="widget">
                            <h3 class="widget_title">Property amenities</h3>
                            <p class="text-muted small mb-2">Available to all guests at this property.</p>
                            <ul class="list-unstyled mb-0">
                                @foreach($hotel->amenities as $amen)
                                    <li class="d-flex align-items-start gap-2 py-1 border-bottom border-light">
                                        @if(!empty($amen->icon))
                                            <i class="{{ $amen->icon }} text-primary mt-1" style="width:1.25rem;"></i>
                                        @else
                                            <i class="fa-solid fa-check text-success mt-1" style="width:1.25rem;"></i>
                                        @endif
                                        <span>{{ $amen->title }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="widget">
                        @include('layouts.includes.property-listing-terms', ['listing' => $hotel])
                    </div>

                    <div class="widget">
                        @include('layouts.includes.property-cancellation-policy', ['listing' => $hotel])
                    </div>

                    <div class="widget widget_search">
                        <form class="search-form">
                            <input type="text" placeholder="Search">
                            <button type="submit"><i class="far fa-search"></i></button>
                        </form>
                    </div>

                    <div class="widget">
                        <h3 class="widget_title">Our Top Upcoming Trips</h3>
                        <div class="recent-post-wrap">
                            @foreach ($trips as $destination)
                                <div class="recent-post">
                                    <div class="media-img">
                                        <a href="{{ route('tour', ['slug' => $destination->slug]) }}"><img src="{{ asset('storage/images/trips/' . $destination->image) }}" alt="Blog Image"></a>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="post-title"><a class="text-inherit" href="{{ route('tour', ['slug' => $destination->slug]) }}">{{ $destination->title }}</a></h4>
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
