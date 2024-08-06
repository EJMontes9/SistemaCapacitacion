<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use DB;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al crear la encuesta: ' . $e->getMessage()], 500);
        }
    }


    public function show($id)
    {
        try {
            $survey = Survey::findOrFail($id);
            return response()->json($survey);
        } catch (Exception $e) {
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
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Encuesta no encontrada'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al actualizar la encuesta: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Survey $survey)
    {
        try {
            $survey->delete();
            return response()->json(['message' => 'Encuesta eliminada con éxito']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al eliminar la encuesta: ' . $e->getMessage()], 500);
        }
    }


    public function getSurveyQuestions($courseId)
    {
        $questions = DB::table('surveys')
            ->join('lessons', 'surveys.target_id', '=', 'lessons.id')
            ->join('sections', 'lessons.section_id', '=', 'sections.id')
            ->join('courses', 'sections.course_id', '=', 'courses.id')
            ->where('surveys.has_rating', 1)
            ->where('courses.id', $courseId)
            ->select('surveys.id', 'surveys.title as question', 'lessons.name as lesson')
            ->get();

        return response()->json($questions);
    }

    public function getSurveyResponses($surveyId)
    {
        $responses = DB::table('survey_responses')
            ->where('survey_id', $surveyId)
            ->select('response_number', DB::raw('count(*) as count'))
            ->groupBy('response_number')
            ->get();

        $labels = ['1 estrella', '2 estrellas', '3 estrellas', '4 estrellas', '5 estrellas'];
        $data = [0, 0, 0, 0, 0];
        foreach ($responses as $response) {
            if ($response->response_number >= 1 && $response->response_number <= 5) {
                $data[$response->response_number - 1] = $response->count;
            }
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }

    public function getSurveyRatings($courseId)
    {
        $responses = DB::table('survey_responses')
            ->where('lesson_id', $courseId)
            ->select('response_number', DB::raw('count(*) as count'))
            ->groupBy('response_number')
            ->get();

        $ratingCounts = [0, 0, 0, 0, 0];
        foreach ($responses as $response) {
            if ($response->response_number >= 1 && $response->response_number <= 5) {
                $ratingCounts[$response->response_number - 1] = $response->count;
            }
        }
        return response()->json($ratingCounts);
    }


}