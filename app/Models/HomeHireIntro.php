<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HomeHireIntro extends Model
{
    protected $table = 'home_hire_intro';

    protected $fillable = [
        'eyebrow',
        'headline',
        'hook',
        'hook_highlight',
        'section_eyebrow',
        'section_title',
        'section_lead',
        'cta_primary_label',
        'cta_primary_url',
        'cta_secondary_label',
        'cta_secondary_url',
        'show_on_hero',
        'is_active',
    ];

    protected $casts = [
        'show_on_hero' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function hookHtml(): string
    {
        $hook = e($this->hook ?? '');
        $highlight = trim((string) $this->hook_highlight);

        if ($highlight === '' || ! Str::contains($this->hook ?? '', $highlight)) {
            return $hook;
        }

        return str_replace(e($highlight), '<strong>' . e($highlight) . '</strong>', $hook);
    }

    public function ctaPrimaryHref(): string
    {
        return $this->normalizeUrl($this->cta_primary_url) ?: route('showCars');
    }

    public function ctaSecondaryHref(): ?string
    {
        return $this->cta_secondary_url ? $this->normalizeUrl($this->cta_secondary_url) : null;
    }

    protected function normalizeUrl(?string $url): ?string
    {
        $url = trim((string) $url);
        if ($url === '') {
            return null;
        }

        if (Str::startsWith($url, ['http://', 'https://', '/'])) {
            return $url;
        }

        return '/' . ltrim($url, '/');
    }
}
