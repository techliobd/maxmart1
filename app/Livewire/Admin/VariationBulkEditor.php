<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\DB;

class VariationBulkEditor extends Component
{
    public array $variations = [];
    public int $productId;
    public bool $isEditing = false;

    protected $listeners = ['refreshComponent' => 'loadVariations'];

    public function mount(int $productId): void
    {
        $this->productId = $productId;
        $this->loadVariations();
    }

    public function loadVariations(): void
    {
        $this->variations = ProductVariation::where('product_id', $this->productId)
            ->with(['variationAttributeValues.attribute', 'variationAttributeValues.attributeValue'])
            ->orderBy('id')
            ->get()
            ->map(function ($variation) {
                return [
                    'id' => $variation->id,
                    'sku' => $variation->sku,
                    'price' => $variation->price,
                    'compare_price' => $variation->compare_price,
                    'stock' => $variation->stock,
                    'is_active' => $variation->is_active,
                    'attributes' => $variation->variationAttributeValues->map(function ($vav) {
                        return [
                            'attribute_name' => $vav->attribute->name ?? '',
                            'value_name' => $vav->attributeValue->value ?? '',
                        ];
                    }),
                ];
            })
            ->toArray();
    }

    public function toggleEdit(): void
    {
        $this->isEditing = !$this->isEditing;
        if (!$this->isEditing) {
            $this->loadVariations();
        }
    }

    public function updateField(int $index, string $field, $value): void
    {
        $this->variations[$index][$field] = $value;
    }

    public function saveAll(): void
    {
        DB::beginTransaction();
        try {
            foreach ($this->variations as $variationData) {
                $variation = ProductVariation::find($variationData['id']);
                if ($variation) {
                    $variation->update([
                        'sku' => $variationData['sku'],
                        'price' => $variationData['price'],
                        'compare_price' => $variationData['compare_price'] ?? 0,
                        'stock' => $variationData['stock'],
                        'is_active' => $variationData['is_active'] ?? true,
                    ]);
                }
            }

            DB::commit();
            $this->isEditing = false;
            $this->dispatch('showSuccess', message: 'Variations updated successfully!');
            $this->dispatch('variationsUpdated');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showError', message: 'Failed to update variations: ' . $e->getMessage());
        }
    }

    public function deleteVariation(int $variationId): void
    {
        try {
            $variation = ProductVariation::find($variationId);
            if ($variation) {
                $variation->delete();
                $this->loadVariations();
                $this->dispatch('showSuccess', message: 'Variation deleted successfully!');
                $this->dispatch('variationsUpdated');
            }
        } catch (\Exception $e) {
            $this->dispatch('showError', message: 'Failed to delete variation: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.variation-bulk-editor');
    }
}
