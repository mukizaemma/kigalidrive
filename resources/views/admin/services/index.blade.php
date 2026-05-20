@extends('layouts.adminBase')

@section('content')
@include('admin.includes.sidebar')

        <div class="content">
            @include('admin.includes.navbar')

            <div class="container-fluid pt-4 px-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
            <div>
                <h4 class="mb-1">Services</h4>
                <p class="text-muted small mb-0">Manage services shown on the public Services page and main menu.</p>
            </div>
            <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add service
            </a>
                        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
        @endif

        <div class="bg-light rounded p-4">
                    <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:70px">Order</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th style="width:140px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                        @forelse($services as $service)
                        <tr>
                            <td><span class="badge bg-secondary">{{ $service->sort_order }}</span></td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if($service->image)
                                    <img src="{{ asset('storage/images/services/' . $service->image) }}" alt="" width="56" height="56" class="rounded object-fit-cover">
                                    @elseif($service->icon)
                                    <span class="d-inline-flex align-items-center justify-content-center rounded bg-white border" style="width:56px;height:56px">
                                        <i class="fas {{ $service->icon }} fa-lg text-primary"></i>
                                    </span>
                                    @endif
                                    <div>
                                        <a href="{{ route('admin.services.edit', $service) }}" class="fw-semibold text-dark">{{ $service->title }}</a>
                                        @if($service->excerpt)
                                        <div class="small text-muted">{{ Str::limit($service->excerpt, 80) }}</div>
                                        @endif
                                            </div>
                                        </div>
                                    </td>
                            <td>
                                <span class="badge {{ $service->status === 'Active' ? 'bg-success' : 'bg-secondary' }}">{{ $service->status }}</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('services.show', $service->slug) }}" class="btn btn-outline-secondary" target="_blank" title="View"><i class="fa fa-external-link-alt"></i></a>
                                    <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-info"><i class="fa fa-edit"></i></a>
                                    <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this service?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-warning"><i class="fa fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No services yet. <a href="{{ route('admin.services.create') }}">Add your first service</a>.</td>
                                </tr>
                        @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
                </div>
        
        @include('admin.includes.footer')
 @endsection
