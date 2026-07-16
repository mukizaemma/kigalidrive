<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarDetail;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminCarDetailController extends Controller
{
    public function index(): View
    {
        $details = CarDetail::ordered()->get();

        return view('admin.car-details.index', [
            'details' => $details,
            'setting' => Setting::first(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'icon' => 'nullable|string|max:80',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $slug = $this->uniqueSlug($data['name']);

        CarDetail::create([
            'name' => $data['name'],
            'slug' => $slug,
            'icon' => $data['icon'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.car-details.index')->with('success', 'Car detail added.');
    }

    public function update(Request $request, CarDetail $carDetail): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'icon' => 'nullable|string|max:80',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $carDetail->update([
            'name' => $data['name'],
            'slug' => $this->uniqueSlug($data['name'], $carDetail->id),
            'icon' => $data['icon'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.car-details.index')->with('success', 'Car detail updated.');
    }

    public function destroy(CarDetail $carDetail): RedirectResponse
    {
        $carDetail->cars()->detach();
        $carDetail->delete();

        return redirect()->route('admin.car-details.index')->with('success', 'Car detail removed.');
    }

    protected function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'detail';
        $slug = $base;
        $counter = 1;

        while (
            CarDetail::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }
}
