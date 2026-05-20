@extends('layouts.frontbase')

@section('content')

    <!--==============================
    Trips Page Hero
============================== -->
    <div class="breadcumb-wrapper" data-bg-src="{{ asset('assets/img/bg/breadcumb-bg-1.jpg') }}" style="position: relative; width: 100%; overflow: hidden;">
        <!-- Dark overlay to ensure text readability over any background image -->
        <div style="position:absolute; inset:0; background:linear-gradient(135deg, rgba(0,0,0,0.75), rgba(0,0,0,0.55));"></div>

        <div class="container">
            <div class="breadcumb-content text-center" style="position: relative; z-index: 1; padding: 10px 0 50px;">
                <h1 class="breadcumb-title" style="color:#fff; margin-bottom:18px;">
                    Explore Rwanda &amp; East Africa
                </h1>
                <p style="max-width: 860px; margin: 0 auto; color:#f5f5f5; font-size:16px; line-height:1.7;">
                    Welcome to Stay Nets. At Stay Nets, we create unforgettable travel experiences across Rwanda and East Africa.
                    Specializing in wildlife safaris, mountain gorilla trekking, cultural tours, and scenic adventures, we craft
                    tailor-made itineraries for individuals, families, and groups. Whether you seek luxury or budget-friendly travel,
                    our professional guides, seamless logistics, and curated experiences ensure an exceptional journey.
                </p>
            </div>
        </div>
    </div>

    <!--==============================
    Destinations Area
==============================-->
    <section class="space">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8">
                    <div class="title-area text-center">
                        <h2 class="sec-title">Explore Our Trip Destinations</h2>
                        <p class="sec-text">Discover amazing destinations and their exciting activities</p>
                    </div>
                </div>
            </div>

            <div class="row gy-30">
                @forelse($destinations as $destination)
                    <div class="col-lg-4 col-md-6">
                        <div class="tour-box th-ani" style="height: 100%;">
                            <div class="tour-box_img global-img" style="position: relative;">
                                @if($destination->image && file_exists(storage_path('app/public/images/trip-destinations/' . $destination->image)))
                                    <img src="{{ asset('storage/images/trip-destinations/' . $destination->image) }}" alt="{{ $destination->name }}" style="height: 250px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('assets/img/tour/tour_3_1.jpg') }}" alt="{{ $destination->name }}" style="height: 250px; object-fit: cover;">
                                @endif
                                <div style="position: absolute; top: 15px; right: 15px; background: rgba(37, 211, 102, 0.95); color: white; padding: 8px 15px; border-radius: 8px; font-weight: 600; font-size: 14px;">
                                    {{ $destination->trips->count() }} Activities
                                </div>
                            </div>
                            <div class="tour-content">
                                <h3 class="box-title">
                                    <a href="{{ route('tripDestination', $destination->slug) }}">{{ $destination->name }}</a>
                                </h3>
                                <div class="tour-rating">
                                    @if($destination->location)
                                        <p style="margin: 5px 0;">
                                            <i class="fa-solid fa-location-dot text-primary me-2"></i>{{ $destination->location }}
                                        </p>
                                    @endif
                                    @if($destination->description)
                                        <p class="mt-2" style="font-size: 14px; color: #666;">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($destination->description), 100) }}
                                        </p>
                                    @endif
                                </div>
                                <div class="tour-action">
                                    <a href="{{ route('tripDestination', $destination->slug) }}" class="th-btn style4 th-icon">View Activities</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <p class="text-muted">No trip destinations available at the moment.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!--==============================
    Why Choose Stay Nets
==============================-->
    <section class="space" style="background:#f7f7f7;">
        <div class="container">
            <div class="row gy-4 align-items-start">
                <!-- Left: Heading + Attractive Benefits List -->
                <div class="col-lg-6">
                    <div class="title-area" style="margin-bottom: 24px;">
                        <h2 class="sec-title">Why Choose Stay Nets</h2>
                        <p class="sec-text">Travel with a trusted local partner dedicated to crafting your ideal journey.</p>
                    </div>

                    <div class="d-flex flex-column">
                        <div class="row g-3">
                            <div class="col-sm-12">
                                <div style="display:flex; gap:14px; padding:14px 16px; border-radius:10px; background:#ffffff; box-shadow:0 8px 20px rgba(0,0,0,0.04);">
                                    <div style="width:34px; height:34px; border-radius:50%; background:#25d366; display:flex; align-items:center; justify-content:center; color:#fff; font-size:18px;">
                                        ★
                                    </div>
                                    <div>
                                        <h5 style="margin:0 0 4px; font-size:16px;">Tailor‑Made Tours</h5>
                                        <p style="margin:0; font-size:14px; color:#555;">Fully customized itineraries across Rwanda &amp; East Africa, designed around you.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div style="display:flex; gap:14px; padding:14px 16px; border-radius:10px; background:#ffffff; box-shadow:0 8px 20px rgba(0,0,0,0.04);">
                                    <div style="width:34px; height:34px; border-radius:50%; background:#25d366; display:flex; align-items:center; justify-content:center; color:#fff; font-size:18px;">
                                        ★
                                    </div>
                                    <div>
                                        <h5 style="margin:0 0 4px; font-size:16px;">Local Expert Guides</h5>
                                        <p style="margin:0; font-size:14px; color:#555;">Experienced professional guides who know the wildlife, culture, and landscapes.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div style="display:flex; gap:14px; padding:14px 16px; border-radius:10px; background:#ffffff; box-shadow:0 8px 20px rgba(0,0,0,0.04);">
                                    <div style="width:34px; height:34px; border-radius:50%; background:#25d366; display:flex; align-items:center; justify-content:center; color:#fff; font-size:18px;">
                                        ★
                                    </div>
                                    <div>
                                        <h5 style="margin:0 0 4px; font-size:16px;">Unforgettable Experiences</h5>
                                        <p style="margin:0; font-size:14px; color:#555;">Wildlife, primates, cultural encounters, and nature adventures in one journey.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div style="display:flex; gap:14px; padding:14px 16px; border-radius:10px; background:#ffffff; box-shadow:0 8px 20px rgba(0,0,0,0.04);">
                                    <div style="width:34px; height:34px; border-radius:50%; background:#25d366; display:flex; align-items:center; justify-content:center; color:#fff; font-size:18px;">
                                        ★
                                    </div>
                                    <div>
                                        <h5 style="margin:0 0 4px; font-size:16px;">For Every Budget</h5>
                                        <p style="margin:0; font-size:14px; color:#555;">Flexible options from luxury safaris to smart, budget‑friendly trips.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div style="display:flex; gap:14px; padding:14px 16px; border-radius:10px; background:#ffffff; box-shadow:0 8px 20px rgba(0,0,0,0.04);">
                                    <div style="width:34px; height:34px; border-radius:50%; background:#25d366; display:flex; align-items:center; justify-content:center; color:#fff; font-size:18px;">
                                        ★
                                    </div>
                                    <div>
                                        <h5 style="margin:0 0 4px; font-size:16px;">End‑to‑End Support</h5>
                                        <p style="margin:0; font-size:14px; color:#555;">Hassle‑free bookings and complete travel assistance from arrival to departure.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Tour Booking Form -->
                <div class="col-lg-6">
                    <div class="card shadow-sm h-100" style="border-radius: 12px; border: none;">
                        <div class="card-body" style="padding: 30px;">
                            <h3 class="box-title" style="margin-bottom: 10px;">Plan Your Next Tour</h3>
                            <p class="text-muted" style="font-size: 14px; margin-bottom: 20px;">
                                Share a few details and we’ll get back to you with a custom trip proposal.
                            </p>
                            <form action="#" method="GET">
                                <div class="mb-3">
                                    <label class="form-label" style="font-weight: 500;">Full Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter your full name">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" style="font-weight: 500;">Email Address</label>
                                    <input type="email" class="form-control" name="email" placeholder="your.email@example.com">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" style="font-weight: 500;">Phone Number</label>
                                    <input type="tel" class="form-control" name="phone" placeholder="+250 788 123 456">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" style="font-weight: 500;">Country</label>
                                    <input type="text" class="form-control" name="country" placeholder="Enter your country">
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" style="font-weight: 500;">Preferred Travel Date</label>
                                        <input type="date" class="form-control" name="preferred_date">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" style="font-weight: 500;">Guests</label>
                                        <input type="number" class="form-control" name="guests" min="1" placeholder="e.g. 2">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" style="font-weight: 500;">Trip Interests</label>
                                    <textarea class="form-control" name="interests" rows="3" placeholder="Tell us what you’d like to experience..."></textarea>
                                </div>
                                <button type="submit" class="th-btn style3 w-100">
                                    Request Tour Plan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection








