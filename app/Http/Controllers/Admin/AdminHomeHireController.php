<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeHireIntro;
use App\Models\HomeHireScenario;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminHomeHireController extends Controller
{
    public function index(): View
    {
        $intro = HomeHireIntro::firstOrCreate(
            ['id' => 1],
            [
                'eyebrow' => 'Car hire in Kigali',
                'headline' => 'The right car for your Rwanda moment',
                'hook' => 'Look no further than Kigali Drive Rentals.',
                'cta_primary_label' => 'Browse fleet',
                'cta_primary_url' => '/cars',
                'show_on_hero' => true,
                'is_active' => true,
            ]
        );

        $scenarios = HomeHireScenario::ordered()->get();

        return view('admin.home-hire.index', [
            'intro' => $intro,
            'scenarios' => $scenarios,
            'setting' => Setting::first(),
        ]);
    }

    public function updateIntro(Request $request): RedirectResponse
    {
        $intro = HomeHireIntro::firstOrCreate(['id' => 1]);

        $data = $request->validate([
            'eyebrow' => 'required|string|max:120',
            'headline' => 'required|string|max:200',
            'hook' => 'required|string|max:600',
            'hook_highlight' => 'nullable|string|max:200',
            'section_eyebrow' => 'nullable|string|max:120',
            'section_title' => 'nullable|string|max:200',
            'section_lead' => 'nullable|string|max:600',
            'cta_primary_label' => 'required|string|max:80',
            'cta_primary_url' => 'required|string|max:255',
            'cta_secondary_label' => 'nullable|string|max:80',
            'cta_secondary_url' => 'nullable|string|max:255',
            'show_on_hero' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $intro->update([
            ...$data,
            'show_on_hero' => $request->boolean('show_on_hero', true),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.home-hire.index')->with('success', 'Hero hire section updated.');
    }

    public function storeScenario(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'icon' => 'required|string|max:80',
            'title' => 'required|string|max:120',
            'description' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        HomeHireScenario::create([
            'icon' => $data['icon'],
            'title' => $data['title'],
            'description' => $data['description'],
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.home-hire.index')->with('success', 'Scenario added.');
    }

    public function updateScenario(Request $request, HomeHireScenario $scenario): RedirectResponse
    {
        $data = $request->validate([
            'icon' => 'required|string|max:80',
            'title' => 'required|string|max:120',
            'description' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $scenario->update([
            'icon' => $data['icon'],
            'title' => $data['title'],
            'description' => $data['description'],
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.home-hire.index')->with('success', 'Scenario updated.');
    }

    public function destroyScenario(HomeHireScenario $scenario): RedirectResponse
    {
        $scenario->delete();

        return redirect()->route('admin.home-hire.index')->with('success', 'Scenario removed.');
    }
}
