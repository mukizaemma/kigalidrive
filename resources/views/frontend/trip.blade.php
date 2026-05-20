@extends('layouts.frontbase')
<base href="/public">
@section('content')

@php
// Ensure $trip exists and relatedTrips is a collection
$trip = $trip ?? null;
$relatedTrips = $relatedTrips ?? collect();
$images = collect();

if($trip) {
    // prepare images array (trip->image might be a single filename or multiple)
    if (!empty($trip->image)) {
        $raw = $trip->image;
        $decoded = @json_decode($raw, true);
        if (is_array($decoded)) {
            $images = collect($decoded);
        } else {
            if (strpos($raw, ',') !== false) $images = collect(array_map('trim', explode(',', $raw)));
            elseif (strpos($raw, '|') !== false) $images = collect(array_map('trim', explode('|', $raw)));
            else $images = collect([trim($raw)]);
        }
    }

    if ($images->isEmpty() && !empty($trip->image)) $images = collect([$trip->image]);
}

if ($images->isEmpty()) {
    $images = collect([asset('assets/img/tour/tour_inner_2_1.jpg')]);
}
@endphp

@if(!$trip)
    <section class="space">
        <div class="container">
            <h2 class="box-title text-center">Trip not found</h2>
            <p class="text-center">The trip you requested does not exist or has been removed.</p>
        </div>
    </section>
@else

    <!-- Breadcumb / Hero -->
    <div class="breadcumb-wrapper" data-bg-src="{{ $images->first() }}" style="height: 500px; width: 70%; margin: 20px auto; background-image: url('{{ $images->first() }}'); background-size: cover; background-position: center;">
        <div class="container">
            <div class="breadcumb-content text-center" style="padding-top:160px;">
                <h1 class="breadcumb-title" style="color:#fff; text-shadow: 0 2px 10px rgba(0,0,0,0.6);">{{ $trip->title }}</h1>
                @if(!empty($trip->location))
                    <p style="color:#fff; opacity:.95; margin-top:8px;"><i class="fa-solid fa-location-dot"></i> {{ $trip->location }}</p>
                @endif
            </div>
        </div>
    </div>

    <section class="space bg-smoke">
        <div class="container mt-10" style="width:90%; margin:0 auto;">
            <div class="row g-5">
                <!-- Main content -->
                <div class="col-lg-8">
                    <div class="card" style="border-radius:10px; overflow:hidden;">
                        <div class="card-body p-4">
                            <h2 class="box-title">{{ $trip->title }}</h2>
                            <div class="page-meta mb-3">
                                @if(!empty($trip->duration)) <span class="me-3"><i class="fa-regular fa-clock"></i> {{ $trip->duration }}</span> @endif
                                @if(!empty($trip->languages)) <span class="me-3"><i class="fa-regular fa-language"></i> {{ $trip->languages }}</span> @endif
                            </div>

                            {{-- Description --}}
                            <div class="mb-4">
                                <h4 class="box-title mb-2">About this trip</h4>
                                <div class="rich-text">
                                    {!! nl2br(e($trip->description ?? 'No description available.')) !!}
                                </div>
                            </div>

                            {{-- Itinerary --}}
                            @if(!empty($trip->itinerary))
                                <div class="mb-4">
                                    <h4 class="box-title mb-2">Itinerary</h4>
                                    <div class="rich-text">{!! nl2br(e($trip->itinerary)) !!}</div>
                                </div>
                            @endif

                            {{-- Expectations --}}
                            @if(!empty($trip->expectations))
                                <div class="mb-4">
                                    <h4 class="box-title mb-2">What to expect</h4>
                                    <div class="rich-text">{!! nl2br(e($trip->expectations)) !!}</div>
                                </div>
                            @endif

                            {{-- Recommendations --}}
                            @if(!empty($trip->recommendations))
                                <div class="mb-4">
                                    <h4 class="box-title mb-2">Recommendations</h4>
                                    <div class="rich-text">{!! nl2br(e($trip->recommendations)) !!}</div>
                                </div>
                            @endif

                            {{-- Inclusions / Exclusions --}}
                            <div class="row">
                                @if(!empty($trip->inclusions))
                                    <div class="col-md-6 mb-4">
                                        <h4 class="box-title mb-2">Inclusions</h4>
                                        <div class="rich-text">{!! nl2br(e($trip->inclusions)) !!}</div>
                                    </div>
                                @endif
                                @if(!empty($trip->exclusions))
                                    <div class="col-md-6 mb-4">
                                        <h4 class="box-title mb-2">Exclusions</h4>
                                        <div class="rich-text">{!! nl2br(e($trip->exclusions)) !!}</div>
                                    </div>
                                @endif
                            </div>

                            {{-- Gallery (thumbnails) --}}
                            @if($images->isNotEmpty())
                                <div class="mb-4">
                                    <h4 class="box-title mb-3">Gallery</h4>
                                    <div class="row g-2">
                                        @foreach($images as $i)
                                            <div class="col-4">
                                                <a href="{{ (preg_match('#^(https?:)?//#', $i) || \Illuminate\Support\Str::startsWith($i, ['assets/','storage/','public/'])) ? $i : asset('storage/images/trips/' . $i) }}" class="d-block">
                                                    <img src="{{ (preg_match('#^(https?:)?//#', $i) || \Illuminate\Support\Str::startsWith($i, ['assets/','storage/','public/'])) ? $i : asset('storage/images/trips/' . $i) }}" alt="{{ $trip->title }}" style="width:100%; height:140px; object-fit:cover; border-radius:6px;">
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>

                    {{-- Related trips (cards) --}}
                    @if($relatedTrips->isNotEmpty())
                        <div class="mt-5">
                            <h3 class="box-title mb-3">Related Trips</h3>
                            <div class="row gy-4">
                                @foreach($relatedTrips as $r)
                                    @php
                                        $rImg = $r->image;
                                        $rUrl = asset('assets/img/tour/tour_3_1.jpg');
                                        if(!empty($rImg)){
                                            if(preg_match('#^(https?:)?//#', $rImg) || \Illuminate\Support\Str::startsWith($rImg, ['assets/','storage/','public/'])) $rUrl = $rImg;
                                            else {
                                                $p = storage_path('app/public/images/trips/' . $rImg);
                                                if(file_exists($p)) $rUrl = asset('storage/images/trips/' . $rImg);
                                            }
                                        }
                                    @endphp

                                    <div class="col-xxl-4 col-xl-6 col-md-6">
                                        <div class="tour-box th-ani">
                                            <div class="tour-box_img global-img">
                                                <img src="{{ $rUrl }}" alt="{{ $r->title }}" style="height:220px; object-fit:cover;">
                                            </div>

                                            <div class="tour-content">
                                                <h3 class="box-title">
                                                    <a href="{{ route('tour', $r->slug ?? $r->id) }}">{{ $r->title }}</a>
                                                </h3>

                                                <p class="mb-2 small" style="margin:6px 0;">
                                                    {{ \Illuminate\Support\Str::limit(strip_tags($r->description), 120) }}
                                                </p>

                                                <div class="tour-action">
                                                    <a href="{{ route('tour', $r->slug ?? $r->id) }}" class="th-btn style4 th-icon">View Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

                <!-- Sidebar (summary + booking) -->
                <div class="col-lg-4">
                    <aside class="sidebar-area">
                        <div class="card" style="border-radius:10px; overflow:hidden;">
                            <div class="card-body p-4">
                                <h4 style="margin-bottom:6px;">{{ $trip->title }}</h4>
                                @if(!empty($trip->location)) <p class="text-muted mb-1"><i class="fa-solid fa-location-dot"></i> {{ $trip->location }}</p> @endif

                                <ul class="list-unstyled mb-3 small">
                                    @if(!empty($trip->duration)) <li><strong>Duration:</strong> {{ $trip->duration }}</li> @endif
                                    @if(!empty($trip->languages)) <li><strong>Languages:</strong> {{ $trip->languages }}</li> @endif
                                    @if(!empty($trip->maxPeople)) <li><strong>Max People:</strong> {{ $trip->maxPeople }}</li> @endif
                                    @if(!empty($trip->minAge)) <li><strong>Min Age:</strong> {{ $trip->minAge }}</li> @endif
                                </ul>
                            </div>
                        </div>

                        {{-- Reservation Form --}}
                        <div class="card mt-4" style="border-radius:10px;">
                            <div class="card-body p-4">
                                <h4 class="mb-3" style="font-weight: 600;">Make a Reservation</h4>
                                <p class="text-muted mb-3" style="font-size: 14px;">Fill in the form below to book this trip. We'll contact you shortly to confirm your reservation.</p>
                                <form action="{{ route('tripInquiry') }}" method="POST" id="trip-reservation-form">
                                    @csrf
                                    <input type="hidden" name="trip_id" value="{{ $trip->id }}">
                                    <input type="hidden" name="trip_title" value="{{ $trip->title }}">
                                    
                                    <div class="mb-3">
                                        <label class="form-label" style="font-weight: 500; margin-bottom: 5px;">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label" style="font-weight: 500; margin-bottom: 5px;">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" placeholder="your.email@example.com" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label" style="font-weight: 500; margin-bottom: 5px;">Phone Number <span class="text-danger">*</span></label>
                                        <input type="tel" name="phone" class="form-control" placeholder="+250 788 123 456" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label" style="font-weight: 500; margin-bottom: 5px;">Preferred Date</label>
                                        <input type="date" name="preferred_date" class="form-control" min="{{ date('Y-m-d') }}">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label" style="font-weight: 500; margin-bottom: 5px;">Number of People</label>
                                        <input type="number" name="number_of_people" class="form-control" placeholder="e.g. 2" min="1" @if(!empty($trip->maxPeople)) max="{{ $trip->maxPeople }}" @endif>
                                        @if(!empty($trip->maxPeople))
                                            <small class="text-muted">Maximum: {{ $trip->maxPeople }} people</small>
                                        @endif
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label" style="font-weight: 500; margin-bottom: 5px;">Additional Message</label>
                                        <textarea name="message" class="form-control" rows="4" placeholder="Any special requests or questions?"></textarea>
                                    </div>
                                    
                                    <div class="mb-0">
                                        <button class="th-btn style4 th-icon w-100" type="submit" style="padding: 12px;">
                                            <i class="fas fa-paper-plane me-2"></i>Submit Reservation
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </aside>
                </div>
            </div>
        </div>
    </section>

@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Optional: simple lightbox for gallery images - uses existing popup-image class if available in template
    // Keep Swiper init if you want to use a slider instead (not included here)
});
</script>
@endpush

@endsection
