<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarRentalRequest;
use App\Models\Setting;
use Illuminate\Http\Request;

class CarRentalRequestController extends Controller
{
    public function index()
    {
        $requests = CarRentalRequest::latest()->paginate(20);
        $setting = Setting::first();

        return view('admin.cars.requests', [
            'requests' => $requests,
            'setting' => $setting,
        ]);
    }

    public function edit($id)
    {
        $requestItem = CarRentalRequest::findOrFail($id);
        $setting = Setting::first();

        return view('admin.cars.requestEdit', [
            'requestItem' => $requestItem,
            'setting' => $setting,
        ]);
    }

    public function update(Request $request, $id)
    {
        $requestItem = CarRentalRequest::findOrFail($id);

        $data = $request->validate([
            'status' => 'required|in:pending,responded',
            'admin_reply' => 'nullable|string|max:5000',
        ]);

        $requestItem->update($data);

        return redirect()->route('admin.carRentalRequests.index')
            ->with('success', 'Car rental request updated successfully.');
    }
}

