<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResourceController;
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

// Route::post('/resources', [ResourceController::class, 'store']);
// Route::put('/resources/{id}', [ResourceController::class, 'update']);
// Route::delete('/resources/{id}', [ResourceController::class, 'destroy']);