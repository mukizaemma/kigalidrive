@extends('layouts.frontbase')

@section('content')


    <div class="about-area position-relative overflow-hidden overflow-hidden space bg-smoke" id="about-sec">
        <div class="container space">
            <div class="row">
                <div class="col-xl-7">
                    <div class="img-box3">
                        <div class="img2">
                            <img src="{{ asset('assets/img/leftbags.webp') }}" alt="Left Bags Management">
                        </div>
                    </div>
                </div>
                <div class="col-xl-5">
                    <div class="ps-xl-4">
                        <div class="title-area mb-20">
                            <h2 class="sec-title mb-20 pe-xl-5 me-xl-5 heading">Left Bags Management</h2>
                        </div>
                        <p class="pe-xl-5">
                            For travelers in transit or exploring the city, Kigali Drive Rentals provides secure luggage storage services.
                            Clients can safely leave their bags for a few hours or days, allowing them to move freely and enjoy their time without worry.
                        </p>

                        <a href="{{ route('leftBags.request') }}" class="btn th-btn style3 th-icon">
                            Request to Leave Your Bag with us
                        </a>

                    </div>
                </div>
            </div>
            <div class="shape-mockup movingX d-none d-xxl-block" data-top="0%" data-left="-18%">
                <img src="assets/img/shape/shape_2_1.png" alt="shape">
            </div>
            <div class="shape-mockup jump d-none d-xxl-block" data-top="28%" data-right="-15%">
                <img src="assets/img/shape/shape_2_2.png" alt="shape">
            </div>
            <div class="shape-mockup spin d-none d-xxl-block" data-bottom="18%" data-left="-112%">
                <img src="assets/img/shape/shape_2_3.png" alt="shape">
            </div>
            <div class="shape-mockup movixgX d-none d-xxl-block" data-bottom="18%" data-right="-12%">
                <img src="assets/img/shape/shape_2_4.png" alt="shape">
            </div>
        </div>
    </div>




@endsection