<?php

namespace App\Http\Controllers;

use App\Http\Requests\Courses\StoreRequest;
use App\Http\Requests\Courses\UpdateRequest;
use App\Models\category;
use App\Models\courses;
use App\Models\Evaluation;
use App\Models\lesson;
use App\Models\level;
use App\Models\section;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class courseController extends Controller
{
    /**
     * Display a listing of the courses.
     * The courses are paginated in groups of 12.
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter');
        $userId = Auth::id();
        $filterByUser = $request->get('filterByUser'); // Nuevo parámetro para el filtro de usuario

        $courses = courses::query()
            ->when($filterByUser, function ($query) use ($userId) {
                return $query->where('user_id', $userId); // Filtra los cursos por el user_id solo cuando el filtro de usuario está activo
            })
            ->when($filter, function ($query, $filter) {
                return $query->where('title', 'like', "%{$filter}%");
            })
            ->paginate(12);

        return view('listcourse', compact('courses'));
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
        //courses::create($request->validated());

        $data = $request->validated();

        if (isset($data['image'])) {
            $data['image'] = $filename = time().$data['image']->getClientOriginalName();
            $request->validated()['image']->move(public_path('images/courses'), $filename);
        }

        courses::create($data);

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

        $evaluation = Evaluation::query()->where('course_id', $course->id)->get();

        return view('courses-view', compact('course', 'section', 'lesson', 'name_user', 'evaluation'));
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
        $evaluation = Evaluation::query()->where('course_id', $course->id)->get();

        return view('lesson.show-lesson', compact('lesson', 'section', 'course', 'thislesson', 'evaluation'));

    }

    public function mycourse()
    {
        $courses = courses::whereHas('users', function ($query) {
            $query->where('users.id', Auth::id());
        })->paginate(12);

        return view('listcourse', ['courses' => $courses]);
    }

    public function addCourse(courses $course)
    {
        $user = Auth::user();
        $user->courses()->attach($course->id);

        return redirect()->back()->with('success', 'Curso agregado con éxito');
    }

    public function getLessonsCompleted()
    {
        $userId = Auth::id(); // Obtiene el ID del usuario autenticado

        $lessonsCompleted = DB::table('lesson_user')
            ->select(DB::raw('DATE(created_at) as date, count(*) as count'))
            ->where('user_id', $userId) // Filtra por el user_id
            ->groupBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        return response()->json($lessonsCompleted);
    }
}
