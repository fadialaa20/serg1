@extends('layouts.app')

@section('title', 'تقارير الأرباح')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">تقارير الأرباح حسب المنتج</h2>
        <div class="badge text-bg-success p-2 fs-6">إجمالي الربح: {{ number_format($totalReportProfit, 2) }} شيكل</div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                <tr>
                    <th>المنتج</th>
                    <th>الكمية المباعة</th>
                    <th>الكمية المتبقية</th>
                    <th>إجمالي الربح</th>
                </tr>
                </thead>
                <tbody>
                @forelse($profitsByProduct as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ (int) ($product->sold_quantity ?? 0) }}</td>
                        <td>{{ $product->remainingQuantity() }}</td>
                        <td class="{{ ($product->total_profit ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($product->total_profit ?? 0, 2) }} شيكل
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center py-4 text-muted">لا توجد بيانات تقارير حتى الآن.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
