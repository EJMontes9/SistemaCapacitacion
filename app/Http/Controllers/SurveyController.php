<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index()
    {
        $surveys = Survey::all();
        return response()->json($surveys);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'required|array',
        ]);

        $survey = Survey::create($validatedData);
        return response()->json($survey, 201);
    }

    public function update(Request $request, Survey $survey)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'required|array',
        ]);

        $survey->update($validatedData);
        return response()->json($survey);
    }

    public function destroy(Survey $survey)
    {
        $survey->delete();
        return response()->json(null, 204);
    }

    public function surveys()
    {
        return view('surveys.manage');
    }
}
