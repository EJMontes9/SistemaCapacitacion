<?php

namespace App\Http\Controllers;

use App\Models\CourseUser;
use App\Models\User;
use App\Models\Courses;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;

class CourseUserController extends Controller
{
    public function search(Request $request)
    {
        $query = CourseUser::query();

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        $results = $query->get();

        return response()->json($results);
    }

    public function userCourses($userId)
    {
        $user = User::findOrFail($userId);
        $courses = $user->courses;

        return response()->json($courses);
    }

    public function courseUsers($courseId)
    {
        $course = Courses::findOrFail($courseId);
        $users = $course->users;

        return response()->json($users);
    }

    public function subscribeUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        $courseUser = CourseUser::firstOrCreate([
            'user_id' => $request->user_id,
            'course_id' => $request->course_id,
        ]);

        return response()->json($courseUser, 201);
    }

    public function subscribeUser2($courseId, $userId)
    {
        // Verificar si el curso y el usuario existen
        $course = Courses::findOrFail($courseId);
        $user = User::findOrFail($userId);

        // Verificar si ya existe la suscripción
        $existingSubscription = CourseUser::where('course_id', $courseId)
            ->where('user_id', $userId)
            ->first();

        if ($existingSubscription) {
            return response()->json([
                'message' => 'El usuario ya está suscrito a este curso.',
                'subscription' => $existingSubscription
            ], 200);
        }

        // Crear la nueva suscripción
        $courseUser = CourseUser::create([
            'course_id' => $courseId,
            'user_id' => $userId,
        ]);

        return response()->json([
            'message' => 'Usuario suscrito al curso exitosamente.',
            'subscription' => $courseUser
        ], 201);
    }

    public function getUserCoursesProgress($userId)
    {
        try {
            $courses = DB::table('courses')
                ->join('course_user', 'courses.id', '=', 'course_user.course_id')
                ->where('course_user.user_id', $userId)
                ->select('courses.id', 'courses.title')
                ->get();

            $coursesProgress = $courses->map(function ($course) use ($userId) {
                $totalLessons = DB::table('lessons')
                    ->join('sections', 'lessons.section_id', '=', 'sections.id')
                    ->where('sections.course_id', $course->id)
                    ->count();

                $completedLessons = DB::table('lesson_user')
                    ->join('lessons', 'lesson_user.lesson_id', '=', 'lessons.id')
                    ->join('sections', 'lessons.section_id', '=', 'sections.id')
                    ->where('sections.course_id', $course->id)
                    ->where('lesson_user.user_id', $userId)
                    ->count();

                $progress = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;

                return [
                    'title' => $course->title,
                    'progress' => round($progress, 2)
                ];
            });

            return response()->json($coursesProgress);
        } catch (Exception $e) {
            Log::error('Error fetching user courses progress: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function getCourseProgress($courseId, $userId)
    {
        try {
            $course = DB::table('courses')
                ->join('course_user', 'courses.id', '=', 'course_user.course_id')
                ->where('course_user.user_id', $userId)
                ->where('courses.id', $courseId)
                ->select('courses.id', 'courses.title')
                ->first();

            if (!$course) {
                return response()->json(['error' => 'Course not found or user not enrolled'], 404);
            }

            $totalLessons = DB::table('lessons')
                ->join('sections', 'lessons.section_id', '=', 'sections.id')
                ->where('sections.course_id', $course->id)
                ->count();

            $completedLessons = DB::table('lesson_user')
                ->join('lessons', 'lesson_user.lesson_id', '=', 'lessons.id')
                ->join('sections', 'lessons.section_id', '=', 'sections.id')
                ->where('sections.course_id', $course->id)
                ->where('lesson_user.user_id', $userId)
                ->count();

            $progress = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;

            return response()->json([
                'title' => $course->title,
                'progress' => round($progress, 2)
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching course progress: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }


}