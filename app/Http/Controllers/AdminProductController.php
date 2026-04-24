<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::query()
            ->orderBy('product_name')
            ->paginate(18);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_code' => ['required', 'string', 'max:255', 'unique:products,product_code'],
            'product_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'unit' => ['nullable', 'string', 'max:50'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        Product::create([
            'product_code' => $validated['product_code'],
            'product_name' => $validated['product_name'],
            'description' => $validated['description'] ?? null,
            'unit_price' => $validated['unit_price'] ?? 0,
            'unit' => $validated['unit'] ?? 'pcs',
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.products.index');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_code' => ['required', 'string', 'max:255', Rule::unique('products', 'product_code')->ignore($product->id)],
            'product_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'unit' => ['nullable', 'string', 'max:50'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $product->update([
            'product_code' => $validated['product_code'],
            'product_name' => $validated['product_name'],
            'description' => $validated['description'] ?? null,
            'unit_price' => $validated['unit_price'] ?? 0,
            'unit' => $validated['unit'] ?? 'pcs',
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.products.index');
    }
}
