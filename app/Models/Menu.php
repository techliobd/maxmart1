<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Menu extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public const LOCATION_MAIN = 'main';
    public const LOCATION_FOOTER = 'footer';
    public const LOCATION_MOBILE = 'mobile';

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('sort_order');
    }

    public function rootItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)
            ->whereNull('parent_id')
            ->orderBy('sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLocation($query, string $location)
    {
        return $query->where('location', $location);
    }

    public static function getMainMenus()
    {
        return self::active()->location(self::LOCATION_MAIN)->with('rootItems.children')->get();
    }

    public static function getFooterMenus()
    {
        return self::active()->location(self::LOCATION_FOOTER)->with('items')->get();
    }
}
