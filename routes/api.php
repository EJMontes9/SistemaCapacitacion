<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SurveyResponseController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\LessonRatingController;
use App\Http\Controllers\CourseUserController;
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
Route::get('/courses/{courseId}/sections', [CourseController::class, 'sections']);// api de listado de secciones
Route::get('/sections/{sectionId}/lessons', [CourseController::class, 'lessons']);// api de listado de lecciones
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
Route::put('/resources/{id}','App\Http\Controllers\ResourceController@update');
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

//endpoint de estadística de lecciones
Route::get('/course/{courseId}/grades', [EvaluationController::class, 'getCourseGrades']);

//apis de matriculación

Route::get('/course-user/search', [CourseUserController::class, 'search']);
Route::get('/user/{userId}/courses', [CourseUserController::class, 'userCourses']);
Route::get('/course/{courseId}/users', [CourseUserController::class, 'courseUsers']);
Route::post('/course-user/subscribe', [CourseUserController::class, 'subscribeUser']);
Route::get('/course-user/{courseId}/{userId}', [CourseUserController::class, 'subscribeUser2']);

Route::get('/survey-questions', [SurveyController::class, 'getSurveyQuestions']);
Route::get('/survey-responses/{surveyId}', [SurveyController::class, 'getSurveyResponses']);