<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Support\Facades\DB;

class MenuBuilder extends Component
{
    public ?Menu $menu = null;
    public array $menuItems = [];
    public array $expandedItems = [];
    public ?int $editingItemId = null;
    public string $newItemLabel = '';
    public string $newItemUrl = '';
    public ?int $newItemParentId = null;

    protected $listeners = ['refreshComponent' => 'loadMenuItems'];

    public function mount(?int $menuId = null): void
    {
        if ($menuId) {
            $this->menu = Menu::find($menuId);
            $this->loadMenuItems();
        }
    }

    public function loadMenuItems(): void
    {
        if (!$this->menu) {
            return;
        }

        $items = MenuItem::where('menu_id', $this->menu->id)
            ->orderBy('order')
            ->get()
            ->toArray();

        $this->menuItems = $this->buildTree($items);
    }

    protected function buildTree(array $items, int $parentId = 0): array
    {
        $branch = [];
        foreach ($items as $item) {
            if ($item['parent_id'] == $parentId) {
                $children = $this->buildTree($items, $item['id']);
                if (!empty($children)) {
                    $item['children'] = $children;
                }
                $branch[] = $item;
            }
        }
        return $branch;
    }

    public function toggleExpand(int $itemId): void
    {
        if (in_array($itemId, $this->expandedItems)) {
            $this->expandedItems = array_diff($this->expandedItems, [$itemId]);
        } else {
            $this->expandedItems[] = $itemId;
        }
    }

    public function startEditing(int $itemId): void
    {
        $this->editingItemId = $itemId;
        $item = MenuItem::find($itemId);
        if ($item) {
            $this->newItemLabel = $item->label;
            $this->newItemUrl = $item->url;
        }
    }

    public function cancelEditing(): void
    {
        $this->editingItemId = null;
        $this->newItemLabel = '';
        $this->newItemUrl = '';
        $this->newItemParentId = null;
    }

    public function saveItem(): void
    {
        $this->validate([
            'newItemLabel' => 'required|string|max:100',
            'newItemUrl' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            if ($this->editingItemId) {
                $item = MenuItem::find($this->editingItemId);
                if ($item) {
                    $item->update([
                        'label' => $this->newItemLabel,
                        'url' => $this->newItemUrl,
                    ]);
                }
            } else {
                MenuItem::create([
                    'menu_id' => $this->menu->id,
                    'label' => $this->newItemLabel,
                    'url' => $this->newItemUrl,
                    'parent_id' => $this->newItemParentId,
                    'order' => MenuItem::where('menu_id', $this->menu->id)->max('order') + 1,
                ]);
            }

            DB::commit();
            $this->cancelEditing();
            $this->loadMenuItems();
            $this->dispatch('showSuccess', message: 'Menu item saved successfully!');
            $this->dispatch('menuUpdated');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showError', message: 'Failed to save menu item: ' . $e->getMessage());
        }
    }

    public function deleteItem(int $itemId): void
    {
        DB::beginTransaction();
        try {
            $this->deleteItemRecursive($itemId);
            DB::commit();
            $this->loadMenuItems();
            $this->dispatch('showSuccess', message: 'Menu item deleted successfully!');
            $this->dispatch('menuUpdated');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showError', message: 'Failed to delete menu item: ' . $e->getMessage());
        }
    }

    protected function deleteItemRecursive(int $itemId): void
    {
        $children = MenuItem::where('parent_id', $itemId)->get();
        foreach ($children as $child) {
            $this->deleteItemRecursive($child->id);
        }
        MenuItem::find($itemId)?->delete();
    }

    public function moveUp(int $itemId): void
    {
        $item = MenuItem::find($itemId);
        if (!$item) {
            return;
        }

        $sibling = MenuItem::where('menu_id', $item->menu_id)
            ->where('parent_id', $item->parent_id)
            ->where('order', '<', $item->order)
            ->orderBy('order', 'desc')
            ->first();

        if ($sibling) {
            DB::table('menu_items')->where('id', $item->id)->update(['order' => $sibling->order]);
            DB::table('menu_items')->where('id', $sibling->id)->update(['order' => $item->order]);
            $this->loadMenuItems();
        }
    }

    public function moveDown(int $itemId): void
    {
        $item = MenuItem::find($itemId);
        if (!$item) {
            return;
        }

        $sibling = MenuItem::where('menu_id', $item->menu_id)
            ->where('parent_id', $item->parent_id)
            ->where('order', '>', $item->order)
            ->orderBy('order', 'asc')
            ->first();

        if ($sibling) {
            DB::table('menu_items')->where('id', $item->id)->update(['order' => $sibling->order]);
            DB::table('menu_items')->where('id', $sibling->id)->update(['order' => $item->order]);
            $this->loadMenuItems();
        }
    }

    public function render()
    {
        return view('livewire.admin.menu-builder');
    }
}
