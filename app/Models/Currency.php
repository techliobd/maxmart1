<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'symbol',
        'exchange_rate',
        'is_default',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'exchange_rate' => 'decimal:4',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the default currency.
     */
    public static function getDefault(): self
    {
        return cache()->remember('default_currency', 3600, function () {
            return static::where('is_default', true)
                ->where('is_active', true)
                ->first()
                ?? static::first();
        });
    }

    /**
     * Get all active currencies.
     */
    public static function getActive(): array
    {
        return cache()->remember('active_currencies', 3600, function () {
            return static::where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();
        });
    }

    /**
     * Format a price with this currency.
     */
    public function format(float $amount): string
    {
        return $this->symbol . number_format($amount * $this->exchange_rate, 2);
    }

    /**
     * Scope for active currencies.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for default currency.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
