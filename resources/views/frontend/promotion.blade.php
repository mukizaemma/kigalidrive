@extends('layouts.frontbase')
@section('content')


<div class="breadcumb-wrapper" data-bg-src="{{ asset('storage/images/promotions/' .$promotion->image) }}" style="height: 550px; width: 70%; margin: 20px auto; background-image: url('{{ asset('storage/images/trips/' .$promotion->image) }}'); background-size: cover; background-position: center;">
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
                <div class="tour-page-single" style="margin: 0 auto;">

                    <!-- Description on top -->
                    <div class="page-content" style="padding-top: 1px !important;">
                        <h2 class="box-title">{{ $promotion->title }}</h2>
                        <p class="box-text mb-30">{!! $promotion->description !!}</p>
                        <div class="tour-action">
                            <a href="{{route('connect')}}" class="th-btn style4 th-icon">Book Now</a>
                        </div>
                    </div>
    
                </div>

            </div>



            <!-- Sidebar Fixed Inside Row -->
            <div class="col-lg-4 col-sm-12">

                <aside class="sidebar-area">
                    <div class="widget">
                        <h3 class="widget_title">Our Rooms</h3>
                        <div class="recent-post-wrap">
                            @foreach ($rooms as $room)
                            <div class="recent-post">
                                <div class="media-img">
                                    <a href="{{ route('room',['slug'=>$promotion->slug]) }}"><img src="{{ asset('storage/images/rooms/' .$room->image) }}" alt="Blog Image"></a>
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
