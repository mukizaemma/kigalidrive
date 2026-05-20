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
            <div class="bg-light text-center rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Reviews Management</h6>
                    <a href="{{ route('admin.reviews.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus me-2"></i>Add New Review
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <form method="GET" action="{{ route('admin.reviews.index') }}" class="row g-3">
                            <div class="col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="featured" class="form-select">
                                    <option value="">All Reviews</option>
                                    <option value="1" {{ request('featured') == '1' ? 'selected' : '' }}>Featured</option>
                                    <option value="0" {{ request('featured') == '0' ? 'selected' : '' }}>Not Featured</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <input type="text" name="search" class="form-control" placeholder="Search by name, email, or review content..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table text-start align-middle table-bordered table-hover mb-0">
                        <thead>
                            <tr class="text-dark">
                                <th scope="col">Reviewer</th>
                                <th scope="col">Rating</th>
                                <th scope="col">Review</th>
                                <th scope="col">Images</th>
                                <th scope="col">Status</th>
                                <th scope="col">Featured</th>
                                <th scope="col">Date</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reviews as $review)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($review->user && $review->user->profile_photo_url)
                                                <img src="{{ $review->user->profile_photo_url }}" alt="{{ $review->names }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px;">
                                            @else
                                                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #25D366, #128C7E); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; margin-right: 10px;">
                                                    {{ strtoupper(substr($review->names ?? 'A', 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $review->names }}</strong>
                                                <br><small class="text-muted">{{ $review->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($review->rating)
                                            <div>
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}" style="font-size: 12px;"></i>
                                                @endfor
                                                <br><small class="text-muted">({{ $review->rating }}/5)</small>
                                            </div>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="max-width: 300px;">
                                            <p style="margin: 0; font-size: 13px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                {{ $review->testimony }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($review->images->count() > 0)
                                            <span class="badge bg-info">{{ $review->images->count() }} image(s)</span>
                                        @else
                                            <span class="text-muted">No images</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($review->is_approved)
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($review->is_featured)
                                            <span class="badge bg-primary">Featured</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $review->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.reviews.show', $review->id) }}" class="btn btn-sm btn-info" title="View">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.reviews.edit', $review->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            @if(!$review->is_approved)
                                                <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning" title="Reject" onclick="return confirm('Are you sure you want to reject this review? It will be removed from public pages.')">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <p class="text-muted mb-0">No reviews found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($reviews->hasPages())
                    <div class="mt-4">
                        {{ $reviews->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection



