@extends('layouts.adminBase')

@section('content')
@include('admin.includes.sidebar')
<div class="content">
    @include('admin.includes.navbar')
    <div class="container-fluid pt-4 px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">FAQ</h4>
                <p class="text-muted small mb-0">Manage questions shown on the public <a href="{{ route('faq') }}" target="_blank">FAQ page</a>.</p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFaqModal">
                <i class="fa fa-plus me-1"></i> Add FAQ
            </button>
        </div>

        <div class="bg-light rounded p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:60px">Order</th>
                            <th>Question</th>
                            <th style="width:100px">Status</th>
                            <th style="width:160px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($faqs as $faq)
                        <tr>
                            <td>{{ $faq->sort_order }}</td>
                            <td>
                                <strong>{{ $faq->question }}</strong>
                                <div class="text-muted small mt-1">{{ Str::limit(strip_tags($faq->answer), 120) }}</div>
                            </td>
                            <td>
                                @if($faq->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Hidden</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editFaq{{ $faq->id }}">Edit</button>
                                <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this FAQ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @include('admin.faqs.partials.edit-modal', ['faq' => $faq])
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No FAQs yet. Add your first question above.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('admin.faqs.partials.add-modal')
@include('admin.includes.footer')
@endsection
