<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::withCount('values')->orderBy('name')->get();

        return view('admin.attributes.index', compact('attributes'));
    }

    public function create()
    {
        return view('admin.attributes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:attributes,name',
            'type' => 'required|in:text,select,color,multiselect',
        ]);

        $attribute = Attribute::create($request->only(['name', 'type']));

        return redirect()->route('admin.attributes.edit', $attribute)->with('success', 'Attribute created successfully.');
    }

    public function show(Attribute $attribute)
    {
        $attribute->load('values');

        return view('admin.attributes.show', compact('attribute'));
    }

    public function edit(Attribute $attribute)
    {
        $attribute->load('values');

        return view('admin.attributes.edit', compact('attribute'));
    }

    public function update(Request $request, Attribute $attribute)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:attributes,name,' . $attribute->id,
            'type' => 'required|in:text,select,color,multiselect',
        ]);

        $attribute->update($request->only(['name', 'type']));

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute updated successfully.');
    }

    public function destroy(Attribute $attribute)
    {
        if ($attribute->values()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete attribute with associated values.']);
        }

        // Check if used in any products
        if ($attribute->products()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete attribute assigned to products.']);
        }

        $attribute->delete();

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute deleted successfully.');
    }

    public function addValue(Request $request, Attribute $attribute)
    {
        $request->validate([
            'value' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:50',
        ]);

        $existingValue = AttributeValue::where('attribute_id', $attribute->id)
            ->where('value', $request->value)
            ->first();

        if ($existingValue) {
            return back()->withErrors(['error' => 'This value already exists for this attribute.']);
        }

        AttributeValue::create([
            'attribute_id' => $attribute->id,
            'value' => $request->value,
            'color_code' => $request->color_code,
        ]);

        return back()->with('success', 'Attribute value added successfully.');
    }

    public function updateValue(Request $request, AttributeValue $value)
    {
        $request->validate([
            'value' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:50',
        ]);

        $value->update($request->only(['value', 'color_code']));

        return back()->with('success', 'Attribute value updated successfully.');
    }

    public function deleteValue(AttributeValue $value)
    {
        $attribute = $value->attribute;

        // Check if used in any product variations
        if ($value->productVariations()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete value used in product variations.']);
        }

        $value->delete();

        return redirect()->route('admin.attributes.edit', $attribute)->with('success', 'Attribute value deleted successfully.');
    }
}
