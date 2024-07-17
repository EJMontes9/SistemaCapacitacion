<?php

namespace App\Http\Controllers;

use App\Models\SurveyResponse;
use App\Models\Survey;
use App\Models\Courses;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SurveyResponseController extends Controller
{
    public function checkSurvey($lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $survey = Survey::where('target_type', 'App\Models\Lesson')
                        ->where('target_id', $lessonId)
                        ->first();

        if (!$survey) {
            $survey = Survey::where('target_type', 'App\Models\Section')
                            ->where('target_id', $lesson->section_id)
                            ->first();
        }

        if (!$survey) {
            $course = $lesson->section->course;
            $survey = Survey::where('target_type', 'App\Models\Course')
                            ->where('target_id', $course->id)
                            ->first();
        }

        if ($survey) {
            $response = SurveyResponse::where('survey_id', $survey->id)
                                        ->where('user_id', Auth::id())
                                        ->where('lesson_id', $lessonId)
                                        ->first();

            return response()->json([
                'hasSurvey' => true,
                'surveyId' => $survey->id,
                'surveyTitle' => $survey->title,
                'hasResponded' => !is_null($response),
                'response' => $response ? $response->response : null
            ]);
        }

        return response()->json(['hasSurvey' => false]);
    }

    
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'survey_id' => 'required|exists:surveys,id',
                'lesson_id' => 'required|exists:lessons,id',
                'response' => 'required|in:yes,no',
                'user_id' => 'required|exists:users,id'
            ]);

            $response = SurveyResponse::updateOrCreate(
                [
                    'survey_id' => $validatedData['survey_id'],
                    // 'user_id' => Auth::id(),
                    'user_id' => $validatedData['user_id'],
                    'lesson_id' => $validatedData['lesson_id'],
                ],
                [
                    'response' => $validatedData['response'],
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Respuesta guardada correctamente',
                'data' => $response
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Log::error('Error al guardar la respuesta de la encuesta: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ha ocurrido un error al guardar la respuesta',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getCourseStatistics($courseId)
    {
        try {
            $statistics = DB::table('sections')
                ->join('lessons', 'sections.id', '=', 'lessons.section_id')
                ->leftJoin('survey_responses', 'lessons.id', '=', 'survey_responses.lesson_id')
                ->leftJoin('surveys', 'survey_responses.survey_id', '=', 'surveys.id')
                ->where('sections.course_id', $courseId)
                ->select(
                    'sections.name as section_name',
                    'lessons.name as lesson_name',
                    'surveys.title as survey_title',
                    DB::raw('SUM(CASE WHEN survey_responses.response = "yes" THEN 1 ELSE 0 END) as yes_count'),
                    DB::raw('SUM(CASE WHEN survey_responses.response = "no" THEN 1 ELSE 0 END) as no_count')
                )
                ->groupBy('sections.name', 'lessons.name', 'surveys.title')
                ->get();

            $formattedStats = [];
            foreach ($statistics as $stat) {
                $formattedStats[$stat->section_name][$stat->lesson_name] = [
                    'yes' => $stat->yes_count,
                    'no' => $stat->no_count,
                    'survey_title' => $stat->survey_title ?? 'Aún no tiene encuestas'
                ];
            }

            return response()->json($formattedStats);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener estadísticas: ' . $e->getMessage()], 500);
        }
    }
}