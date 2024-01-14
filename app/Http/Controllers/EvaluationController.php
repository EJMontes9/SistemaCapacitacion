<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Option;
use App\Models\Courses;
use App\Models\Question;

class EvaluationController extends Controller
{
    protected $casts = [
        'options' => 'array',
    ];

    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 20);

        $evaluations = Evaluation::query()
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
        $courses = Courses::all();
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
            'course_id' => 2,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        foreach ($request->questions as $question) {
            $newQuestion = Question::create([
                'question' => $question['question'],
                'evaluation_id' => $evaluation->id,
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
        $questions = Question::where('evaluation_id', $id)->with('options')->get();
        $evaluation = Evaluation::find($id);

        return view('evaluations.show', compact('questions', 'evaluation'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evaluation $evaluation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Evaluation $evaluation)
    {
        //
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
}
