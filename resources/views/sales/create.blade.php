@extends('layouts.app')

@section('title', 'إضافة عملية بيع')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">إضافة عملية بيع</h2>
        <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">عودة</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('sales.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">اختر المنتج</label>
                    <select name="product_id" class="form-select" required>
                        <option value="">-- اختر --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                                {{ $product->name }} (المتبقي: {{ $product->remainingQuantity() }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">نوع البيع</label>
                    <select name="sale_method" class="form-select" required>
                        <option value="cash" @selected(old('sale_method', 'cash') === 'cash')>كاش</option>
                        <option value="app" @selected(old('sale_method') === 'app')>تطبيق</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">عدد الحبات المباعة</label>
                    <input type="number" min="1" name="quantity_sold" class="form-control" value="{{ old('quantity_sold') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">سعر البيع للحبة</label>
                    <input type="number" step="0.01" min="0" name="sale_price" class="form-control" value="{{ old('sale_price') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">مواصلات البيع</label>
                    <input type="number" step="0.01" min="0" name="transport_cost" class="form-control" value="{{ old('transport_cost', 0) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">تاريخ البيع</label>
                    <input type="date" name="sale_date" class="form-control" value="{{ old('sale_date', now()->format('Y-m-d')) }}" required>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary">تسجيل عملية البيع</button>
                </div>
            </form>
        </div>
    </div>
@endsection
