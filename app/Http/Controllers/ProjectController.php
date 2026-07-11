<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    // Tampil semua project (admin)
    public function index()
    {
        $projects = Project::orderBy('order')->latest()->get();
        return view('admin.projects.index', compact('projects'));
    }

    // Form tambah project
    public function create()
    {
        return view('admin.projects.create');
    }

    // Simpan project baru
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'image'       => 'nullable|image|max:2048',
            'visit_url'   => 'nullable|url',
            'github_url'  => 'nullable|url',
            'tags'        => 'nullable|string',
            'order'       => 'nullable|integer',
        ]);

        $data = $request->only(['title', 'description', 'visit_url', 'github_url', 'order']);
        $data['is_featured'] = $request->boolean('is_featured');

        // Tags: dari input string "Laravel, Tailwind" → array
        $data['tags'] = $request->tags
            ? array_map('trim', explode(',', $request->tags))
            : [];

        // Upload gambar
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('projects', 'public');
        }

        Project::create($data);

        return redirect()->route('admin.projects.index')
                         ->with('success', 'Project berhasil ditambahkan!');
    }

    // Form edit project
    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    // Update project
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'image'       => 'nullable|image|max:2048',
            'visit_url'   => 'nullable|url',
            'github_url'  => 'nullable|url',
            'tags'        => 'nullable|string',
            'order'       => 'nullable|integer',
        ]);

        $data = $request->only(['title', 'description', 'visit_url', 'github_url', 'order']);
        $data['is_featured'] = $request->boolean('is_featured');

        $data['tags'] = $request->tags
            ? array_map('trim', explode(',', $request->tags))
            : [];

        // Ganti gambar kalau ada upload baru
        if ($request->hasFile('image')) {
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            $data['image'] = $request->file('image')->store('projects', 'public');
        }

        $project->update($data);

        return redirect()->route('admin.projects.index')
                         ->with('success', 'Project berhasil diupdate!');
    }

    // Hapus project
    public function destroy(Project $project)
    {
        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }
        $project->delete();

        return redirect()->route('admin.projects.index')
                         ->with('success', 'Project berhasil dihapus!');
    }
}