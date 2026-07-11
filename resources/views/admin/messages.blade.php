@extends('admin.layout')

@section('content')

<style>
    .msg-table { width: 100%; border-collapse: collapse; }
    .msg-table th {
        padding: 0.75rem 1rem; text-align: left; font-size: 0.65rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.25);
        border-bottom: 1px solid rgba(255,255,255,0.05); background: rgba(255,255,255,0.01);
    }
    .msg-table td { padding: 1rem; border-bottom: 1px solid rgba(255,255,255,0.04); }
    .msg-table tr:last-child td { border-bottom: none; }
    .msg-table tr:hover td { background: rgba(255,255,255,0.015); }

    .msg-card {
        display: flex; align-items: flex-start; gap: 0.875rem;
        padding: 0.875rem 1rem; border: 1px solid rgba(255,255,255,0.06);
        border-radius: 0.65rem; transition: all 0.2s;
    }
    .msg-card:hover { border-color: rgba(16,185,129,0.2); background: rgba(255,255,255,0.01); }
    .msg-card.unread { background: rgba(16,185,129,0.03); border-color: rgba(16,185,129,0.15); }

    .msg-unread-indicator {
        width: 8px; height: 8px; border-radius: 50%; background: #34d399;
        flex-shrink: 0; margin-top: 6px; box-shadow: 0 0 8px #34d399;
    }

    .msg-card-content { flex: 1; min-width: 0; }
    .msg-card-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.4rem; gap: 0.5rem; }
    .msg-from { font-size: 0.8rem; font-weight: 700; color: rgba(255,255,255,0.7); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .msg-email { font-size: 0.7rem; color: rgba(255,255,255,0.3); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .msg-time { font-size: 0.7rem; color: rgba(255,255,255,0.25); white-space: nowrap; flex-shrink: 0; }
    .msg-body { font-size: 0.78rem; color: rgba(255,255,255,0.4); line-height: 1.6; margin-bottom: 0.5rem; }

    .msg-actions {
        display: flex; align-items: center; gap: 0.5rem; padding-top: 0.5rem;
        border-top: 1px solid rgba(255,255,255,0.03); flex-wrap: wrap;
    }

    .btn-action {
        display: inline-flex; align-items: center; gap: 0.3rem;
        font-size: 0.72rem; font-weight: 600; padding: 0.35rem 0.6rem;
        border-radius: 0.5rem; border: 1px solid rgba(255,255,255,0.08);
        background: transparent; color: rgba(255,255,255,0.4);
        cursor: pointer; transition: all 0.2s; font-family: inherit;
    }
    .btn-action:hover { color: rgba(255,255,255,0.7); border-color: rgba(255,255,255,0.15); }
    .btn-action-danger:hover { color: #f87171; border-color: rgba(239,68,68,0.2); background: rgba(239,68,68,0.05); }
    .btn-action svg { width: 12px; height: 12px; }

    .card { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 1rem; overflow: hidden; }
    .card-header { padding: 1rem 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: space-between; }
    .card-header-left { display: flex; align-items: center; gap: 0.6rem; min-width: 0; }
    .card-header-icon { width: 26px; height: 26px; border-radius: 0.45rem; background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.15); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .card-header-icon svg { width: 12px; height: 12px; color: #34d399; }
    .card-title { font-size: 0.75rem; font-weight: 700; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 0.1em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .card-body { padding: 1.25rem; display: flex; flex-direction: column; gap: 0.75rem; }

    .empty-state { padding: 3rem 1.25rem; text-align: center; color: rgba(255,255,255,0.25); }
    .empty-state svg { width: 40px; height: 40px; margin-bottom: 1rem; opacity: 0.5; }

    .pagination { display: flex; align-items: center; justify-content: center; gap: 0.375rem; flex-wrap: wrap; }
    .pagination a, .pagination span {
        padding: 0.5rem 0.75rem; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 600;
        text-decoration: none; color: rgba(255,255,255,0.4); border: 1px solid rgba(255,255,255,0.08);
        transition: all 0.2s; white-space: nowrap;
    }
    .pagination a:hover { color: #34d399; border-color: rgba(16,185,129,0.2); }
    .pagination .active { background: rgba(16,185,129,0.1); border-color: rgba(16,185,129,0.2); color: #34d399; }
    .pagination .disabled { opacity: 0.3; cursor: not-allowed; }

    @media (max-width: 640px) {
        .msg-card { padding: 0.75rem; gap: 0.625rem; }
        .msg-card-top { flex-wrap: wrap; }
        .msg-time { order: -1; width: 100%; margin-bottom: -0.15rem; }
        .msg-body { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .card-body { padding: 0.875rem; gap: 0.5rem; }
        .card-header { padding: 0.875rem 1rem; }
        .pagination a, .pagination span { padding: 0.4rem 0.6rem; font-size: 0.7rem; }
        .pagination .pag-text { display: none; }
        .btn-action { padding: 0.3rem 0.5rem; font-size: 0.68rem; }
    }
</style>

<div class="flex flex-wrap items-center justify-between gap-3 mb-8">
    <div>
        <p class="text-[0.65rem] text-white/25 uppercase tracking-[0.18em] font-semibold mb-1">Admin Panel</p>
        <h1 class="text-2xl font-extrabold text-white">Pesan <span class="gradient-text">Masuk</span></h1>
    </div>
</div>

@if(session('success'))
<div class="flex items-center gap-2 px-5 py-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-medium mb-6">
    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
</div>
@endif

<div class="card">
    <div class="card-header">
        <div class="card-header-left">
            <div class="card-header-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <span class="card-title">Semua Pesan ({{ $messages->total() }})</span>
        </div>
    </div>
    <div class="card-body">
        @forelse($messages as $msg)
        <div class="msg-card {{ !$msg->is_read ? 'unread' : '' }}">
            @if(!$msg->is_read)
            <div class="msg-unread-indicator"></div>
            @endif
            <div class="msg-card-content">
                <div class="msg-card-top">
                    <div class="min-w-0">
                        <p class="msg-from">{{ $msg->name }}</p>
                        <p class="msg-email">{{ $msg->email }}</p>
                    </div>
                    <span class="msg-time">{{ $msg->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="msg-body">{{ $msg->message }}</div>
                <div class="msg-actions">
                    @if(!$msg->is_read)
                    <form action="{{ route('admin.messages.read', $msg) }}" method="POST" style="display:inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-action">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Tandai Dibaca
                        </button>
                    </form>
                    @endif
                    <form action="{{ route('admin.messages.destroy', $msg) }}" method="POST"
                          onsubmit="return confirm('Yakin hapus pesan ini?')"
                          style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action btn-action-danger">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <p class="text-sm m-0">Belum ada pesan masuk</p>
        </div>
        @endforelse
    </div>

    @if($messages->hasPages())
    <div style="padding:1rem 1.25rem;border-top:1px solid rgba(255,255,255,0.05)">
        <nav class="pagination">
            @if($messages->onFirstPage())
            <span class="disabled"><span class="pag-text">← </span>Sebelumnya</span>
            @else
            <a href="{{ $messages->previousPageUrl() }}"><span class="pag-text">← </span>Sebelumnya</a>
            @endif

            @foreach($messages->getUrlRange(1, $messages->lastPage()) as $page => $url)
                @if($page == $messages->currentPage())
                <span class="active">{{ $page }}</span>
                @else
                <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            @if($messages->hasMorePages())
            <a href="{{ $messages->nextPageUrl() }}">Selanjutnya<span class="pag-text"> →</span></a>
            @else
            <span class="disabled">Selanjutnya<span class="pag-text"> →</span></span>
            @endif
        </nav>
    </div>
    @endif
</div>

@endsection