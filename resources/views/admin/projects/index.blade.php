@extends('admin.layout')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-8">
    <div>
        <p class="text-[0.68rem] text-white/30 uppercase tracking-widest font-semibold mb-1">Admin</p>
        <h1 class="text-2xl font-extrabold text-white">Semua Project</h1>
    </div>
    <a href="{{ route('admin.projects.create') }}"
       class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-[#0d0d0d] font-bold text-sm px-5 py-2.5 rounded-xl transition-all duration-300">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Tambah Project
    </a>
</div>

<div class="rounded-2xl border border-white/[0.06] overflow-x-auto">
    <table class="w-full text-sm min-w-[640px]">
        <thead>
            <tr class="border-b border-white/[0.06] bg-white/[0.02]">
                <th class="text-left px-5 py-3.5 text-white/40 font-semibold text-xs uppercase tracking-wider">Project</th>
                <th class="text-left px-5 py-3.5 text-white/40 font-semibold text-xs uppercase tracking-wider hidden md:table-cell">Tags</th>
                <th class="text-left px-5 py-3.5 text-white/40 font-semibold text-xs uppercase tracking-wider hidden sm:table-cell">Order</th>
                <th class="text-left px-5 py-3.5 text-white/40 font-semibold text-xs uppercase tracking-wider hidden lg:table-cell">Tanggal</th>
                <th class="px-5 py-3.5"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/[0.04]">
            @forelse($projects as $project)
            <tr class="hover:bg-white/[0.02] transition-colors">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ $project->image ? Storage::url($project->image) : 'https://picsum.photos/seed/'.$project->id.'/80/50' }}"
                             class="w-16 h-10 rounded-lg object-cover border border-white/[0.06] flex-shrink-0" />
                        <div class="min-w-0">
                            <p class="font-semibold text-white/80 truncate">{{ $project->title }}</p>
                            <p class="text-white/30 text-xs mt-0.5 truncate max-w-[200px] hidden md:block">{{ $project->description }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-4 hidden md:table-cell">
                    <div class="flex flex-wrap gap-1">
                        @foreach($project->tags ?? [] as $tag)
                        <span class="text-[0.65rem] px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/15">{{ $tag }}</span>
                        @endforeach
                    </div>
                </td>
                <td class="px-5 py-4 text-white/40 hidden sm:table-cell">{{ $project->order }}</td>
                <td class="px-5 py-4 text-white/40 hidden lg:table-cell">{{ $project->created_at->format('d M Y') }}</td>
                <td class="px-5 py-4">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.projects.edit', $project) }}"
                           class="px-3 py-1.5 rounded-lg bg-white/[0.04] border border-white/[0.08] text-white/50 hover:text-white hover:border-white/20 transition-all text-xs font-medium">
                            Edit
                        </a>
                        <form action="{{ route('admin.projects.destroy', $project) }}" method="POST"
                              onsubmit="return confirm('Yakin hapus project ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-3 py-1.5 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 hover:bg-red-500/20 transition-all text-xs font-medium">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-5 py-16 text-center text-white/25 text-sm">
                    Belum ada project. Klik "Tambah Project" untuk mulai.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection