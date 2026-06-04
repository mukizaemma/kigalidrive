<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeHireScenario extends Model
{
    protected $fillable = [
        'icon',
        'title',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function iconClass(): string
    {
        $icon = trim((string) $this->icon);
        if ($icon === '') {
            return 'fas fa-car';
        }
        if (preg_match('/\bfa[sbrl]?\s+fa-/', $icon)) {
            return $icon;
        }
        if (str_starts_with($icon, 'fa-')) {
            return 'fas ' . $icon;
        }

        return 'fas fa-' . ltrim($icon, '-');
    }
}
