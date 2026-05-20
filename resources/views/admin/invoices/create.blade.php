@extends('layouts.adminBase')

@section('content')
    @include('admin.includes.sidebar')
    <div class="content">
        @include('admin.includes.navbar')

        <div class="container-fluid pt-4 px-4">
            <div class="bg-light rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Create Invoice</h6>
                    <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary btn-sm">Back to Invoices</a>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="GET" action="{{ route('admin.invoices.create') }}" class="row g-3 mb-4">
                    <div class="col-md-2">
                        <label class="form-label">Period from</label>
                        <input type="date" name="period_start" class="form-control" value="{{ $period_start }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Period to</label>
                        <input type="date" name="period_end" class="form-control" value="{{ $period_end }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Property</label>
                        <select name="property_id" class="form-select">
                            <option value="">All (show summary)</option>
                            @foreach($properties as $p)
                                <option value="{{ $p->id }}" {{ $property_id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary">Preview</button>
                    </div>
                </form>

                @if(count($summary) > 0)
                    <p class="text-muted">Uninvoiced confirmed bookings (check-out in period). Create an invoice for one property below.</p>
                    @foreach($summary as $pid => $data)
                        <div class="card mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <strong>{{ $data['property'] ? $data['property']->name : 'Property #' . $pid }}</strong>
                                <span>Revenue: ${{ number_format($data['total_amount'], 2) }} · Commission: ${{ number_format($data['commission'], 2) }}</span>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.invoices.store') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="property_id" value="{{ $pid }}">
                                    <input type="hidden" name="period_start" value="{{ $period_start }}">
                                    <input type="hidden" name="period_end" value="{{ $period_end }}">
                                    <input type="hidden" name="notes" value="">
                                    <button type="submit" class="btn btn-primary">Create invoice for this property</button>
                                </form>
                                <small class="text-muted ms-2">{{ $data['bookings']->count() }} booking(s)</small>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No uninvoiced confirmed bookings for the selected period and property.</p>
                @endif
            </div>
        </div>
    </div>
    @include('admin.includes.footer')
@endsection
