<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaxClass extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function taxRules(): HasMany
    {
        return $this->hasMany(TaxRule::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public static function getDefault(): ?self
    {
        return self::default()->first();
    }

    public function calculateTax(float $amount, string $country, ?string $state = null): float
    {
        $taxRate = $this->taxRules()
            ->where('country', $country)
            ->when($state, fn($q) => $q->where('state', $state))
            ->where('is_active', true)
            ->value('rate') ?? 0;

        return round(($amount * $taxRate) / 100, 2);
    }
}
