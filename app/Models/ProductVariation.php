<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'old_price',
        'stock_quantity',
        'weight',
        'barcode',
        'image',
        'is_default',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'old_price' => 'decimal:2',
            'weight' => 'decimal:3',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($variation) {
            if (empty($variation->sku)) {
                $variation->sku = 'VAR-' . strtoupper(\Str::random(8));
            }
        });
    }

    /**
     * Get the product this variation belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the attribute values for this variation.
     */
    public function attributeValues(): HasMany
    {
        return $this->hasMany(VariationAttributeValue::class);
    }

    /**
     * Get all attribute value IDs grouped by attribute.
     */
    public function getAttributeOptionsAttribute(): array
    {
        $options = [];
        foreach ($this->attributeValues as $av) {
            $options[$av->attribute_id] = [
                'attribute_name' => $av->attribute->name,
                'attribute_slug' => $av->attribute->slug,
                'attribute_type' => $av->attribute->type,
                'value_id' => $av->attribute_value_id,
                'value' => $av->attributeValue->value,
                'color_hex' => $av->attributeValue->color_hex,
                'image' => $av->attributeValue->image,
            ];
        }
        return $options;
    }

    /**
     * Check if this variation is in stock.
     */
    public function isInStock(): bool
    {
        return $this->stock_quantity > 0 || $this->product?->allow_backorder;
    }

    /**
     * Get discount percentage.
     */
    public function getDiscountPercentAttribute(): float
    {
        if (!$this->old_price || $this->old_price <= $this->price) {
            return 0;
        }
        return round((($this->old_price - $this->price) / $this->old_price) * 100, 2);
    }

    /**
     * Scope for active variations.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for default variation.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
