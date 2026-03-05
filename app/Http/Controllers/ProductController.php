<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->toString();

        $products = Product::query()
            ->withSum('sales as sold_quantity', 'quantity_sold')
            ->withSum('sales as total_profit', 'profit')
            ->when($search, fn ($query) => $query->where('name', 'like', '%' . $search . '%'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('products.index', compact('products', 'search'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'wholesale_price' => ['required', 'numeric', 'min:0'],
            'transport_cost' => ['required', 'numeric', 'min:0'],
            'extra_cost' => ['nullable', 'numeric', 'min:0'],
        ], [], [
            'name' => 'اسم المنتج',
            'quantity' => 'عدد الحبات',
            'wholesale_price' => 'سعر الحبة بالجملة',
            'transport_cost' => 'تكلفة المواصلات',
            'extra_cost' => 'المصاريف الإضافية',
        ]);

        $extraCost = (float) ($validated['extra_cost'] ?? 0);
        $totalCost = ($validated['quantity'] * $validated['wholesale_price']) + $validated['transport_cost'] + $extraCost;

        Product::create([
            'name' => $validated['name'],
            'quantity' => $validated['quantity'],
            'wholesale_price' => $validated['wholesale_price'],
            'transport_cost' => $validated['transport_cost'],
            'extra_cost' => $extraCost,
            'total_cost' => $totalCost,
        ]);

        return redirect()->route('products.index')->with('success', 'تمت إضافة عملية الشراء بنجاح.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'wholesale_price' => ['required', 'numeric', 'min:0'],
            'transport_cost' => ['required', 'numeric', 'min:0'],
            'extra_cost' => ['nullable', 'numeric', 'min:0'],
        ]);

        $soldQuantity = $product->sales()->sum('quantity_sold');
        if ($validated['quantity'] < $soldQuantity) {
            return back()
                ->withInput()
                ->withErrors(['quantity' => 'لا يمكن أن يكون عدد الحبات أقل من الكمية المباعة مسبقًا (' . $soldQuantity . ').']);
        }

        $extraCost = (float) ($validated['extra_cost'] ?? 0);
        $totalCost = ($validated['quantity'] * $validated['wholesale_price']) + $validated['transport_cost'] + $extraCost;

        $product->update([
            'name' => $validated['name'],
            'quantity' => $validated['quantity'],
            'wholesale_price' => $validated['wholesale_price'],
            'transport_cost' => $validated['transport_cost'],
            'extra_cost' => $extraCost,
            'total_cost' => $totalCost,
        ]);

        return redirect()->route('products.index')->with('success', 'تم تعديل عملية الشراء بنجاح.');
    }
}
