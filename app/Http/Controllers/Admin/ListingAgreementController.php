<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\ListingAgreementSignature;
use App\Models\ListingAgreementTemplate;
use App\Models\Property;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ListingAgreementController extends Controller
{
    public function index()
    {
        $template = ListingAgreementTemplate::query()->first();

        $signatures = ListingAgreementSignature::query()
            ->whereNotNull('signed_at')
            ->with([
                'signable' => function (MorphTo $morphTo) {
                    $morphTo->morphWith([
                        Hotel::class => ['owner'],
                        Property::class => ['owner'],
                    ]);
                },
            ])
            ->orderByDesc('signed_at')
            ->paginate(30)
            ->withQueryString();

        $setting = \App\Models\Setting::first();

        return view('admin.listing-agreement.index', [
            'signatures' => $signatures,
            'template' => $template,
            'setting' => $setting,
        ]);
    }

    public function showSigned(ListingAgreementSignature $signature)
    {
        $signature->load([
            'signable' => function (MorphTo $morphTo) {
                $morphTo->morphWith([
                    Hotel::class => ['owner'],
                    Property::class => ['owner'],
                ]);
            },
        ]);

        $template = ListingAgreementTemplate::current();
        if (empty($template->sections)) {
            $template->update(['sections' => ListingAgreementTemplate::defaultSections()]);
            $template->refresh();
        }

        $listing = $signature->signable;
        $owner = $listing ? ($listing->owner ?? null) : null;
        $listingName = $listing ? ($listing->name ?? '—') : '(listing removed)';
        $typeLabel = class_basename($signature->signable_type);
        $matchesTemplate = $template ? $signature->isCurrentForTemplate($template) : false;

        $setting = \App\Models\Setting::first();

        return view('admin.listing-agreement.signed-show', [
            'signature' => $signature,
            'template' => $template,
            'listing' => $listing,
            'owner' => $owner,
            'listingName' => $listingName,
            'typeLabel' => $typeLabel,
            'matchesTemplate' => $matchesTemplate,
            'setting' => $setting,
        ]);
    }

    public function edit()
    {
        $template = ListingAgreementTemplate::current();
        if (empty($template->sections)) {
            $template->update(['sections' => ListingAgreementTemplate::defaultSections()]);
            $template->refresh();
        }
        $setting = \App\Models\Setting::first();

        return view('admin.listing-agreement.edit', [
            'template' => $template,
            'setting' => $setting,
        ]);
    }

    public function update(Request $request)
    {
        $template = ListingAgreementTemplate::current();

        $validated = $request->validate([
            'platform_name' => 'required|string|max:255',
            'platform_representative_name' => 'nullable|string|max:255',
            'intro_text' => 'nullable|string|max:65000',
            'platform_signature' => 'nullable|image|max:4096',
            'sections' => 'nullable|array',
            'sections.*.heading' => 'nullable|string|max:500',
            'sections.*.items_text' => 'nullable|string|max:10000',
        ]);

        $sections = [];
        foreach ($request->input('sections', []) as $block) {
            if (empty($block['heading']) || ! is_string($block['heading'])) {
                continue;
            }
            $lines = preg_split('/\r\n|\r|\n/', (string) ($block['items_text'] ?? ''));
            $items = array_values(array_filter(array_map('trim', $lines), static function ($line) {
                return $line !== '';
            }));
            $sections[] = [
                'heading' => trim($block['heading']),
                'items' => $items,
            ];
        }

        $payload = [
            'platform_name' => $validated['platform_name'],
            'platform_representative_name' => $validated['platform_representative_name'] ?? null,
            'intro_text' => $validated['intro_text'] ?? null,
            'sections' => $sections ?: ListingAgreementTemplate::defaultSections(),
        ];

        if ($request->hasFile('platform_signature')) {
            if ($template->platform_signature_path && Storage::exists('public/'.$template->platform_signature_path)) {
                Storage::delete('public/'.$template->platform_signature_path);
            }
            $path = $request->file('platform_signature')->store('public/listing-agreements/platform');
            $payload['platform_signature_path'] = str_replace('public/', '', $path);
        }

        $template->update($payload);

        return redirect()->route('admin.listing-agreement.index')
            ->with('success', 'Listing agreement template updated. Hosts may need to sign again if the agreement changed.');
    }
}
