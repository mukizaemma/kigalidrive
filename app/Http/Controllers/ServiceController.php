<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::active()
            ->ordered()
            ->paginate(12);

        return view('frontend.services', compact('services'));
    }

    public function show(string $slug)
    {
        $service = Service::active()->where('slug', $slug)->firstOrFail();

        $related = Service::active()
            ->where('id', '!=', $service->id)
            ->ordered()
            ->limit(3)
            ->get();

        return view('frontend.service-show', compact('service', 'related'));
    }
}
