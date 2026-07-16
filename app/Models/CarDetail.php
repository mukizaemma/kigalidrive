<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CarDetail extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function cars(): BelongsToMany
    {
        return $this->belongsToMany(Car::class, 'car_car_detail')
            ->withTimestamps();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function iconClass(): string
    {
        $icon = trim((string) $this->icon);

        if ($icon === '') {
            return 'fas fa-check';
        }

        if (str_starts_with($icon, 'fa ') || str_starts_with($icon, 'fas ') || str_starts_with($icon, 'far ') || str_starts_with($icon, 'fab ')) {
            return $icon;
        }

        return 'fas ' . ltrim($icon, '.');
    }
}
