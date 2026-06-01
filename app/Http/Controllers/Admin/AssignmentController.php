<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $courses = $this->userIsAdmin($user)
            ? Course::all()
            : Course::where('user_id', $user->id)->get();

        $query = Assignment::with('course', 'user');

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        } elseif (!$this->userIsAdmin($user)) {
            $query->whereIn('course_id', $courses->pluck('id'));
        }

        $assignments = $query->latest()->paginate(15);

        return view('admin.assignments.index', compact('assignments', 'courses'));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        $courses = $this->userIsAdmin($user)
            ? Course::all()
            : Course::where('user_id', $user->id)->get();

        $selectedCourseId = $request->integer('course_id');

        return view('admin.assignments.create', compact('courses', 'selectedCourseId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'max_score' => 'required|integer|min:1|max:10',
            'allow_late' => 'boolean',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['allow_late'] = $request->boolean('allow_late');

        Assignment::create($validated);

        return redirect()->route('admin.assignments.index')->with('info', 'Tarea creada con éxito.');
    }

    public function edit(Assignment $assignment)
    {
        $user = auth()->user();
        $courses = $this->userIsAdmin($user)
            ? Course::all()
            : Course::where('user_id', $user->id)->get();

        return view('admin.assignments.edit', compact('assignment', 'courses'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'max_score' => 'required|integer|min:1|max:10',
            'allow_late' => 'boolean',
        ]);

        $validated['allow_late'] = $request->boolean('allow_late');

        $assignment->update($validated);

        return redirect()->route('admin.assignments.index')->with('info', 'Tarea actualizada con éxito.');
    }

    public function destroy(Assignment $assignment)
    {
        $assignment->delete();
        return redirect()->route('admin.assignments.index')->with('info', 'Tarea eliminada.');
    }

    public function submissions(Assignment $assignment)
    {
        $assignment->load('submissions.user');
        return view('admin.assignments.submissions', compact('assignment'));
    }

    public function grade(Request $request, Assignment $assignment)
    {
        $submission = \App\Models\AssignmentSubmission::findOrFail($request->submission_id);

        $request->validate([
            'score' => 'required|integer|min:0|max:' . $assignment->max_score,
            'feedback' => 'nullable|string',
        ]);

        $submission->update([
            'score' => $request->score,
            'feedback' => $request->feedback,
        ]);

        return redirect()->route('admin.assignments.submissions', $assignment)
            ->with('info', 'Calificación guardada.');
    }

    public function submit(Request $request, Assignment $assignment)
    {
        $request->validate([
            'text_content' => 'nullable|string|max:10000',
            'file' => 'nullable|file|max:51200|mimes:pdf,doc,docx,zip,rar,jpg,jpeg,png,txt',
        ]);

        $user = auth()->user();

        if ($assignment->due_date && strtotime((string) now()) > strtotime((string) $assignment->due_date) && !$assignment->allow_late) {
            return redirect()->back()->with('error', 'El plazo de entrega ya venció y esta tarea no permite entregas tardías.');
        }

        $exists = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('info', 'Ya has entregado esta tarea.');
        }

        $data = [
            'assignment_id' => $assignment->id,
            'user_id' => $user->id,
            'text_content' => $request->text_content,
            'submitted_at' => now(),
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('assignments/' . $assignment->id, 'public');
            $data['file_path'] = $path;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
        }

        AssignmentSubmission::create($data);

        return redirect()->back()->with('info', 'Tarea entregada con éxito.');
    }

    private function userIsAdmin(?\App\Models\User $user): bool
    {
        if (!$user) {
            return false;
        }

        return DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_type', \App\Models\User::class)
            ->where('model_has_roles.model_id', $user->id)
            ->where('roles.name', 'Admin')
            ->exists();
    }
}
