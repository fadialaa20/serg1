<?php

namespace App\Http\Controllers;

use App\Models\Capital;
use App\Models\Product;
use App\Models\Sale;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $capital = Capital::query()->where('user_id', $userId)->latest()->first();
        $totalSales = (float) Sale::query()->where('user_id', $userId)->sum('total_sale');
        $totalProfit = (float) Sale::query()->where('user_id', $userId)->sum('profit');
        $productCount = Product::query()->where('user_id', $userId)->count();

        $remainingItems = Product::query()
            ->where('user_id', $userId)
            ->withSum('sales as sold_quantity', 'quantity_sold')
            ->get()
            ->sum(fn (Product $product) => $product->remainingQuantity());

        $capitalAmount = (float) ($capital?->capital_amount ?? 0);
        $previousProfit = (float) ($capital?->previous_profit ?? 0);
        $currentCapital = $capitalAmount + $previousProfit + $totalProfit;
        $openingCash = (float) ($capital?->cash_amount ?? 0);
        $openingApp = (float) ($capital?->app_amount ?? 0);

        $purchaseCash = (float) Product::query()
            ->where('user_id', $userId)
            ->where(function ($query) {
                $query->where('purchase_method', 'cash')->orWhereNull('purchase_method');
            })
            ->sum('total_cost');
        $purchaseApp = (float) Product::query()
            ->where('user_id', $userId)
            ->where('purchase_method', 'app')
            ->sum('total_cost');

        $salesCash = (float) Sale::query()
            ->where('user_id', $userId)
            ->where(function ($query) {
                $query->where('sale_method', 'cash')->orWhereNull('sale_method');
            })
            ->sum('total_sale');
        $salesApp = (float) Sale::query()
            ->where('user_id', $userId)
            ->where('sale_method', 'app')
            ->sum('total_sale');

        $currentCash = $openingCash - $purchaseCash + $salesCash;
        $currentApp = $openingApp - $purchaseApp + $salesApp;
        $walletTotal = $currentCash + $currentApp;

        $recentSales = Sale::query()
            ->where('user_id', $userId)
            ->with('product')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'capitalAmount',
            'previousProfit',
            'totalSales',
            'totalProfit',
            'productCount',
            'remainingItems',
            'currentCapital',
            'currentCash',
            'currentApp',
            'walletTotal',
            'recentSales'
        ));
    }
}
