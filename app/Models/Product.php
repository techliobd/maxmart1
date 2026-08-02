<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'price',
        'old_price',
        'cost_price',
        'stock_quantity',
        'min_stock',
        'track_inventory',
        'allow_backorder',
        'weight',
        'length',
        'width',
        'height',
        'barcode',
        'stock_status',
        'is_featured',
        'is_new',
        'is_on_sale',
        'sale_start_date',
        'sale_end_date',
        'view_count',
        'rating',
        'review_count',
        'sold_count',
        'tags',
        'specifications',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_active',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'old_price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'weight' => 'decimal:3',
            'length' => 'decimal:2',
            'width' => 'decimal:2',
            'height' => 'decimal:2',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_on_sale' => 'boolean',
            'view_count' => 'integer',
            'rating' => 'decimal:2',
            'review_count' => 'integer',
            'sold_count' => 'integer',
            'tags' => 'array',
            'specifications' => 'array',
            'is_active' => 'boolean',
            'published_at' => 'datetime',
            'sale_start_date' => 'date',
            'sale_end_date' => 'date',
        ];
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = \Str::slug($product->name);
            }
            if (empty($product->sku)) {
                $product->sku = 'PRD-' . strtoupper(\Str::random(8));
            }
        });

        static::updating(function ($product) {
            // Update stock status based on quantity
            if ($product->track_inventory && !$product->allow_backorder) {
                if ($product->stock_quantity <= 0) {
                    $product->stock_status = 'out_of_stock';
                } elseif ($product->stock_quantity <= $product->min_stock) {
                    $product->stock_status = 'on_backorder';
                } else {
                    $product->stock_status = 'in_stock';
                }
            }

            // Auto-set is_on_sale flag
            if ($product->old_price && $product->price < $product->old_price) {
                $product->is_on_sale = true;
            } else {
                $product->is_on_sale = false;
            }
        });
    }

    /**
     * Get the category for this product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the brand for this product.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the images for this product.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    /**
     * Get the primary image.
     */
    public function primaryImage(): ?ProductImage
    {
        return $this->images()->where('is_primary', true)->first()
            ?? $this->images()->first();
    }

    /**
     * Get the attributes for this product.
     */
    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'product_attributes')
            ->withPivot('attribute_value_id')
            ->withTimestamps();
    }

    /**
     * Get the variations for this product.
     */
    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    /**
     * Get the default variation.
     */
    public function defaultVariation(): ?ProductVariation
    {
        return $this->variations()->where('is_default', true)->first()
            ?? $this->variations()->first();
    }

    /**
     * Get related products.
     */
    public function relatedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'related_products', 'product_id', 'related_product_id');
    }

    /**
     * Get frequently bought together products.
     */
    public function frequentlyBoughtTogether(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'frequently_bought_together', 'product_id', 'associated_product_id')
            ->withPivot('discount_percent');
    }

    /**
     * Get reviews for this product.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get questions for this product.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(ProductQuestion::class);
    }

    /**
     * Get cart items containing this product.
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get order items containing this product.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scope for active products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for featured products.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->where('is_active', true);
    }

    /**
     * Scope for new products.
     */
    public function scopeNew($query)
    {
        return $query->where('is_new', true)->where('is_active', true);
    }

    /**
     * Scope for sale products.
     */
    public function scopeOnSale($query)
    {
        return $query->where('is_on_sale', true)->where('is_active', true);
    }

    /**
     * Scope for in-stock products.
     */
    public function scopeInStock($query)
    {
        return $query->where('stock_status', 'in_stock');
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
     * Check if product is available for purchase.
     */
    public function isAvailable(int $quantity = 1): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if (!$this->track_inventory || $this->allow_backorder) {
            return true;
        }

        return $this->stock_quantity >= $quantity;
    }

    /**
     * Get searchable data for Scout.
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'short_description' => $this->short_description,
            'category_name' => $this->category?->name,
            'brand_name' => $this->brand?->name,
            'price' => $this->price,
            'is_active' => $this->is_active,
        ];
    }

    /**
     * Get minimum price considering variations.
     */
    public function getMinPriceAttribute(): float
    {
        if ($this->variations()->exists()) {
            return $this->variations()->where('is_active', true)->min('price') ?? $this->price;
        }
        return $this->price;
    }

    /**
     * Get maximum price considering variations.
     */
    public function getMaxPriceAttribute(): float
    {
        if ($this->variations()->exists()) {
            return $this->variations()->where('is_active', true)->max('price') ?? $this->price;
        }
        return $this->price;
    }
}
