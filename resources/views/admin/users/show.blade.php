@extends('layouts.app')

@section('title', 'تفاصيل المستخدم')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">تفاصيل المستخدم</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">تعديل</a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">رجوع</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="text-muted small">الاسم</div>
                    <div class="fw-semibold">{{ $user->name }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">اسم المستخدم</div>
                    <div class="fw-semibold">{{ $user->username }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">البريد الإلكتروني</div>
                    <div class="fw-semibold">{{ $user->email }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">كود الدخول</div>
                    <div class="fw-semibold">{{ $user->login_code ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">الصلاحية</div>
                    <div class="fw-semibold">{{ $user->is_admin ? 'أدمن' : 'مستخدم' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">تاريخ الإنشاء</div>
                    <div class="fw-semibold">{{ $user->created_at?->format('Y-m-d H:i') }}</div>
                </div>
                <div class="col-12">
                    <div class="alert alert-warning mb-0">
                        لا يمكن عرض كلمة المرور الحالية لأنها محفوظة بشكل مشفّر. يمكنك تغييرها من صفحة التعديل.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
