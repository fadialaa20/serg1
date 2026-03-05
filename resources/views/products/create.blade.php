@extends('layouts.app')

@section('title', 'إضافة شراء جديد')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">إضافة عملية شراء</h2>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">عودة</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('products.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">اسم المنتج</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">نوع الشراء</label>
                    <select name="purchase_method" class="form-select" required>
                        <option value="cash" @selected(old('purchase_method', 'cash') === 'cash')>كاش</option>
                        <option value="app" @selected(old('purchase_method') === 'app')>تطبيق</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">عدد الحبات</label>
                    <input type="number" name="quantity" min="1" class="form-control" value="{{ old('quantity') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">سعر الحبة بالجملة</label>
                    <input type="number" step="0.01" min="0" name="wholesale_price" class="form-control" value="{{ old('wholesale_price') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">تكلفة المواصلات</label>
                    <input type="number" step="0.01" min="0" name="transport_cost" class="form-control" value="{{ old('transport_cost', 0) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">مصاريف إضافية (اختياري)</label>
                    <input type="number" step="0.01" min="0" name="extra_cost" class="form-control" value="{{ old('extra_cost', 0) }}">
                </div>
                <div class="col-12">
                    <button class="btn btn-primary">حفظ عملية الشراء</button>
                </div>
            </form>
        </div>
    </div>
@endsection
