<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingRate extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'cost' => 'decimal:2',
        'min_weight' => 'decimal:2',
        'max_weight' => 'decimal:2',
        'min_order_total' => 'decimal:2',
        'max_order_total' => 'decimal:2',
        'is_free_above' => 'boolean',
    ];

    public function shippingZone(): BelongsTo
    {
        return $this->belongsTo(ShippingZone::class);
    }

    public function scopeActive($query)
    {
        return $query->whereHas('shippingZone', fn($q) => $q->active());
    }

    public function getFormattedCostAttribute(): string
    {
        if ($this->is_free_above && $this->free_above_amount) {
            return 'Free';
        }
        return number_format($this->cost, 2);
    }

    public function isFree(): bool
    {
        return $this->cost == 0 || $this->is_free_above;
    }
}
