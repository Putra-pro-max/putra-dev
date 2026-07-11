    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Dashboard — Putra Dev Admin</title>
        @vite('resources/css/app.css')
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
        <link rel="icon" type="image/webp" href="{{ asset('images/logo_neo_umbrella.webp') }}">
        <style>
            *, *::before, *::after { box-sizing: border-box; }
            body { 
                font-family: 'Plus Jakarta Sans', sans-serif; 
                background: #0a0a0a; 
                color: #f5f5f5; 
                margin: 0; 
                padding: 0;
                overflow-x: hidden;
            }

            .gradient-text {
                background: linear-gradient(135deg, #34d399, #10b981, #6ee7b7);
                -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
            }

            /* ═══════ MASTER GRID SYSTEMS ═══════ */
            .admin-grid-container {
                display: grid;
                grid-template-columns: 240px 1fr;
                min-height: 100vh;
                width: 100%;
            }

            /* Sidebar Desktop */
            .sidebar {
                background: rgba(255,255,255,0.02); 
                border-right: 1px solid rgba(255,255,255,0.05);
                display: flex; 
                flex-direction: column; 
                padding: 1.75rem 1.25rem;
                position: sticky;
                top: 0;
                height: 100vh;
                z-index: 40;
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

            /* Main Workspace Container */
            .main { 
                padding: 2.5rem 2.5rem; 
                width: 100%;
                min-width: 0; /* Anti-jebol layout flex/grid */
                display: flex;
                flex-direction: column;
            }

            /* Flash Message */
            .flash-success {
                display: flex; align-items: center; gap: 0.6rem;
                padding: 0.875rem 1.25rem; border-radius: 0.75rem;
                background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.2);
                color: #34d399; font-size: 0.82rem; font-weight: 500; margin-bottom: 1.5rem;
                width: 100%;
            }

            /* Page header */
            .page-sub { font-size: 0.65rem; color: rgba(255,255,255,0.25); text-transform: uppercase; letter-spacing: 0.18em; font-weight: 600; margin-bottom: 0.25rem; }
            .page-title { font-size: 1.5rem; font-weight: 800; color: #fff; margin-bottom: 2rem; }

            /* Stat cards (Grid Desktop 4 Kolom) */
            .stats-grid { 
                display: grid; 
                grid-template-columns: repeat(4, 1fr); 
                gap: 1.25rem; 
                margin-bottom: 1.5rem; 
                width: 100%;
            }
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
            .stat-badge-up { font-size: 0.65rem; font-weight: 700; color: #34d399; background: rgba(16,185,129,0.1); border-radius: 999px; padding: 0.2rem 0.5rem; }
            .stat-badge-msg { font-size: 0.65rem; font-weight: 700; color: #f87171; background: rgba(239,68,68,0.1); border-radius: 999px; padding: 0.2rem 0.5rem; }

            /* Content grids (Desktop Layouts) */
            .content-grid { 
                display: grid; 
                grid-template-columns: 1fr 360px; 
                gap: 1.25rem; 
                margin-bottom: 1.25rem; 
                width: 100%;
            }
            .content-grid-3 { 
                display: grid; 
                grid-template-columns: repeat(3, 1fr); 
                gap: 1.25rem; 
                width: 100%;
            }

            /* Cards Base */
            .card {
                background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06);
                border-radius: 1rem; overflow: hidden; width: 100%;
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
            .view-all { font-size: 0.72rem; color: rgba(255,255,255,0.25); text-decoration: none; transition: color 0.2s; }
            .view-all:hover { color: #34d399; }

            /* Responsive Table Shell */
            .table-responsive-wrapper {
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            /* Visitor table */
            .vtable { width: 100%; border-collapse: collapse; font-size: 0.78rem; min-width: 500px; }
            .vtable th { padding: 0.5rem 0.75rem; text-align: left; font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.2); border-bottom: 1px solid rgba(255,255,255,0.04); }
            .vtable td { padding: 0.65rem 0.75rem; border-bottom: 1px solid rgba(255,255,255,0.03); }
            .vtable tr:last-child td { border-bottom: none; }
            .vtable tr:hover td { background: rgba(255,255,255,0.015); }
            .ip-text { font-family: monospace; font-size: 0.75rem; color: rgba(255,255,255,0.55); font-weight: 600; }
            .page-text { font-size: 0.72rem; color: rgba(255,255,255,0.35); }
            
            .pill {
                display: inline-flex; align-items: center; gap: 0.25rem;
                font-size: 0.62rem; font-weight: 600; padding: 0.2rem 0.55rem;
                border-radius: 999px; white-space: nowrap;
            }
            .pill-green  { background: rgba(16,185,129,0.1);  border: 1px solid rgba(16,185,129,0.2);  color: #34d399; }
            .pill-blue   { background: rgba(59,130,246,0.1);  border: 1px solid rgba(59,130,246,0.2);  color: #60a5fa; }
            .pill-purple { background: rgba(168,85,247,0.1);  border: 1px solid rgba(168,85,247,0.2);  color: #c084fc; }
            .pill-orange { background: rgba(249,115,22,0.1);  border: 1px solid rgba(249,115,22,0.2);  color: #fb923c; }
            .pill-gray   { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.4); }
            .time-text { font-size: 0.68rem; color: rgba(255,255,255,0.2); white-space: nowrap; }

            /* Bar charts */
            .bar-list { display: flex; flex-direction: column; gap: 0.75rem; }
            .bar-item { display: flex; flex-direction: column; gap: 0.3rem; }
            .bar-info { display: flex; justify-content: space-between; align-items: center; }
            .bar-name { font-size: 0.75rem; color: rgba(255,255,255,0.5); font-weight: 500; }
            .bar-count { font-size: 0.72rem; color: rgba(255,255,255,0.3); font-weight: 600; }
            .bar-track { height: 4px; background: rgba(255,255,255,0.05); border-radius: 999px; overflow: hidden; }
            .bar-fill { height: 100%; border-radius: 999px; background: linear-gradient(90deg, #10b981, #34d399); transition: width 1s ease; }
            .bar-fill.blue { background: linear-gradient(90deg, #3b82f6, #60a5fa); }

            /* Message items */
            .msg-list { display: flex; flex-direction: column; gap: 0.75rem; }
            .msg-item {
                padding: 0.875rem 1rem; border-radius: 0.75rem;
                background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.05);
                transition: border-color 0.2s;
            }
            .msg-item:hover { border-color: rgba(16,185,129,0.2); }
            .msg-item.unread { border-color: rgba(16,185,129,0.15); background: rgba(16,185,129,0.03); }
            .msg-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.35rem; gap: 0.5rem; }
            .msg-name { font-size: 0.8rem; font-weight: 700; color: rgba(255,255,255,0.7); }
            .msg-email { font-size: 0.68rem; color: rgba(255,255,255,0.25); }
            .msg-text { font-size: 0.75rem; color: rgba(255,255,255,0.35); line-height: 1.5; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
            .unread-dot { width: 6px; height: 6px; border-radius: 50%; background: #34d399; box-shadow: 0 0 6px #34d399; flex-shrink: 0; }

            .divider { height: 1px; background: rgba(255,255,255,0.05); margin: 0.5rem 0; }
            .btn-logout { display: flex; align-items: center; gap: 0.65rem; padding: 0.6rem 0.75rem; border-radius: 0.65rem; color: rgba(255,255,255,0.3); font-size: 0.82rem; font-weight: 500; background: none; border: none; cursor: pointer; width: 100%; transition: all 0.25s ease; font-family: inherit; }
            .btn-logout:hover { background: rgba(239,68,68,0.08); color: #f87171; }
            .empty-state { padding: 2rem; text-align: center; color: rgba(255,255,255,0.2); font-size: 0.78rem; }

            /* Mobile Layout Components */
            .mobile-navbar {
                display: none;
                position: fixed; top: 0; left: 0; right: 0; height: 60px;
                background: #0f0f0f; border-bottom: 1px solid rgba(255,255,255,0.05);
                padding: 0 1.25rem; align-items: center; justify-content: space-between; z-index: 50;
            }
            .menu-toggle-input { display: none; }
            .menu-toggle-btn { cursor: pointer; color: rgba(255,255,255,0.6); display: none; }
            .menu-toggle-btn svg { width: 24px; height: 24px; }

            /* ═══════════════════════════════════════════
            MEDIA QUERY ENGINE (RESPONSIVE SMARTPHONE)
            ═══════════════════════════════════════════ */
            @media (max-width: 1024px) {
                .content-grid {
                    grid-template-columns: 1fr;
                }
                .content-grid-3 {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 768px) {
                .admin-grid-container {
                    grid-template-columns: 1fr;
                }
                .mobile-navbar { display: flex; }
                .menu-toggle-btn { display: block; }

                .sidebar {
                    position: fixed;
                    top: 60px; left: 0; bottom: 0;
                    width: 240px; height: calc(100vh - 60px);
                    transform: translateX(-100%);
                    background: #0f0f0f;
                    box-shadow: 15px 0 30px rgba(0,0,0,0.7);
                }

                .main {
                    padding: 5.5rem 1rem 2rem 1rem;
                }

                /* Responsive Stat Cards Engine */
                .stats-grid {
                    grid-template-columns: repeat(2, 1fr);
                }

                /* Sidebar Toggle Action */
                .menu-toggle-input:checked ~ .admin-grid-container .sidebar {
                    transform: translateX(0);
                }
            }

            @media (max-width: 480px) {
                .stats-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    </head>
    <body>

    <input type="checkbox" id="menu-trigger" class="menu-toggle-input" />

    <header class="mobile-navbar">
        <a href="#" class="sidebar-brand" style="margin-bottom:0;">
            Putra<span class="gradient-text">Dev.</span>
        </a>
        <label for="menu-trigger" class="menu-toggle-btn">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </label>
    </header>

    <div class="admin-grid-container">

        {{-- ═══ SIDEBAR COMPONENT ═══ --}}
        <aside class="sidebar">
            <a href="{{ url('/') }}" class="sidebar-brand">
                <span style="display:block;font-size:0.6rem;color:rgba(255,255,255,0.2);font-weight:400;letter-spacing:0.18em;text-transform:uppercase;margin-top:2px;">Admin Panel</span>
            </a>

            <p class="sidebar-label">Menu</p>
            <a href="{{ route('admin.dashboard') }}" class="nav-item active">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.projects.index') }}" class="nav-item">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                Projects
            </a>
            <a href="{{ route('admin.messages') }}" class="nav-item">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Messages
                @if($unreadMessages > 0)
                <span class="nav-badge">{{ $unreadMessages }}</span>
                @endif
            </a>

            <div style="margin-top:auto">
                <div class="divider"></div>
                <a href="{{ url('/') }}" class="nav-item" target="_blank">
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

        {{-- ═══ MAIN WORKSPACE (FULL TO THE RIGHT) ═══ --}}
        <main class="main">

            @if(session('success'))
            <div class="flash-success">
                <svg style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
            @endif

            <p class="page-sub">Admin Panel</p>
            <p class="page-title">Dashboard <span class="gradient-text">Overview</span></p>

            {{-- ═══ STAT CARDS ═══ --}}
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card-top">
                        <span class="stat-label">Total Visitor</span>
                        <div class="stat-icon green">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                    </div>
                    <p class="stat-value">{{ number_format($totalVisitors) }}</p>
                    <p class="stat-sub">Semua kunjungan</p>
                </div>

               <div class="stat-card">
    <div class="stat-card-top">
            <span class="stat-label">Total Kunjungan</span>
            <div class="stat-icon green">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></  svg>
                </div>
                </div>
                <p class="stat-value">{{ number_format($totalVisitors) }}</p>
                <p class="stat-sub">Semua sesi/kunjungan</p>
                </div>

                <div class="stat-card">
                    <div class="stat-card-top">
                        <span class="stat-label">Unik IP</span>
                        <div class="stat-icon purple">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                    </div>
                    <p class="stat-value">{{ number_format($uniqueVisitors) }}</p>
                    <p class="stat-sub">IP unik tercatat</p>
                </div>

                <div class="stat-card">
                    <div class="stat-card-top">
                        <span class="stat-label">Pesan Masuk</span>
                        <div class="stat-icon orange">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                    </div>
                    <p class="stat-value">{{ number_format($totalMessages) }}</p>
                    <div style="display:flex;align-items:center;gap:0.5rem;margin-top:0.2rem">
                        <p class="stat-sub" style="margin:0">Total pesan</p>
                        @if($unreadMessages > 0)
                        <span class="stat-badge-msg">{{ $unreadMessages }} belum dibaca</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ═══ RECENT VISITORS + MESSAGES ═══ --}}
            <div class="content-grid">

                {{-- Recent Visitors Table --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="card-header-icon">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </div>
                            <span class="card-title">Pengunjung Terbaru</span>
                        </div>
                    </div>
                    <div class="table-responsive-wrapper">
                        <table class="vtable">
                            <thead>
                                <tr>
                                    <th>IP Address</th>
                                    <th>Browser</th>
                                    <th>OS</th>
                                    <th>Halaman</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentVisitors as $v)
                                <tr>
                                    <td><span class="ip-text">{{ $v->ip_address }}</span></td>
                                    <td>
                                        @php
                                            $bClass = match($v->browser) {
                                                'Chrome'  => 'pill-green',
                                                'Firefox' => 'pill-orange',
                                                'Safari'  => 'pill-blue',
                                                'Edge'    => 'pill-purple',
                                                default   => 'pill-gray'
                                            };
                                        @endphp
                                        <span class="pill {{ $bClass }}">{{ $v->browser ?? 'Unknown' }}</span>
                                    </td>
                                    <td><span class="pill pill-gray">{{ $v->os ?? 'Unknown' }}</span></td>
                                    <td><span class="page-text">/{{ $v->page }}</span></td>
                                    <td><span class="time-text">{{ $v->created_at->diffForHumans() }}</span></td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="empty-state">Belum ada data pengunjung.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Recent Messages --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="card-header-icon">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <span class="card-title">Pesan Terbaru</span>
                        </div>
                        <a href="{{ route('admin.messages') }}" class="view-all">Lihat semua →</a>
                    </div>
                    <div class="card-body">
                        @forelse($recentMessages as $msg)
                        <div class="msg-item {{ !$msg->is_read ? 'unread' : '' }}" style="margin-bottom:0.6rem">
                            <div class="msg-top">
                                <div>
                                    <p class="msg-name">
                                        {{ $msg->name }}
                                        @if(!$msg->is_read)<span class="unread-dot" style="display:inline-block;margin-left:4px;vertical-align:middle"></span>@endif
                                    </p>
                                    <p class="msg-email">{{ $msg->email }}</p>
                                </div>
                                <span class="time-text">{{ $msg->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="msg-text">{{ $msg->message }}</p>
                        </div>
                        @empty
                        <div class="empty-state">Belum ada pesan masuk.</div>
                        @endforelse
                    </div>
                </div>

            </div>

            {{-- ═══ BROWSER + OS STATS ═══ --}}
            <div class="content-grid-3">

                {{-- Browser Stats --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="card-header-icon">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M2 12h20M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20"/></svg>
                            </div>
                            <span class="card-title">Browser</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @php $maxB = $browserStats->max('total') ?: 1; @endphp
                        <div class="bar-list">
                            @forelse($browserStats as $b)
                            <div class="bar-item">
                                <div class="bar-info">
                                    <span class="bar-name">{{ $b->browser ?? 'Unknown' }}</span>
                                    <span class="bar-count">{{ $b->total }}</span>
                                </div>
                                <div class="bar-track">
                                    <div class="bar-fill" style="width:{{ ($b->total / $maxB) * 100 }}%"></div>
                                </div>
                            </div>
                            @empty
                            <p class="empty-state" style="padding:0.5rem 0">Belum ada data.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- OS Stats --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="card-header-icon">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 21h8m-4-4v4"/></svg>
                            </div>
                            <span class="card-title">Sistem Operasi</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @php $maxO = $osStats->max('total') ?: 1; @endphp
                        <div class="bar-list">
                            @forelse($osStats as $o)
                            <div class="bar-item">
                                <div class="bar-info">
                                    <span class="bar-name">{{ $o->os ?? 'Unknown' }}</span>
                                    <span class="bar-count">{{ $o->total }}</span>
                                </div>
                                <div class="bar-track">
                                    <div class="bar-fill blue" style="width:{{ ($o->total / $maxO) * 100 }}%"></div>
                                </div>
                            </div>
                            @empty
                            <p class="empty-state" style="padding:0.5rem 0">Belum ada data.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Quick Stats --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="card-header-icon">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <span class="card-title">Ringkasan</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="display:flex;flex-direction:column;gap:0.75rem">
                            <div style="display:flex;justify-content:space-between;align-items:center;padding:0.65rem 0.875rem;background:rgba(0,0,0,0.2);border-radius:0.65rem;border:1px solid rgba(255,255,255,0.05)">
                                <span style="font-size:0.78rem;color:rgba(255,255,255,0.4)">Total Project</span>
                                <span style="font-size:0.9rem;font-weight:800;color:#fff">{{ $totalProjects }}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;align-items:center;padding:0.65rem 0.875rem;background:rgba(0,0,0,0.2);border-radius:0.65rem;border:1px solid rgba(255,255,255,0.05)">
                                <span style="font-size:0.78rem;color:rgba(255,255,255,0.4)">Total Pesan</span>
                                <span style="font-size:0.9rem;font-weight:800;color:#fff">{{ $totalMessages }}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;align-items:center;padding:0.65rem 0.875rem;background:rgba(16,185,129,0.05);border-radius:0.65rem;border:1px solid rgba(16,185,129,0.12)">
                                <span style="font-size:0.78rem;color:rgba(255,255,255,0.4)">Belum Dibaca</span>
                                <span style="font-size:0.9rem;font-weight:800;color:#34d399">{{ $unreadMessages }}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;align-items:center;padding:0.65rem 0.875rem;background:rgba(0,0,0,0.2);border-radius:0.65rem;border:1px solid rgba(255,255,255,0.05)">
                                <span style="font-size:0.78rem;color:rgba(255,255,255,0.4)">Visitor Hari Ini</span>
                                <span style="font-size:0.9rem;font-weight:800;color:#fff">{{ $todayVisitors }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </main>
    </div>

    </body>
    </html>