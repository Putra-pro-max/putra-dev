<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Putra Dev Admin')</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link rel="icon" type="image/webp" href="{{ asset('images/logo_neo_umbrella.webp') }}">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #0a0a0a; color: #f5f5f5; margin: 0; padding-top: 0; }

        .gradient-text {
            background: linear-gradient(135deg, #34d399, #10b981, #6ee7b7);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }

        /* Mobile Header Trigger (Hidden on Desktop) */
        .mobile-header {
            display: none; position: fixed; top: 0; left: 0; right: 0; height: 60px;
            background: #0f0f0f; border-bottom: 1px solid rgba(255,255,255,0.05);
            padding: 0 1.25rem; align-items: center; justify-content: space-between; z-index: 50;
        }
        .mobile-brand { font-size: 1rem; font-weight: 800; color: #fff; text-decoration: none; }
        .menu-toggle { display: none; }
        .menu-btn { display: none; cursor: pointer; color: rgba(255,255,255,0.6); transition: color 0.2s; }
        .menu-btn:hover { color: #34d399; }
        .menu-btn svg { width: 24px; height: 24px; }

        /* Sidebar */
        .sidebar {
            width: 240px; min-height: 100vh; position: fixed; left: 0; top: 0;
            background: rgba(255,255,255,0.02); border-right: 1px solid rgba(255,255,255,0.05);
            display: flex; flex-direction: column; padding: 1.75rem 1.25rem; z-index: 45;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-brand { font-size: 1.1rem; font-weight: 800; color: #fff; text-decoration: none; margin-bottom: 2rem; display: block; }
        .sidebar-label { font-size: 0.6rem; font-weight: 600; letter-spacing: 0.18em; text-transform: uppercase; color: rgba(255,255,255,0.2); margin-bottom: 0.5rem; padding: 0 0.75rem; }
        .nav-item {
            display: flex; align-items: center; gap: 0.65rem;
            padding: 0.6rem 0.75rem; border-radius: 0.65rem;
            color: rgba(255,255,255,0.4); font-size: 0.82rem; font-weight: 500;
            text-decoration: none; transition: all 0.25s ease; margin-bottom: 2px;
        }
        .nav-item:hover { background: rgba(255,255,255,0.04); color: rgba(255,255,255,0.75); }
        .nav-item.active { background: rgba(16,185,129,0.1); color: #34d399; }
        .nav-item svg { width: 15px; height: 15px; flex-shrink: 0; }
        .nav-badge {
            margin-left: auto; font-size: 0.6rem; font-weight: 700;
            background: #ef4444; color: #fff; border-radius: 999px;
            padding: 0.1rem 0.45rem; min-width: 18px; text-align: center;
        }

        /* Main Content Area */
        .main { margin-left: 220px; min-height: 100vh; padding: 2.5rem 2rem; transition: padding 0.3s ease; }

        /* Flash Messages */
        .flash-success {
            display: flex; align-items: center; gap: 0.6rem;
            padding: 0.875rem 1.25rem; border-radius: 0.75rem;
            background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.2);
            color: #34d399; font-size: 0.82rem; font-weight: 500; margin-bottom: 1.5rem;
        }

        /* Page Headers */
        .page-sub { font-size: 0.65rem; color: rgba(255,255,255,0.25); text-transform: uppercase; letter-spacing: 0.18em; font-weight: 600; margin-bottom: 0.25rem; }
        .page-title { font-size: 1.5rem; font-weight: 800; color: #fff; margin-bottom: 2rem; }

        /* Stat Cards Responsive Layout */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
        .stat-card {
            background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06);
            border-radius: 1rem; padding: 1.25rem 1.5rem;
            transition: border-color 0.25s ease;
        }
        .stat-card:hover { border-color: rgba(16,185,129,0.2); }
        .stat-card-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
        .stat-icon {
            width: 36px; height: 36px; border-radius: 0.65rem;
            display: flex; align-items: center; justify-content: center;
        }
        .stat-icon svg { width: 16px; height: 16px; }
        .stat-icon.green { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.15); color: #34d399; }
        .stat-icon.blue  { background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.15); color: #60a5fa; }
        .stat-icon.purple{ background: rgba(168,85,247,0.1); border: 1px solid rgba(168,85,247,0.15); color: #c084fc; }
        .stat-icon.orange{ background: rgba(249,115,22,0.1); border: 1px solid rgba(249,115,22,0.15); color: #fb923c; }
        .stat-label { font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.3); }
        .stat-value { font-size: 2rem; font-weight: 800; color: #fff; line-height: 1; margin: 0.35rem 0 0.2rem; }
        .stat-sub { font-size: 0.7rem; color: rgba(255,255,255,0.25); }

        /* Dashboard Content Grids */
        .content-grid { display: grid; grid-template-columns: 1fr 340px; gap: 1.25rem; margin-bottom: 1.25rem; }
        .content-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.25rem; }

        /* Universal Cards & Form Fields - FULL WIDTH BY DEFAULT */
        .card {
            background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06);
            border-radius: 1rem; overflow: hidden; width: 100%; width: -webkit-fill-available;
        }
        .card-header {
            padding: 1rem 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.05);
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-header-left { display: flex; align-items: center; gap: 0.6rem; }
        .card-header-icon {
            width: 26px; height: 26px; border-radius: 0.45rem;
            background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.15);
            display: flex; align-items: center; justify-content: center;
        }
        .card-header-icon svg { width: 12px; height: 12px; color: #34d399; }
        .card-title { font-size: 0.75rem; font-weight: 700; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 0.1em; }
        .card-body { padding: 1.25rem; }
        
        /* Form Element Optimization for Full Weight */
        .form-group { width: 100%; margin-bottom: 1.25rem; }
        .form-control {
            width: 100% !important; max-width: 100% !important; display: block;
            background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08);
            border-radius: 0.75rem; padding: 0.85rem 1rem; color: #fff; font-family: inherit;
        }

        /* Tables & Lists responsive overflow handles */
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .vtable { width: 100%; border-collapse: collapse; font-size: 0.78rem; min-width: 500px; }
        .vtable th { padding: 0.5rem 0.75rem; text-align: left; font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.2); border-bottom: 1px solid rgba(255,255,255,0.04); }
        .vtable td { padding: 0.65rem 0.75rem; border-bottom: 1px solid rgba(255,255,255,0.03); }
        
        /* Miscellaneous styles */
        .pill { display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.62rem; font-weight: 600; padding: 0.2rem 0.55rem; border-radius: 999px; white-space: nowrap; }
        .pill-green { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2); color: #34d399; }
        .pill-blue  { background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.2); color: #60a5fa; }
        .view-all { font-size: 0.72rem; color: rgba(255,255,255,0.25); text-decoration: none; }
        .view-all:hover { color: #34d399; }
        .divider { height: 1px; background: rgba(255,255,255,0.05); margin: 0.5rem 0; }
        .btn-logout { display: flex; align-items: center; gap: 0.65rem; padding: 0.6rem 0.75rem; border-radius: 0.65rem; color: rgba(255,255,255,0.3); font-size: 0.82rem; font-weight: 500; background: none; border: none; cursor: pointer; width: 100%; transition: all 0.25s ease; font-family: inherit; }
        .btn-logout:hover { background: rgba(239,68,68,0.08); color: #f87171; }
        .btn-logout svg { width: 15px; height: 15px; flex-shrink: 0; }

        /* ═══════════════════════════════════════════
           MEDIA QUERY: MOBILE RESPONSIVE ENGINE
           ═══════════════════════════════════════════ */
        @media (max-width: 1024px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .content-grid { grid-template-columns: 1fr; }
            .content-grid-3 { grid-template-columns: 1fr; }
        }

        @media (max-width: 768px) {
            .mobile-header { display: flex; }
            .menu-btn { display: block; }
            
            /* Sembunyikan sidebar ke kiri secara default */
            .sidebar {
                transform: translateX(-100%);
                top: 60px; height: calc(100vh - 60px); min-height: auto;
                background: #0f0f0f; width: 240px; box-shadow: 10px 0 30px rgba(0,0,0,0.5);
            }
            
            /* Geser area main ke atas & penuhi layar */
            .main { margin-left: 0; padding: 5.5rem 1.25rem 2rem 1.25rem; }

            /* Trigger Buka Sidebar menggunakan Checkbox Status */
            .menu-toggle:checked ~ .sidebar { transform: translateX(0); }

            .stats-grid { grid-template-columns: 1fr; gap: 0.85rem; }
            .page-title { font-size: 1.25rem; margin-bottom: 1.5rem; }
            .card-body { padding: 1rem; }
        }
    </style>
</head>
<body>

<input type="checkbox" id="sidebar-toggle" class="menu-toggle" />

{{-- ═══ MOBILE TOP HEADER ═══ --}}
<header class="mobile-header">
    <a href="{{ url('/') }}" class="mobile-brand">
        Putra<span class="gradient-text">Dev.</span>
    </a>
    <label for="sidebar-toggle" class="menu-btn">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </label>
</header>

{{-- ═══ SIDEBAR ═══ --}}
<aside class="sidebar">
    <a href="{{ url('/') }}" class="sidebar-brand">
        <span style="display:block;font-size:0.6rem;color:rgba(255,255,255,0.2);font-weight:400;letter-spacing:0.18em;text-transform:uppercase;margin-top:2px;">Admin Panel</span>
    </a>

    <p class="sidebar-label">Menu</p>
    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        Dashboard
    </a>
    <a href="{{ route('admin.projects.index') }}" class="nav-item {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
        Projects
    </a>
    <a href="{{ route('admin.messages') }}" class="nav-item {{ request()->routeIs('admin.messages') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        Messages
        @if($unreadMessages > 0)
        <span class="nav-badge">{{ $unreadMessages }}</span>
        @endif
    </a>

    <div style="margin-top:auto">
        <div class="divider"></div>
        <a href="{{ url('/') }}" class="nav-item">
            Lihat Portfolio
        </a>
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                Logout
            </button>
        </form>
    </div>
</aside>

{{-- ═══ MAIN CONTENT AREA ═══ --}}
<main class="main">
    @yield('content')
</main>

</body>
</html>