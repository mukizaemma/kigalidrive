<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminServicesController extends Controller
{
    public function index()
    {
        $services = Service::ordered()->get();

        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.form', [
            'service' => new Service(['status' => 'Active', 'sort_order' => 0]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['added_by'] = $request->user()->id;

        if ($request->hasFile('image')) {
            $data['image'] = basename($request->file('image')->store('public/images/services'));
        }

        Service::create($data);

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.form', compact('service'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $data = $this->validated($request, $service);

        if ($request->hasFile('image')) {
            if ($service->image) {
                Storage::delete('public/images/services/' . $service->image);
            }
            $data['image'] = basename($request->file('image')->store('public/images/services'));
        }

        if ($data['title'] !== $service->title) {
            $data['slug'] = $this->uniqueSlug($data['title'], $service->id);
        }

        $service->update($data);

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        if ($service->image) {
            Storage::delete('public/images/services/' . $service->image);
        }

        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }

    private function validated(Request $request, ?Service $service = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:80'],
            'status' => ['required', Rule::in(['Active', 'Inactive'])],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ], [], [
            'title' => 'service title',
        ]);
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $base = $slug;
        $n = 1;

        while (
            Service::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $n++;
        }

        return $slug;
    }
}
