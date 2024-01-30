<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\Evaluation;
use App\Models\EvaluationResult;
use App\Models\Option;
use App\Models\Question;
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
        $search = $request->input('search');
        $perPage = $request->input('perPage', 6);

        $evaluations = Evaluation::query()
            ->where('instructor_id', auth()->id())
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->paginate($perPage);

        // Obtiene el primer elemento de los resultados paginados
        $firstEvaluation = collect($evaluations->items())->first();

        // Verifica si $firstEvaluation es null antes de intentar acceder a course->name
        $courseName = $firstEvaluation ? $firstEvaluation->course->name : 'Nombre del curso por defecto';

        return view('evaluations.index', compact('evaluations', 'courseName'));
    }

    public function create()
    {
        $userId = auth()->user()->id;
        $courses = Courses::where('user_id', $userId)->with('sections')->get();

        return view('evaluations.create', compact('courses'));
    }

    public function store(Request $request)
    {
        //dd($request->all()); //permite ver lo que se envia en el formulario

        $validator = Validator::make($request->all(), [ //valida los campos del formulario
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string|max:255',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.options.*' => 'required|string|max:255',
        ], [
            'questions.*.question.string' => 'El campo pregunta debe ser una cadena de texto.',
            'questions.*.question.max' => 'El campo pregunta no puede tener más de 255 caracteres.',
            'questions.*.options.*.required' => 'El campo opción es obligatorio.',
            'questions.*.options.*.string' => 'El campo opción debe ser una cadena de texto.',
            'questions.*.options.*.max' => 'El campo opción no puede tener más de 255 caracteres.',
        ]);

        if ($validator->fails()) { //si falla la validacion
            return redirect()
                ->route('tevaluations.create')
                ->withErrors($validator)
                ->withInput();
        }

        $evaluation = Evaluation::create([ //crea la evaluacion
            'instructor_id' => auth()->id(),
            'course_id' => $request->course_id,
            'title' => $request->title,
            'description' => $request->description,
            'module_id' => $request->module_id,
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

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $evaluation = Evaluation::with('course', 'module')->findOrFail($id);
            $course = $evaluation->course; // Asumiendo que la relación en Evaluation se llama "course"
            $section = $evaluation->module; // Asumiendo que la relación en Evaluation se llama "module"
        } catch (ModelNotFoundException $e) {
            return redirect()->route('evaluations.index')
                ->with('error', 'No existen registros.');
        }

        // Verifica si el instructor actual es el propietario de la evaluación
        if (auth()->user()->id != $evaluation->instructor_id && ! auth()->user()->roles->pluck('id')->contains(3)) {
            return redirect()->route('evaluations.index')
                ->with('error', 'Esta evaluación no se encuentra en tus registros.');
        }

        $questions = Question::where('evaluation_id', $id)->with('options')->get();

        return view('evaluations.show', compact('questions', 'evaluation', 'course', 'section'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $evaluation = Evaluation::with('course', 'module', 'questions.options')->find($id);
        $courses = Courses::where('user_id', auth()->user()->id)->with('sections')->get();

        return view('evaluations.edit', ['evaluation' => $evaluation, 'courses' => $courses]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //dd($request->all());

        $evaluation = Evaluation::find($id); //busca la evaluacion por id
        $evaluation->update($request->only('title', 'description', 'course_id', 'module_id')); //actualiza los campos de la evaluacion

        // Eliminar todas las preguntas y sus opciones
        foreach ($evaluation->questions as $question) {
            $question->options()->delete();
            $question->delete();
        }

        foreach ($request->questions as $questionData) { //recorre las preguntas
            // Crear una nueva pregunta
            $question = Question::create([
                'question' => $questionData['question'],
                'score' => $questionData['score'],
                'evaluation_id' => $evaluation->id,
            ]);

            for ($i = 0; $i < count($questionData['options']); $i++) { //recorre las opciones
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evaluation $evaluation)
    {
        // Verifica si el usuario actual tiene permiso para eliminar la evaluación
        if (auth()->id() !== $evaluation->instructor_id) {
            return redirect()->route('evaluations.index')->with('error', 'No tienes permiso para eliminar esta evaluación.');
        }

        // Elimina la evaluación
        $evaluation->delete();

        // Redirige al usuario a la lista de evaluaciones con un mensaje de éxito que contenga el nombre de la evaluacion eliminada
        return redirect()->route('evaluations.index')->with('success', "La evaluación {$evaluation->title} ha sido eliminada con éxito.");
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

        if ($request->questions) {
            foreach ($request->questions as $questionId => $selectedOptions) {
                $question = Question::find($questionId);

                if ($question) {
                    $correctOptionIds = $question->options()->where('correct_answer', true)->pluck('id')->toArray();
                    $incorrectOptionIds = $question->options()->where('correct_answer', false)->pluck('id')->toArray();

                    $selectedOptionIds = Option::whereIn('id', $selectedOptions)->pluck('id')->toArray();

                    // Verifica si todas las opciones seleccionadas son correctas
                    $allCorrect = empty(array_diff($selectedOptionIds, $correctOptionIds));

                    // Verifica si hay opciones incorrectas seleccionadas
                    $hasIncorrect = ! empty(array_intersect($selectedOptionIds, $incorrectOptionIds));

                    if ($allCorrect && ! $hasIncorrect) {
                        // Calcula el puntaje para la pregunta basándose en el número de opciones correctas seleccionadas
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
        $course = Courses::find($request->course_id);
        $section = Section::find($request->module_id);

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
        ])->with('success', 'La evaluación ha sido finalizada con éxito.');
    }

    public function getLowScoreEvaluations()
    {
        $userId = Auth::id(); // Obtiene el ID del usuario autenticado

        $lowScoreEvaluations = DB::table('evaluation_results')
            ->join('courses', 'evaluation_results.course_id', '=', 'courses.id')
            ->join('sections', 'evaluation_results.module_id', '=', 'sections.id')
            ->where('evaluation_results.user_id', $userId) // Filtra por el user_id
            ->where('total_score', '<', 9) // Filtra por calificación menor a nueve
            ->select('evaluation_results.*', 'courses.title as course_name', 'sections.name as section_name')
            ->get()
            ->toArray();

        return response()->json($lowScoreEvaluations);
    }
}
