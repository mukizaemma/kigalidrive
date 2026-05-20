@extends('layouts.adminBase')

@section('content')
    @include('admin.includes.sidebar')
    <div class="content">
        @include('admin.includes.navbar')

        <div class="container-fluid pt-4 px-4">
            <div class="bg-light rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Kigali Drive Rentals Invoices</h6>
                    <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary">Create Invoice</a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead>
                            <tr class="text-dark">
                                <th>Invoice #</th>
                                <th>Property</th>
                                <th>Period</th>
                                <th>Total Amount</th>
                                <th>Commission</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $inv)
                                <tr>
                                    <td><code>{{ $inv->invoice_number }}</code></td>
                                    <td>{{ $inv->property ? $inv->property->name : '—' }}</td>
                                    <td>{{ $inv->period_start->format('M j, Y') }} – {{ $inv->period_end->format('M j, Y') }}</td>
                                    <td>${{ number_format($inv->total_booking_amount, 2) }}</td>
                                    <td>${{ number_format($inv->commission_amount, 2) }}</td>
                                    <td>
                                        @if($inv->status === 'draft')
                                            <span class="badge bg-secondary">Draft</span>
                                        @elseif($inv->status === 'sent')
                                            <span class="badge bg-info">Sent</span>
                                        @else
                                            <span class="badge bg-success">Paid</span>
                                        @endif
                                    </td>
                                    <td>{{ $inv->created_at->format('M j, Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.invoices.show', $inv->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No invoices yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $invoices->links() }}
                </div>
            </div>
        </div>
    </div>
    @include('admin.includes.footer')
@endsection
