<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::withCount('items')->orderBy('name')->get();

        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menus.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:menus,name',
            'location' => 'required|in:header,footer,mobile',
        ]);

        Menu::create($request->only(['name', 'location']));

        return redirect()->route('admin.menus.index')->with('success', 'Menu created successfully.');
    }

    public function show(Menu $menu)
    {
        $menu->load('items.parent');

        return view('admin.menus.show', compact('menu'));
    }

    public function edit(Menu $menu)
    {
        $menu->load('items');

        return view('admin.menus.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:menus,name,' . $menu->id,
            'location' => 'required|in:header,footer,mobile',
        ]);

        $menu->update($request->only(['name', 'location']));

        return redirect()->route('admin.menus.index')->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        $menu->items()->delete();
        $menu->delete();

        return redirect()->route('admin.menus.index')->with('success', 'Menu deleted successfully.');
    }

    public function build(Menu $menu)
    {
        $menu->load('items');

        return view('admin.menus.build', compact('menu'));
    }

    public function addItem(Request $request, Menu $menu)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:500',
            'parent_id' => 'nullable|exists:menu_items,id',
            'sort_order' => 'integer',
        ]);

        $maxOrder = MenuItem::where('menu_id', $menu->id)->max('sort_order') ?? 0;

        MenuItem::create([
            'menu_id' => $menu->id,
            'parent_id' => $request->parent_id,
            'label' => $request->label,
            'url' => $request->url,
            'sort_order' => $request->sort_order ?? ($maxOrder + 1),
        ]);

        return back()->with('success', 'Menu item added successfully.');
    }

    public function updateItem(Request $request, MenuItem $item)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:500',
            'parent_id' => 'nullable|exists:menu_items,id',
        ]);

        $item->update($request->only(['label', 'url', 'parent_id']));

        return back()->with('success', 'Menu item updated successfully.');
    }

    public function deleteItem(MenuItem $item)
    {
        $menu = $item->menu;
        
        // Delete children first
        $item->children()->delete();
        $item->delete();

        return redirect()->route('admin.menus.build', $menu)->with('success', 'Menu item deleted successfully.');
    }

    public function reorderItems(Request $request, Menu $menu)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:menu_items,id',
            'items.*.sort_order' => 'required|integer',
            'items.*.parent_id' => 'nullable|exists:menu_items,id',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->items as $item) {
                MenuItem::where('id', $item['id'])->update([
                    'sort_order' => $item['sort_order'],
                    'parent_id' => $item['parent_id'] ?? null,
                ]);
            }

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
