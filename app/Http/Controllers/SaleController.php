<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function index(Request $request): View
    {
        $dateFrom = $request->string('date_from')->toString();
        $dateTo = $request->string('date_to')->toString();
        $userId = auth()->id();

        $query = Sale::query()
            ->where('user_id', $userId)
            ->with('product')
            ->when($dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('created_at', '<=', $dateTo));

        $filteredTotalSales = (float) (clone $query)->sum('total_sale');
        $filteredTotalProfit = (float) (clone $query)->sum('profit');

        $sales = $query->latest('created_at')->paginate(12)->withQueryString();

        return view('sales.index', compact('sales', 'dateFrom', 'dateTo', 'filteredTotalSales', 'filteredTotalProfit'));
    }

    public function create(): View
    {
        $products = Product::query()
            ->where('user_id', auth()->id())
            ->withSum('sales as sold_quantity', 'quantity_sold')
            ->latest()
            ->get();

        return view('sales.create', compact('products'));
    }

    public function pos(): View
    {
        $products = Product::query()
            ->where('user_id', auth()->id())
            ->withSum('sales as sold_quantity', 'quantity_sold')
            ->withMax('sales as last_sale_price', 'sale_price')
            ->latest()
            ->get();

        $recentSales = Sale::query()
            ->where('user_id', auth()->id())
            ->with('product')
            ->latest('created_at')
            ->limit(10)
            ->get();

        return view('sales.pos', compact('products', 'recentSales'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateSaleRequest($request, true);
        $this->createSale($validated, Carbon::parse($validated['sale_date'])->startOfDay());

        return redirect()->route('sales.index')->with('success', 'تم تسجيل عملية البيع بنجاح.');
    }

    public function posStore(Request $request): RedirectResponse
    {
        $validated = $this->validateSaleRequest($request, false);
        $this->createSale($validated, now());

        return redirect()->route('sales.pos')->with('success', 'تم حفظ عملية البيع من شاشة POS بنجاح.');
    }

    public function destroy(Sale $sale): RedirectResponse
    {
        abort_if($sale->user_id !== auth()->id(), 404);

        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'تم حذف عملية البيع بنجاح.');
    }

    private function validateSaleRequest(Request $request, bool $requireDate): array
    {
        $rules = [
            'product_id' => ['required', 'exists:products,id'],
            'sale_method' => ['required', 'in:cash,app'],
            'quantity_sold' => ['required', 'integer', 'min:1'],
            'sale_price' => ['required', 'numeric', 'min:0'],
        ];

        if ($requireDate) {
            $rules['sale_date'] = ['required', 'date'];
        }

        return $request->validate($rules, [], [
            'product_id' => 'المنتج',
            'sale_method' => 'نوع البيع',
            'quantity_sold' => 'عدد الحبات المباعة',
            'sale_price' => 'سعر البيع للحبة',
            'sale_date' => 'تاريخ البيع',
        ]);
    }

    private function createSale(array $validated, Carbon $createdAt): void
    {
        $product = Product::query()
            ->where('user_id', auth()->id())
            ->withSum('sales as sold_quantity', 'quantity_sold')
            ->findOrFail($validated['product_id']);

        $remaining = $product->remainingQuantity();
        if ($validated['quantity_sold'] > $remaining) {
            throw ValidationException::withMessages([
                'quantity_sold' => 'الكمية المباعة أكبر من المتبقي. المتاح حاليًا: ' . $remaining . ' حبة.',
            ]);
        }

        $costPerItem = $product->costPerItem();
        $totalSale = $validated['quantity_sold'] * $validated['sale_price'];
        $profit = $totalSale - ($costPerItem * $validated['quantity_sold']);

        Sale::query()->create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'sale_method' => $validated['sale_method'],
            'quantity_sold' => $validated['quantity_sold'],
            'sale_price' => $validated['sale_price'],
            'total_sale' => $totalSale,
            'profit' => $profit,
            'created_at' => $createdAt,
            'updated_at' => now(),
        ]);
    }
}
