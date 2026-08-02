<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    public const TYPE_HERO = 'hero';
    public const TYPE_CATEGORIES = 'categories';
    public const TYPE_FEATURED_PRODUCTS = 'featured_products';
    public const TYPE_NEW_ARRIVALS = 'new_arrivals';
    public const TYPE_BEST_SELLERS = 'best_sellers';
    public const TYPE_FLASH_SALE = 'flash_sale';
    public const TYPE_BRANDS = 'brands';
    public const TYPE_TESTIMONIALS = 'testimonials';
    public const TYPE_BLOG = 'blog';
    public const TYPE_NEWSLETTER = 'newsletter';
    public const TYPE_PROMO_BANNER = 'promo_banner';
    public const TYPE_USP_STRIP = 'usp_strip';

    public static function getTypes(): array
    {
        return [
            self::TYPE_HERO => 'Hero Slider',
            self::TYPE_CATEGORIES => 'Category Tiles',
            self::TYPE_FEATURED_PRODUCTS => 'Featured Products',
            self::TYPE_NEW_ARRIVALS => 'New Arrivals',
            self::TYPE_BEST_SELLERS => 'Best Sellers',
            self::TYPE_FLASH_SALE => 'Flash Sale',
            self::TYPE_BRANDS => 'Brand Strip',
            self::TYPE_TESTIMONIALS => 'Testimonials',
            self::TYPE_BLOG => 'Blog Preview',
            self::TYPE_NEWSLETTER => 'Newsletter',
            self::TYPE_PROMO_BANNER => 'Promo Banner',
            self::TYPE_USP_STRIP => 'USP Strip',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public static function getActiveSections()
    {
        return self::active()->get();
    }

    public function getSetting(string $key, $default = null)
    {
        return $this->settings[$key] ?? $default;
    }

    public function setSetting(string $key, $value): void
    {
        $settings = $this->settings ?? [];
        $settings[$key] = $value;
        $this->update(['settings' => $settings]);
    }
}
