<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\SurveyController;

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

Route::get('/certificates/verify/{code}', 'App\Http\Controllers\Admin\CertificateController@verify')->name('certificates.verify');

Route::get('/', function () {
    return view('welcome');
})->name('raiz');

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
// Route::get('/surveys', 'App\Http\Controllers\courseController@surveys');
    Route::get('/surveys', [SurveyController::class, 'index'])->name('surveys.index');
    Route::get('/surveys/create', [SurveyController::class, 'create'])->name('surveys.create');

// Route::get('/surveys', [SurveyController::class, 'index'])->name('surveys.index');
// Route::get('/surveys/create', 'App\Http\Controllers\courseController@create')->name('surveys.create');

//Ruta para evaluations unlink
    Route::get('/evaluations/{evaluation}/unlink', 'App\Http\Controllers\EvaluationController@unlink')->name('evaluations.unlink');

//ruta reporteria de evalauciones
    Route::get('reportePorAlumno/{courseId}/{sectionId}', 'App\Http\Controllers\EvaluationController@reportePorAlumno')->name('reportePorAlumno');


//Ruta para evaluaciones report busqueda
//Route::get('/reporte-por-alumno/{courseId}/{sectionId}', [EvaluationController::class, 'reportePorAlumno'])->name('reportePorAlumno');

// Fase 2 - Evaluación y seguimiento avanzado
    Route::get('/gradebook', [EvaluationController::class, 'gradebookIndex'])->name('evaluations.gradebook.index');
    Route::get('/gradebook/{courseId}', [EvaluationController::class, 'gradebook'])->name('evaluations.gradebook');
    Route::get('/gradebook/{courseId}/export', [EvaluationController::class, 'gradebookExport'])->name('evaluations.gradebook.export');
    Route::get('/question-bank/{courseId}', [EvaluationController::class, 'questionBank'])->name('evaluations.question-bank');
    Route::post('/question-bank/{courseId}', [EvaluationController::class, 'storeQuestionBank'])->name('evaluations.question-bank.store');
    Route::post('/evaluations/{evaluation}/import-from-bank', [EvaluationController::class, 'importFromBank'])->name('evaluations.import-from-bank');

    // Asistencia para estudiantes
    Route::get('/my-attendance', [AttendanceController::class, 'myAttendance'])->name('attendance.my');
    Route::get('/my-attendance/{courseId}', [AttendanceController::class, 'myAttendanceByCourse'])->name('attendance.my.course');

    // Dashboard de asistencia por curso (instructor/admin)
    Route::get('/course-attendance/{courseId}', [AttendanceController::class, 'courseAttendanceDashboard'])->name('attendance.course.dashboard');

    // Calendario para estudiantes
    Route::get('/calendar', 'App\Http\Controllers\Admin\CalendarController@studentIndex')->name('calendar.student');

    // Asignaciones - entregas de estudiantes
    Route::post('/assignments/{assignment}/submit', 'App\Http\Controllers\Admin\AssignmentController@submit')->name('assignments.submit');
});

