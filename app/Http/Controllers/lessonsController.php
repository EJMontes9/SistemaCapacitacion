<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lessons\StoreRequest;
use App\Http\Requests\Lessons\UpdateRequest;
use App\Models\lesson;
use App\Models\platforms;
use App\Models\section;

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

        return redirect()->route('courses.create');
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

        return redirect()->route('courses.index');
    }
}
