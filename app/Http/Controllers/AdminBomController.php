<?php

namespace App\Http\Controllers;

use App\Models\BillOfMaterial;
use App\Models\Material;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminBomController extends Controller
{
    public function index()
    {
        $bomLines = BillOfMaterial::query()
            ->with(['product', 'material'])
            ->orderBy('product_id')
            ->orderBy('material_id')
            ->paginate(25);

        return view('admin.bom.index', compact('bomLines'));
    }

    public function create()
    {
        $products = Product::query()->orderBy('product_name')->get();
        $materials = Material::query()->orderBy('material_name')->get();

        return view('admin.bom.create', compact('products', 'materials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'material_id' => ['required', 'exists:materials,id'],
            'quantity_required' => ['required', 'numeric', 'gt:0'],
            'unit' => ['nullable', 'string', 'max:20'],
        ]);

        $exists = BillOfMaterial::query()
            ->where('product_id', $validated['product_id'])
            ->where('material_id', $validated['material_id'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['material_id' => 'This material is already in the BOM for the selected product.'])->withInput();
        }

        BillOfMaterial::create([
            'product_id' => $validated['product_id'],
            'material_id' => $validated['material_id'],
            'quantity_required' => $validated['quantity_required'],
            'unit' => $validated['unit'] ?? null,
        ]);

        return redirect()->route('admin.bom.index');
    }

    public function edit(BillOfMaterial $bom)
    {
        $products = Product::query()->orderBy('product_name')->get();
        $materials = Material::query()->orderBy('material_name')->get();

        return view('admin.bom.edit', compact('bom', 'products', 'materials'));
    }

    public function update(Request $request, BillOfMaterial $bom)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'material_id' => ['required', 'exists:materials,id'],
            'quantity_required' => ['required', 'numeric', 'gt:0'],
            'unit' => ['nullable', 'string', 'max:20'],
        ]);

        $exists = BillOfMaterial::query()
            ->where('product_id', $validated['product_id'])
            ->where('material_id', $validated['material_id'])
            ->where('id', '!=', $bom->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['material_id' => 'This material is already in the BOM for the selected product.'])->withInput();
        }

        $bom->update([
            'product_id' => $validated['product_id'],
            'material_id' => $validated['material_id'],
            'quantity_required' => $validated['quantity_required'],
            'unit' => $validated['unit'] ?? null,
        ]);

        return redirect()->route('admin.bom.index');
    }

    public function destroy(BillOfMaterial $bom)
    {
        $bom->delete();

        return redirect()->route('admin.bom.index');
    }
}
