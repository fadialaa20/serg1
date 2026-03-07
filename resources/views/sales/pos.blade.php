@extends('layouts.app')

@section('title', 'شاشة POS')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h2 class="mb-0">شاشة البيع السريع (POS)</h2>
        <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">سجل المبيعات</a>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('sales.pos.store') }}" class="row g-3" id="pos-form">
                        @csrf
                        <div class="col-12">
                            <label class="form-label">المنتج</label>
                            <select name="product_id" id="product_id" class="form-select" required>
                                <option value="">اختر منتجًا</option>
                                @foreach($products as $product)
                                    <option
                                        value="{{ $product->id }}"
                                        data-remaining="{{ $product->remainingQuantity() }}"
                                        data-last-price="{{ (float) ($product->last_sale_price ?? 0) }}"
                                        data-cost="{{ number_format($product->costPerItem(), 2, '.', '') }}"
                                        @selected(old('product_id') == $product->id)
                                    >
                                        {{ $product->name }} (المتبقي: {{ $product->remainingQuantity() }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-6">
                            <label class="form-label">الكمية</label>
                            <input type="number" min="1" name="quantity_sold" id="quantity_sold" value="{{ old('quantity_sold', 1) }}" class="form-control" required>
                        </div>

                        <div class="col-6">
                            <label class="form-label">نوع البيع</label>
                            <select name="sale_method" class="form-select" required>
                                <option value="cash" @selected(old('sale_method') === 'cash')>كاش</option>
                                <option value="app" @selected(old('sale_method') === 'app')>تطبيق</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">سعر البيع للحبة</label>
                            <input type="number" step="0.01" min="0" name="sale_price" id="sale_price" value="{{ old('sale_price') }}" class="form-control" required>
                        </div>

                        <div class="col-12">
                            <div class="p-3 rounded bg-light border">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">المتبقي</span>
                                    <strong id="remaining-info">-</strong>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span class="text-muted">الإجمالي</span>
                                    <strong id="total-info">0.00</strong>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span class="text-muted">الربح التقريبي</span>
                                    <strong id="profit-info">0.00</strong>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <button class="btn btn-success w-100">تسجيل البيع الآن</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">آخر 10 عمليات بيع</h5>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                            <tr>
                                <th>المنتج</th>
                                <th>الكمية</th>
                                <th>الإجمالي</th>
                                <th>التاريخ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($recentSales as $sale)
                                <tr>
                                    <td>{{ $sale->product?->name }}</td>
                                    <td>{{ $sale->quantity_sold }}</td>
                                    <td>{{ number_format($sale->total_sale, 2) }}</td>
                                    <td>{{ $sale->created_at?->format('H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">لا توجد مبيعات بعد.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const productSelect = document.getElementById('product_id');
        const quantityInput = document.getElementById('quantity_sold');
        const priceInput = document.getElementById('sale_price');
        const remainingInfo = document.getElementById('remaining-info');
        const totalInfo = document.getElementById('total-info');
        const profitInfo = document.getElementById('profit-info');

        function selectedOption() {
            return productSelect.options[productSelect.selectedIndex] || null;
        }

        function recalc() {
            const option = selectedOption();
            const qty = Number(quantityInput.value || 0);
            const price = Number(priceInput.value || 0);

            const remaining = option ? Number(option.dataset.remaining || 0) : 0;
            const cost = option ? Number(option.dataset.cost || 0) : 0;

            const total = qty * price;
            const profit = total - (qty * cost);

            remainingInfo.textContent = option ? remaining : '-';
            totalInfo.textContent = total.toFixed(2);
            profitInfo.textContent = profit.toFixed(2);
        }

        productSelect.addEventListener('change', () => {
            const option = selectedOption();
            if (option && !priceInput.value) {
                const lastPrice = Number(option.dataset.lastPrice || 0);
                if (lastPrice > 0) {
                    priceInput.value = lastPrice.toFixed(2);
                }
            }
            recalc();
        });

        quantityInput.addEventListener('input', recalc);
        priceInput.addEventListener('input', recalc);

        recalc();
    </script>
@endsection
