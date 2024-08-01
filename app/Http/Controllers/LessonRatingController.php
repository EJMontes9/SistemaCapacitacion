<?php


namespace App\Http\Controllers;

use App\Models\LessonRating;
use App\Models\Course;
use Exception;
use Illuminate\Http\Request;
use App\Models\Lesson;
use Illuminate\Support\Facades\DB;
use App\Models\Survey;
use App\Models\SurveyResponse;

class LessonRatingController extends Controller
{

    public function getCourseSurvey($courseId)
    {
        try {
            $userId = request('user_id');

            $surveys = DB::table('surveys')
                ->join('courses', 'surveys.target_id', '=', 'courses.id')
                ->leftJoin('survey_responses', 'surveys.id', '=', 'survey_responses.survey_id')
                ->where('target_id', $courseId)
                ->where('surveys.category', 'course')
                ->select(
                    'surveys.id as survey_id',
                    'surveys.title',
                    'surveys.description',
                    'surveys.target_id',
                    DB::raw('AVG(survey_responses.response_number) as average_rating'),
                    DB::raw('COUNT(survey_responses.id) as rating_count')
                )
                ->groupBy('surveys.id', 'surveys.title', 'surveys.description', 'surveys.target_id')
                ->get();

            $userRatings = DB::table('survey_responses')
                ->where('user_id', $userId)
                ->whereIn('survey_id', $surveys->pluck('survey_id'))
                ->select('survey_id', 'response_number as user_rating')
                ->get()
                ->keyBy('survey_id');

            $formattedSurveys = $surveys->map(function ($survey) use ($userRatings) {
                return [
                    'survey_id' => $survey->survey_id,
                    'title' => $survey->title,
                    'description' => $survey->description,
                    'target_id' => $survey->target_id,
                    'averageRating' => round($survey->average_rating, 2),
                    'ratingCount' => $survey->rating_count,
                    'userRating' => $userRatings->get($survey->survey_id) ? $userRatings->get($survey->survey_id)->user_rating : null,
                ];
            });

            if ($formattedSurveys->isEmpty()) {
                return response()->json([
                    'message' => 'No hay encuestas disponibles para este curso.',
                    'data' => []
                ]);
            }

            return response()->json([
                'message' => 'Encuestas del curso obtenidas con éxito.',
                'data' => $formattedSurveys
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener las encuestas del curso: ' . $e->getMessage()], 500);
        }
    }


    public function getLessonRatings($courseId)
    {
        try {
            $ratings = DB::table('surveys')
                ->join('lessons', 'surveys.target_id', '=', 'lessons.id')
                ->join('sections', 'lessons.section_id', '=', 'sections.id')
                ->leftJoin('survey_responses', function ($join) {
                    $join->on('surveys.id', '=', 'survey_responses.survey_id')
                        ->on('lessons.id', '=', 'survey_responses.lesson_id');
                })
                ->where('sections.course_id', $courseId)
                ->where('surveys.category', 'lesson')
                ->where('surveys.has_rating', true)
                ->select(
                    'lessons.id as lesson_id',
                    'surveys.id as survey_id',
                    'lessons.name as lesson_name',
                    DB::raw('AVG(survey_responses.response_number) as average_rating'),
                    DB::raw('COUNT(DISTINCT survey_responses.id) as rating_count')
                )
                ->groupBy('lessons.id', 'surveys.id', 'lessons.name')
                ->get();

            dd($ratings);

            $userId = request('user_id');

            $userRatings = DB::table('survey_responses')
                ->where('user_id', $userId)
                ->whereIn('survey_id', $ratings->pluck('survey_id'))
                ->select('lesson_id', 'response_number as user_rating')
                ->get()
                ->keyBy('lesson_id');

            $formattedRatings = $ratings->map(function ($rating) use ($userRatings) {
                return [
                    'lesson_id' => $rating->lesson_id,
                    'survey_id' => $rating->survey_id,
                    'lesson_name' => $rating->lesson_name,
                    'averageRating' => round($rating->average_rating, 2),
                    'ratingCount' => $rating->rating_count,
                    'userRating' => $userRatings->get($rating->lesson_id) ?
                        $userRatings->get($rating->lesson_id)->user_rating : null,
                    'has_rating' => true
                ];
            });

            if ($formattedRatings->isEmpty()) {
                return response()->json([
                    'message' => 'No hay encuestas con calificaciones disponibles para las lecciones de este curso.',
                    'data' => []
                ]);
            }

            return response()->json([
                'message' => 'Datos de calificaciones de lecciones obtenidos con éxito.',
                'data' => $formattedRatings
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener las calificaciones de las lecciones: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'lesson_id' => 'required|exists:lessons,id',
                'survey_id' => 'required|exists:surveys,id',
                'rating' => 'required|integer|between:1,5',
            ]);
            $response = SurveyResponse::updateOrCreate(
                [
                    'user_id' => $validatedData['user_id'],
                    'lesson_id' => $validatedData['lesson_id'],
                    'survey_id' => $validatedData['survey_id'],
                ],
                ['response_number' => $validatedData['rating']]
            );
            // Recalcular el promedio y el conteo directamente en la base de datos
            $stats = SurveyResponse::where('survey_id', $validatedData['survey_id'])
                ->where('lesson_id', $validatedData['lesson_id'])
                ->selectRaw('AVG(response_number) as average_rating, COUNT(*) as rating_count')
                ->first();

            return response()->json([
                'message' => 'Calificación guardada con éxito',
                'averageRating' => round($stats->average_rating, 2),
                'ratingCount' => $stats->rating_count,
                'userRating' => $response->response_number
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Error al guardar la calificación: ' . $e->getMessage()
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
                        ->where('surveys.has_rating', '=', true);
                })
                ->leftJoin('survey_responses', function ($join) {
                    $join->on('surveys.id', '=', 'survey_responses.survey_id')
                        ->on('lessons.id', '=', 'survey_responses.lesson_id');
                })
                ->where('sections.course_id', $courseId)
                ->select(
                    'sections.name as section_name',
                    'lessons.name as lesson_name',
                    DB::raw('SUM(CASE WHEN survey_responses.response_number = 1 THEN 1 ELSE 0 END) as rating_1'),
                    DB::raw('SUM(CASE WHEN survey_responses.response_number = 2 THEN 1 ELSE 0 END) as rating_2'),
                    DB::raw('SUM(CASE WHEN survey_responses.response_number = 3 THEN 1 ELSE 0 END) as rating_3'),
                    DB::raw('SUM(CASE WHEN survey_responses.response_number = 4 THEN 1 ELSE 0 END) as rating_4'),
                    DB::raw('SUM(CASE WHEN survey_responses.response_number = 5 THEN 1 ELSE 0 END) as rating_5')
                )
                ->groupBy('sections.name', 'lessons.name')
                ->get();
            $formattedStats = [];
            foreach ($statistics as $stat) {
                $formattedStats[$stat->section_name][$stat->lesson_name] = [
                    1 => $stat->rating_1 ?? 0,
                    2 => $stat->rating_2 ?? 0,
                    3 => $stat->rating_3 ?? 0,
                    4 => $stat->rating_4 ?? 0,
                    5 => $stat->rating_5 ?? 0,
                ];
            }
            return response()->json($formattedStats);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Error al obtener las estadísticas del curso: ' . $e->getMessage()
            ], 500);
        }
    }
}