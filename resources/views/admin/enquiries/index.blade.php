@extends('layouts.adminBase')

@section('content')
@include('admin.includes.sidebar')
<div class="content">
    @include('admin.includes.navbar')

    <div class="container-fluid pt-4 px-4">
        <div class="mb-4">
            <h4 class="mb-1">Enquiries & submission channels</h4>
            <p class="text-muted small mb-0">Tracks contact forms and general enquiries. Car/apartment reservations are counted from bookings.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="bg-light rounded p-3 h-100">
                    <h6 class="text-muted text-uppercase small mb-3">By channel (all sources)</h6>
                    @foreach(['whatsapp' => 'WhatsApp', 'email' => 'Email', 'form' => 'Online form'] as $key => $label)
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ $label }}</span>
                        <strong>{{ $channelStats[$key] ?? 0 }}</strong>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-8">
                <div class="bg-light rounded p-3 h-100">
                    <h6 class="text-muted text-uppercase small mb-3">By form type</h6>
                    <div class="row g-2">
                        @foreach(\App\Models\Enquiry::formTypeLabels() as $key => $label)
                        <div class="col-sm-6 col-lg-4">
                            <div class="d-flex justify-content-between border rounded px-3 py-2 bg-white small">
                                <span>{{ $label }}</span>
                                <strong>{{ $formStats[$key] ?? 0 }}</strong>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-light rounded p-4">
            <h6 class="mb-3">Recent enquiries</h6>
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Form</th>
                            <th>Channel</th>
                            <th>Contact</th>
                            <th>Subject / message</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($enquiries as $row)
                        <tr>
                            <td class="small text-nowrap">{{ $row->created_at->format('M j, Y H:i') }}</td>
                            <td><span class="badge bg-secondary">{{ $row->formTypeLabel() }}</span></td>
                            <td><span class="badge bg-info text-dark">{{ $row->channelLabel() }}</span></td>
                            <td class="small">
                                <strong>{{ $row->names }}</strong><br>
                                {{ $row->email }}<br>
                                @if($row->phone){{ $row->phone }}@endif
                            </td>
                            <td class="small">
                                @if($row->subject)<strong>{{ $row->subject }}</strong><br>@endif
                                {{ Str::limit(strip_tags($row->message), 100) }}
                            </td>
                            <td>
                                <a href="{{ route('admin.enquiries.destroy', $row->id) }}" class="btn btn-sm btn-danger"
                                   onclick="return confirm('Delete this enquiry?')">Delete</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No enquiries yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $enquiries->links() }}</div>
        </div>
    </div>
</div>
@include('admin.includes.footer')
@endsection
