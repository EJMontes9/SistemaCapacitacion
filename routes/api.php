<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseUserController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\LessonRatingController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\SurveyResponseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/courses/list', [CourseController::class, 'list']); // api de listado de cursos
Route::get('/courses/{courseId}/sections', [CourseController::class, 'sections']); // api de listado de secciones
Route::get('/sections/{sectionId}/lessons', [CourseController::class, 'lessons']); // api de listado de lecciones
Route::get('/sections/{id}', [CourseController::class, 'getSection']); //api de datos de seccion
Route::get('/lessons/{id}', [CourseController::class, 'getLesson']); //api de datos de lección

// Route::get('/modules2', 'App\Http\Controllers\LessonsController@index2');
Route::get('/modules', 'App\Http\Controllers\ModuleController@index');
Route::get('/modules/{id}', 'ModuleController@show');
Route::post('/modules', 'ModuleController@store');
Route::put('/modules/{id}', 'ModuleController@update');
Route::delete('/modules/{id}', 'ModuleController@destroy');

// creando secciones por api
Route::get('/sections', 'App\Http\Controllers\sectionsController@index');
Route::post('/sections2', 'App\Http\Controllers\sectionsController@addbyApi')->name('sections.addbyApi');

// creeando lecciones por api
Route::post('/lessons', 'App\Http\Controllers\LessonsController@storeLesson');
Route::put('/lessons/{id}', 'App\Http\Controllers\lessonsController@updateLesson');

// creeando recursos por api
Route::post('/resources', 'App\Http\Controllers\ResourceController@store');
Route::put('/resources/{id}', 'App\Http\Controllers\ResourceController@update');
Route::delete('/resources/{id}', 'App\Http\Controllers\ResourceController@destroy')->name('sections.addbyApi');

Route::get('/lessons/{lessonId}/resources', [ResourceController::class, 'getResourcesByLesson']);

// Route::post('/resources', [ResourceController::class, 'store']);
// Route::put('/resources/{id}', [ResourceController::class, 'update']);
// Route::delete('/resources/{id}', [ResourceController::class, 'destroy']);

// Route::resource('surveys', SurveyController::class)->except(['create', 'edit', 'show']);

Route::get('/surveys', [SurveyController::class, 'list']);
Route::post('/surveys', [SurveyController::class, 'store']);
Route::get('/surveys/{id}', [SurveyController::class, 'show']);
Route::put('/surveys/{id}', [SurveyController::class, 'update']);
Route::delete('/surveys/{survey}', [SurveyController::class, 'destroy']);

Route::get('/check-survey/{lessonId}', [SurveyResponseController::class, 'checkSurvey']);
Route::post('/survey-responses', [SurveyResponseController::class, 'store']);
Route::get('/course-statistics/{courseId}', [SurveyResponseController::class, 'getCourseStatistics']);

Route::get('/course/{courseId}/lesson-ratings', [LessonRatingController::class, 'getLessonRatings']);
Route::post('/lesson-ratings', [LessonRatingController::class, 'store']);
Route::get('/course-statistics-rating/{courseId}', [LessonRatingController::class, 'getCourseStatistics']);
Route::get('/course/{courseId}/surveys', [LessonRatingController::class, 'getCourseSurvey']);

//endpoint de estadística de lecciones
Route::get('/course/{courseId}/grades', [EvaluationController::class, 'getCourseGrades']);

//apis de matriculación

Route::get('/course-user/search', [CourseUserController::class, 'search']);
Route::get('/user/{userId}/courses', [CourseUserController::class, 'userCourses']);
Route::get('/course/{courseId}/users', [CourseUserController::class, 'courseUsers']);
Route::post('/course-user/subscribe', [CourseUserController::class, 'subscribeUser']);
Route::get('/course-user/{courseId}/{userId}', [CourseUserController::class, 'subscribeUser2']);

Route::get('/survey-questions/{courseId}', [SurveyController::class, 'getSurveyQuestions']);
Route::get('/survey-responses/{surveyId}', [SurveyController::class, 'getSurveyResponses']);
Route::get('/survey-ratings/{surveyId}', [SurveyController::class, 'getSurveyRatings']);

Route::get('/section-completion-stats/{courseId}', [CourseController::class, 'getSectionCompletionStats']);


Route::get('/lesson-user/{userId}/{lessonId}', function ($userId, $lessonId) {
    $completed = DB::table('lesson_user')
        ->where('user_id', $userId)
        ->where('lesson_id', $lessonId)
        ->exists();

    return response()->json(['completed' => $completed]);
});

Route::post('/lesson-user', function (Request $request) {
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'lesson_id' => 'required|exists:lessons,id',
        'created_at' => 'required|date',
        'updated_at' => 'required|date',
    ]);

    try {
        DB::table('lesson_user')->insert($validated);

        return response()->json(['success' => true, 'message' => 'Data inserted successfully']);
    } catch (Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
});

Route::delete('/lesson-user/{userId}/{lessonId}', function ($userId, $lessonId) {
    try {
        DB::table('lesson_user')
            ->where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->delete();

        return response()->json(['success' => true, 'message' => 'Data deleted successfully']);
    } catch (Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
});

Route::get('/course-completion-stats/{courseId}', function ($courseId) {
    $totalStudents = DB::table('course_user')
        ->where('course_id', $courseId)
        ->count();

    $completedStudents = DB::table('course_user')
        ->join('lesson_user', 'course_user.user_id', '=', 'lesson_user.user_id')
        ->join('lessons', 'lesson_user.lesson_id', '=', 'lessons.id')
        ->join('sections', 'lessons.section_id', '=', 'sections.id')
        ->where('sections.course_id', $courseId)
        ->select('course_user.user_id')
        ->groupBy('course_user.user_id')
        ->havingRaw('COUNT(lesson_user.lesson_id) = (
            SELECT COUNT(*)
            FROM lessons
            JOIN sections ON lessons.section_id = sections.id
            WHERE sections.course_id = ?
        )', [$courseId])
        ->get()
        ->count();

    $incompleteStudents = $totalStudents - $completedStudents;

    return response()->json([
        'completed' => $completedStudents,
        'incomplete' => $incompleteStudents,
    ]);
});

Route::get('/user-courses-progress/{userId}', [CourseUserController::class, 'getUserCoursesProgress']);

Route::get('/course-progress/{courseId}/{userId}', [CourseUserController::class, 'getCourseProgress']);
