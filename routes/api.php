<?php

use Illuminate\Http\Request;
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


// Route::get('/modules2', 'App\Http\Controllers\LessonsController@index2');
Route::get('/modules', 'App\Http\Controllers\ModuleController@index');
Route::get('/modules/{id}', 'ModuleController@show');
Route::post('/modules', 'ModuleController@store');
Route::put('/modules/{id}', 'ModuleController@update');
Route::delete('/modules/{id}', 'ModuleController@destroy');


Route::get('/sections', 'App\Http\Controllers\sectionsController@index');
Route::post('/sections2', 'App\Http\Controllers\sectionsController@addbyApi')->name('sections.addbyApi');