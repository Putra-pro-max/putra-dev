@extends('admin.layout')

@section('content')
<div class="mb-8">
    <a href="{{ route('admin.projects.index') }}" class="text-white/30 hover:text-white text-sm flex items-center gap-1.5 mb-4 transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali
    </a>
    <h1 class="text-2xl font-extrabold text-white">Edit Project</h1>
</div>

<form action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data"
      class="max-full space-y-5 p-8 rounded-2xl border border-white/[0.06] bg-white/[0.02]">
    @csrf
    @method('PUT')
    @include('admin.projects._form')
    <div class="pt-2">
        <button type="submit"
            class="bg-emerald-500 hover:bg-emerald-400 text-[#0d0d0d] font-bold text-sm px-6 py-3 rounded-xl transition-all duration-300">
            Update Project
        </button>
    </div>
</form>
@endsection