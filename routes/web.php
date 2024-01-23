<?php

use App\Http\Controllers\EvaluationController;
use Illuminate\Support\Facades\Route;

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

Route::resource('evaluations', EvaluationController::class)->middleware(['auth:sanctum', 'verified']);

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

Route::get('courses/{slug}', 'App\Http\Controllers\courseController@show')->name('courses.show');
Route::resource('courses', 'App\Http\Controllers\courseController')->except([
    'show',
]);
Route::resource('sections', 'App\Http\Controllers\sectionsController');
Route::resource('lessons', 'App\Http\Controllers\lessonsController');

