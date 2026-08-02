<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\VariationAttributeValue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductService
{
    /**
     * Generate all variations for a product based on attributes
     */
    public function generateVariations(Product $product, array $attributeIds): array
    {
        return DB::transaction(function () use ($product, $attributeIds) {
            // Delete existing variations
            $product->variations()->delete();

            // Get all attribute values for each attribute
            $attributeValuesMap = [];
            foreach ($attributeIds as $attributeId) {
                $attribute = Attribute::with('values')->findOrFail($attributeId);
                $attributeValuesMap[$attributeId] = $attribute->values->pluck('id')->toArray();
            }

            // Generate all combinations
            $combinations = $this->generateCombinations($attributeValuesMap);
            $createdVariations = [];

            foreach ($combinations as $combination) {
                $variationName = $this->buildVariationName($product, $combination);
                
                $variation = ProductVariation::create([
                    'product_id' => $product->id,
                    'sku' => $this->generateVariationSku($product, $combination),
                    'name' => $variationName,
                    'price' => $product->base_price,
                    'compare_at_price' => $product->compare_at_price,
                    'stock_quantity' => 0,
                    'is_active' => true,
                ]);

                // Link attribute values to variation
                foreach ($combination as $attributeId => $valueId) {
                    VariationAttributeValue::create([
                        'variation_id' => $variation->id,
                        'attribute_value_id' => $valueId,
                    ]);
                }

                $createdVariations[] = $variation->fresh(['attributeValues.attribute']);
            }

            return $createdVariations;
        });
    }

    /**
     * Generate combinations of attribute values (Cartesian product)
     */
    protected function generateCombinations(array $arrays): array
    {
        $result = [[]];
        
        foreach ($arrays as $property => $propertyValues) {
            $temp = [];
            foreach ($result as $resultItem) {
                foreach ($propertyValues as $propertyValue) {
                    $temp[] = array_merge($resultItem, [$property => $propertyValue]);
                }
            }
            $result = $temp;
        }

        return $result;
    }

    /**
     * Build variation name from attribute values
     */
    protected function buildVariationName(Product $product, array $combination): string
    {
        $valueNames = [];
        
        foreach ($combination as $attributeId => $valueId) {
            $attributeValue = AttributeValue::find($valueId);
            if ($attributeValue) {
                $valueNames[] = $attributeValue->value;
            }
        }

        return $product->name . ' - ' . implode(' / ', $valueNames);
    }

    /**
     * Generate SKU for variation
     */
    protected function generateVariationSku(Product $product, array $combination): string
    {
        $baseSku = Str::slug($product->sku ?? $product->name);
        $suffixParts = [];

        foreach ($combination as $attributeId => $valueId) {
            $attributeValue = AttributeValue::find($valueId);
            if ($attributeValue) {
                $suffixParts[] = strtoupper(substr($attributeValue->value, 0, 3));
            }
        }

        return $baseSku . '-' . implode('-', $suffixParts);
    }

    /**
     * Bulk update variations
     */
    public function bulkUpdateVariations(array $variationData): int
    {
        $updated = 0;

        foreach ($variationData as $data) {
            if (!isset($data['variation_id'])) {
                continue;
            }

            $variation = ProductVariation::find($data['variation_id']);
            if (!$variation) {
                continue;
            }

            $updateData = [];
            
            if (isset($data['price'])) {
                $updateData['price'] = $data['price'];
            }
            if (isset($data['compare_at_price'])) {
                $updateData['compare_at_price'] = $data['compare_at_price'];
            }
            if (isset($data['stock_quantity'])) {
                $updateData['stock_quantity'] = $data['stock_quantity'];
            }
            if (isset($data['is_active'])) {
                $updateData['is_active'] = $data['is_active'];
            }
            if (isset($data['sku'])) {
                $updateData['sku'] = $data['sku'];
            }

            if (!empty($updateData)) {
                $variation->update($updateData);
                $updated++;
            }
        }

        return $updated;
    }

    /**
     * Get product with variations and stock info
     */
    public function getProductWithVariations(int $productId): Product
    {
        return Product::with([
            'variations.attributeValues.attribute',
            'images',
            'category',
            'brand',
            'attributes.attribute',
        ])->findOrFail($productId);
    }

    /**
     * Find variation by selected attribute values
     */
    public function findVariationByAttributes(int $productId, array $attributeValueIds): ?ProductVariation
    {
        $variations = ProductVariation::where('product_id', $productId)
            ->where('is_active', true)
            ->get();

        foreach ($variations as $variation) {
            $variationAttributeIds = $variation->attributeValues()
                ->pluck('attribute_value_id')
                ->sort()
                ->values()
                ->toArray();

            $selectedIds = collect($attributeValueIds)->sort()->values()->toArray();

            if ($variationAttributeIds === $selectedIds) {
                return $variation;
            }
        }

        return null;
    }

    /**
     * Update product stock from variations
     */
    public function syncProductStock(Product $product): void
    {
        if ($product->track_variations) {
            $totalStock = $product->variations()->sum('stock_quantity');
            $product->update(['stock_quantity' => $totalStock]);
        }
    }

    /**
     * Get available variations for dropdown/filter
     */
    public function getAvailableVariationOptions(Product $product): array
    {
        $options = [];
        
        $attributes = Attribute::whereIn('id', $product->attributes()->pluck('attribute_id'))
            ->with(['values' => function ($query) {
                $query->orderBy('sort_order');
            }])
            ->get();

        foreach ($attributes as $attribute) {
            $availableValueIds = $product->variations()
                ->where('is_active', true)
                ->where('stock_quantity', '>', 0)
                ->with('attributeValues')
                ->get()
                ->flatMap(fn($v) => $v->attributeValues->pluck('attribute_value_id'))
                ->unique()
                ->toArray();

            $availableValues = $attribute->values->filter(fn($v) => in_array($v->id, $availableValueIds));

            $options[$attribute->id] = [
                'attribute' => $attribute,
                'values' => $availableValues->values(),
            ];
        }

        return $options;
    }

    /**
     * Calculate price range for product variations
     */
    public function getPriceRange(Product $product): array
    {
        $prices = $product->variations()
            ->where('is_active', true)
            ->pluck('price');

        if ($prices->isEmpty()) {
            return ['min' => $product->price, 'max' => $product->price];
        }

        return [
            'min' => $prices->min(),
            'max' => $prices->max(),
        ];
    }

    /**
     * Check if variation is in stock
     */
    public function isVariationAvailable(ProductVariation $variation): bool
    {
        return $variation->is_active && 
               $variation->stock_quantity > 0 &&
               $variation->product->isAvailable();
    }

    /**
     * Reserve variation stock temporarily (for checkout)
     */
    public function reserveStock(ProductVariation $variation, int $quantity, int $minutes = 15): bool
    {
        if ($variation->stock_quantity < $quantity) {
            return false;
        }

        // Store reservation in cache
        $reservationKey = "variation_reservation_{$variation->id}";
        $currentReservation = cache($reservationKey, 0);
        
        $availableStock = $variation->stock_quantity - $currentReservation;
        if ($availableStock < $quantity) {
            return false;
        }

        cache([$reservationKey => $currentReservation + $quantity], now()->addMinutes($minutes));
        
        return true;
    }

    /**
     * Release reserved stock
     */
    public function releaseReservedStock(ProductVariation $variation, int $quantity): void
    {
        $reservationKey = "variation_reservation_{$variation->id}";
        $currentReservation = cache($reservationKey, 0);
        cache([$reservationKey => max(0, $currentReservation - $quantity)]);
    }
}
