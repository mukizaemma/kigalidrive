<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ListingRequest;
use Illuminate\Http\Request;

class ListingRequestsController extends Controller
{
    public function index()
    {
        $requests = ListingRequest::latest()->paginate(20);

        return view('admin.listing-requests.index', compact('requests'));
    }

    public function update(Request $request, ListingRequest $listingRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $listingRequest->update($validated);

        return back()->with('success', 'Listing request updated.');
    }
}
