<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lessons\StoreRequest;
use App\Http\Requests\Lessons\UpdateRequest;
use App\Models\lesson;
use App\Models\platforms;
use App\Models\section;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;

class lessonsController extends Controller
{
    public function index()
    {

    }

    public function create($courseId = null)
    {
        $platform = platforms::pluck('name', 'id');
        if ($courseId) {
            $section = section::where('course_id', $courseId)->pluck('name', 'id');
        } else {
            $section = section::pluck('name', 'id');
        }
        return view('lesson.create-lesson', compact('platform', 'section'));
    }

    public function store(StoreRequest $request)
    {
        lesson::create($request->validated());
        return back()->with('success', 'La lección se registró correctamente');
    }

    // creando la leección por api
    public function storeLesson(Request $request)
    {
        $lesson = Lesson::create([
            'name' => $request->input('name'),
            'url' => $request->input('url'),
            'section_id' => $request->input('section_id'),
            'platform_id' => $request->input('platform_id'),
            'iframe' => $request->input('iframe')
        ]);
        return response()->json([
            'message' => 'La lección se registró con exito',
            'section' => $lesson
        ]);
    }

    // editando la lección por API
    public function updateLesson(Request $request, $id)
    {
        $lesson = Lesson::find($id);
        if (!$lesson) {
            return response()->json(['error' => 'Lesson not found'], 404);
        }
        $lesson->update($request->all());
        return response()->json([
            'message' => 'La lección se actualizó con éxito',
            'lesson' => $lesson
        ]);
    }



    public function show($id)
    {
        return view('lesson.show-lesson', ['lesson' => lesson::findOrFail($id)]);
    }

    public function edit(lesson $lesson)
    {
        $platform = platforms::pluck('name', 'id');
        $section = section::pluck('name', 'id');

        return view('lesson.edit-lesson', compact('platform', 'section', 'lesson'));
    }

    public function update(UpdateRequest $request, $id)
    {
        $lesson = lesson::findOrFail($id);

        $lesson->update($request->validated());

        return redirect()->route('courses.index');
    }

    public function destroy(lesson $lesson)
    {
        $lesson->delete();

        return back();
    }

    public function markLessonAsSeen(Request $request)
    {
        $userId = Auth::id();
        $lessonId = $request->input('lesson_id');

        DB::table('lesson_user')->insert([
            'user_id' => $userId,
            'lesson_id' => $lessonId,
        ]);

        return response()->json(['success' => true]);
    }
}
