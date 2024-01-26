<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EvaluationController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::resource('evaluations', EvaluationController::class)->middleware('can:instructor.home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/listcourse', 'App\Http\Controllers\courseController@index');


Route::resource('courses', 'App\Http\Controllers\courseController')->except([
    'show',
]);

Route::get('courses/add/{course}', 'App\Http\Controllers\courseController@addCourse')->name('courses.add');
Route::get('/courses/{slug}/{id_lesson}', 'App\Http\Controllers\courseController@showLesson')->name('courses.showLesson');
Route::get('courses/{slug}', 'App\Http\Controllers\courseController@show')->name('courses.show');
Route::resource('sections', 'App\Http\Controllers\sectionsController');
Route::resource('lessons', 'App\Http\Controllers\lessonsController')->except(['create']);

Route::get('lessons/create/{courseId}', 'App\Http\Controllers\lessonsController@create')->name('lessons.create');



Route::post('/evaluations/{evaluation}/finished', [EvaluationController::class, 'finish'])->name('evaluations.finished');

Route::get('/evaluations/{evaluation}/finished', 'App\Http\Controllers\EvaluationController@finish')->name('evaluations.finished');

//Route::get('/evaluations/{evaluation}/finished', 'EvaluationController@finish')->name('evaluations.finished');
Route::get('mycourses', 'App\Http\Controllers\courseController@mycourse')->name('courses.mycourse');


