<?php

namespace App\Http\Controllers;

use App\Http\Requests\Courses\StoreRequest;
use App\Http\Requests\Courses\UpdateRequest;
use App\Models\category;
use App\Models\courses;
use App\Models\lesson;
use App\Models\level;
use App\Models\section;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class courseController extends Controller
{
    /**
     * Display a listing of the courses.
     * The courses are paginated in groups of 12.
     */
    public function index()
    {
        $courses = courses::where('user_id', Auth::id())->paginate(12);

        return view('listcourse', ['courses' => $courses]);
    }

    /**
     * Show the form for creating a new course.
     * The categories and levels are fetched to be used in the form.
     */
    public function create()
    {
        $categories = category::pluck('name', 'id');
        $levels = level::pluck('name', 'id');

        return view('courses.create-courses', compact('categories', 'levels'));
    }

    /**
     * Store a newly created course in the database.
     * The course data is validated using the StoreRequest class before being stored.
     */
    public function store(StoreRequest $request)
    {
        courses::create($request->validated());

        return redirect()->route('courses.index');
    }

    /**
     * Display a specific course.
     */
    public function show(string $slug)
    {
        $lesson = [];
        $numSection = 1;
        $course = courses::where('slug', $slug)->firstOrFail();
        $section = section::where('course_id', $course->id)->get();
        $section_id = section::where('course_id', $course->id)->pluck('id');
        $user = User::findOrFail($course->user_id);
        $name_user = $user->name;
        foreach ($section_id as $id) {
            $lesson[$numSection] = lesson::where('section_id', $id)->get();
            $numSection++;
        }

        return view('courses-view', compact('course', 'section', 'lesson', 'name_user'));
    }

    /**
     * Show the form for editing a specific course.
     * The categories and levels are fetched to be used in the form.
     */
    public function edit(courses $course)
    {
        $categories = category::pluck('name', 'id');
        $levels = level::pluck('name', 'id');

        return view('courses.edit-courses', compact('categories', 'levels', 'course'));
    }

    /**
     * Update a specific course in the database.
     * The course data is validated using the UpdateRequest class before being updated.
     * If an image is provided, it is stored on the server and the course's image field is updated.
     */
    public function update(UpdateRequest $request, courses $course)
    {
        $data = $request->validated();

        if (isset($data['image'])) {
            $data['image'] = $filename = time().$data['image']->getClientOriginalName();
            $request->validated()['image']->move(public_path('images/courses'), $filename);
        }

        $course->update($data);

        return redirect()->route('courses.index');
    }

    /**
     * Remove a specific course from the database.
     */
    public function destroy(courses $course)
    {
        $course->delete();

        return redirect()->route('courses.index');
    }

    /**
     * Display a specific course's sections and lessons as JSON.
     */
    public function showLesson(string $slug, int $id_lesson)
    {
        $lesson = [];
        $numSection = 1;
        $course = courses::where('slug', $slug)->firstOrFail();
        $section = section::where('course_id', $course->id)->get();
        $section_id = section::where('course_id', $course->id)->pluck('id');
        foreach ($section_id as $id) {
            $lesson[$numSection] = lesson::where('section_id', $id)->get();
            $numSection++;
        }
        $thislesson = lesson::findOrFail($id_lesson);

        return view('lesson.show-lesson', compact('lesson', 'section', 'course', 'thislesson'));

    }
}
