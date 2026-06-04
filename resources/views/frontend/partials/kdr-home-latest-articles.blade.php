@php
    $latestArticles = ($latestArticles ?? collect())->take(3);
@endphp
@if($latestArticles->isNotEmpty())
<section class="kdr-home-articles" aria-labelledby="kdr-home-articles-heading">
    <div class="container">
        <div class="kdr-home-articles__head">
            <div>
                <p class="kdr-home-articles__eyebrow">Updates &amp; tips</p>
                <h2 id="kdr-home-articles-heading" class="kdr-home-articles__title">Latest from Kigali Drive</h2>
                <p class="kdr-home-articles__lead">News, travel tips, and rental updates from our team.</p>
            </div>
            <a href="{{ route('blogs') }}" class="th-btn btn-kdr-primary kdr-home-articles__more">View all articles</a>
        </div>
        <div class="row g-4">
            @foreach($latestArticles as $article)
            <div class="col-md-6 col-lg-4">
                <article class="kdr-article-card h-100">
                    <a href="{{ route('singleBlog', $article->slug) }}" class="kdr-article-card__media">
                        @if($article->image)
                        <img src="{{ asset('storage/images/blogs/' . $article->image) }}" alt="{{ $article->title }}" loading="lazy">
                        @else
                        <span class="kdr-article-card__media-placeholder" aria-hidden="true"><i class="fas fa-newspaper"></i></span>
                        @endif
                    </a>
                    <div class="kdr-article-card__body">
                        <time class="kdr-article-card__date" datetime="{{ $article->created_at->toDateString() }}">
                            {{ $article->created_at->format('d M Y') }}
                        </time>
                        <h3 class="kdr-article-card__title">
                            <a href="{{ route('singleBlog', $article->slug) }}">{{ $article->title }}</a>
                        </h3>
                        <p class="kdr-article-card__excerpt">{{ Str::limit(strip_tags($article->body), 110) }}</p>
                        <a href="{{ route('singleBlog', $article->slug) }}" class="kdr-article-card__link">
                            Read article <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </article>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
