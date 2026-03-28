<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Auxinor</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, -apple-system, sans-serif; background: #0f0f0f; color: #e5e5e5; }
        .admin-nav {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 24px; height: 52px; background: #1a1a1a;
            border-bottom: 1px solid #2a2a2a; position: sticky; top: 0; z-index: 1000;
        }
        .admin-nav .logo { font-weight: 600; font-size: 15px; color: #fff; text-decoration: none; }
        .admin-nav .nav-links { display: flex; gap: 20px; }
        .admin-nav .nav-links a { color: #aaa; font-size: 13px; text-decoration: none; }
        .admin-nav .nav-links a:hover { color: #fff; }
        .admin-nav .user-info { font-size: 12px; color: #666; }
    </style>
    @stack('styles')
</head>
<body>
<nav class="admin-nav">
    <a href="{{ route('admin.grapesjs.index') }}" class="logo">Auxinor Admin • GrapesJS</a>
    <div class="nav-links">
        <a href="{{ route('admin.grapesjs.index') }}">All Pages</a>
        <a href="{{ route('admin.dashboard') }}">Main Admin</a>
        <a href="/" target="_blank">View Site</a>
    </div>
    <div class="user-info">{{ auth()->user()->name ?? 'Admin' }}</div>
</nav>
@yield('content')
@stack('scripts')
</body>
</html>
