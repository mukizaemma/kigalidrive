@extends('layouts.frontbase')
<base href="/public">
@section('content')

    <!--==============================
    Breadcumb
============================== -->
    <div class="breadcumb-wrapper " data-bg-src="{{ asset('storage/images/facilities/' .$facility->image) }}" style="height: 550px; width: 70%; margin: 0 auto;">
    {{-- <div class="breadcumb-wrapper " data-bg-src="{{ asset('storage/images/destinations/' .$destination->image) }}" style="height: 550px; margin: 20px auto;"> --}}
        <div class="container">
            <div class="breadcumb-content">
                {{-- <h1 class="breadcumb-title">Activities for {{$destination->title  }}</h1> --}}
            </div>
        </div>
    </div>

        <section class="space bg-smoke">
            <div class="container mt-10">
                <h2 class="box-title text-center">About our accommodation </h2>
            </div>
            <div class="container d-flex justify-content-center mt-20">
                   
                <div class="row">
                <div class="col-10 text-center">
                    <p>{!! $facility->description !!}</p>
                </div>
                </div>
            </div>

        <div class="container">
                <h2 class="box-title text-center text-dark">Our Rooms</h2>
            <div class="row">
                <div class="col-12">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade active show" id="tab-grid" role="tabpanel" aria-labelledby="tab-destination-grid">
                            <div class="row gy-30">

                                @foreach ($rooms as $room)
                                <div class="col-xxl-4 col-xl-4 col-sm-12">
                                    <div class="tour-box th-ani">
                                        <div class="tour-box_img global-img">
                                            <img src="{{ asset('storage/images/rooms/' .$room->image) }}" alt="image" style="height: 300px !important;">
                                        </div>
                                        <div class="tour-content">
                                        <div class="flex items-center justify-between">
                                                <h3 class="box-title">
                                                    <a href="{{ route('room',['slug'=>$room->slug]) }}">{{ $room->title }}</a>
                                                </h3>
                                                <span class="text-lg font-semibold text-indigo-600">
                                                    ${{ number_format($room->price, 2) }}
                                                </span>
                                            </div>
                                            <div class="tour-rating">
                                              <p>{{ \Illuminate\Support\Str::limit(strip_tags($room->description), 150) }}</p>
                                            </div>
                                            <div class="tour-action">
                                                <a href="{{route('room',['slug'=>$room->slug])}}" class="th-btn style4 th-icon">View Details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach




                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </section>

  {{-- @include('frontend.includes.partners') --}}
@endsection