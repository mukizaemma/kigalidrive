@extends('layouts.frontbase')

@section('content')
<section class="th-blog-wrapper space-top space-extra-bottom">
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-lg-8">
                <h1 class="kdr-section-title mb-2">Updates &amp; news</h1>
                <p class="text-muted mb-0">Tips, announcements, and news from Kigali Drive Rentals.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-xxl-8 col-lg-7">
                @forelse($blogs as $article)
                <article class="kdr-card mb-4 overflow-hidden">
                    <div class="row g-0">
                        @if($article->image)
                        <div class="col-md-4">
                            <a href="{{ route('singleBlog', $article->slug) }}">
                                <img src="{{ asset('storage/images/blogs/' . $article->image) }}" alt="{{ $article->title }}" class="w-100 h-100" style="min-height:180px;object-fit:cover">
                            </a>
                        </div>
                        @endif
                        <div class="{{ $article->image ? 'col-md-8' : 'col-12' }}">
                            <div class="p-4">
                                <div class="small text-muted mb-2">
                                    <i class="fa-regular fa-calendar me-1"></i>{{ $article->created_at->format('d M Y') }}
                                    @if($article->author)
                                    <span class="ms-2"><i class="fa-regular fa-user me-1"></i>{{ $article->author }}</span>
                                    @endif
                                </div>
                                <h2 class="h5 mb-2">
                                    <a href="{{ route('singleBlog', $article->slug) }}" class="text-decoration-none text-dark">{{ $article->title }}</a>
                                </h2>
                                <p class="text-muted mb-3">{{ Str::limit(strip_tags($article->body), 160) }}</p>
                                <a href="{{ route('singleBlog', $article->slug) }}" class="th-btn btn-kdr-primary btn-sm">Read more</a>
                            </div>
                        </div>
                    </div>
                </article>
                @empty
                <div class="kdr-empty-state text-center py-5">
                    <p class="text-muted mb-0">No updates published yet. Check back soon.</p>
                </div>
                @endforelse

                @if($blogs->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $blogs->links('vendor.pagination.kdr') }}
                </div>
                @endif
            </div>

            <div class="col-xxl-4 col-lg-5">
                <aside class="kdr-card p-4">
                    <h3 class="h6 text-uppercase text-muted mb-3">Explore</h3>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><a href="{{ route('showCars') }}" class="fw-semibold text-decoration-none"><i class="fas fa-car me-2 text-warning"></i>Rent a car</a></li>
                        <li class="mb-2"><a href="{{ route('showCars', ['listing_type' => 'sale']) }}" class="fw-semibold text-decoration-none"><i class="fas fa-tags me-2 text-warning"></i>Cars for sale</a></li>
                        <li class="mb-2"><a href="{{ route('services.index') }}" class="fw-semibold text-decoration-none"><i class="fas fa-concierge-bell me-2 text-warning"></i>Our services</a></li>
                        <li><a href="{{ route('contact') }}" class="fw-semibold text-decoration-none"><i class="fas fa-envelope me-2 text-warning"></i>Contact us</a></li>
                    </ul>
                </aside>
            </div>
        </div>
    </div>
</section>
@endsection
