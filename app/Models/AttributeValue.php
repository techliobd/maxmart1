<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'attribute_id',
        'value',
        'slug',
        'color_hex',
        'image',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [];
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($attributeValue) {
            if (empty($attributeValue->slug)) {
                $attributeValue->slug = Str::slug($attributeValue->value);
            }
        });
    }

    /**
     * Get the attribute this value belongs to.
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    /**
     * Check if this is a color value.
     */
    public function isColor(): bool
    {
        return $this->attribute && $this->attribute->type === 'color';
    }

    /**
     * Check if this is an image value.
     */
    public function isImage(): bool
    {
        return $this->attribute && $this->attribute->type === 'image';
    }

    /**
     * Get products that have this attribute value.
     */
    public function products()
    {
        return $this->hasManyThrough(
            Product::class,
            ProductAttribute::class,
            'attribute_value_id',
            'id',
            'id',
            'product_id'
        );
    }

    /**
     * Get variations that have this attribute value.
     */
    public function variations()
    {
        return $this->hasManyThrough(
            ProductVariation::class,
            VariationAttributeValue::class,
            'attribute_value_id',
            'id',
            'id',
            'variation_id'
        );
    }
}
