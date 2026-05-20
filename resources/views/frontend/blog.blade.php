@extends('layouts.frontbase')
@section('content')


<div class="breadcumb-wrapper " data-bg-src="{{ asset('storage/images/blogs/' .$blog->image) }}" style="height: 550px; width: 80%; margin: 20px auto; background-image: url('{{ asset('storage/images/trips/' .$blog->image) }}'); background-size: cover; background-position: center;">
    <div class="container bg-smoke">
        <div class="breadcumb-content">
            <!-- Optional static content -->
        </div>
    </div>
</div>
{{-- @endif --}}


<!--==============================
Tour Area
==============================-->
<section class="tour-area3 position-relative bg-top-center overflow-hidden space " id="service-sec" style="width: 80%; margin: 20px auto;">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8 col-sm-12">
                <div class="tour-page-single" style="margin: 0 auto;">

                    <!-- Description on top -->
                    <div class="page-content" style="padding-top: 1px !important;">
                        <h2 class="box-title">{{ $blog->title }}</h2>

                        <!-- Blog meta -->
                        <div class="blog-meta mb-3" style="font-size:14px; color:#666;">
                            <span><i class="far fa-calendar-alt me-1" style="color:#ecdd0c;"></i> {{ $blog->created_at->format('d M Y') }}</span>
                            <span class="ms-3"><i class="far fa-clock me-1" style="color:#ecdd0c;"></i> 
                                {{ ceil(str_word_count(strip_tags($blog->body)) / 200) }} min read
                            </span>
                        </div>

                        <p class="box-text mb-30">{!! $blog->body !!}</p>
                    </div>
                 

                    {{-- @if($images->isNotEmpty())
                    <div class="tour-gallery-wrapper">
                        <h3 class="page-title mt-50 mb-30">Activity's highlights</h3>
                        <div class="row gy-4 gallery-row filter-active">
                            <div class="col-md-6 col-xl-auto filter-item">
                                @foreach($images as $image)
                                <div class="tour-gallery-card">
                                    <div class="gallery-img global-img">
                                        <img src="{{ asset('storage/images/blogs/' . $image->image) }}" alt="gallery image">
                                        <a href="{{ asset('storage/images/blogs/' . $image->image) }}" class="icon-btn popup-image"><i class="fal fa-magnifying-glass-plus"></i></a>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                    @endif --}}
                </div>

            </div>



            <!-- Sidebar Fixed Inside Row -->
            <div class="col-lg-4 col-sm-12">

                <aside class="sidebar-area">
                    <div class="widget">
                        <h3 class="widget_title">Related Articles</h3>
                        <div class="recent-post-wrap">
                            @foreach ($latestBlogs as $blog)
                            <div class="recent-post">
                                <div class="media-img">
                                    <a href="{{ route('singleBlog',['slug'=>$blog->slug]) }}"><img src="{{ asset('storage/images/blogs/' .$blog->image) }}" alt="Blog Image"></a>
                                </div>
                                <div class="media-body">
                                    <h4 class="post-title"><a class="text-inherit" href="{{ route('singleBlog',['slug'=>$blog->slug]) }}">{{ $blog->title }}</a></h4>
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
