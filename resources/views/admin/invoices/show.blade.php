@extends('layouts.adminBase')

@section('content')
    @include('admin.includes.sidebar')
    <div class="content">
        @include('admin.includes.navbar')

        <div class="container-fluid pt-4 px-4">
            <div class="bg-light rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Invoice: {{ $invoice->invoice_number }}</h6>
                    <div>
                        @if($invoice->status === 'draft')
                            <form action="{{ route('admin.invoices.send', $invoice->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">Mark as Sent</button>
                            </form>
                        @endif
                        <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">Back to Invoices</a>
                    </div>
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

                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-4"><strong>Property:</strong></div>
                            <div class="col-md-8">{{ $invoice->property ? $invoice->property->name : '—' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4"><strong>Period:</strong></div>
                            <div class="col-md-8">{{ $invoice->period_start->format('F j, Y') }} – {{ $invoice->period_end->format('F j, Y') }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4"><strong>Total booking amount:</strong></div>
                            <div class="col-md-8">${{ number_format($invoice->total_booking_amount, 2) }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4"><strong>Commission to pay Kigali Drive Rentals:</strong></div>
                            <div class="col-md-8"><strong class="h5">${{ number_format($invoice->commission_amount, 2) }}</strong></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4"><strong>Status:</strong></div>
                            <div class="col-md-8">
                                @if($invoice->status === 'draft')
                                    <span class="badge bg-secondary">Draft</span>
                                @elseif($invoice->status === 'sent')
                                    <span class="badge bg-info">Sent</span>
                                    @if($invoice->sent_at)
                                        <small class="text-muted"> {{ $invoice->sent_at->format('M j, Y') }}</small>
                                    @endif
                                @else
                                    <span class="badge bg-success">Paid</span>
                                @endif
                            </div>
                        </div>
                        @if($invoice->notes)
                            <div class="row">
                                <div class="col-md-4"><strong>Notes:</strong></div>
                                <div class="col-md-8">{{ $invoice->notes }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><strong>Bookings included</strong></div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>Reference</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                    <th>Guest</th>
                                    <th>Amount</th>
                                    <th>Commission</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->invoiceBookings ?? [] as $ib)
                                    <tr>
                                        <td>{{ $ib->hotelBooking ? $ib->hotelBooking->reference_number : '—' }}</td>
                                        <td>{{ $ib->hotelBooking && $ib->hotelBooking->check_in ? \Carbon\Carbon::parse($ib->hotelBooking->check_in)->format('M j, Y') : '—' }}</td>
                                        <td>{{ $ib->hotelBooking && $ib->hotelBooking->check_out ? \Carbon\Carbon::parse($ib->hotelBooking->check_out)->format('M j, Y') : '—' }}</td>
                                        <td>{{ $ib->hotelBooking ? ($ib->hotelBooking->guest_name ?? $ib->hotelBooking->guest_email) : '—' }}</td>
                                        <td>${{ number_format($ib->booking_total ?? 0, 2) }}</td>
                                        <td>${{ number_format($ib->commission ?? 0, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.includes.footer')
@endsection
