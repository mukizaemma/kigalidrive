@extends('layouts.frontbase')
<base href="/public">
@section('content')

@php
$images = $destination->images ?? collect();
if ($images->isEmpty() && !empty($destination->image)) {
    $images = collect([$destination->image]);
}
if ($images->isEmpty()) {
    $images = collect([asset('assets/img/tour/tour_inner_2_1.jpg')]);
}
@endphp

<!-- Breadcumb / Hero -->
<div class="breadcumb-wrapper" data-bg-src="{{ $destination->image ? asset('storage/images/trip-destinations/' . $destination->image) : asset('assets/img/bg/breadcumb-bg-1.jpg') }}" style="height: 500px; width: 100%; background-size: cover; background-position: center;">
    <div class="container">
        <div class="breadcumb-content text-center" style="padding-top:160px;">
            <h1 class="breadcumb-title" style="color:#fff; text-shadow: 0 2px 10px rgba(0,0,0,0.6);">{{ $destination->name }}</h1>
            @if(!empty($destination->location))
                <p style="color:#fff; opacity:.95; margin-top:8px;"><i class="fa-solid fa-location-dot"></i> {{ $destination->location }}</p>
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
                        <h2 class="box-title">{{ $destination->name }}</h2>
                        
                        {{-- Description --}}
                        @if(!empty($destination->description))
                            <div class="mb-4">
                                {{-- <h4 class="box-title mb-2">About this Destination</h4> --}}
                                <div class="rich-text">
                                    {!!$destination->description !!}
                                </div>
                            </div>
                        @endif

                        {{-- Gallery --}}
                        @if($destination->images->isNotEmpty())
                            <div class="mb-4">
                                <h4 class="box-title mb-3">Gallery</h4>
                                <div class="row g-2">
                                    @foreach($destination->images as $img)
                                        <div class="col-4">
                                            <a href="{{ asset('storage/images/trip-destinations/' . $img->image) }}" class="d-block">
                                                <img src="{{ asset('storage/images/trip-destinations/' . $img->image) }}" alt="{{ $destination->name }}" style="width:100%; height:140px; object-fit:cover; border-radius:6px;">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Activities Section --}}
                        @if($destination->trips->isNotEmpty())
                            <div class="mb-4">
                                <h4 class="box-title mb-3">Available Activities</h4>

                                <div class="card mb-4" style="border-radius:10px;">
                                    <div class="card-body p-4">
                                        <h5 class="mb-2">Plan Your Trip</h5>
                                        <p class="text-muted mb-3">Select one or more activities below and submit a request. We'll respond with a trip plan and total cost.</p>
                                        <form action="{{ route('tripRequestMultiple') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="trip_destination_id" value="{{ $destination->id }}">

                                            <div class="mb-3">
                                                <label class="form-label">Your Name *</label>
                                                <input type="text" name="name" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Email *</label>
                                                <input type="email" name="email" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Phone *</label>
                                                <input type="tel" name="phone" class="form-control" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Preferred Date</label>
                                                    <input type="date" name="preferred_date" class="form-control" min="{{ date('Y-m-d') }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Number of People</label>
                                                    <input type="number" name="number_of_people" class="form-control" min="1">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Additional Message</label>
                                                <textarea name="message" class="form-control" rows="3" placeholder="Tell us your interests, budget, or timing..."></textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Select Activities *</label>
                                                <div class="row">
                                                    @foreach($destination->trips as $trip)
                                                        <div class="col-md-6 mb-2">
                                                            <label class="d-flex align-items-center gap-2">
                                                                <input type="checkbox" name="trip_ids[]" value="{{ $trip->id }}">
                                                                <span>{{ $trip->title }}</span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-end">
                                                <button type="submit" class="th-btn style3">Send Trip Request</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="row gy-4">
                                    @foreach($destination->trips as $trip)
                                        @php
                                            $tripImg = $trip->image;
                                            $tripUrl = asset('assets/img/tour/tour_3_1.jpg');
                                            if(!empty($tripImg)){
                                                if(preg_match('#^(https?:)?//#', $tripImg) || \Illuminate\Support\Str::startsWith($tripImg, ['assets/','storage/','public/'])) $tripUrl = $tripImg;
                                                else {
                                                    $p = storage_path('app/public/images/trips/' . $tripImg);
                                                    if(file_exists($p)) $tripUrl = asset('storage/images/trips/' . $tripImg);
                                                }
                                            }
                                        @endphp

                                        <div class="col-12">
                                            <div class="tour-box th-ani" style="display: flex; gap: 20px;">
                                                <div class="tour-box_img global-img" style="flex: 0 0 200px;">
                                                    <img src="{{ $tripUrl }}" alt="{{ $trip->title }}" style="height:150px; width:100%; object-fit:cover; border-radius:6px;">
                                                </div>
                                                <div class="tour-content" style="flex: 1;">
                                                    <h3 class="box-title">
                                                        <a href="{{ route('tour', $trip->slug) }}">{{ $trip->title }}</a>
                                                    </h3>
                                                    @if($trip->location)
                                                        <p style="margin: 5px 0; color: #666;">
                                                            <i class="fa-solid fa-location-dot text-primary me-2"></i>{{ $trip->location }}
                                                        </p>
                                                    @endif
                                                    @if($trip->duration)
                                                        <p style="margin: 5px 0; color: #666;">
                                                            <i class="fas fa-clock text-primary me-2"></i>{{ $trip->duration }}
                                                        </p>
                                                    @endif
                                                    @if($trip->description)
                                                        <p class="mt-2" style="font-size: 14px; color: #666;">
                                                            {{ \Illuminate\Support\Str::limit(strip_tags($trip->description), 120) }}
                                                        </p>
                                                    @endif
                                                    <div class="tour-action mt-3">
                                                        <a href="{{ route('tour', $trip->slug) }}" class="th-btn style4 th-icon">View Details</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <p>No activities available for this destination yet. Check back soon!</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Related Destinations --}}
                @if($relatedDestinations->isNotEmpty())
                    <div class="mt-5">
                        <h3 class="box-title mb-3">Other Destinations</h3>
                        <div class="row gy-4">
                            @foreach($relatedDestinations as $relDest)
                                @php
                                    $relImg = $relDest->image;
                                    $relUrl = asset('assets/img/tour/tour_3_1.jpg');
                                    if(!empty($relImg)){
                                        if(preg_match('#^(https?:)?//#', $relImg) || \Illuminate\Support\Str::startsWith($relImg, ['assets/','storage/','public/'])) $relUrl = $relImg;
                                        else {
                                            $p = storage_path('app/public/images/trip-destinations/' . $relImg);
                                            if(file_exists($p)) $relUrl = asset('storage/images/trip-destinations/' . $relImg);
                                        }
                                    }
                                @endphp

                                <div class="col-xxl-4 col-xl-6 col-md-6">
                                    <div class="tour-box th-ani">
                                        <div class="tour-box_img global-img">
                                            <img src="{{ $relUrl }}" alt="{{ $relDest->name }}" style="height:220px; object-fit:cover;">
                                        </div>
                                        <div class="tour-content">
                                            <h3 class="box-title">
                                                <a href="{{ route('tripDestination', $relDest->slug) }}">{{ $relDest->name }}</a>
                                            </h3>
                                            <p class="mb-2 small" style="margin:6px 0;">
                                                {{ \Illuminate\Support\Str::limit(strip_tags($relDest->description ?? ''), 120) }}
                                            </p>
                                            <div class="tour-action">
                                                <a href="{{ route('tripDestination', $relDest->slug) }}" class="th-btn style4 th-icon">View Activities</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <aside class="sidebar-area">
                    <div class="card" style="border-radius:10px; overflow:hidden;">
                        <div class="card-body p-4">
                            <h4 style="margin-bottom:6px;">{{ $destination->name }}</h4>
                            @if(!empty($destination->location)) 
                                <p class="text-muted mb-1"><i class="fa-solid fa-location-dot"></i> {{ $destination->location }}</p> 
                            @endif
                            <ul class="list-unstyled mb-3 small">
                                <li><strong>Activities:</strong> {{ $destination->trips->count() }}</li>
                                <li><strong>Gallery Images:</strong> {{ $destination->images->count() }}</li>
                            </ul>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>

@endsection








