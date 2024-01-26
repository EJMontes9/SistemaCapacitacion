<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;

//Solo con rol Admin pueden acceder a esta ruta

Route::get('', [HomeController::class, 'index'])->name('home')->middleware('can:admin.home'); //can:admin.home es una politica de acceso

Route::resource('roles', RoleController::class)->names('roles');

Route::resource('users', UserController::class)->only('index', 'edit', 'update')->names('users');
