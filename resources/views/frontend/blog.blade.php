@extends('layouts.frontbase')

@section('title', $blog->title . ' | ' . (optional($setting)->company ?? 'Kigali Drive Rentals'))

@section('content')
<section class="kdr-article-page py-4 py-lg-5 mt-3">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('blogs') }}">Updates</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($blog->title, 50) }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            <div class="col-lg-8">
                <article class="kdr-card p-4 p-lg-5">
                    @if($blog->image)
                    <img src="{{ asset('storage/images/blogs/' . $blog->image) }}" alt="{{ $blog->title }}"
                         class="w-100 rounded mb-4" style="max-height:420px;object-fit:cover">
                    @endif

                    <h1 class="h2 mb-3">{{ $blog->title }}</h1>

                    <div class="d-flex flex-wrap gap-3 text-muted small mb-4">
                        <span><i class="far fa-calendar-alt me-1"></i>{{ $blog->created_at->format('d M Y') }}</span>
                        <span><i class="far fa-clock me-1"></i>{{ ceil(str_word_count(strip_tags($blog->body)) / 200) }} min read</span>
                        <span><i class="far fa-eye me-1"></i>{{ number_format($blog->views ?? 0) }} views</span>
                    </div>

                    <div class="kdr-rich-text article-body">
                        {!! $blog->body !!}
                    </div>
                </article>

                @include('frontend.partials.article-comment-form', [
                    'blog' => $blog,
                    'comments' => $comments,
                    'commentsCount' => $commentsCount,
                    'commentChallenge' => $commentChallenge,
                ])
            </div>

            <div class="col-lg-4">
                <aside class="kdr-card p-4 mb-4">
                    <h3 class="h6 text-uppercase text-muted mb-3">Related articles</h3>
                    @forelse($latestBlogs as $related)
                    <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                        @if($related->image)
                        <img src="{{ asset('storage/images/blogs/' . $related->image) }}" alt="" width="72" height="72" class="rounded" style="object-fit:cover">
                        @endif
                        <div>
                            <a href="{{ route('singleBlog', $related->slug) }}" class="fw-semibold text-decoration-none text-dark small d-block">
                                {{ $related->title }}
                            </a>
                            <span class="text-muted small">{{ $related->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted small mb-0">No other articles yet.</p>
                    @endforelse
                </aside>

                <aside class="kdr-card p-4">
                    <h3 class="h6 text-uppercase text-muted mb-3">Explore</h3>
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2"><a href="{{ route('showCars') }}">Rent a car</a></li>
                        <li class="mb-2"><a href="{{ route('faq') }}">FAQ</a></li>
                        <li class="mb-2"><a href="{{ route('reviews.index') }}">Google reviews</a></li>
                        <li><a href="{{ route('contact') }}">Contact us</a></li>
                    </ul>
                </aside>
            </div>
        </div>
    </div>
</section>
@endsection
