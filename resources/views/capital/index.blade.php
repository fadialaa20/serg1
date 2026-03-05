@extends('layouts.app')

@section('title', 'إدارة رأس المال')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">إدارة رأس المال</h2>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('capital.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">رأس المال الحالي (شيكل)</label>
                    <input type="number" step="0.01" min="0" name="capital_amount" class="form-control"
                           value="{{ old('capital_amount', $capital?->capital_amount ?? 0) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">الأرباح السابقة (شيكل)</label>
                    <input type="number" step="0.01" min="0" name="previous_profit" class="form-control"
                           value="{{ old('previous_profit', $capital?->previous_profit ?? 0) }}" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">حفظ البيانات</button>
                </div>
            </form>
        </div>
    </div>
@endsection
