@extends('layouts.adminBase')

@section('content')
<div class="container-fluid pt-4 px-4">
    <h4 class="mb-4">Owner listing requests</h4>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="table-responsive bg-white rounded p-3">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th><th>Type</th><th>Ad</th><th>Contact</th><th>Location</th><th>Amount</th><th>Status</th><th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                <tr>
                    <td>{{ $req->created_at->format('d M Y') }}</td>
                    <td>{{ ucfirst($req->product_type) }}</td>
                    <td>{{ ucfirst($req->ad_type) }}</td>
                    <td>{{ $req->contact_name }}<br><small>{{ $req->phone }}</small></td>
                    <td>{{ $req->location }}</td>
                    <td>{{ $req->amount ? number_format($req->amount).' RWF' : '—' }}</td>
                    <td><span class="badge bg-secondary">{{ $req->status }}</span></td>
                    <td>
                        <form method="POST" action="{{ route('admin.listing-requests.update', $req) }}" class="d-flex gap-1">
                            @csrf @method('PUT')
                            <select name="status" class="form-select form-select-sm">
                                <option value="pending" @selected($req->status==='pending')>Pending</option>
                                <option value="approved" @selected($req->status==='approved')>Approved</option>
                                <option value="rejected" @selected($req->status==='rejected')>Rejected</option>
                            </select>
                            <button class="btn btn-sm btn-primary">Save</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-muted">No requests yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $requests->links() }}
    </div>
</div>
@endsection
