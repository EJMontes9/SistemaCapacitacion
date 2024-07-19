<?php


namespace App\Http\Controllers;

use App\Models\LessonRating;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LessonRatingController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'survey_id' => 'required|exists:surveys,id',
            'lesson_id' => 'required|exists:lessons,id',
            'rating' => 'required|integer|between:1,5',
        ]);

        $rating = LessonRating::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'lesson_id' => $validatedData['lesson_id'],
                'survey_id' => $validatedData['survey_id'],
            ],
            ['rating' => $validatedData['rating']]
        );

        return response()->json(['message' => 'Calificación guardada con éxito', 'rating' => $rating]);
    }

    public function getCourseStatistics($courseId)
    {
        $statistics = DB::table('sections')
            ->join('lessons', 'sections.id', '=', 'lessons.section_id')
            ->leftJoin('lesson_ratings', 'lessons.id', '=', 'lesson_ratings.lesson_id')
            ->where('sections.course_id', $courseId)
            ->select(
                'sections.name as section_name',
                'lessons.name as lesson_name',
                DB::raw('SUM(CASE WHEN lesson_ratings.rating = 1 THEN 1 ELSE 0 END) as rating_1'),
                DB::raw('SUM(CASE WHEN lesson_ratings.rating = 2 THEN 1 ELSE 0 END) as rating_2'),
                DB::raw('SUM(CASE WHEN lesson_ratings.rating = 3 THEN 1 ELSE 0 END) as rating_3'),
                DB::raw('SUM(CASE WHEN lesson_ratings.rating = 4 THEN 1 ELSE 0 END) as rating_4'),
                DB::raw('SUM(CASE WHEN lesson_ratings.rating = 5 THEN 1 ELSE 0 END) as rating_5')
            )
            ->groupBy('sections.name', 'lessons.name')
            ->get();

        $formattedStats = [];
        foreach ($statistics as $stat) {
            $formattedStats[$stat->section_name][$stat->lesson_name] = [
                1 => $stat->rating_1,
                2 => $stat->rating_2,
                3 => $stat->rating_3,
                4 => $stat->rating_4,
                5 => $stat->rating_5,
            ];
        }

        return response()->json($formattedStats);
    }
}