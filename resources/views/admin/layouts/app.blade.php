<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/admin.css">
    <title>NCB Admin – @yield('title')</title>
</head>
<body>
    <div class="admin-wrapper">

        {{-- Sidebar --}}
        <aside class="admin-sidebar">
            <div class="sidebar-logo">
                <img src="/img/logo.png" alt="NCB Logo">
                <span>NCB Admin</span>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('admin.products.index') }}"
                   class="sidebar-link {{ request()->is('admin/products*') ? 'active' : '' }}">
                    📦 Products
                </a>
                <a href="{{ route('admin.vouchers.index') }}"
                   class="sidebar-link {{ request()->is('admin/vouchers*') ? 'active' : '' }}">
                    🎟️ Vouchers
                </a>
            </nav>
        </aside>

        {{-- Main --}}
        <div class="admin-main">
            <header class="admin-topbar">
                <h2>@yield('title')</h2>
                <span>Admin Panel</span>
            </header>

            <main class="admin-content">

                {{-- Flash messages --}}
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-error">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-error">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @yield('content')
            </main>
        </div>

    </div>
</body>
</html>