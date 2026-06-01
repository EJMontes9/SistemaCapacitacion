<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\Course;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::with('course', 'user');

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('is_published')) {
            $query->where('is_published', $request->is_published);
        }

        $announcements = $query->latest()->paginate(15);
        $courses = Course::all();

        return view('admin.announcements.index', compact('announcements', 'courses'));
    }

    public function create()
    {
        $courses = Course::all();
        return view('admin.announcements.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'course_id' => 'nullable|exists:courses,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:published_at',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['is_published'] = $request->boolean('is_published');

        if ($validated['is_published'] && !$request->published_at) {
            $validated['published_at'] = now();
        }

        Announcement::create($validated);

        return redirect()->route('admin.announcements.index')->with('info', 'Anuncio creado con éxito.');
    }

    public function edit(Announcement $announcement)
    {
        $courses = Course::all();
        return view('admin.announcements.edit', compact('announcement', 'courses'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'course_id' => 'nullable|exists:courses,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:published_at',
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        if ($validated['is_published'] && !$request->published_at) {
            $validated['published_at'] = now();
        }

        $announcement->update($validated);

        return redirect()->route('admin.announcements.index')->with('info', 'Anuncio actualizado con éxito.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('admin.announcements.index')->with('info', 'Anuncio eliminado con éxito.');
    }
}
