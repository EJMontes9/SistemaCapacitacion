<?php

namespace App\Http\Controllers;

use App\Models\SurveyResponse;
use App\Models\Survey;
use App\Models\Courses;
use App\Models\Lesson;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SurveyResponseController extends Controller
{
    public function checkSurvey($lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $surveys = collect();

        // Buscar encuestas de la lección
        $lessonSurveys = Survey::where('target_type', 'App\Models\Lesson')
            ->where('target_id', $lessonId)
            ->get();
        $surveys = $surveys->concat($lessonSurveys);

        // Si no hay encuestas de la lección, buscar encuestas de la sección
        if ($surveys->isEmpty()) {
            $sectionSurveys = Survey::where('target_type', 'App\Models\Section')
                ->where('target_id', $lesson->section_id)
                ->get();
            $surveys = $surveys->concat($sectionSurveys);
        }

        // Si aún no hay encuestas, buscar encuestas del curso
        if ($surveys->isEmpty()) {
            $course = $lesson->section->course;
            $courseSurveys = Survey::where('target_type', 'App\Models\Course')
                ->where('target_id', $course->id)
                ->get();
            $surveys = $surveys->concat($courseSurveys);
        }

        if ($surveys->isNotEmpty()) {
            $surveyData = $surveys->map(function ($survey) use ($lessonId) {
                $response = SurveyResponse::where('survey_id', $survey->id)
                    ->where('user_id', Auth::id())
                    ->where('lesson_id', $lessonId)
                    ->first();

                return [
                    'surveyId' => $survey->id,
                    'surveyTitle' => $survey->title,
                    'hasResponded' => !is_null($response),
                    'response' => $response ? $response->response : null,
                    'targetType' => $survey->target_type,
                    'targetId' => $survey->target_id,
                    'hasYesNo' => $survey->has_yes_no
                ];
            });

            return response()->json([
                'hasSurvey' => true,
                'surveys' => $surveyData
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

            $survey = Survey::findOrFail($validatedData['survey_id']);

            if (!$survey->has_yes_no) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta encuesta no admite respuestas de sí/no.'
                ], 422);
            }

            $response = SurveyResponse::updateOrCreate(
                [
                    'survey_id' => $validatedData['survey_id'],
                    'user_id' => $validatedData['user_id'],
                    'lesson_id' => $validatedData['lesson_id'],
                ],
                [
                    'response_text' => $validatedData['response'],
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Respuesta guardada correctamente',
                'data' => $response
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            // \Log::error('Error al guardar la respuesta de la encuesta: ' . $e->getMessage());
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
                ->join('surveys', function ($join) {
                    $join->on('surveys.target_id', '=', 'lessons.id')
                        ->where('surveys.category', '=', 'lesson')
                        ->where('surveys.has_yes_no', '=', true);
                })
                ->leftJoin('survey_responses', function ($join) {
                    $join->on('surveys.id', '=', 'survey_responses.survey_id')
                        ->on('lessons.id', '=', 'survey_responses.lesson_id');
                })
                ->where('sections.course_id', $courseId)
                ->select(
                    'sections.name as section_name',
                    'lessons.name as lesson_name',
                    'surveys.title as survey_title',
                    DB::raw('SUM(CASE WHEN survey_responses.response_text = "yes" THEN 1 ELSE 0 END) as yes_count'),
                    DB::raw('SUM(CASE WHEN survey_responses.response_text = "no" THEN 1 ELSE 0 END) as no_count')
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
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener estadísticas: ' . $e->getMessage()], 500);
        }
    }
}