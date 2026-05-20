<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListingAgreementTemplate extends Model
{
    protected $fillable = [
        'template_type',
        'platform_name',
        'platform_representative_name',
        'platform_signature_path',
        'intro_text',
        'sections',
    ];

    protected $casts = [
        'sections' => 'array',
    ];

    public static function current(): self
    {
        $row = static::query()->first();
        if ($row) {
            return $row;
        }

        return static::query()->create([
            'platform_name' => 'Kigali Drive Rentals',
            'template_type' => 'listing',
            'platform_representative_name' => null,
            'platform_signature_path' => null,
            'intro_text' => self::defaultIntro(),
            'sections' => self::defaultSections(),
        ]);
    }

    public static function defaultIntro(): string
    {
        return "This Agreement is made between:\n\n"
            . "1. Platform Owner: Stay Nets, operating an online booking platform (“Platform”)\n\n"
            . "AND\n\n"
            . "2. Property Owner / Host (“Host”)";
    }

    /**
     * @return array<int, array{heading: string, items: array<int, string>}>
     */
    public static function defaultSections(): array
    {
        return [
            [
                'heading' => '1. PURPOSE',
                'items' => [
                    'The Host agrees to list their property on the Platform, and the Platform agrees to market and facilitate bookings for the Host.',
                ],
            ],
            [
                'heading' => '2. PROPERTY DETAILS',
                'items' => [
                    'Property Name, Location, and Type will be taken from the listing information you provide on the Platform.',
                ],
            ],
            [
                'heading' => '3. PLATFORM SERVICES',
                'items' => [
                    'Display the property listing to users',
                    'Process reservations',
                    'Provide customer support tools',
                    'Promote the property online',
                ],
            ],
            [
                'heading' => '4. COMMISSION & PAYMENTS',
                'items' => [
                    'Commission Rate: as published on the Platform (e.g. up to 5% per booking unless otherwise agreed)',
                    'Payment Method: Bank transfer / Mobile Money / Card as configured',
                    'Payment Timeline: as stated in your host dashboard (e.g. within 7–14 days after guest checkout)',
                ],
            ],
            [
                'heading' => '5. HOST RESPONSIBILITIES',
                'items' => [
                    'Provide accurate property information',
                    'Maintain availability and pricing',
                    'Deliver the service booked by guests',
                    'Handle guest complaints related to the property',
                ],
            ],
            [
                'heading' => '6. BOOKINGS & CONTRACT',
                'items' => [
                    'A booking becomes valid once confirmed and paid',
                    'The agreement for stay is between Host and Guest',
                    'The Platform acts only as an intermediary',
                ],
            ],
            [
                'heading' => '7. CANCELLATION POLICY',
                'items' => [
                    'The Host must define: free cancellation period (if any), refund conditions, and no-show policy on the listing.',
                ],
            ],
            [
                'heading' => '8. DAMAGE & LIABILITY',
                'items' => [
                    'Guests are responsible for damages as applicable',
                    'Host must report damages within the timeframe stated in house rules',
                    'Platform is not liable for property damage or guest behavior',
                ],
            ],
            [
                'heading' => '9. CONTENT & LISTING RIGHTS',
                'items' => [
                    'Host grants Platform the right to use photos and descriptions for the listing',
                    'Platform may promote listings for marketing',
                ],
            ],
            [
                'heading' => '10. TERM & TERMINATION',
                'items' => [
                    'Either party may terminate with reasonable notice as stated in host terms',
                    'Platform may suspend listing for policy violations',
                ],
            ],
            [
                'heading' => '11. LEGAL COMPLIANCE',
                'items' => [
                    'The Host must follow local laws and tax regulations',
                    'Ensure the property is legally rentable',
                ],
            ],
            [
                'heading' => '12. LIMITATION OF LIABILITY',
                'items' => [
                    'The Platform is not responsible for guest behavior',
                    'Does not guarantee bookings',
                    'Is not liable for indirect losses except as required by law',
                ],
            ],
            [
                'heading' => '13. GOVERNING LAW',
                'items' => [
                    'This Agreement shall be governed by applicable laws of the jurisdiction stated in the Platform’s terms.',
                ],
            ],
            [
                'heading' => '14. SIGNATURES',
                'items' => [
                    'By signing below, the Host confirms they have read and accept this Property Listing Agreement.',
                ],
            ],
        ];
    }
}
