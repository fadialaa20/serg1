<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تسجيل الدخول</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: radial-gradient(circle at top right, #e0f2fe, #f8fafc 45%, #e2e8f0 100%);
            padding: 1rem;
        }
        .login-card {
            width: min(100%, 430px);
            border: 0;
            border-radius: 16px;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.15);
        }
        .login-input {
            direction: rtl;
            text-align: right;
            unicode-bidi: plaintext;
            font-size: 1rem;
        }
    </style>
</head>
<body>
<div class="card login-card">
    <div class="card-body p-4 p-md-5">
        <h3 class="fw-bold mb-3">تسجيل الدخول</h3>
        <p class="text-muted mb-4">ادخل باسم المستخدم أو الرقم المميز مع كلمة المرور.</p>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-warning">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}" class="row g-3">
            @csrf
            <div class="col-12">
                <label class="form-label">اسم المستخدم أو الرقم المميز</label>
                <input type="text" name="login" value="{{ old('login') }}" class="form-control login-input" dir="auto" autocomplete="username" required autofocus>
            </div>
            <div class="col-12">
                <label class="form-label">كلمة المرور</label>
                <input type="password" name="password" class="form-control login-input" dir="auto" autocomplete="current-password" required>
            </div>
            <div class="col-12 form-check">
                <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
                <label class="form-check-label" for="remember">تذكرني</label>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary w-100">دخول</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
