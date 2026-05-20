@extends('layouts.frontbase')

@section('content')
<section class="position-relative overflow-hidden space-top space-extra-bottom" style="background: #f8f9fa;">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="review-detail-card" style="background: #fff; border-radius: 16px; padding: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                    <!-- Review Header -->
                    <div class="review-header mb-4">
                        <div class="d-flex align-items-center mb-3">
                            @if($review->user && $review->user->profile_photo_url)
                                <img src="{{ $review->user->profile_photo_url }}" alt="{{ $review->names }}" style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover; margin-right: 20px;">
                            @else
                                <div class="author-avatar" style="width: 70px; height: 70px; border-radius: 50%; background: linear-gradient(135deg, #25D366, #128C7E); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 30px; margin-right: 20px;">
                                    {{ strtoupper(substr($review->names ?? 'A', 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <h3 style="margin: 0; font-size: 24px; font-weight: 600; color: #333;">
                                    {{ $review->names ?? 'Anonymous' }}
                                </h3>
                                @if($review->rating)
                                    <div class="mt-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}" style="font-size: 18px;"></i>
                                        @endfor
                                        <span class="ms-2 text-muted">({{ $review->rating }}/5)</span>
                                    </div>
                                @endif
                                <p style="margin: 5px 0 0 0; font-size: 14px; color: #999;">
                                    <i class="far fa-calendar me-1"></i>{{ $review->created_at->format('F d, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Review Content -->
                    <div class="review-content mb-4">
                        <div class="quote-icon mb-3" style="font-size: 50px; color: #25D366; opacity: 0.3;">
                            <i class="fas fa-quote-left"></i>
                        </div>
                        <p style="color: #666; font-size: 18px; line-height: 1.8; font-style: italic;">
                            {{ $review->testimony }}
                        </p>
                    </div>

                    <!-- Review Images Gallery -->
                    @if($review->images->count() > 0)
                        <div class="review-images mb-4">
                            <h5 class="mb-3">Review Images</h5>
                            <div class="row g-3">
                                @foreach($review->images as $image)
                                    <div class="col-md-4">
                                        <a href="{{ asset('storage/' . $image->image) }}" data-lightbox="review-images" data-title="{{ $image->caption ?? 'Review Image' }}">
                                            <img src="{{ asset('storage/' . $image->image) }}" alt="Review Image" class="img-fluid" style="border-radius: 8px; cursor: pointer; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                        </a>
                                        @if($image->caption)
                                            <p class="text-muted mt-2" style="font-size: 12px;">{{ $image->caption }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Admin Response -->
                    @if($review->admin_response)
                        <div class="admin-response mt-4" style="background: #f8f9fa; border-left: 4px solid #25D366; padding: 20px; border-radius: 8px;">
                            <h5 class="mb-2" style="color: #25D366;">
                                <i class="fas fa-reply me-2"></i>Response from {{ $setting->company ?? 'Admin' }}
                            </h5>
                            <p style="color: #666; margin: 0; line-height: 1.8;">
                                {{ $review->admin_response }}
                            </p>
                        </div>
                    @endif

                    <!-- Back Button -->
                    <div class="mt-4">
                        <a href="{{ route('reviews.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to All Reviews
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection



