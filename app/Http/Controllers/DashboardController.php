<?php

namespace App\Http\Controllers;

use App\Models\Capital;
use App\Models\Product;
use App\Models\Expense;
use App\Models\Sale;
use App\Models\Transfer;

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
        $openingCash = (float) ($capital?->cash_amount ?? 0);
        $openingBank = (float) ($capital?->bank_amount ?? 0);

        $purchaseCash = (float) Product::query()
            ->where('user_id', $userId)
            ->where(function ($query) {
                $query->where('purchase_method', 'cash')->orWhereNull('purchase_method');
            })
            ->sum('total_cost');
        $purchaseBank = (float) Product::query()
            ->where('user_id', $userId)
            ->where('purchase_method', 'bank')
            ->sum('total_cost');

        $salesCash = (float) Sale::query()
            ->where('user_id', $userId)
            ->where(function ($query) {
                $query->where('sale_method', 'cash')->orWhereNull('sale_method');
            })
            ->sum('total_sale');
        $salesBank = (float) Sale::query()
            ->where('user_id', $userId)
            ->where('sale_method', 'bank')
            ->sum('total_sale');

        $expensesCash = Expense::where('user_id', $userId)->where('wallet_type', 'cash')->sum('amount');
        $expensesBank = Expense::where('user_id', $userId)->where('wallet_type', 'bank')->sum('amount');

        $transfersOutCash = Transfer::where('user_id', $userId)->where('from_wallet', 'cash')->sum('amount');
        $transfersOutBank = Transfer::where('user_id', $userId)->where('from_wallet', 'bank')->sum('amount');

        $currentCash = $openingCash - $purchaseCash - $expensesCash - $transfersOutCash + $salesCash + $transfersOutBank;
        $currentBank = $openingBank - $purchaseBank - $expensesBank - $transfersOutBank + $salesBank + $transfersOutCash;
        $walletTotal = $currentCash + $currentBank;
        $currentCapital = $walletTotal;

        $recentSales = Sale::query()
            ->where('user_id', $userId)
            ->with('product')
            ->latest()
            ->take(5)
            ->get();

        $recentExpenses = Expense::where('user_id', $userId)->latest()->take(5)->get();
        $recentTransfers = Transfer::where('user_id', $userId)->latest()->take(5)->get();

        return view('dashboard.index', compact(
            'capitalAmount',
            'previousProfit',
            'totalSales',
            'totalProfit',
            'productCount',
            'remainingItems',
            'currentCapital',
            'currentCash',
            'currentBank',
            'walletTotal',
            'recentSales',
            'recentExpenses',
            'recentTransfers'
        ));
    }
}

