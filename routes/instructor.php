<?php

use Illuminate\Support\Facades\Route;

Route::redirect('', '/instructor/courses');

Route::get('courses', function () {
    return 'Instructor';
})->middleware('can:Leer cursos')->name('instructors.courses.index');
