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
                    <div class="text-muted">الرصيد الحالي بنكي</div>
                    <div class="value text-info">{{ number_format($currentBank, 2) }} شيكل</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted">إجمالي (كاش + بنكي)</div>
                    <div class="value">{{ number_format($walletTotal, 2) }} شيكل</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted">رأس المال المُدخل</div>
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

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">آخر المصروفات</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                    <tr>
                        <th>نوع المحفظة</th>
                        <th>المبلغ</th>
                        <th>السبب</th>
                        <th>التاريخ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($recentExpenses as $expense)
                        <tr>
                            <td>{{ $expense->wallet_type == 'cash' ? 'كاش' : 'بنكي' }}</td>
                            <td class="text-danger">-{{ number_format($expense->amount, 2) }} شيكل</td>
                            <td>{{ Str::limit($expense->reason, 30) }}</td>
                            <td>{{ $expense->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-4 text-muted">لا توجد مصروفات بعد.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">آخر التحويلات</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                    <tr>
                        <th>من</th>
                        <th>المبلغ</th>
                        <th>السبب</th>
                        <th>التاريخ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($recentTransfers as $transfer)
                        <tr>
                            <td>{{ $transfer->from_wallet == 'cash' ? 'كاش → بنكي' : 'بنكي → كاش' }}</td>
                            <td class="text-warning">{{ number_format($transfer->amount, 2) }} شيكل</td>
                            <td>{{ Str::limit($transfer->reason, 30) }}</td>
                            <td>{{ $transfer->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-4 text-muted">لا توجد تحويلات بعد.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">صرف جديد</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.expense.store') }}" method="POST" class="row g-3">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                        <div class="col-md-6">
                            <label class="form-label">نوع المحفظة</label>
                            <select name="wallet_type" class="form-select" required>
                                <option value="cash">كاش</option>
                                <option value="bank">بنكي</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">المبلغ (شيكل)</label>
                            <input type="number" step="0.01" min="0" name="amount" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-danger w-100">صرف</button>
                        </div>
                        <div class="col-12">
                            <label class="form-label">السبب</label>
                            <textarea name="reason" class="form-control" rows="2" placeholder="مثال: فادي عيدية"></textarea>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">تحويل كاش إلى بنكي</h6>
                    <a href="#" class="btn btn-sm btn-outline-secondary reverse-transfer" data-bs-toggle="modal" data-bs-target="#reverseTransferModal">← العكس</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.transfer.store') }}" method="POST" class="row g-3">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                        <input type="hidden" name="from_wallet" value="cash">
                        <div class="col-md-6">
                            <label class="form-label">المبلغ (شيكل)</label>
                            <input type="number" step="0.01" min="0" name="amount" class="form-control transfer-amount" required>
                            <small class="text-muted">الكاش الحالي: {{ number_format($currentCash, 2) }} شيكل</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-warning w-100">تحويل</button>
                        </div>
                        <div class="col-12">
                            <label class="form-label">السبب</label>
                            <textarea name="reason" class="form-control" rows="2" placeholder="سبب التحويل..."></textarea>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.querySelector('.reverse-transfer').addEventListener('click', function(e) {
    e.preventDefault();
    // Simple JS to switch form - full modal optional
    alert('تحويل بنكي إلى كاش سيتم في المستقبل');
});
</script>
@endpush
