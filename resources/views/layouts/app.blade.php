<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'نظام إدارة الأرباح')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(180deg, #f7fafc 0%, #eef2f7 100%);
        }
        .sidebar {
            min-height: calc(100vh - 56px);
            background: #0f172a;
        }
        .sidebar .nav-link {
            color: #cbd5e1;
            border-radius: 8px;
            margin-bottom: 6px;
        }
        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background: #1e293b;
            color: #fff;
        }
        .card-stat {
            border: 0;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
        }
        .card-stat .value {
            font-size: 1.3rem;
            font-weight: 800;
        }
        .table thead th {
            background: #f8fafc;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">نظام إدارة أرباح التاجر</a>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <aside class="col-lg-2 col-md-3 sidebar p-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">لوحة التحكم</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('capital.*') ? 'active' : '' }}" href="{{ route('capital.index') }}">رأس المال</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">المشتريات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}" href="{{ route('sales.index') }}">المبيعات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">تقارير الأرباح</a>
                    </li>
                </ul>
            </aside>

            <main class="col-lg-10 col-md-9 p-4">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
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

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
