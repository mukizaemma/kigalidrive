<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'icon',
        'image',
        'excerpt',
        'description',
        'status',
        'sort_order',
        'added_by',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    /**
     * Primary call-to-action based on service slug / type.
     *
     * @return array{route: string, label: string, icon: string, params?: array<string, string>}
     */
    public function primaryCta(): array
    {
        $slug = $this->slug ?? '';

        if (str_contains($slug, 'apartment') || str_contains($slug, 'villa')) {
            return ['route' => 'apartments', 'label' => 'Browse apartments', 'icon' => 'fa-building'];
        }

        if (str_contains($slug, 'list')) {
            return ['route' => 'listYourProperty', 'label' => 'List with us', 'icon' => 'fa-list'];
        }

        if (str_contains($slug, 'car-sale')) {
            return [
                'route' => 'showCars',
                'label' => 'View cars for sale',
                'icon' => 'fa-car-side',
                'params' => ['listing_type' => 'sale'],
            ];
        }

        if (str_contains($slug, 'car') || str_contains($slug, 'chauffeur') || str_contains($slug, 'corporate') || str_contains($slug, 'fleet')) {
            return ['route' => 'showCars', 'label' => 'Browse our fleet', 'icon' => 'fa-car-side'];
        }

        return ['route' => 'contact', 'label' => 'Contact us', 'icon' => 'fa-envelope'];
    }

    public function primaryCtaUrl(): string
    {
        $cta = $this->primaryCta();

        return route($cta['route'], $cta['params'] ?? []);
    }

    /**
     * @return list<string>
     */
    public function highlights(): array
    {
        if (!$this->description) {
            return [];
        }

        return collect(preg_split('/\r\n|\r|\n/', $this->description))
            ->map(fn ($line) => trim($line))
            ->filter(fn ($line) => (bool) preg_match('/^[•·\-\*]\s*/u', $line))
            ->map(fn ($line) => (string) preg_replace('/^[•·\-\*]\s*/u', '', $line))
            ->values()
            ->all();
    }

    public function introText(): string
    {
        if (!$this->description) {
            return '';
        }

        $blocks = preg_split("/\n\s*\n/", trim($this->description), 2);
        $intro = trim($blocks[0] ?? '');

        $lines = collect(preg_split('/\r\n|\r|\n/', $intro))
            ->map(fn ($line) => trim($line))
            ->filter(fn ($line) => $line !== '' && !preg_match('/^[•·\-\*]\s*/u', $line))
            ->implode("\n");

        return trim($lines);
    }

    public function serviceTypeLabel(): string
    {
        $slug = $this->slug ?? '';

        return match (true) {
            str_contains($slug, 'car-sale') => 'Car sales',
            str_contains($slug, 'car') => 'Car rental',
            str_contains($slug, 'apartment') || str_contains($slug, 'villa') => 'Apartments',
            str_contains($slug, 'chauffeur') || str_contains($slug, 'airport') => 'Chauffeur & transfers',
            str_contains($slug, 'corporate') || str_contains($slug, 'fleet') => 'Corporate',
            str_contains($slug, 'list') => 'Listings',
            default => 'Service',
        };
    }
}
