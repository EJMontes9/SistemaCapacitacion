<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Evaluation;
use App\Models\EvaluationAttempt;
use App\Models\EvaluationResult;
use App\Models\GradebookRecord;
use App\Models\Option;
use App\Models\Question;
use App\Models\QuestionBankItem;
use App\Models\Section;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EvaluationController extends Controller
{
    protected $casts = [
        'options' => 'array',
    ];

    public function index(Request $request)
    {
        try {
            if (auth()->user()->roles->pluck('id')->contains(3)) {
                return redirect()->route('courses.mycourse')
                    ->with('error', 'No tienes permiso para ver esta página.');
            }
        } catch (\Exception $e) {
            return redirect()->route('login');
        }

        $search = $request->input('search');
        $perPage = $request->input('perPage', 6);

        $evaluations = Evaluation::query()
            ->with('section')
            ->where('instructor_id', auth()->id())
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->paginate($perPage);

        $firstEvaluation = collect($evaluations->items())->first();
        $courseName = $firstEvaluation ? $firstEvaluation->course->name : 'Nombre del curso por defecto';
        $sectionName = $firstEvaluation && $firstEvaluation->section ? $firstEvaluation->section->name : 'Nombre de la sección por defecto';

        return view('evaluations.index', compact('evaluations', 'courseName', 'sectionName'));
    }

    public function create()
    {
        $userId = auth()->user()->id;
        $courses = Course::where('user_id', $userId)->with('sections')->get();

        return view('evaluations.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string|max:255',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.options.*' => 'required|string|max:255',
            'max_attempts' => 'nullable|integer|min:1',
            'time_limit' => 'nullable|integer|min:0',
            'passing_score' => 'nullable|numeric|min:0|max:999.99',
        ], [
            'questions.*.question.string' => 'El campo pregunta debe ser una cadena de texto.',
            'questions.*.question.max' => 'El campo pregunta no puede tener más de 255 caracteres.',
            'questions.*.options.*.required' => 'El campo opción es obligatorio.',
            'questions.*.options.*.string' => 'El campo opción debe ser una cadena de texto.',
            'questions.*.options.*.max' => 'El campo opción no puede tener más de 255 caracteres.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('evaluations.create')
                ->withErrors($validator)
                ->withInput();
        }

        $evaluation = Evaluation::create([
            'instructor_id' => auth()->id(),
            'course_id' => $request->course_id,
            'title' => $request->title,
            'description' => $request->description,
            'module_id' => $request->module_id,
            'max_attempts' => $request->max_attempts ?? 3,
            'time_limit' => $request->time_limit,
            'passing_score' => $request->passing_score ?? 5.00,
            'allow_retake' => $request->has('allow_retake'),
        ]);

        foreach ($request->questions as $question) {
            $newQuestion = Question::create([
                'question' => $question['question'],
                'evaluation_id' => $evaluation->id,
                'score' => $question['score'],
            ]);

            foreach ($question['options'] as $optionUUID => $option) {
                $isCorrect = isset($question['correct_answer'][$optionUUID]) && $question['correct_answer'][$optionUUID] == true ? true : false;

                Option::create([
                    'question_id' => $newQuestion->id,
                    'options' => $option,
                    'correct_answer' => $isCorrect,
                ]);
            }
        }

        return redirect()->route('evaluations.index')->with('success', 'La evaluación ha sido creada con éxito.');
    }

    public function show($id)
    {
        try {
            $evaluation = Evaluation::with('course', 'module', 'questions.options')->findOrFail($id);
            $course = $evaluation->course;
            $section = $evaluation->module;
        } catch (ModelNotFoundException $e) {
            return redirect()->route('evaluations.index')
                ->with('error', 'No existen registros.');
        }

        if (auth()->user()->id != $evaluation->instructor_id && !auth()->user()->roles->pluck('id')->contains(3)) {
            return redirect()->route('evaluations.index')
                ->with('error', 'Esta evaluación no se encuentra en tus registros.');
        }

        if ($evaluation->module_id != $section->id) {
            return redirect()->route('evaluations.index')
                ->with('error', 'La evaluación no pertenece al módulo especificado.');
        }

        $questions = $evaluation->questions;

        return view('evaluations.show', compact('questions', 'evaluation', 'course', 'section'));
    }

    public function edit($id)
    {
        $evaluation = Evaluation::with('course', 'module', 'questions.options')->find($id);

        if (auth()->user()->id != $evaluation->instructor_id) {
            return redirect()->route('evaluations.index')
                ->with('error', 'No tienes permiso para visualizar esta página');
        }

        $courses = Course::where('user_id', auth()->user()->id)->with('sections')->get();

        return view('evaluations.edit', ['evaluation' => $evaluation, 'courses' => $courses]);
    }

    public function update(Request $request, $id)
    {
        $evaluation = Evaluation::find($id);
        $evaluation->update($request->only('title', 'description', 'course_id', 'module_id', 'max_attempts', 'time_limit', 'passing_score'));
        $evaluation->allow_retake = $request->has('allow_retake');
        $evaluation->save();

        foreach ($evaluation->questions as $question) {
            $question->options()->delete();
            $question->delete();
        }

        foreach ($request->questions as $questionData) {
            $question = Question::create([
                'question' => $questionData['question'],
                'score' => $questionData['score'],
                'evaluation_id' => $evaluation->id,
            ]);

            for ($i = 0; $i < count($questionData['options']); $i++) {
                $isCorrect = isset($questionData['correct_answer'][$i]) && $questionData['correct_answer'][$i] == 'true';

                Option::create([
                    'options' => $questionData['options'][$i],
                    'question_id' => $question->id,
                    'correct_answer' => $isCorrect,
                ]);
            }
        }

        return redirect('/evaluations')->with('success', 'La evaluación ha sido actualizada con éxito.');
    }

    public function destroy(Evaluation $evaluation)
    {
        if (auth()->id() !== $evaluation->instructor_id) {
            return redirect()->route('evaluations.index')->with('error', 'No tienes permiso para eliminar esta evaluación.');
        }

        $evaluation->delete();

        return redirect()->route('evaluations.index')->with('success', "La evaluación {$evaluation->title} ha sido eliminada con éxito.");
    }

    public function startAttempt(Evaluation $evaluation)
    {
        $user = auth()->user();

        $attemptsCount = EvaluationAttempt::where('evaluation_id', $evaluation->id)
            ->where('user_id', $user->id)
            ->count();

        if ($attemptsCount >= $evaluation->max_attempts) {
            return redirect()->back()->with('error', 'Has alcanzado el número máximo de intentos permitidos para esta evaluación.');
        }

        $attempt = EvaluationAttempt::create([
            'evaluation_id' => $evaluation->id,
            'user_id' => $user->id,
            'attempt_number' => $attemptsCount + 1,
            'started_at' => now(),
        ]);

        return redirect()->route('evaluations.show', $evaluation->id)->with('attempt_id', $attempt->id);
    }

    public function finish(Request $request, Evaluation $evaluation)
    {
        $totalScore = 0;
        $totalEvaluationScore = 0;
        $incorrectQuestions = [];
        $unansweredQuestions = [];

        $questions = $evaluation->questions;

        foreach ($questions as $question) {
            $totalEvaluationScore += $question->score;
        }

        $unansweredQuestionIds = array_diff($questions->pluck('id')->toArray(), array_keys($request->questions ?? []));

        foreach ($unansweredQuestionIds as $questionId) {
            $unansweredQuestions[] = [
                'question' => Question::find($questionId),
                'selectedOptions' => [],
            ];
        }

        $answersJson = [];

        if ($request->questions) {
            foreach ($request->questions as $questionId => $selectedOptions) {
                $question = Question::find($questionId);

                if ($question) {
                    $correctOptionIds = $question->options()->where('correct_answer', true)->pluck('id')->toArray();
                    $incorrectOptionIds = $question->options()->where('correct_answer', false)->pluck('id')->toArray();

                    $selectedOptionIds = Option::whereIn('id', $selectedOptions)->pluck('id')->toArray();

                    $allCorrect = empty(array_diff($selectedOptionIds, $correctOptionIds));
                    $hasIncorrect = !empty(array_intersect($selectedOptionIds, $incorrectOptionIds));

                    $answersJson[$questionId] = [
                        'selected' => $selectedOptionIds,
                        'correct' => $allCorrect && !$hasIncorrect,
                    ];

                    if ($allCorrect && !$hasIncorrect) {
                        $totalScore += $question->score * (count($selectedOptionIds) / count($correctOptionIds));
                    } else {
                        $incorrectQuestions[] = [
                            'question' => $question,
                            'selectedOptions' => Option::whereIn('id', $selectedOptions)->get(),
                        ];
                    }
                }
            }
        }

        $user = User::find($request->user_id);
        $course = Course::find($request->course_id);
        $section = Section::find($request->module_id);
        $rolId = auth()->user()->roles->pluck('id')->first();

        $passed = $totalScore >= $evaluation->passing_score;

        $attempt = EvaluationAttempt::where('evaluation_id', $evaluation->id)
            ->where('user_id', $user->id)
            ->whereNull('finished_at')
            ->latest('id')
            ->first();

        if ($attempt) {
            $attempt->update([
                'finished_at' => now(),
                'score' => $totalScore,
                'passed' => $passed,
                'answers' => $answersJson,
            ]);
        }

        $evaluationResults = EvaluationResult::where('user_id', $user->id)
            ->where('evaluation_id', $evaluation->id)
            ->orderBy('created_at', 'desc')
            ->paginate(3);

        if ($user && $course && $section && $evaluation) {
            $newResult = EvaluationResult::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'module_id' => $section->id,
                'evaluation_id' => $evaluation->id,
                'total_score' => $totalScore,
            ]);

            $evaluationResults->prepend($newResult);
        }

        return view('evaluations.finished', [
            'user' => $user,
            'evaluation' => $evaluation,
            'section' => $section,
            'course' => $course,
            'totalScore' => $totalScore,
            'totalEvaluationScore' => $totalEvaluationScore,
            'incorrectQuestions' => $incorrectQuestions,
            'unansweredQuestions' => $unansweredQuestions,
            'evaluationResults' => $evaluationResults,
            'rolId' => $rolId,
        ])->with('success', 'La evaluación ha sido finalizada con éxito.');
    }

    public function view($evaluationId, $userId)
    {
        $evaluation = Evaluation::find($evaluationId);
        $user = User::find($userId);

        $rolId = auth()->user()->roles->pluck('id')->first();

        $evaluationResults = EvaluationResult::where('user_id', $user->id)
            ->where('evaluation_id', $evaluation->id)
            ->orderBy('created_at', 'desc')
            ->paginate(3);

        $course = $evaluation->course;
        $section = $evaluation->module;
        $userName = $user->name;

        return view('evaluations.finished', [
            'evaluation' => $evaluation,
            'user' => $user,
            'evaluationResults' => $evaluationResults,
            'course' => $course,
            'section' => $section,
            'userName' => $userName,
            'rolId' => $rolId,
        ]);
    }

    public function getLowScoreEvaluations()
    {
        $userId = Auth::id();

        $lowScoreEvaluations = DB::table('evaluation_results')
            ->join('courses', 'evaluation_results.course_id', '=', 'courses.id')
            ->join('sections', 'evaluation_results.module_id', '=', 'sections.id')
            ->where('evaluation_results.user_id', $userId)
            ->where('total_score', '<', 9)
            ->select('evaluation_results.*', 'courses.title as course_name', 'sections.name as section_name')
            ->get()
            ->toArray();

        return response()->json($lowScoreEvaluations);
    }

    public function searchEvaluation(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 6);

        $evaluations = Evaluation::query()
            ->with('section')
            ->where('instructor_id', auth()->id())
            ->where('title', 'like', "%{$search}%")
            ->paginate($perPage);

        $firstEvaluation = collect($evaluations->items())->first();
        $courseName = $firstEvaluation ? $firstEvaluation->course->name : 'Nombre del curso por defecto';
        $sectionName = $firstEvaluation && $firstEvaluation->section ? $firstEvaluation->section->name : 'Nombre de la sección por defecto';

        return view('evaluations.index', compact('evaluations', 'courseName', 'sectionName'));
    }

    public function getCourseGrades($courseId)
    {
        if (!Course::find($courseId)) {
            return response()->json([
                'message' => 'El curso especificado no existe.',
                'data' => null
            ], 404);
        }

        $results = EvaluationResult::where('course_id', $courseId)
            ->select('user_id', 'total_score')
            ->get();

        $studentAverages = [];

        foreach ($results as $result) {
            if (!isset($studentAverages[$result->user_id])) {
                $studentAverages[$result->user_id] = [
                    'total_score' => 0,
                    'count' => 0
                ];
            }
            $studentAverages[$result->user_id]['total_score'] += $result->total_score;
            $studentAverages[$result->user_id]['count']++;
        }

        foreach ($studentAverages as $userId => $data) {
            $studentAverages[$userId] = $data['total_score'] / $data['count'];
        }

        $grades = [
            '0-4' => 0,
            '4-6' => 0,
            '6-8' => 0,
            '8-10' => 0
        ];

        foreach ($studentAverages as $average) {
            if ($average >= 0 && $average <= 4) {
                $grades['0-4']++;
            } elseif ($average > 4 && $average <= 6) {
                $grades['4-6']++;
            } elseif ($average > 6 && $average <= 8) {
                $grades['6-8']++;
            } else {
                $grades['8-10']++;
            }
        }

        return response()->json([
            'message' => 'Promedios de calificaciones obtenidos con éxito.',
            'data' => $grades
        ]);
    }

    public function unlink($evaluationId)
    {
        $evaluation = Evaluation::find($evaluationId);
        $evaluation->module_id = null;
        $evaluation->save();

        return back()->with('success', 'La evaluación ha sido desvinculada de la sección.');
    }

    public function reportePorAlumno(Request $request, $courseId, $sectionId)
    {
        $search = $request->input('search');

        $evaluaciones = EvaluationResult::with(['user', 'evaluation'])
            ->whereHas('evaluation', function ($query) use ($courseId, $sectionId) {
                $query->where('course_id', $courseId)->where('module_id', $sectionId);
            })
            ->get()
            ->groupBy('user_id');

        $course = Course::find($courseId);
        $section = Section::find($sectionId);
        $title = $course->title;

        $nameEvaluation = Evaluation::where('course_id', $courseId)->where('module_id', $sectionId)->first()->title;

        $rolId = auth()->user()->roles->pluck('id')->first();

        $datosParaVista = [];
        foreach ($evaluaciones as $userId => $resultados) {
            $alumno = $resultados->first()->user->name;
            if ($search && stripos($alumno, $search) === false) {
                continue;
            }
            $datosParaVista[] = [
                'alumno' => $alumno,
                'resultados' => $resultados->map(function ($resultado) {
                    return [
                        'evaluacion' => $resultado->evaluation->title,
                        'puntuacion' => $resultado->total_score,
                        'fecha' => $resultado->created_at->format('d/m/Y'),
                    ];
                }),
            ];
        }

        return view('evaluations.finished', [
            'datos' => $datosParaVista,
            'course' => $course->title,
            'section' => $section->name,
            'rolId' => $rolId,
            'title' => $title,
            'nameEvaluation' => $nameEvaluation,
            'search' => $search,
            'courseId' => $courseId,
            'sectionId' => $sectionId,
        ]);
    }

    public function gradebookIndex()
    {
        $user = auth()->user();
        if ($user->hasRole('Admin')) {
            $courses = Course::all();
        } else {
            $courses = $user->courses;
        }
        return view('evaluations.gradebook-index', compact('courses'));
    }

    public function gradebook(Request $request, $courseId)
    {
        $course = Course::with('sections')->findOrFail($courseId);
        $students = User::whereHas('roles', function ($q) {
            $q->where('name', 'Alumno');
        })->whereHas('courses', function ($q) use ($courseId) {
            $q->where('course_id', $courseId);
        })->get();

        $evaluations = Evaluation::where('course_id', $courseId)->with('section')->get();

        $sectionFilter = $request->input('section_id');

        if ($sectionFilter) {
            $evaluations = $evaluations->where('module_id', $sectionFilter);
        }

        $gradebookData = [];
        foreach ($students as $student) {
            $row = ['student' => $student->name, 'student_id' => $student->id];
            $totalScore = 0;
            $totalMaxScore = 0;

            foreach ($evaluations as $evaluation) {
                $record = GradebookRecord::where('course_id', $courseId)
                    ->where('user_id', $student->id)
                    ->where('evaluation_id', $evaluation->id)
                    ->first();

                if ($record) {
                    $row['evaluation_' . $evaluation->id] = $record->score . '/' . $record->max_score;
                    $totalScore += $record->score;
                    $totalMaxScore += $record->max_score;
                } else {
                    $bestAttempt = EvaluationAttempt::where('evaluation_id', $evaluation->id)
                        ->where('user_id', $student->id)
                        ->where('passed', true)
                        ->orderBy('score', 'desc')
                        ->first();

                    if (!$bestAttempt) {
                        $bestAttempt = EvaluationAttempt::where('evaluation_id', $evaluation->id)
                            ->where('user_id', $student->id)
                            ->orderBy('score', 'desc')
                            ->first();
                    }

                    if ($bestAttempt) {
                        $row['evaluation_' . $evaluation->id] = ($bestAttempt->score ?? 0) . '/' . ($evaluation->questions->sum('score') ?: 10);
                        $totalScore += $bestAttempt->score ?? 0;
                        $totalMaxScore += $evaluation->questions->sum('score') ?: 10;
                    } else {
                        $row['evaluation_' . $evaluation->id] = '-';
                    }
                }
            }

            $row['average'] = $totalMaxScore > 0 ? round(($totalScore / $totalMaxScore) * 10, 2) : '-';
            $gradebookData[] = $row;
        }

        return view('evaluations.gradebook', compact('course', 'evaluations', 'gradebookData', 'sectionFilter'));
    }

    public function gradebookExport($courseId)
    {
        $course = Course::findOrFail($courseId);
        $students = User::whereHas('roles', function ($q) {
            $q->where('name', 'Alumno');
        })->whereHas('courses', function ($q) use ($courseId) {
            $q->where('course_id', $courseId);
        })->get();

        $evaluations = Evaluation::where('course_id', $courseId)->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="calificaciones_' . $course->title . '.csv"',
        ];

        $callback = function () use ($students, $evaluations, $course) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['Libro de Calificaciones - ' . $course->title]);
            fputcsv($file, []);

            $headerRow = ['Estudiante'];
            foreach ($evaluations as $evaluation) {
                $headerRow[] = $evaluation->title;
            }
            $headerRow[] = 'Promedio';
            fputcsv($file, $headerRow);

            foreach ($students as $student) {
                $row = [$student->name];
                $totalScore = 0;
                $totalMaxScore = 0;

                foreach ($evaluations as $evaluation) {
                    $bestAttempt = EvaluationAttempt::where('evaluation_id', $evaluation->id)
                        ->where('user_id', $student->id)
                        ->orderBy('score', 'desc')
                        ->first();

                    if ($bestAttempt) {
                        $score = $bestAttempt->score ?? 0;
                        $maxScore = $evaluation->questions->sum('score') ?: 10;
                        $row[] = number_format($score, 2) . '/' . number_format($maxScore, 2);
                        $totalScore += $score;
                        $totalMaxScore += $maxScore;
                    } else {
                        $row[] = '-';
                    }
                }

                $row[] = $totalMaxScore > 0 ? number_format(($totalScore / $totalMaxScore) * 10, 2) : '-';
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function questionBank($courseId)
    {
        $course = Course::findOrFail($courseId);
        $questions = QuestionBankItem::where('course_id', $courseId)->with('creator', 'options')->get();

        return view('evaluations.question-bank', compact('course', 'questions'));
    }

    public function storeQuestionBank(Request $request, $courseId)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,short_answer',
            'score' => 'required|numeric|min:0|max:999.99',
            'options' => 'required_if:type,multiple_choice|array|min:2',
            'options.*' => 'required_if:type,multiple_choice|string|max:255',
            'correct_option' => 'required_if:type,multiple_choice',
            'true_false_correct' => 'required_if:type,true_false|in:true,false',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $questionItem = QuestionBankItem::create([
            'course_id' => $courseId,
            'question' => $request->question,
            'type' => $request->type,
            'score' => $request->score,
            'created_by' => auth()->id(),
        ]);

        if ($request->type === 'multiple_choice' && $request->has('options')) {
            foreach ($request->options as $index => $optionText) {
                Option::create([
                    'optionable_id' => $questionItem->id,
                    'optionable_type' => QuestionBankItem::class,
                    'options' => $optionText,
                    'correct_answer' => isset($request->correct_option[$index]) && $request->correct_option[$index] == true,
                ]);
            }
        } elseif ($request->type === 'true_false') {
            Option::create([
                'optionable_id' => $questionItem->id,
                'optionable_type' => QuestionBankItem::class,
                'options' => 'Verdadero',
                'correct_answer' => $request->true_false_correct === 'true',
            ]);
            Option::create([
                'optionable_id' => $questionItem->id,
                'optionable_type' => QuestionBankItem::class,
                'options' => 'Falso',
                'correct_answer' => $request->true_false_correct === 'false',
            ]);
        }

        return redirect()->route('evaluations.question-bank', $courseId)
            ->with('success', 'Pregunta agregada al banco correctamente.');
    }

    public function importFromBank(Request $request, Evaluation $evaluation)
    {
        $validator = Validator::make($request->all(), [
            'question_ids' => 'required|array|min:1',
            'question_ids.*' => 'exists:question_bank_items,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $bankQuestions = QuestionBankItem::whereIn('id', $request->question_ids)->get();

        foreach ($bankQuestions as $bankQuestion) {
            $question = Question::create([
                'question' => $bankQuestion->question,
                'evaluation_id' => $evaluation->id,
                'score' => $bankQuestion->score,
            ]);

            foreach ($bankQuestion->options as $option) {
                Option::create([
                    'question_id' => $question->id,
                    'options' => $option->options,
                    'correct_answer' => $option->correct_answer,
                ]);
            }
        }

        return redirect()->route('evaluations.show', $evaluation->id)
            ->with('success', 'Preguntas importadas del banco correctamente.');
    }
}
