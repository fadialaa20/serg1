@extends('layouts.app')

@section('title', 'إدارة المستخدمين')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">إدارة المستخدمين</h2>
        <span class="text-muted">إضافة بائعين/أدمن مع فصل كامل للبيانات</span>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-5"><div class="card border-0 shadow-sm"><div class="card-body">
            <h5 class="mb-3">إضافة مستخدم جديد</h5>
            <form action="{{ route('admin.users.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-12"><label class="form-label">الاسم الكامل</label><input type="text" name="name" value="{{ old('name') }}" class="form-control" required></div>
                <div class="col-12"><label class="form-label">اسم المستخدم</label><input type="text" name="username" value="{{ old('username') }}" class="form-control" required></div>
                <div class="col-12"><label class="form-label">البريد الإلكتروني (اختياري)</label><input type="email" name="email" value="{{ old('email') }}" class="form-control"></div>
                <div class="col-12"><label class="form-label">الرقم المميز (اختياري)</label><input type="text" name="login_code" value="{{ old('login_code') }}" class="form-control" placeholder="يتم توليده تلقائيًا إذا تركته فارغًا"></div>
                <div class="col-12"><label class="form-label">كلمة المرور</label><input type="text" name="password" class="form-control" required></div>
                <div class="col-12 form-check"><input class="form-check-input" type="checkbox" value="1" id="is_admin" name="is_admin" @checked(old('is_admin'))><label class="form-check-label" for="is_admin">صلاحية أدمن</label></div>
                <div class="col-12"><button type="submit" class="btn btn-primary w-100">حفظ المستخدم</button></div>
            </form>
        </div></div></div>
        <div class="col-12 col-lg-7"><div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table mb-0 align-middle"><thead><tr>
            <th>الاسم</th><th>اسم المستخدم</th><th>الرقم المميز</th><th>النوع</th>
        </tr></thead><tbody>
        @forelse($users as $user)
            <tr><td>{{ $user->name }}</td><td>{{ $user->username }}</td><td>{{ $user->login_code ?? '-' }}</td><td><span class="badge {{ $user->is_admin ? 'text-bg-warning' : 'text-bg-secondary' }}">{{ $user->is_admin ? 'أدمن' : 'بائع' }}</span></td></tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted py-4">لا يوجد مستخدمون حاليًا.</td></tr>
        @endforelse
        </tbody></table></div></div></div>
    </div>
@endsection
