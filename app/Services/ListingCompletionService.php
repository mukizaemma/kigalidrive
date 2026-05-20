<?php

namespace App\Services;

use App\Models\Hotel;
use App\Models\ListingAgreementTemplate;
use App\Models\Property;

class ListingCompletionService
{
    /**
     * @return array{complete: bool, percent: int, steps: array<int, array{key: string, label: string, done: bool, url: string}>}
     */
    public static function forHotel(Hotel $hotel): array
    {
        $template = ListingAgreementTemplate::query()->first();

        $steps = [
            [
                'key' => 'property',
                'label' => 'Add property',
                'done' => true,
                'url' => route('my.properties.hotels.edit', $hotel),
            ],
            [
                'key' => 'rooms',
                'label' => 'Add rooms / units',
                'done' => $hotel->rooms()->count() >= 1,
                'url' => route('my.properties.rooms.create', $hotel),
            ],
            [
                'key' => 'gallery',
                'label' => 'Add property gallery',
                'done' => $hotel->images()->count() >= 1,
                'url' => route('my.properties.hotels.edit', $hotel).'#property-gallery',
            ],
            [
                'key' => 'agreement',
                'label' => 'Sign listing agreement',
                'done' => false,
                'url' => route('my.properties.listing-agreement.show', $hotel),
            ],
        ];

        $sig = $hotel->listingAgreementSignature;
        $steps[3]['done'] = $template && $sig && $sig->isCurrentForTemplate($template);

        $doneCount = collect($steps)->where('done', true)->count();

        return [
            'complete' => $doneCount === count($steps),
            'percent' => (int) round(100 * $doneCount / count($steps)),
            'steps' => $steps,
        ];
    }

    /**
     * @return array{complete: bool, percent: int, steps: array<int, array{key: string, label: string, done: bool, url: string}>}
     */
    public static function forProperty(Property $property): array
    {
        $template = ListingAgreementTemplate::query()->first();

        $steps = [
            [
                'key' => 'property',
                'label' => 'Add property',
                'done' => true,
                'url' => route('admin.properties.edit', $property->id),
            ],
            [
                'key' => 'rooms',
                'label' => 'Add rooms / units',
                'done' => $property->units()->count() >= 1,
                'url' => route('admin.units.index', ['property_id' => $property->id]),
            ],
            [
                'key' => 'gallery',
                'label' => 'Add property gallery',
                'done' => $property->images()->count() >= 1,
                'url' => route('admin.properties.edit', $property->id),
            ],
            [
                'key' => 'agreement',
                'label' => 'Sign listing agreement',
                'done' => false,
                'url' => route('my.properties.property.listing-agreement.show', $property),
            ],
        ];

        $sig = $property->listingAgreementSignature;
        $steps[3]['done'] = $template && $sig && $sig->isCurrentForTemplate($template);

        $doneCount = collect($steps)->where('done', true)->count();

        return [
            'complete' => $doneCount === count($steps),
            'percent' => (int) round(100 * $doneCount / count($steps)),
            'steps' => $steps,
        ];
    }
}
