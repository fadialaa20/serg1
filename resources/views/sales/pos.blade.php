@extends('layouts.app')

@section('title', 'شاشة POS')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h2 class="mb-0">شاشة البيع السريع (POS)</h2>
        <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">سجل المبيعات</a>
    </div>

    <div class="alert alert-info small">
        اختصارات الكيبورد: <strong>F2</strong> اختيار المنتج، <strong>F4</strong> الكمية، <strong>F6</strong> السعر، <strong>Enter</strong> إضافة للسلة، <strong>F9</strong> حفظ الفاتورة، <strong>Esc</strong> مسح الإدخال الحالي.
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">المنتج</label>
                            <select id="product_id" class="form-select">
                                <option value="">اختر منتجًا</option>
                                @foreach($products as $product)
                                    <option
                                        value="{{ $product->id }}"
                                        data-name="{{ $product->name }}"
                                        data-remaining="{{ $product->remainingQuantity() }}"
                                        data-last-price="{{ (float) ($product->last_sale_price ?? 0) }}"
                                        data-cost="{{ number_format($product->costPerItem(), 2, '.', '') }}"
                                    >
                                        {{ $product->name }} (المتبقي: {{ $product->remainingQuantity() }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-6">
                            <label class="form-label">الكمية</label>
                            <input type="number" min="1" id="quantity_sold" value="1" class="form-control">
                        </div>

                        <div class="col-6">
                            <label class="form-label">سعر البيع</label>
                            <input type="number" step="0.01" min="0" id="sale_price" class="form-control">
                        </div>

                        <div class="col-12">
                            <button type="button" id="add-item-btn" class="btn btn-primary w-100">إضافة للسلة</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-7">
            <form method="POST" action="{{ route('sales.pos.store') }}" id="pos-form">
                @csrf
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label">نوع البيع</label>
                                <select name="sale_method" class="form-select" required>
                                    <option value="cash" @selected(old('sale_method') === 'cash')>كاش</option>
                                    <option value="app" @selected(old('sale_method') === 'app')>تطبيق</option>
                                </select>
                            </div>
                            <div class="col-md-7 d-flex align-items-end justify-content-md-end">
                                <button type="submit" class="btn btn-success">حفظ الفاتورة (F9)</button>
                            </div>
                        </div>

                        <hr>

                        <div class="table-responsive">
                            <table class="table align-middle mb-0" id="cart-table">
                                <thead>
                                <tr>
                                    <th>المنتج</th>
                                    <th>الكمية</th>
                                    <th>السعر</th>
                                    <th>الإجمالي</th>
                                    <th>إجراء</th>
                                </tr>
                                </thead>
                                <tbody id="cart-body">
                                    <tr id="empty-row">
                                        <td colspan="5" class="text-center text-muted">السلة فارغة</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="p-3 rounded bg-light border mt-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">إجمالي الفاتورة</span>
                                <strong id="grand-total">0.00</strong>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <span class="text-muted">الربح التقريبي</span>
                                <strong id="grand-profit">0.00</strong>
                            </div>
                        </div>

                        <div id="cart-inputs"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-3">
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
                            <td>{{ $sale->product?->name ?? 'منتج محذوف' }}</td>
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

    <script>
        const productSelect = document.getElementById('product_id');
        const qtyInput = document.getElementById('quantity_sold');
        const priceInput = document.getElementById('sale_price');
        const addItemBtn = document.getElementById('add-item-btn');
        const cartBody = document.getElementById('cart-body');
        const emptyRow = document.getElementById('empty-row');
        const cartInputs = document.getElementById('cart-inputs');
        const form = document.getElementById('pos-form');
        const grandTotalEl = document.getElementById('grand-total');
        const grandProfitEl = document.getElementById('grand-profit');

        const cart = [];

        function getSelectedOption() {
            return productSelect.options[productSelect.selectedIndex] || null;
        }

        function usedQtyForProduct(productId) {
            return cart
                .filter(item => item.productId === productId)
                .reduce((sum, item) => sum + item.qty, 0);
        }

        function resetCurrentEntry() {
            productSelect.value = '';
            qtyInput.value = 1;
            priceInput.value = '';
            productSelect.focus();
        }

        function renderCart() {
            cartBody.innerHTML = '';

            if (cart.length === 0) {
                cartBody.appendChild(emptyRow);
            } else {
                cart.forEach((item, index) => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${item.name}</td>
                        <td>${item.qty}</td>
                        <td>${item.price.toFixed(2)}</td>
                        <td>${(item.qty * item.price).toFixed(2)}</td>
                        <td><button type="button" class="btn btn-sm btn-outline-danger" data-remove="${index}">حذف</button></td>
                    `;
                    cartBody.appendChild(tr);
                });
            }

            const total = cart.reduce((sum, item) => sum + (item.qty * item.price), 0);
            const profit = cart.reduce((sum, item) => sum + ((item.qty * item.price) - (item.qty * item.cost)), 0);

            grandTotalEl.textContent = total.toFixed(2);
            grandProfitEl.textContent = profit.toFixed(2);

            cartInputs.innerHTML = cart.map((item, index) => `
                <input type="hidden" name="items[${index}][product_id]" value="${item.productId}">
                <input type="hidden" name="items[${index}][quantity_sold]" value="${item.qty}">
                <input type="hidden" name="items[${index}][sale_price]" value="${item.price}">
            `).join('');
        }

        addItemBtn.addEventListener('click', () => {
            const option = getSelectedOption();
            if (!option || !option.value) {
                alert('اختر منتجًا أولًا.');
                return;
            }

            const productId = Number(option.value);
            const name = option.dataset.name;
            const remaining = Number(option.dataset.remaining || 0);
            const lastPrice = Number(option.dataset.lastPrice || 0);
            const cost = Number(option.dataset.cost || 0);
            const qty = Number(qtyInput.value || 0);
            const price = Number(priceInput.value || lastPrice || 0);

            if (!Number.isInteger(qty) || qty < 1) {
                alert('أدخل كمية صحيحة.');
                return;
            }

            if (price < 0) {
                alert('أدخل سعرًا صحيحًا.');
                return;
            }

            const totalQtyAfterAdd = usedQtyForProduct(productId) + qty;
            if (totalQtyAfterAdd > remaining) {
                alert(`الكمية المطلوبة أكبر من المتاح للمنتج ${name}. المتاح: ${remaining}`);
                return;
            }

            cart.push({ productId, name, qty, price, cost });
            renderCart();
            resetCurrentEntry();
        });

        cartBody.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-remove]');
            if (!btn) return;
            const idx = Number(btn.dataset.remove);
            cart.splice(idx, 1);
            renderCart();
        });

        productSelect.addEventListener('change', () => {
            const option = getSelectedOption();
            if (option && option.value && !priceInput.value) {
                const lastPrice = Number(option.dataset.lastPrice || 0);
                if (lastPrice > 0) {
                    priceInput.value = lastPrice.toFixed(2);
                }
            }
        });

        form.addEventListener('submit', (e) => {
            if (cart.length === 0) {
                e.preventDefault();
                alert('السلة فارغة. أضف منتجًا واحدًا على الأقل.');
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'F2') {
                e.preventDefault();
                productSelect.focus();
            } else if (e.key === 'F4') {
                e.preventDefault();
                qtyInput.focus();
                qtyInput.select();
            } else if (e.key === 'F6') {
                e.preventDefault();
                priceInput.focus();
                priceInput.select();
            } else if (e.key === 'F9') {
                e.preventDefault();
                form.requestSubmit();
            } else if (e.key === 'Escape') {
                e.preventDefault();
                resetCurrentEntry();
            } else if (e.key === 'Enter') {
                const active = document.activeElement;
                if (active === productSelect || active === qtyInput || active === priceInput) {
                    e.preventDefault();
                    addItemBtn.click();
                }
            }
        });
    </script>
@endsection
