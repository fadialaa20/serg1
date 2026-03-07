@extends('layouts.app')

@section('title', 'تعديل المستخدم')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">تعديل المستخدم</h2>
        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-secondary">رجوع للتفاصيل</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')
                <div class="col-md-6">
                    <label class="form-label">الاسم الكامل</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">اسم المستخدم</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">البريد الإلكتروني</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">كود الدخول</label>
                    <input type="text" name="login_code" value="{{ old('login_code', $user->login_code) }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">كلمة مرور جديدة (اختياري)</label>
                    <input type="text" name="password" class="form-control" placeholder="اتركها فارغة للإبقاء على الحالية">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" value="1" id="is_admin" name="is_admin" @checked(old('is_admin', $user->is_admin))>
                        <label class="form-check-label" for="is_admin">صلاحية أدمن</label>
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
@endsection
