<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'is_filterable',
        'is_visible_on_product',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_filterable' => 'boolean',
            'is_visible_on_product' => 'boolean',
        ];
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($attribute) {
            if (empty($attribute->slug)) {
                $attribute->slug = \Str::slug($attribute->name);
            }
        });
    }

    /**
     * Get the values for this attribute.
     */
    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class)->orderBy('sort_order');
    }

    /**
     * Get products that have this attribute.
     */
    public function products(): HasMany
    {
        return $this->hasManyThrough(
            Product::class,
            ProductAttribute::class,
            'attribute_id',
            'id',
            'id',
            'product_id'
        );
    }

    /**
     * Scope for filterable attributes.
     */
    public function scopeFilterable($query)
    {
        return $query->where('is_filterable', true);
    }

    /**
     * Scope for visible on product.
     */
    public function scopeVisibleOnProduct($query)
    {
        return $query->where('is_visible_on_product', true);
    }

    /**
     * Get all filterable attributes with their values.
     */
    public static function getFilterableWithValues(): array
    {
        return cache()->remember('filterable_attributes', 3600, function () {
            return static::filterable()
                ->with('values')
                ->orderBy('sort_order')
                ->get();
        });
    }
}
