@extends('layouts.frontbase')
<base href="/public">
@section('content')


        <section class="space bg-smoke">

        <div class="container">
                <h2 class="box-title text-center text-dark">Our Promotions  </h2>
            <div class="row">
                <div class="col-12">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade active show" id="tab-grid" role="tabpanel" aria-labelledby="tab-destination-grid">
                            <div class="row gy-30">

                                @foreach ($promotions as $promotion)
                                <div class="col-xxl-4 col-xl-4 col-sm-12">
                                    <div class="tour-box th-ani">
                                        <div class="tour-box_img global-img">
                                            <img src="{{ asset('storage/images/promotions/' .$promotion->image) }}" alt="image" style="height: 300px !important;">
                                        </div>
                                        <div class="tour-content">
                                        <div class="flex items-center justify-between">
                                                <h3 class="box-title">
                                                    <a href="{{ route('promotion',['slug'=>$promotion->slug]) }}">{{ $promotion->title }}</a>
                                                </h3>
                                            </div>
                                            <div class="tour-rating">
                                              <p>{{ \Illuminate\Support\Str::limit(strip_tags($promotion->description), 150) }}</p>
                                            </div>
                                            <div class="tour-action">
                                                <a href="{{route('promotion',['slug'=>$promotion->slug])}}" class="th-btn style4 th-icon">View Details</a>
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