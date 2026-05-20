@extends('layouts.frontbase')

@section('content')
<style>
    .rich-html-content { line-height: 1.65; }
    .rich-html-content p:last-child { margin-bottom: 0; }
    .rich-html-content ul, .rich-html-content ol { margin: 0.75rem 0; padding-left: 1.25rem; }
    .rich-html-content h1, .rich-html-content h2, .rich-html-content h3 { margin-top: 1rem; margin-bottom: 0.5rem; font-size: 1.1rem; }
    /* Gallery Styles - Same as property view */
    .image-gallery-main {
        position: relative;
        width: 100%;
        height: 500px;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 10px;
        background: #f0f0f0;
        cursor: pointer;
    }
    .image-gallery-main img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .image-gallery-thumbs {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }
    .gallery-thumb {
        position: relative;
        height: 120px;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        opacity: 0.8;
        transition: opacity 0.3s;
    }
    .gallery-thumb:hover {
        opacity: 1;
    }
    .gallery-thumb.active {
        opacity: 1;
        border: 3px solid #0071c2;
    }
    .gallery-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .gallery-overlay {
        position: absolute;
        bottom: 15px;
        right: 15px;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 8px 15px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
    }
    .gallery-thumbnail.active {
        border: 2px solid #0071c2 !important;
    }
    .gallery-thumbnail:hover {
        opacity: 0.8;
    }
    @media (max-width: 991px) {
        .image-gallery-main {
            height: 300px;
        }
        .image-gallery-thumbs {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    @media (max-width: 767px) {
        .image-gallery-thumbs {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

@php
$allImages = $images ?? collect();
if(! ($allImages instanceof \Illuminate\Support\Collection)) {
    $allImages = collect($allImages);
}
// Ensure images have url key
if($allImages->isNotEmpty() && !isset($allImages->first()['url'])) {
    $allImages = $allImages->map(function($img) {
        if(is_string($img)) {
            return ['url' => $img, 'type' => 'room', 'caption' => ''];
        }
        return $img;
    });
}
$mainImage = $allImages->isNotEmpty() ? $allImages->first()['url'] : asset('assets/img/tour/tour_inner_2_1.jpg');
$relatedRooms = $relatedRooms ?? ($rooms ?? collect());
$trips = $trips ?? collect();
@endphp

<section class="space">
    <div class="container">
        <div style="width:90%; margin:0 auto;">
            <div class="tour-page-single">

                <!-- Image Gallery - Same layout as property view -->
                <div class="mb-4">
                    <div class="image-gallery-main">
                        <img src="{{ $mainImage }}" alt="{{ $room->room_type }}" id="mainGalleryImage" onclick="openGalleryModal(0)">
                        @if($allImages->count() > 1)
                            <div class="gallery-overlay" onclick="openGalleryModal(0)">
                                <i class="fas fa-images"></i> View all {{ $allImages->count() }} photos
                            </div>
                        @endif
                    </div>
                    @if($allImages->count() > 1)
                        <div class="image-gallery-thumbs">
                            @foreach($allImages->take(4) as $index => $img)
                                <div class="gallery-thumb {{ $index == 0 ? 'active' : '' }}" onclick="changeMainImage('{{ $img['url'] }}', this, {{ $index }})">
                                    <img src="{{ $img['url'] }}" alt="Gallery thumbnail">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="page-content mt-4">
                    <div class="page-meta mb-30 d-flex align-items-center justify-content-between flex-wrap">
                        <div>
                            <a class="page-tag" href="#">{{ $hotel->name ?? '' }}</a>
                            <span class="ratting ms-3"><i class="fa-sharp fa-solid fa-star"></i><span>{{ number_format((float)($room->rating ?? 4.8), 1) }}</span></span>
                        </div>
                        <div class="text-end">
                            @php
                                $roomCurrency = $room->currency ?? 'USD';
                                $roomCurrencySymbol = getCurrencySymbol($roomCurrency);
                                $pt = $room->price_display_type ?? 'per_night';
                            @endphp
                            <div style="font-size:18px; font-weight:700;">
                                @if($pt === 'per_month')
                                    {{ $roomCurrencySymbol }}{{ number_format($room->price_per_month ?? 0, 2) }} <small class="text-muted" style="font-weight:400;">/ month</small>
                                @elseif($pt === 'both')
                                    {{ $roomCurrencySymbol }}{{ number_format($room->price_per_night ?? 0, 2) }} <small class="text-muted" style="font-weight:400;">/ night</small>
                                    @if(!empty($room->price_per_month))
                                        <span class="ms-2">{{ $roomCurrencySymbol }}{{ number_format($room->price_per_month, 2) }} <small class="text-muted" style="font-weight:400;">/ month</small></span>
                                    @endif
                                @else
                                    {{ $roomCurrencySymbol }}{{ number_format($room->price_per_night ?? 0, 2) }} <small class="text-muted" style="font-weight:400;">/ night</small>
                                @endif
                            </div>
                            <div class="mt-1"><a href="{{ route('contact') }}" class="th-btn style4 th-icon">Book Now</a></div>
                        </div>
                    </div>

                    <h2 class="box-title">{{ $room->room_type }}</h2>

                    <div class="box-text mb-30 rich-html-content">
                        @php
                            $__roomDesc = $room->description ?? null;
                            $__hotelDesc = $hotel->description ?? null;
                        @endphp
                        @if(filled($__roomDesc))
                            {!! $__roomDesc !!}
                        @elseif(filled($__hotelDesc))
                            {!! $__hotelDesc !!}
                        @else
                            <p class="text-muted mb-0">No description available.</p>
                        @endif
                    </div>

                    <div class="tour-snapshot mb-4">
                        <h4 class="box-title">Room Snapshot</h4>
                        <div class="tour-snap-wrapp d-flex flex-wrap gap-3">
                            <div class="tour-snap">
                                <div class="icon"><i class="fa-light fa-clock"></i></div>
                                <div class="content">
                                    <span class="title">Max Occupancy:</span>
                                    <span>{{ $room->max_occupancy }}</span>
                                </div>
                            </div>

                            <div class="tour-snap">
                                <div class="icon"><img src="{{ asset('assets/img/icon/guide2.svg') }}" alt=""></div>
                                <div class="content">
                                    <span class="title">Price</span>
                                    <span>
                                        @if($pt === 'per_month')
                                            {{ $roomCurrencySymbol }}{{ number_format($room->price_per_month ?? 0, 2) }}/month
                                        @elseif($pt === 'both')
                                            {{ $roomCurrencySymbol }}{{ number_format($room->price_per_night ?? 0, 2) }}/night
                                            @if(!empty($room->price_per_month))
                                                · {{ $roomCurrencySymbol }}{{ number_format($room->price_per_month, 2) }}/month
                                            @endif
                                        @else
                                            {{ $roomCurrencySymbol }}{{ number_format($room->price_per_night ?? 0, 2) }}/night
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="tour-snap">
                                <div class="icon"><img src="{{ asset('assets/img/icon/ship.svg') }}" alt=""></div>
                                <div class="content">
                                    <span class="title">Available Rooms:</span>
                                    <span>{{ $room->available_rooms ?? 0 }} / {{ $room->total_rooms ?? 0 }}</span>
                                </div>
                            </div>

                            <div class="tour-snap">
                                <div class="icon"><img src="{{ asset('assets/img/icon/01.svg') }}" alt=""></div>
                                <div class="content">
                                    <span class="title">Status</span>
                                    <span>
                                        @if(($room->status ?? '') === 'Available')
                                            <span class="badge" style="background:#e6f9ee;color:#0b7a3a;padding:4px 8px;border-radius:10px;">Available</span>
                                        @else
                                            <span class="badge" style="background:#fff1f0;color:#a30000;padding:4px 8px;border-radius:10px;">Unavailable</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($hotel->amenities->isNotEmpty())
                        <div class="tour-snapshot mb-4">
                            <h4 class="box-title">At this property</h4>
                            <p class="text-muted small mb-2">Shared facilities for all guests (e.g. pool, spa, meeting rooms).</p>
                            <div class="tour-snap-wrapp">
                                <div class="row gx-2 gy-2" style="margin:0;">
                                    @foreach($hotel->amenities as $amen)
                                        <div class="col-auto" style="padding:4px;">
                                            <span class="badge" style="display:inline-block;padding:8px 12px;border-radius:8px;background:#eef6ff;color:#0a3d62;">
                                                @if(!empty($amen->icon))<i class="{{ $amen->icon }} me-1"></i>@endif
                                                {{ $amen->title }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($room->roomAmenities->isNotEmpty())
                        <div class="tour-snapshot mb-4">
                            <h4 class="box-title">This room</h4>
                            <div class="tour-snap-wrapp">
                                <div class="row gx-2 gy-2" style="margin:0;">
                                    @foreach($room->roomAmenities as $amen)
                                        <div class="col-auto" style="padding:4px;">
                                            <span class="badge" style="display:inline-block;padding:8px 12px;border-radius:8px;background:#f5f6fb;color:#222;">
                                                @if(!empty($amen->icon))<i class="{{ $amen->icon }} me-1"></i>@endif
                                                {{ $amen->title }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @php
                        $maxGuestsRoom = max(1, (int) ($room->max_occupancy ?? 1));
                        $roomBookable = ($room->accepts_room_bookings ?? true)
                            && (int) ($room->available_rooms ?? 0) > 0
                            && ($hotel->accepts_bookings ?? true);
                    @endphp
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 small">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </div>
                    @endif

                    @if(!$roomBookable)
                        <div class="alert alert-warning border-0 mb-4" style="border-radius:10px;">
                            <strong>Booking unavailable.</strong>
                            @if(!($hotel->accepts_bookings ?? true))
                                This property is not accepting new bookings.
                            @elseif(!($room->accepts_room_bookings ?? true))
                                This room is marked as fully booked (owner closed new requests).
                            @else
                                There are no available units left for this room type.
                            @endif
                        </div>
                    @endif

                    <div class="th-comment-form mt-4">
                        <div class="row gx-4">
                            <div class="col-lg-8">
                                <h3 class="blog-inner-title h4 mb-2">Request a stay</h3>
                                <p class="mb-25 text-muted small">Check-in day is included; check-out is the day you leave (standard hotel nights). Max {{ $maxGuestsRoom }} guests for this room.</p>

                                <form action="{{ route('hotel.room.booking.request', ['hotelSlug' => $hotel->slug, 'roomSlug' => $room->slug]) }}" method="POST" class="row">
                                    @csrf

                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Full name *</label>
                                        <input type="text" name="guest_name" value="{{ old('guest_name') }}" class="form-control" required maxlength="255">
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Email *</label>
                                        <input type="email" name="guest_email" value="{{ old('guest_email') }}" class="form-control" required>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Phone</label>
                                        <input type="tel" name="guest_phone" value="{{ old('guest_phone') }}" class="form-control" maxlength="100">
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Guests *</label>
                                        <select name="guests_count" class="form-control" required @if(!$roomBookable) disabled @endif>
                                            @for($g = 1; $g <= $maxGuestsRoom; $g++)
                                                <option value="{{ $g }}" @selected((int)old('guests_count', 1) === $g)>{{ $g }}</option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Check-in *</label>
                                        <input type="date" name="check_in" id="hotelRoomCheckIn" value="{{ old('check_in') }}" class="form-control" required min="{{ date('Y-m-d') }}" @if(!$roomBookable) disabled @endif>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Check-out *</label>
                                        <input type="date" name="check_out" id="hotelRoomCheckOut" value="{{ old('check_out') }}" class="form-control" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" @if(!$roomBookable) disabled @endif>
                                    </div>

                                    <div class="col-12 form-group mb-0">
                                        <div id="hotelRoomAvailabilityNote" class="alert alert-sm py-2 px-3 mb-0 d-none small" role="status"></div>
                                    </div>

                                    <div class="col-12 form-group">
                                        <label class="form-label">Special requests</label>
                                        <textarea name="special_requests" class="form-control" rows="3" placeholder="Optional">{{ old('special_requests') }}</textarea>
                                    </div>

                                    <div class="col-12 form-group mb-0">
                                        <button class="th-btn" type="submit" @if(!$roomBookable) disabled @endif>Request booking <img src="{{ asset('assets/img/icon/plane2.svg') }}" alt=""></button>
                                    </div>
                                </form>
                            </div>

                            <div class="col-lg-4 mt-4 mt-lg-0">
                                <div class="card shadow-sm" style="border-radius:8px;">
                                    <div class="card-body">
                                        <h5 style="font-weight:700;margin-bottom:8px;">{{ $room->room_type }}</h5>
                                        <p class="mb-1 text-muted">{{ $hotel->name ?? '' }}</p>
                                        <p style="font-size:18px;font-weight:700;">
                                            @if($pt === 'per_month')
                                                {{ $roomCurrencySymbol }}{{ number_format($room->price_per_month ?? 0, 2) }} <small class="text-muted" style="font-weight:400;">/ month</small>
                                            @elseif($pt === 'both')
                                                {{ $roomCurrencySymbol }}{{ number_format($room->price_per_night ?? 0, 2) }} <small class="text-muted" style="font-weight:400;">/ night</small>
                                                @if(!empty($room->price_per_month))
                                                    · {{ $roomCurrencySymbol }}{{ number_format($room->price_per_month, 2) }} <small class="text-muted" style="font-weight:400;">/ month</small>
                                                @endif
                                            @else
                                                {{ $roomCurrencySymbol }}{{ number_format($room->price_per_night ?? 0, 2) }} <small class="text-muted" style="font-weight:400;">/ night</small>
                                            @endif
                                        </p>
                                        <ul class="list-unstyled small mb-3">
                                            <li>Max occupancy: <strong>{{ $room->max_occupancy ?? 0 }}</strong></li>
                                            <li>Available: <strong>{{ $room->available_rooms ?? 0 }}</strong></li>
                                            <li>Type: <strong>{{ $room->room_type ?? '' }}</strong></li>
                                        </ul>
                                        <a href="{{ route('contact') }}" class="btn btn-primary w-100">Contact / Book</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($relatedRooms->isNotEmpty())
                        <div class="related-rooms mt-5">
                            <h4 class="box-title mb-3">Related Rooms</h4>

                            <div class="row gy-4">
                                @foreach($relatedRooms as $r)
                                    <div class="col-xxl-4 col-xl-6 col-md-6">
                                        <div class="tour-box th-ani">
                                            <div class="tour-box_img global-img">
                                                @php
                                                    $imgSrc = asset('assets/img/tour/tour_3_1.jpg');
                                                    if(!empty($r->image)){
                                                        if(preg_match('#^(https?:)?//#', $r->image) || \Illuminate\Support\Str::startsWith($r->image, ['assets/','storage/','public/'])) $imgSrc = $r->image;
                                                        else {
                                                            $p = storage_path('app/public/images/rooms/' . $r->image);
                                                            if(file_exists($p)) $imgSrc = asset('storage/images/rooms/' . $r->image);
                                                        }
                                                    }
                                                @endphp

                                                <img src="{{ $imgSrc }}" alt="{{ $r->room_type }}">
                                            </div>

                                            <div class="tour-content">
                                                <h3 class="box-title">
                                                    @php
                                                        $hotelParam = $hotel->slug ?? $hotel->id ?? ($r->hotel->slug ?? $r->hotel->id ?? null);
                                                        $roomParam  = $r->slug  ?? $r->id ?? null;
                                                    @endphp

                                                    @if($hotelParam && $roomParam)
                                                        <a href="{{ route('roomDetails', ['hotel' => $hotelParam, 'room' => $roomParam]) }}">{{ $r->room_type }}</a>
                                                    @else
                                                        <a href="#">{{ $r->room_type }}</a>
                                                    @endif
                                                </h3>

                                                <p class="mb-2" style="margin:6px 0;">
                                                    @php
                                                        $relatedRoomCurrency = $r->currency ?? 'USD';
                                                        $relatedRoomCurrencySymbol = getCurrencySymbol($relatedRoomCurrency);
                                                        $rPt = $r->price_display_type ?? 'per_night';
                                                    @endphp
                                                    <strong>Price:</strong>
                                                    @if($rPt === 'per_month')
                                                        {{ $relatedRoomCurrencySymbol }}{{ number_format($r->price_per_month ?? 0, 2) }}/month
                                                    @elseif($rPt === 'both')
                                                        {{ $relatedRoomCurrencySymbol }}{{ number_format($r->price_per_night ?? 0, 2) }}/night
                                                        @if(!empty($r->price_per_month))
                                                            · {{ $relatedRoomCurrencySymbol }}{{ number_format($r->price_per_month, 2) }}/month
                                                        @endif
                                                    @else
                                                        {{ $relatedRoomCurrencySymbol }}{{ number_format($r->price_per_night ?? 0, 2) }}/night
                                                    @endif
                                                </p>

                                                <p class="mb-2" style="margin:6px 0;">
                                                    <i class="fa-solid fa-users"></i> Max: {{ $r->max_occupancy ?? 0 }}
                                                    &nbsp;•&nbsp;
                                                    <i class="fa-solid fa-door-open"></i> Available: {{ $r->available_rooms ?? 0 }}
                                                </p>

                                                @if(!empty($r->description))
                                                    <p class="small text-muted" style="margin:6px 0;">{{ \Illuminate\Support\Str::limit(strip_tags($r->description), 120) }}</p>
                                                @endif

                                                @if($r->roomAmenities->isNotEmpty())
                                                    <div class="room-amenities mt-2">
                                                        @foreach($r->roomAmenities->take(6) as $amen)
                                                            <span class="badge rounded-pill bg-light text-dark me-1 mb-1">{{ $amen->title }}</span>
                                                        @endforeach
                                                        @if($r->roomAmenities->count() > 6)
                                                            <span class="badge rounded-pill bg-light text-muted me-1 mb-1">+{{ $r->roomAmenities->count() - 6 }} more</span>
                                                        @endif
                                                    </div>
                                                @endif

                                                <div class="tour-action mt-3">
                                                    @if($hotelParam && $roomParam)
                                                        <a href="{{ route('roomDetails', ['hotel' => $hotelParam, 'room' => $roomParam]) }}" class="th-btn style4 th-icon">View</a>
                                                    @elseif($hotelParam)
                                                        <a href="{{ route('hotel', $hotelParam) }}" class="th-btn style4 th-icon">View hotel</a>
                                                    @else
                                                        <button type="button" class="th-btn style4 th-icon" disabled>View</button>
                                                    @endif

                                                    <a href="{{ route('contact') }}" class="th-btn style3">Book Now</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Swiper !== 'undefined') {
        var thumb = new Swiper('.tour-thumb-slider', {
            loop: true,
            slidesPerView: 3,
            breakpoints: { 0: { slidesPerView: 2 }, 576: { slidesPerView: 2 }, 768: { slidesPerView: 3 }, 992: { slidesPerView: 4 }, 1200: { slidesPerView: 5 } }
        });

        var main = new Swiper('#tourSlider4', {
            effect: 'fade',
            loop: true,
            autoplay: { delay: 5000 },
            thumbs: { swiper: thumb }
        });
    }
});
</script>
@endpush

<!-- Gallery Modal - Same as property view -->
<div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white">Photo Gallery - {{ $room->room_type }}</h5>
                <span class="text-white me-3" id="galleryCounter">1 / {{ $allImages->count() }}</span>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 position-relative" style="display: flex; align-items: center; justify-content: center; min-height: 80vh;">
                <button class="btn btn-light position-absolute" style="left: 20px; top: 50%; transform: translateY(-50%); z-index: 10;" onclick="previousImage()" id="prevBtn">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <img id="galleryMainImage" src="" alt="Gallery Image" style="max-width: 90%; max-height: 80vh; object-fit: contain;">
                <button class="btn btn-light position-absolute" style="right: 20px; top: 50%; transform: translateY(-50%); z-index: 10;" onclick="nextImage()" id="nextBtn">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div class="modal-footer border-0 bg-dark">
                <div class="w-100">
                    <div class="d-flex gap-2 overflow-auto pb-2" style="max-height: 120px;">
                        @foreach($allImages as $index => $img)
                            <img src="{{ $img['url'] }}" 
                                 alt="Thumbnail" 
                                 class="gallery-thumbnail {{ $index == 0 ? 'active' : '' }}" 
                                 style="width: 100px; height: 80px; object-fit: cover; border-radius: 8px; cursor: pointer; border: 2px solid transparent;"
                                 onclick="showImage({{ $index }})"
                                 data-index="{{ $index }}">
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Gallery images array
    const galleryImages = @json($allImages->map(function($img) { return $img['url']; })->values());
    let currentImageIndex = 0;
    
    function changeMainImage(src, thumbElement, index = 0) {
        document.getElementById('mainGalleryImage').src = src;
        currentImageIndex = index;
        if (thumbElement) {
            document.querySelectorAll('.gallery-thumb').forEach(el => el.classList.remove('active'));
            thumbElement.classList.add('active');
        }
    }
    
    function openGalleryModal(startIndex = 0) {
        currentImageIndex = startIndex;
        showImage(startIndex);
        const modal = new bootstrap.Modal(document.getElementById('galleryModal'));
        modal.show();
    }
    
    function showImage(index) {
        if (index < 0 || index >= galleryImages.length) return;
        currentImageIndex = index;
        document.getElementById('galleryMainImage').src = galleryImages[index];
        document.getElementById('galleryCounter').textContent = (index + 1) + ' / ' + galleryImages.length;
        
        // Update thumbnails
        document.querySelectorAll('.gallery-thumbnail').forEach((thumb, i) => {
            if (i === index) {
                thumb.classList.add('active');
            } else {
                thumb.classList.remove('active');
            }
        });
    }
    
    function nextImage() {
        const next = (currentImageIndex + 1) % galleryImages.length;
        showImage(next);
    }
    
    function previousImage() {
        const prev = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
        showImage(prev);
    }
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('galleryModal');
        if (modal && modal.classList.contains('show')) {
            if (e.key === 'ArrowRight') {
                nextImage();
            } else if (e.key === 'ArrowLeft') {
                previousImage();
            } else if (e.key === 'Escape') {
                bootstrap.Modal.getInstance(modal).hide();
            }
        }
    });

    (function () {
        const ci = document.getElementById('hotelRoomCheckIn');
        const co = document.getElementById('hotelRoomCheckOut');
        const note = document.getElementById('hotelRoomAvailabilityNote');
        const availUrl = @json(route('hotel.room.booking.availability', ['hotelSlug' => $hotel->slug, 'roomSlug' => $room->slug]));
        if (!ci || !co || !note) return;
        function checkAvail() {
            if (!ci.value || !co.value || co.value <= ci.value) {
                note.classList.add('d-none');
                note.classList.remove('alert-success', 'alert-danger', 'alert-warning');
                return;
            }
            note.classList.remove('d-none', 'alert-success', 'alert-danger', 'alert-warning');
            note.textContent = 'Checking availability…';
            fetch(availUrl + '?check_in=' + encodeURIComponent(ci.value) + '&check_out=' + encodeURIComponent(co.value), {
                headers: { 'Accept': 'application/json' }
            }).then(r => r.json()).then(function (j) {
                if (j.available) {
                    note.classList.add('alert-success');
                    note.textContent = 'These dates look available for this room category (subject to confirmation).';
                } else {
                    note.classList.add('alert-danger');
                    note.textContent = j.message || 'No remaining rooms for one or more nights in this range.';
                }
            }).catch(function () {
                note.classList.add('alert-warning');
                note.textContent = 'Could not verify availability right now. You may still submit your request.';
            });
        }
        ci.addEventListener('change', checkAvail);
        co.addEventListener('change', checkAvail);
    })();
</script>

@endsection
