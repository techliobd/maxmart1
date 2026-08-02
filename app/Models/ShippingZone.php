<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingZone extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'countries' => 'array',
        'states' => 'array',
        'zip_codes' => 'array',
    ];

    public function rates(): HasMany
    {
        return $this->hasMany(ShippingRate::class)->orderBy('min_weight');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function calculateShipping(float $weight, float $orderTotal): ?ShippingRate
    {
        return $this->rates()->active()
            ->where('min_weight', '<=', $weight)
            ->where('max_weight', '>=', $weight)
            ->where(function ($q) use ($orderTotal) {
                $q->whereNull('min_order_total')
                    ->orWhere('min_order_total', '<=', $orderTotal);
            })
            ->where(function ($q) use ($orderTotal) {
                $q->whereNull('max_order_total')
                    ->orWhere('max_order_total', '>=', $orderTotal);
            })
            ->first();
    }

    public function isApplicable(string $country, ?string $state = null, ?string $zipCode = null): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $countries = $this->countries ?? [];
        $states = $this->states ?? [];
        $zipCodes = $this->zip_codes ?? [];

        if (!empty($countries) && !in_array($country, $countries)) {
            return false;
        }

        if (!empty($states) && $state && !in_array($state, $states)) {
            return false;
        }

        if (!empty($zipCodes) && $zipCode && !in_array($zipCode, $zipCodes)) {
            return false;
        }

        return true;
    }
}
