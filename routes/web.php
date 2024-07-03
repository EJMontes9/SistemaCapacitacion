<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/send-test-email', function () {
    Mail::raw('This is a test email', function ($message) {
        $message->to('guillermobaquerizo35@gmail.com')
                ->subject('Test Email');
    });

    return 'Test email sent!';
});

Route::get('/', function () {
    return view('welcome');
});

Route::resource('evaluations', EvaluationController::class)->middleware('auth');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    //Route::get('/dashboard', 'App\Http\Controllers\courseController@dashboard')->name('dashboard');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/lessons-completed', 'App\Http\Controllers\courseController@getLessonsCompleted');
Route::get('/low-score-evaluations', 'App\Http\Controllers\EvaluationController@getLowScoreEvaluations');

Route::post('/lesson/status', 'App\Http\Controllers\lessonsController@markLessonAsSeen');

Route::get('/listcourse', 'App\Http\Controllers\courseController@index');

Route::resource('courses', 'App\Http\Controllers\courseController')->except([
    'show',
]);
// paso 2 de creacion de curso
Route::get('/courses/{course}/paso2', 'App\Http\Controllers\courseController@paso2')->name('courses.paso2');

Route::get('courses/add/{course}', 'App\Http\Controllers\courseController@addCourse')->name('courses.add');
Route::get('/courses/{slug}/{id_lesson}', 'App\Http\Controllers\courseController@showLesson')->name('courses.showLesson');
Route::get('courses/{slug}', 'App\Http\Controllers\courseController@show')->name('courses.show');
Route::resource('sections', 'App\Http\Controllers\sectionsController');
Route::resource('lessons', 'App\Http\Controllers\lessonsController')->except(['create']);

Route::resource('resources', 'App\Http\Controllers\ResourceController')->except(['create']);

Route::get('lessons/create/{courseId}', 'App\Http\Controllers\lessonsController@create')->name('lessons.create');


//Ruta para invocar el index de evaluations y mostrar todos los resultados

Route::get('/evaluations', 'App\Http\Controllers\EvaluationController@index')->name('evaluations.index');
Route::post('/evaluations/{evaluation}/finished', [EvaluationController::class, 'finish'])->name('evaluations.finished');
Route::get('/evaluations/{evaluation}/finished', 'App\Http\Controllers\EvaluationController@finish')->name('evaluations.finished');
Route::get('/evaluations/{evaluation}/{user}/view', 'App\Http\Controllers\EvaluationController@view')->name('evaluations.view');
Route::get('/searchEvaluation', 'App\Http\Controllers\EvaluationController@searchEvaluation')->name('searchEvaluation');

//Route::get('/evaluations/{evaluation}/finished', 'EvaluationController@finish')->name('evaluations.finished');

Route::get('mycourses', 'App\Http\Controllers\courseController@mycourse')->name('courses.mycourse');

//Categories
Route::resource('admin/categories', 'Admin\CategoryController');

// encuesta de satisfacción 
Route::get('/surveys', 'App\Http\Controllers\courseController@surveys');