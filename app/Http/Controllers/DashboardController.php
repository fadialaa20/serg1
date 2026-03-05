<?php

namespace App\Http\Controllers;

use App\Models\Capital;
use App\Models\Product;
use App\Models\Sale;

class DashboardController extends Controller
{
    public function index()
    {
        $capital = Capital::latest()->first();
        $totalSales = (float) Sale::sum('total_sale');
        $totalProfit = (float) Sale::sum('profit');
        $productCount = Product::count();

        $remainingItems = Product::query()
            ->withSum('sales as sold_quantity', 'quantity_sold')
            ->get()
            ->sum(fn (Product $product) => $product->remainingQuantity());

        $capitalAmount = (float) ($capital?->capital_amount ?? 0);
        $previousProfit = (float) ($capital?->previous_profit ?? 0);
        $currentCapital = $capitalAmount + $previousProfit + $totalProfit;

        $recentSales = Sale::with('product')->latest()->take(5)->get();

        return view('dashboard.index', compact(
            'capitalAmount',
            'previousProfit',
            'totalSales',
            'totalProfit',
            'productCount',
            'remainingItems',
            'currentCapital',
            'recentSales'
        ));
    }
}
