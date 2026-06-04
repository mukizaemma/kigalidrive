@extends('layouts.adminBase')

@section('content')
@include('admin.includes.sidebar')

<div class="content">
    @include('admin.includes.navbar')

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-10 col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Terms &amp; Conditions</h5>
                        <p class="text-muted small mb-0">One document shown on the public Terms page.</p>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <form action="{{ route('saveTerms', $data->id) }}" method="POST">
                            @csrf
                            <label for="termsContent" class="form-label fw-semibold">Terms &amp; conditions content</label>
                            <textarea id="termsContent" name="terms" rows="16" class="form-control summernote">{!! old('terms', $data->terms) !!}</textarea>
                            @error('terms')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save me-1"></i> Save
                                </button>
                                <a href="{{ route('terms') }}" target="_blank" rel="noopener" class="btn btn-outline-secondary ms-2">View public page</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
