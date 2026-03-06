<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ReportController extends Controller
{
    public function index()
    {
        $profitsByProduct = Product::query()
            ->where('user_id', auth()->id())
            ->withSum('sales as total_profit', 'profit')
            ->withSum('sales as sold_quantity', 'quantity_sold')
            ->orderBy('name')
            ->get();

        $totalReportProfit = (float) $profitsByProduct->sum(fn (Product $product) => (float) ($product->total_profit ?? 0));

        return view('reports.index', compact('profitsByProduct', 'totalReportProfit'));
    }
}
