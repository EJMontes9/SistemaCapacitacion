<?php

namespace App\Http\Controllers;

use App\Models\SurveyResponse;
use App\Models\Survey;
use App\Models\Courses;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
            $course = Courses::with(['sections.lessons.surveyResponses'])
                ->findOrFail($courseId);

            $statistics = [];

            foreach ($course->sections as $section) {
                $sectionStats = [];
                foreach ($section->lessons as $lesson) {
                    $yesCount = $lesson->surveyResponses->where('response', 'yes')->count();
                    $noCount = $lesson->surveyResponses->where('response', 'no')->count();
                    $sectionStats[$lesson->name] = ['yes' => $yesCount, 'no' => $noCount];
                }
                $statistics[$section->name] = $sectionStats;
            }

            return response()->json([
                'success' => true,
                'data' => $statistics
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Curso no encontrado: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Curso no encontrado',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error en getCourseStatistics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ha ocurrido un error al obtener las estadísticas del curso',
            ], 500);
        }
    }
}