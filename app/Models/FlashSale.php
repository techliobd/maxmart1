<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class FlashSale extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'discount_fixed' => 'decimal:2',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'flash_sale_product')
            ->withPivot('discount_override')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>', now());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('is_active', true)
            ->where('starts_at', '>', now());
    }

    public function scopeEnded($query)
    {
        return $query->where('ends_at', '<=', now());
    }

    public function isActiveNow(): bool
    {
        return $this->is_active 
            && $this->starts_at <= now() 
            && $this->ends_at > now();
    }

    public function isUpcoming(): bool
    {
        return $this->starts_at > now();
    }

    public function hasEnded(): bool
    {
        return $this->ends_at <= now();
    }

    public function getTimeUntilStartAttribute(): ?string
    {
        if ($this->isUpcoming()) {
            return $this->starts_at->diffForHumans();
        }
        return null;
    }

    public function getTimeRemainingAttribute(): ?string
    {
        if ($this->isActiveNow()) {
            return $this->ends_at->diffForHumans(['short' => true]);
        }
        return null;
    }

    public function getProgressPercentageAttribute(): float
    {
        if (!$this->isActiveNow()) {
            return 0;
        }

        $total = $this->ends_at->timestamp - $this->starts_at->timestamp;
        $elapsed = now()->timestamp - $this->starts_at->timestamp;

        return min(100, max(0, ($elapsed / $total) * 100));
    }

    public function getDiscountForProduct(Product $product): float
    {
        $pivot = $this->products()->where('product_id', $product->id)->first()?->pivot;
        
        if ($pivot && $pivot->discount_override) {
            return $pivot->discount_override;
        }

        return $this->discount_percentage ?? 0;
    }

    public function getSalePrice(float $originalPrice): float
    {
        if ($this->discount_fixed) {
            return max(0, $originalPrice - $this->discount_fixed);
        }

        return $originalPrice * (1 - ($this->discount_percentage / 100));
    }
}
