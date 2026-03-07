@extends('layouts.app')

@section('title', 'سجل المبيعات')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h2 class="mb-0">سجل المبيعات</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('sales.pos') }}" class="btn btn-success">شاشة POS</a>
            <a href="{{ route('sales.create') }}" class="btn btn-primary">إضافة بيع جديد</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('sales.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">من تاريخ</label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">إلى تاريخ</label>
                    <input type="date" name="date_to" value="{{ $dateTo }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <button class="btn btn-outline-primary w-100">تطبيق الفلتر</button>
                </div>
            </form>
            <hr>
            <div class="d-flex flex-wrap gap-4">
                <div>إجمالي المبيعات المعروضة: <strong>{{ number_format($filteredTotalSales, 2) }} شيكل</strong></div>
                <div>إجمالي الأرباح المعروضة: <strong class="{{ $filteredTotalProfit >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($filteredTotalProfit, 2) }} شيكل</strong></div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                <tr>
                    <th>المنتج</th>
                    <th>نوع البيع</th>
                    <th>عدد الحبات</th>
                    <th>سعر البيع</th>
                    <th>إجمالي البيع</th>
                    <th>الربح</th>
                    <th>التاريخ</th>
                    <th>إجراء</th>
                </tr>
                </thead>
                <tbody>
                @forelse($sales as $sale)
                    <tr>
                        <td>{{ $sale->product?->name }}</td>
                        <td>{{ $sale->sale_method === 'app' ? 'تطبيق' : 'كاش' }}</td>
                        <td>{{ $sale->quantity_sold }}</td>
                        <td>{{ number_format($sale->sale_price, 2) }} شيكل</td>
                        <td>{{ number_format($sale->total_sale, 2) }} شيكل</td>
                        <td class="{{ $sale->profit >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($sale->profit, 2) }} شيكل</td>
                        <td>{{ $sale->created_at?->format('Y-m-d') }}</td>
                        <td>
                            <form action="{{ route('sales.destroy', $sale) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف عملية البيع؟')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">لا توجد عمليات بيع.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if ($sales->hasPages())
            <div class="card-footer bg-white">{{ $sales->links() }}</div>
        @endif
    </div>
@endsection
