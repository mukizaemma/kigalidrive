    <section class="th-blog-wrapper space-top space-extra-bottom">
        <div class="container">
            <div class="row">
                <div class="col-xxl-8 col-lg-7">
                    @foreach($blogs as $article)
                        <div class="th-blog blog-single has-post-thumbnail mb-4">
                            <div class="blog-img">
                                <a href="{{ route('article', $article->slug) }}">
                                    <img src="{{ asset('storage/images/blogs/' .$article->image) }}" alt="{{ $article->title }}">
                                </a>
                            </div>
                            <div class="blog-content">
                                <div class="blog-meta">
                                    <a class="author" href="#">
                                        <i class="fa-light fa-user"></i>by {{ $article->author ?? 'Admin' }}
                                    </a>
                                    <a href="#">
                                        <i class="fa-solid fa-calendar-days"></i>
                                        {{ $article->created_at->format('d M, Y') }}
                                    </a>
                                </div>
                                <h2 class="blog-title">
                                    <a href="{{ route('article', $article->slug) }}">{{ $article->title }}</a>
                                </h2>
                                <p class="blog-text">
                                    {{ Str::limit(strip_tags($article->body), 150, '...') }}
                                </p>
                                <a href="{{ route('article', $article->slug) }}" class="th-btn style4 th-icon">
                                    Read More
                                </a>
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    <div class="th-pagination mt-4">
                        {{ $blogs->links() }}

                    </div>
                </div>

                <div class="col-xxl-4 col-lg-5">
                    <aside class="sidebar-area">
                        {{-- <div class="widget widget_search  ">
                            <form class="search-form">
                                <input type="text" placeholder="Search">
                                <button type="submit"><i class="far fa-search"></i></button>
                            </form>
                        </div> --}}

                        <div class="widget">
                            <h3 class="widget_title">Our Top Destinations</h3>
                            <div class="recent-post-wrap">
                                @foreach ($destinations as $destination)
                                <div class="recent-post">
                                    <div class="media-img">
                                        <a href="{{route('tour',['slug'=>$destination->slug])}}"><img src="{{ asset('storage/images/destinations/' .$destination->image) }}" alt="Blog Image"></a>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="post-title"><a class="text-inherit" href="{{route('showTrips',['slug'=>$destination->slug])}}">{{ $destination->title }}</a></h4>

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