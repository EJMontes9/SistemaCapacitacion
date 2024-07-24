<?php

namespace App\Http\Controllers;

use App\Models\CourseUser;
use App\Models\User;
use App\Models\Courses;
use Illuminate\Http\Request;

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
}