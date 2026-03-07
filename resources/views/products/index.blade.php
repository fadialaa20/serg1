@extends('layouts.app')

@section('title', 'المشتريات')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h2 class="mb-0">المشتريات</h2>
        <a href="{{ route('products.create') }}" class="btn btn-primary">إضافة عملية شراء</a>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('products.index') }}" class="row g-2 align-items-end">
                <div class="col-md-8">
                    <label class="form-label">بحث عن منتج</label>
                    <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="اكتب اسم المنتج...">
                </div>
                <div class="col-md-4">
                    <button class="btn btn-outline-primary w-100">بحث</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                <tr>
                    <th>اسم المنتج</th>
                    <th>نوع الشراء</th>
                    <th>عدد الحبات</th>
                    <th>المباع</th>
                    <th>المتبقي</th>
                    <th>التكلفة الكلية</th>
                    <th>الربح الحالي</th>
                    <th>تاريخ الشراء</th>
                    <th>إجراء</th>
                </tr>
                </thead>
                <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->purchase_method === 'app' ? 'تطبيق' : 'كاش' }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td>{{ $product->soldQuantity() }}</td>
                        <td>{{ $product->remainingQuantity() }}</td>
                        <td>{{ number_format($product->total_cost, 2) }} شيكل</td>
                        <td class="{{ ($product->total_profit ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($product->total_profit ?? 0, 2) }} شيكل
                        </td>
                        <td>{{ $product->created_at?->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-secondary">تعديل</a>
                            @if($product->remainingQuantity() === 0)
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('سيتم حذف المنتج من قائمة المشتريات فقط مع بقاء جميع أرقام المبيعات. متابعة؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">حذف</button>
                                </form>
                            @else
                                <button class="btn btn-sm btn-outline-danger" disabled title="يمكن حذف المنتج فقط بعد نفاد الكمية">حذف</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center py-4 text-muted">لا توجد مشتريات حتى الآن.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if ($products->hasPages())
            <div class="card-footer bg-white">{{ $products->links() }}</div>
        @endif
    </div>
@endsection
