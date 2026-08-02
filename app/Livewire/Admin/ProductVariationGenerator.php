<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductVariation;
use App\Models\VariationAttributeValue;
use Illuminate\Support\Facades\DB;

class ProductVariationGenerator extends Component
{
    public Product $product;
    public array $selectedAttributes = [];
    public array $attributeValues = [];
    public array $generatedVariations = [];
    public bool $showPreview = false;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount(Product $product): void
    {
        $this->product = $product;
        $this->loadAttributeValues();
    }

    public function loadAttributeValues(): void
    {
        $attributes = Attribute::with('values')->get();
        foreach ($attributes as $attribute) {
            $this->attributeValues[$attribute->id] = $attribute->values->pluck('id', 'value')->toArray();
        }
    }

    public function toggleAttributeValue(int $attributeId, int $valueId): void
    {
        if (!isset($this->selectedAttributes[$attributeId])) {
            $this->selectedAttributes[$attributeId] = [];
        }

        $index = array_search($valueId, $this->selectedAttributes[$attributeId]);
        if ($index !== false) {
            unset($this->selectedAttributes[$attributeId][$index]);
        } else {
            $this->selectedAttributes[$attributeId][] = $valueId;
        }

        $this->generatePreview();
    }

    public function generatePreview(): void
    {
        $this->showPreview = true;
        $this->generatedVariations = [];

        $combinations = $this->generateCombinations($this->selectedAttributes);

        foreach ($combinations as $combination) {
            $variationData = [
                'attribute_combination' => $combination,
                'sku' => '',
                'price' => $this->product->price,
                'compare_price' => $this->product->compare_price ?? 0,
                'stock' => 0,
                'exists' => false,
            ];

            // Check if variation already exists
            $existingVariation = $this->findExistingVariation($combination);
            if ($existingVariation) {
                $variationData['exists'] = true;
                $variationData['id'] = $existingVariation->id;
                $variationData['sku'] = $existingVariation->sku;
                $variationData['price'] = $existingVariation->price;
                $variationData['compare_price'] = $existingVariation->compare_price;
                $variationData['stock'] = $existingVariation->stock;
            }

            $this->generatedVariations[] = $variationData;
        }
    }

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

    protected function findExistingVariation(array $combination): ?ProductVariation
    {
        $query = ProductVariation::where('product_id', $this->product->id);
        
        foreach ($combination as $attributeId => $valueId) {
            $query->whereHas('variationAttributeValues', function ($q) use ($attributeId, $valueId) {
                $q->where('attribute_id', $attributeId)
                  ->where('attribute_value_id', $valueId);
            });
        }

        return $query->first();
    }

    public function saveVariations(): void
    {
        DB::beginTransaction();
        try {
            foreach ($this->generatedVariations as $variationData) {
                if (!$variationData['exists']) {
                    $variation = new ProductVariation();
                    $variation->product_id = $this->product->id;
                    $variation->sku = $variationData['sku'] ?: $this->generateSku($variationData['attribute_combination']);
                    $variation->price = $variationData['price'];
                    $variation->compare_price = $variationData['compare_price'];
                    $variation->stock = $variationData['stock'];
                    $variation->save();

                    // Save attribute values
                    foreach ($variationData['attribute_combination'] as $attributeId => $valueId) {
                        VariationAttributeValue::create([
                            'product_variation_id' => $variation->id,
                            'attribute_id' => $attributeId,
                            'attribute_value_id' => $valueId,
                        ]);
                    }
                }
            }

            DB::commit();
            $this->dispatch('showSuccess', message: 'Variations generated successfully!');
            $this->dispatch('variationsGenerated');
            $this->showPreview = false;
            $this->selectedAttributes = [];
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showError', message: 'Failed to generate variations: ' . $e->getMessage());
        }
    }

    protected function generateSku(array $combination): string
    {
        $skuParts = [$this->product->sku ?? 'PRD'];
        
        foreach ($combination as $attributeId => $valueId) {
            $attribute = Attribute::find($attributeId);
            $value = AttributeValue::find($valueId);
            if ($attribute && $value) {
                $skuParts[] = strtoupper(substr($attribute->name, 0, 3)) . substr($value->value, 0, 2);
            }
        }

        return implode('-', $skuParts);
    }

    public function render()
    {
        return view('livewire.admin.product-variation-generator');
    }
}
