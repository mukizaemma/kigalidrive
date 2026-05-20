@extends('layouts.adminBase')

@section('content')
    <!-- Sidebar Start -->
    @include('admin.includes.sidebar')
    <!-- Sidebar End -->

    <!-- Content Start -->
    <div class="content">
        <!-- Navbar Start -->
        @include('admin.includes.navbar')
        <!-- Navbar End -->

        <div class="container-fluid pt-4 px-4">
            <div class="bg-light rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Review Details</h6>
                    <div>
                        <a href="{{ route('admin.reviews.edit', $review->id) }}" class="btn btn-primary">
                            <i class="fa fa-edit me-2"></i>Edit Review
                        </a>
                        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <!-- Review Content -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    @if($review->user && $review->user->profile_photo_url)
                                        <img src="{{ $review->user->profile_photo_url }}" alt="{{ $review->names }}" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; margin-right: 15px;">
                                    @else
                                        <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #25D366, #128C7E); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 24px; margin-right: 15px;">
                                            {{ strtoupper(substr($review->names ?? 'A', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h5 class="mb-0">{{ $review->names }}</h5>
                                        <small class="text-muted">{{ $review->email }}</small>
                                    </div>
                                </div>
                                
                                @if($review->rating)
                                    <div class="mb-3">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                        <span class="ms-2">({{ $review->rating }}/5)</span>
                                    </div>
                                @endif

                                <p class="mb-0" style="font-size: 16px; line-height: 1.8;">{{ $review->testimony }}</p>

                                @if($review->website)
                                    <div class="mt-3">
                                        <strong>Website:</strong> <a href="{{ $review->website }}" target="_blank">{{ $review->website }}</a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Images -->
                        @if($review->images->count() > 0)
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">Review Images ({{ $review->images->count() }})</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        @foreach($review->images as $image)
                                            <div class="col-md-4">
                                                <div class="position-relative">
                                                    <img src="{{ asset('storage/' . $image->image) }}" alt="Review Image" class="img-fluid rounded">
                                                    <form action="{{ route('admin.reviews.deleteImage', ['reviewId' => $review->id, 'imageId' => $image->id]) }}" method="POST" class="position-absolute top-0 end-0 m-2">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this image?')">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                                @if($image->caption)
                                                    <p class="text-muted mt-2" style="font-size: 12px;">{{ $image->caption }}</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Admin Response -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Admin Response</h6>
                            </div>
                            <div class="card-body">
                                @if($review->admin_response)
                                    <p>{{ $review->admin_response }}</p>
                                @else
                                    <p class="text-muted">No response yet.</p>
                                @endif
                                
                                <form action="{{ route('admin.reviews.respond', $review->id) }}" method="POST" class="mt-3">
                                    @csrf
                                    <div class="mb-3">
                                        <textarea name="admin_response" class="form-control" rows="4" placeholder="Add or update admin response...">{{ $review->admin_response }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save Response</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Review Info -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Review Information</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Status:</strong> 
                                    @if($review->is_approved)
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </p>
                                <p><strong>Featured:</strong> 
                                    @if($review->is_featured)
                                        <span class="badge bg-primary">Yes</span>
                                    @else
                                        <span class="text-muted">No</span>
                                    @endif
                                </p>
                                <p><strong>Submitted:</strong> {{ $review->created_at->format('M d, Y H:i') }}</p>
                                <p><strong>Last Updated:</strong> {{ $review->updated_at->format('M d, Y H:i') }}</p>

                                <div class="mt-3">
                                    @if(!$review->is_approved)
                                        <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success w-100 mb-2">
                                                <i class="fa fa-check me-2"></i>Approve Review
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-warning w-100 mb-2" onclick="return confirm('Reject this review?')">
                                                <i class="fa fa-times me-2"></i>Reject Review
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Delete this review permanently?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100">
                                            <i class="fa fa-trash me-2"></i>Delete Review
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



