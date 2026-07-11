@php $isEdit = isset($project); @endphp

<style>
    .form-input {
        width: 100%;
        background: rgba(255,255,255,0.02);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 0.75rem;
        padding: 0.875rem 1.25rem;
        color: #f5f5f5;
        font-size: 0.875rem;
        outline: none;
        transition: border-color 0.3s ease;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .form-input:focus { border-color: rgba(16,185,129,0.5); }
    .form-input::placeholder { color: rgba(255,255,255,0.2); }
    .form-label {
        display: block;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: rgba(255,255,255,0.35);
        margin-bottom: 0.5rem;
    }
    .form-error { color: #f87171; font-size: 0.75rem; margin-top: 0.4rem; }
</style>

{{-- Title --}}
<div>
    <label class="form-label">Judul Project *</label>
    <input type="text" name="title" class="form-input"
           value="{{ old('title', $project->title ?? '') }}"
           placeholder="Nama project kamu" required />
    @error('title') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Description --}}
<div>
    <label class="form-label">Deskripsi *</label>
    <textarea name="description" rows="4" class="form-input" style="resize:none"
              placeholder="Ceritakan project ini..." required>{{ old('description', $project->description ?? '') }}</textarea>
    @error('description') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Image --}}
<div>
    <label class="form-label">Gambar {{ $isEdit ? '(kosongkan jika tidak ingin ganti)' : '' }}</label>
    @if($isEdit && $project->image)
    <img src="{{ Storage::url($project->image) }}" class="w-32 h-20 object-cover rounded-lg border border-white/10 mb-3" />
    @endif
    <input type="file" name="image" accept="image/*" class="form-input" style="padding: 0.6rem 1rem;" />
    @error('image') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Visit URL --}}
<div>
    <label class="form-label">Link Visit Project</label>
    <input type="url" name="visit_url" class="form-input"
           value="{{ old('visit_url', $project->visit_url ?? '') }}"
           placeholder="https://project-kamu.com" />
    @error('visit_url') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- GitHub URL --}}
<div>
    <label class="form-label">Link GitHub</label>
    <input type="url" name="github_url" class="form-input"
           value="{{ old('github_url', $project->github_url ?? '') }}"
           placeholder="https://github.com/username/repo" />
    @error('github_url') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Tags --}}
<div>
    <label class="form-label">Tags <span class="normal-case tracking-normal font-normal text-white/20">(pisah dengan koma)</span></label>
    <input type="text" name="tags" class="form-input"
           value="{{ old('tags', $isEdit ? implode(', ', $project->tags ?? []) : '') }}"
           placeholder="Laravel, Tailwind, MySQL" />
    @error('tags') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Order --}}
<div>
    <label class="form-label">Urutan Tampil</label>
    <input type="number" name="order" class="form-input"
           value="{{ old('order', $project->order ?? 0) }}"
           placeholder="0" min="0" />
</div>

{{-- Featured --}}
<div class="flex items-center gap-3">
    <input type="checkbox" name="is_featured" id="is_featured" value="1"
           {{ old('is_featured', $project->is_featured ?? true) ? 'checked' : '' }}
           class="w-4 h-4 accent-emerald-500" />
    <label for="is_featured" class="text-sm text-white/50 cursor-pointer">Tampilkan di portfolio</label>
</div>