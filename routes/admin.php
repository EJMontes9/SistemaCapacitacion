<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandingController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\CatalogController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\AssignmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\MediaController;

//Solo con rol Admin pueden acceder a esta ruta

Route::get('', [HomeController::class, 'index'])->name('home')->middleware('can:admin.home'); //can:admin.home es una politica de acceso

Route::resource('roles', RoleController::class)->names('roles');

Route::resource('users', UserController::class)->names('users');
Route::get('users/import', [UserController::class, 'importForm'])->name('users.import.form');
Route::post('users/import-csv', [UserController::class, 'importCsv'])->name('users.import');

//Solo con rol Admin pueden acceder a esta ruta
Route::resource('categories', CategoryController::class)->names('categories');

// Branding
Route::get('branding', [BrandingController::class, 'index'])->name('branding.index');
Route::post('branding', [BrandingController::class, 'update'])->name('branding.update');
Route::post('branding/reset', [BrandingController::class, 'reset'])->name('branding.reset');

// Menus
Route::resource('menus', MenuController::class)->names('menus');
Route::post('menus/{menu}/items', [MenuController::class, 'addItem'])->name('menus.items.store');
Route::put('menus/items/{item}', [MenuController::class, 'updateItem'])->name('menus.items.update');
Route::delete('menus/items/{item}', [MenuController::class, 'deleteItem'])->name('menus.items.destroy');
Route::post('menus/items/reorder', [MenuController::class, 'reorder'])->name('menus.items.reorder');
Route::get('menus/{menu}/items', [MenuController::class, 'items'])->name('menus.items');

// Catalogs
Route::resource('catalogs', CatalogController::class)->names('catalogs');
Route::post('catalogs/{catalog}/items', [CatalogController::class, 'storeItem'])->name('catalogs.items.store');
Route::put('catalogs/items/{item}', [CatalogController::class, 'updateItem'])->name('catalogs.items.update');
Route::delete('catalogs/items/{item}', [CatalogController::class, 'destroyItem'])->name('catalogs.items.destroy');
Route::post('catalogs/items/reorder', [CatalogController::class, 'reorderItems'])->name('catalogs.items.reorder');
Route::get('catalogs/{catalog}/items', [CatalogController::class, 'items'])->name('catalogs.items');

Route::resource('attendance', AttendanceController::class)->names('attendance');
Route::post('attendance/{session}/mark', [AttendanceController::class, 'markAttendance'])->name('attendance.mark');

Route::get('calendar', [CalendarController::class, 'index'])->name('calendar.index');
Route::get('calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
Route::post('calendar', [CalendarController::class, 'store'])->name('calendar.store');
Route::put('calendar/{event}', [CalendarController::class, 'update'])->name('calendar.update');
Route::delete('calendar/{event}', [CalendarController::class, 'destroy'])->name('calendar.destroy');
Route::resource('announcements', AnnouncementController::class)->names('announcements');
// Certificate Templates
Route::get('certificates', [CertificateController::class, 'index'])->name('certificates.index');
Route::get('certificates/create', [CertificateController::class, 'createTemplate'])->name('certificates.create');
Route::post('certificates', [CertificateController::class, 'storeTemplate'])->name('certificates.store');
Route::get('certificates/{template}/edit', [CertificateController::class, 'editTemplate'])->name('certificates.edit');
Route::put('certificates/{template}', [CertificateController::class, 'updateTemplate'])->name('certificates.update');
Route::post('certificates/preview', [CertificateController::class, 'preview'])->name('certificates.preview');
Route::post('certificates/generate/{template}', [CertificateController::class, 'generateForCourse'])->name('certificates.generate');
Route::get('certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');
Route::get('certificates/{template}/show', [CertificateController::class, 'showTemplate'])->name('certificates.show');

// Assignments
Route::resource('assignments', AssignmentController::class)->names('assignments');
Route::get('assignments/{assignment}/submissions', [AssignmentController::class, 'submissions'])->name('assignments.submissions');
Route::post('assignments/{assignment}/grade', [AssignmentController::class, 'grade'])->name('assignments.grade');

// Media
Route::resource('media', MediaController::class)->only(['index', 'destroy'])->names('media');
Route::post('media/upload', [MediaController::class, 'upload'])->name('media.upload');
Route::get('media/{media}/download', [MediaController::class, 'download'])->name('media.download');
Route::get('media/{media}/stream', [MediaController::class, 'stream'])->name('media.stream');
