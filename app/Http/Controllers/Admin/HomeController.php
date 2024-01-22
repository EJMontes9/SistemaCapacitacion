<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\Admin\UserController;
use App\http\Controllers\courseController;

class HomeController extends Controller
{
    public function index()
    {
        $numCursos = \App\Models\Courses::count();
        $numAlumnos = \App\Models\User::whereHas('roles', function ($query) {
            $query->where('name', 'alumno');
        })->count();
        $numInstructores = \App\Models\User::whereHas('roles', function ($query) {
            $query->where('name', 'instructor');
        })->count();

        return view('admin.index', compact('numCursos', 'numAlumnos', 'numInstructores'));
    }
}
