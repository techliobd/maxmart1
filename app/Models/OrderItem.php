<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class OrderItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'price' => 'decimal:2',
        'old_price' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'options' => 'array',
        'meta' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }

    public function refundItems(): MorphMany
    {
        return $this->morphMany(RefundItem::class, 'refundable');
    }

    public function getSubtotalAttribute($value): float
    {
        return round(($this->price * $this->quantity) + $this->tax_amount - $this->discount_amount, 2);
    }

    public function getProfitAttribute(): float
    {
        $cost = $this->product?->cost_price ?? 0;
        if ($this->variation && $this->variation->cost_price) {
            $cost = $this->variation->cost_price;
        }
        return round(($this->price - $cost) * $this->quantity, 2);
    }

    public function isDigital(): bool
    {
        return $this->product?->is_digital ?? false;
    }

    public function requiresShipping(): bool
    {
        return !$this->isDigital();
    }
}
