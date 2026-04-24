<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminMaterialController extends Controller
{
    public function index()
    {
        $materials = Material::query()
            ->orderBy('material_name')
            ->orderBy('material_code')
            ->paginate(20);

        return view('admin.materials.index', compact('materials'));
    }

    public function create()
    {
        return view('admin.materials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'material_code' => ['required', 'string', 'max:255', 'unique:materials,material_code'],
            'material_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'unit_cost' => ['nullable', 'numeric', 'min:0'],
            'unit' => ['nullable', 'string', 'max:20'],
            'stock_quantity' => ['nullable', 'numeric', 'min:0'],
            'reorder_level' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        Material::create([
            'material_code' => $validated['material_code'],
            'material_name' => $validated['material_name'],
            'description' => $validated['description'] ?? null,
            'unit_cost' => $validated['unit_cost'] ?? 0,
            'unit' => $validated['unit'] ?? 'pcs',
            'stock_quantity' => $validated['stock_quantity'] ?? 0,
            'reorder_level' => $validated['reorder_level'] ?? 0,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.materials.index');
    }

    public function edit(Material $material)
    {
        return view('admin.materials.edit', compact('material'));
    }

    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'material_code' => ['required', 'string', 'max:255', Rule::unique('materials', 'material_code')->ignore($material->id)],
            'material_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'unit_cost' => ['nullable', 'numeric', 'min:0'],
            'unit' => ['nullable', 'string', 'max:20'],
            'stock_quantity' => ['nullable', 'numeric', 'min:0'],
            'reorder_level' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $material->update([
            'material_code' => $validated['material_code'],
            'material_name' => $validated['material_name'],
            'description' => $validated['description'] ?? null,
            'unit_cost' => $validated['unit_cost'] ?? 0,
            'unit' => $validated['unit'] ?? 'pcs',
            'stock_quantity' => $validated['stock_quantity'] ?? 0,
            'reorder_level' => $validated['reorder_level'] ?? 0,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.materials.index');
    }

    public function destroy(Material $material)
    {
        $hasBom = $material->billOfMaterials()->exists();
        if ($hasBom) {
            return back()->withErrors(['material' => 'Cannot delete: this material is used in a BOM.']);
        }

        $hasMovements = $material->movements()->exists();
        if ($hasMovements) {
            return back()->withErrors(['material' => 'Cannot delete: this material has stock movements history.']);
        }

        $material->delete();

        return redirect()->route('admin.materials.index');
    }
}
