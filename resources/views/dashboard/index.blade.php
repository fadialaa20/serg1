@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">لوحة التحكم</h2>
        <span class="text-muted">ملخص أداء المتجر</span>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted">رأس المال الحالي (الفعلي)</div>
                    <div class="value text-primary">{{ number_format($currentCapital, 2) }} شيكل</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted">الأرباح السابقة</div>
                    <div class="value text-success">{{ number_format($previousProfit, 2) }} شيكل</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted">إجمالي المبيعات</div>
                    <div class="value text-info">{{ number_format($totalSales, 2) }} شيكل</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted">إجمالي الأرباح</div>
                    <div class="value text-success">{{ number_format($totalProfit, 2) }} شيكل</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted">الرصيد الحالي كاش</div>
                    <div class="value text-primary">{{ number_format($currentCash, 2) }} شيكل</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted">الرصيد الحالي تطبيق</div>
                    <div class="value text-info">{{ number_format($currentApp, 2) }} شيكل</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted">إجمالي (كاش + تطبيق)</div>
                    <div class="value">{{ number_format($walletTotal, 2) }} شيكل</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted">رأس المال المدخل</div>
                    <div class="value">{{ number_format($capitalAmount, 2) }} شيكل</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted">عدد المنتجات</div>
                    <div class="value">{{ $productCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted">عدد الحبات المتبقية</div>
                    <div class="value">{{ $remainingItems }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">آخر عمليات البيع</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>الكمية</th>
                        <th>إجمالي البيع</th>
                        <th>الربح</th>
                        <th>التاريخ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($recentSales as $sale)
                        <tr>
                            <td>{{ $sale->product?->name }}</td>
                            <td>{{ $sale->quantity_sold }}</td>
                            <td>{{ number_format($sale->total_sale, 2) }} شيكل</td>
                            <td class="{{ $sale->profit >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($sale->profit, 2) }} شيكل</td>
                            <td>{{ $sale->created_at?->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">لا توجد مبيعات بعد.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
