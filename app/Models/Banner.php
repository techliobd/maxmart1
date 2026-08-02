<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'click_count' => 'integer',
        'impressions' => 'integer',
        'scheduled_from' => 'datetime',
        'scheduled_to' => 'datetime',
    ];

    public const POSITION_HOMEPAGE_HERO = 'homepage_hero';
    public const POSITION_HOMEPAGE_MIDDLE = 'homepage_middle';
    public const POSITION_SHOP_SIDEBAR = 'shop_sidebar';
    public const POSITION_PRODUCT_PAGE = 'product_page';
    public const POSITION_CHECKOUT = 'checkout';
    public const POSITION_FOOTER = 'footer';

    public static function getPositions(): array
    {
        return [
            self::POSITION_HOMEPAGE_HERO => 'Homepage Hero',
            self::POSITION_HOMEPAGE_MIDDLE => 'Homepage Middle',
            self::POSITION_SHOP_SIDEBAR => 'Shop Sidebar',
            self::POSITION_PRODUCT_PAGE => 'Product Page',
            self::POSITION_CHECKOUT => 'Checkout',
            self::POSITION_FOOTER => 'Footer',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('scheduled_from')
                    ->orWhere('scheduled_from', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('scheduled_to')
                    ->orWhere('scheduled_to', '>', now());
            });
    }

    public function scopePosition($query, string $position)
    {
        return $query->where('position', $position);
    }

    public static function getForPosition(string $position, int $limit = 1)
    {
        return self::active()->position($position)
            ->orderByRaw('RAND()')
            ->limit($limit)
            ->get();
    }

    public function trackImpression(): void
    {
        $this->increment('impressions');
    }

    public function trackClick(): void
    {
        $this->increment('click_count');
    }

    public function getCtrAttribute(): float
    {
        if ($this->impressions == 0) {
            return 0;
        }
        return round(($this->click_count / $this->impressions) * 100, 2);
    }
}
