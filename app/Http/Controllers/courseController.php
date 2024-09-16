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
use App\Models\Resource;
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
        //  dumpdata de secciones
        // if (session()->has('new_course_sections')) {
        //     $newCourseSections = session('new_course_sections');
        //     dump('Secciones del nuevo curso:', $newCourseSections);
        // }
        // fin dumpdata de secciones

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
        $categories = Category::pluck('name', 'id');
        $levels = Level::pluck('name', 'id');

        // Obtener todas las secciones existentes
        $existingSections = section::all();

        // Agregar un dump para verificar los datos
        // dump($existingSections);

        return view('courses.create-courses', compact('categories', 'levels', 'existingSections'));
    }

    /**
     * Store a newly created course in the database.
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        if (isset($data['image'])) {
            $data['image'] = $filename = time() . $data['image']->getClientOriginalName();
            $request->validated()['image']->move(public_path('images/courses'), $filename);
        }

        $course = courses::create($data); // Crear el curso

        // Guardar las secciones
        $newCourseSections = [];
        if (isset($data['sections'])) {
            foreach ($data['sections'] as $sectionName) {
                if (!empty($sectionName)) {
                    $section = new section();
                    $section->name = $sectionName;
                    $section->course_id = $course->id;
                    $section->save();
                    $newCourseSections[] = $sectionName; // Agregar el nombre de la sección al array
                }
            }
        }
        // Pasar los datos de las secciones a la sesión
        session(['new_course_sections' => $newCourseSections]);

        //redireccion anterior
        // return redirect()->route('courses.index');
        // Redirigir a la pantalla de edición del curso
        return redirect()->route('courses.paso2', $course->id);
    }

    /**
     * Display a specific course.
     */
    public function show(string $slug)
    {
        $course = Courses::where('slug', $slug)->firstOrFail();
        $sections = Section::where('course_id', $course->id)->get();
        $user = User::findOrFail($course->user_id);
        $name_user = $user->name;

        $lessons = Lesson::whereIn('section_id', $sections->pluck('id'))->get()->groupBy('section_id');
        $resources = Resource::whereIn('lesson_id', $lessons->flatten()->pluck('id'))->get()->groupBy('lesson_id');

        $evaluation = Evaluation::where('course_id', $course->id)->get();

        $userId = Auth::id();
        $hasResponded = DB::table('survey_responses')
            ->where('user_id', $userId)
            ->where('lesson_id', $course->id)
            ->whereNotNull('response_number')
            ->exists();

        return view('courses-view', compact('course', 'sections', 'lessons', 'name_user', 'evaluation', 'resources', 'hasResponded'));
    }

    // paso 2 de creación, las secciones
    public function paso2(courses $course)
    {
        $categories = category::pluck('name', 'id');
        $levels = level::pluck('name', 'id');

        $lesson = [];
        $resources = [];
        $numSection = 1;
        $course = courses::where('id', $course->id)->firstOrFail();
        $section = section::where('course_id', $course->id)->get();
        $section_id = section::where('course_id', $course->id)->pluck('id');

        foreach ($section_id as $id) {
            $lesson[$numSection] = lesson::where('section_id', $id)->get();
            // $resources[$numSection] = Resource::where('lesson_id', $id)->get();
            $numSection++;
        }

        $evaluation = Evaluation::query()->where('course_id', $course->id)->get();

        return view('courses.paso2-courses', compact('course', 'section', 'lesson', 'categories', 'levels', 'evaluation', 'resources'));
        // return view('courses.paso2-courses', compact('categories', 'levels', 'course'));
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
            $data['image'] = $filename = time() . $data['image']->getClientOriginalName();
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
        $resources = Resource::where('lesson_id', $id_lesson)->get();
        $evaluation = Evaluation::query()->where('course_id', $course->id)->get();

        $userId = auth()->id();
        $hasResponded = \DB::table('survey_responses')
            ->where('user_id', $userId)
            ->where('lesson_id', $id_lesson)
            ->exists();

        // Obtener la siguiente lección
        $nextLesson = lesson::where('section_id', $thislesson->section_id)
            ->where('id', '>', $thislesson->id)
            ->orderBy('id', 'asc')
            ->first();

        return view('lesson.show-lesson', compact('lesson', 'section', 'course', 'thislesson', 'resources', 'evaluation', 'hasResponded', 'nextLesson'));
    }

    // public function addCourse(courses $course)
    // {
    //     $user = Auth::user();
    //     $user->courses()->attach($course->id);

    //     return redirect()->back()->with('success', 'Curso agregado con éxito');
    // }

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


    public function surveys()
    {
        return view('surveys.manage');
    }

    // metodos para apis 

    public function list() // listado de cursos
    {
        $courses = Courses::select('id', 'title')->get();
        return response()->json($courses);
    }

    public function sections($courseId) //listado de secciones
    {
        $sections = Section::where('course_id', $courseId)->select('id', 'name')->get();
        return response()->json($sections);
    }

    public function lessons($sectionId) // listado de lecciones
    {
        $lessons = Lesson::where('section_id', $sectionId)->select('id', 'name')->get();
        return response()->json($lessons);
    }

    public function getSection($id) //datos de una seccion
    {
        $section = Section::findOrFail($id);
        return response()->json($section);
    }

    public function getLesson($id) //datos de una leccion
    {
        $lesson = Lesson::findOrFail($id);
        return response()->json($lesson);
    }

    public function getSectionCompletionStats($courseId)
    {
        $course = Courses::find($courseId);

        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        $sections = $course->sections()->get();
        $sectionStats = $sections->map(function ($section) {
            $completedStudentsCount = DB::selectOne('
        SELECT COUNT(*) AS completed_users
        FROM (
            SELECT lesson_user.user_id
            FROM lessons
            JOIN lesson_user ON lessons.id = lesson_user.lesson_id
            WHERE lessons.section_id = ?
            GROUP BY lesson_user.user_id
            HAVING COUNT(lesson_user.lesson_id) = (SELECT COUNT(*) FROM lessons WHERE section_id = ?)
        ) AS subquery
    ', [$section->id, $section->id])->completed_users;

            return [
                'name' => $section->name,
                'completed_students_count' => $completedStudentsCount,
            ];
        });

        return response()->json($sectionStats->values()->all());
    }

    public function getAverageGradesBySection($courseId, $userId) {
        $averageGrades = DB::table('evaluation_results')
            ->join('sections', 'evaluation_results.module_id', '=', 'sections.id')
            ->select('sections.name as section_name', DB::raw('AVG(total_score) as average_score'))
            ->where('evaluation_results.course_id', $courseId)
            ->where('evaluation_results.user_id', $userId)
            ->groupBy('sections.name')
            ->get();

        return response()->json($averageGrades);
    }
}
