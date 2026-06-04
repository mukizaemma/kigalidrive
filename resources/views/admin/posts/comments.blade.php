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

            <!-- Recent Sales Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                        <div>
                            <h6 class="mb-0">Article comments</h6>
                            <p class="text-muted small mb-0">Approve real comments before they appear on the site. Reject spam without publishing.</p>
                        </div>
                        @if(($pendingCount ?? 0) > 0)
                        <span class="badge bg-warning text-dark">{{ $pendingCount }} pending approval</span>
                        @endif
                    </div>
                
                    <!-- Tabs for Filters -->
                    <ul class="nav nav-tabs mb-3">
                        <li class="nav-item">
                            <a class="nav-link {{ $filter === 'all' ? 'active' : '' }}" 
                               href="{{ route('blogsComment', ['filter' => 'all']) }}">All Comments</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $filter === 'published' ? 'active' : '' }}" 
                               href="{{ route('blogsComment', ['filter' => 'published']) }}">Published Comments</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $filter === 'unpublished' ? 'active' : '' }}" 
                               href="{{ route('blogsComment', ['filter' => 'unpublished']) }}">Pending approval</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $filter === 'rejected' ? 'active' : '' }}" 
                               href="{{ route('blogsComment', ['filter' => 'rejected']) }}">Rejected</a>
                        </li>
                    </ul>
                
                    <!-- Table Section -->
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-dark">
                                    <th scope="col">Date</th>
                                    <th scope="col">Comment</th>
                                    <th scope="col">Article</th>
                                    <th scope="col">Author</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" style="width:200px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($comments as $comment)
                                <tr>
                                    <td class="small">{{ $comment->created_at->format('d M Y H:i') }}</td>
                                    <td>{!! \Illuminate\Support\Str::limit(strip_tags($comment->comment), 80) !!}</td>
                                    <td class="small">
                                        @if($comment->blog)
                                        <a href="{{ route('viewBlog', $comment->blog_id) }}">{{ Str::limit($comment->blog->title, 40) }}</a>
                                        @endif
                                    </td>
                                    <td class="small">
                                        {{ $comment->names }}<br>
                                        <span class="text-muted">{{ $comment->email }}</span>
                                        @if($comment->ip_address)
                                        <br><span class="text-muted">{{ $comment->ip_address }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($comment->status === 'Published')
                                            <span class="badge bg-success">Published</span>
                                        @elseif($comment->status === 'Rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#comment_{{ $comment->id }}">View</button>
                                            @if($comment->status !== 'Published')
                                            <form action="{{ route('commentApprove', $comment) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                            </form>
                                            @endif
                                            @if($comment->status !== 'Rejected')
                                            <form action="{{ route('commentReject', $comment) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject this comment?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Reject</button>
                                            </form>
                                            @endif
                                            <a href="{{ route('destroyBlogComment', ['id' => $comment->id]) }}" 
                                               class="btn btn-sm btn-outline-secondary" 
                                               onclick="return confirm('Delete permanently?')"> 
                                               <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Modal -->
                                <div class="modal" id="comment_{{ $comment->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Commented By: {{ $comment->names }}</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>{{ $comment->comment }}</p>
                                                <form class="form" action="{{ route('commentApprove', ['comment' => $comment->id]) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary text-black">
                                                        <i class="fa fa-save"></i> Approve
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" style="color:black">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Pagination -->
                        <div style="margin-top: 20px;">
                            {{ $comments->links('pagination::simple-bootstrap-4') }}
                        </div>
                    </div>
                </div>
                
            </div>
            <!-- Recent Sales End -->



            <!-- Footer Start -->
          
            <!-- Footer End -->
        </div>
        <!-- Content End -->

        @include('admin.includes.footer')

 @endsection