<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index()
    {
        return view('surveys.index');
    }

    public function create()
    {
        return view('surveys.create');
    }

    public function list()
    {
        $surveys = Survey::all();
        return response()->json($surveys);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'required|string|in:lesson,course,section',
                'target_id' => 'required|integer',
                'has_yes_no' => 'required|boolean',
                'has_rating' => 'required|boolean',
                'has_comment' => 'required|boolean',
            ]);

            $targetTypes = [
                'lesson' => 'App\Models\Lesson',
                'course' => 'App\Models\Course',
                'section' => 'App\Models\Section',
            ];

            $validatedData['target_type'] = $targetTypes[$validatedData['category']];

            $survey = Survey::create($validatedData);

            return response()->json($survey, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear la encuesta: ' . $e->getMessage()], 500);
        }
    }


    public function show($id)
    {
        try {
            $survey = Survey::findOrFail($id);
            return response()->json($survey);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Encuesta no encontrada'], 404);
        }
    }

    
    public function update(Request $request, $id)
{
    try {
        $survey = Survey::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|in:lesson,course,section',
            'target_id' => 'required|integer',
            'has_yes_no' => 'required|boolean',
            'has_rating' => 'required|boolean',
            'has_comment' => 'required|boolean',
        ]);

        $targetTypes = [
            'lesson' => 'App\Models\Lesson',
            'course' => 'App\Models\Course',
            'section' => 'App\Models\Section',
        ];

        $validatedData['target_type'] = $targetTypes[$validatedData['category']];

        $survey->fill($validatedData);
        $survey->save();

        return response()->json($survey);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['error' => 'Encuesta no encontrada'], 404);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al actualizar la encuesta: ' . $e->getMessage()], 500);
    }
}

    public function destroy(Survey $survey)
    {
        try {
            $survey->delete();
            return response()->json(['message' => 'Encuesta eliminada con éxito']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar la encuesta: ' . $e->getMessage()], 500);
        }
    }
    // ... otros métodos como show, edit, update, destroy ...
}