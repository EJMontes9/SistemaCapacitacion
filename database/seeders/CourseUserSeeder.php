<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Course;

class CourseUserSeeder extends Seeder
{
    public function run()
    {
        $course = Course::first();
        $users = User::take(5)->get();

        foreach ($users as $user) {
            DB::table('course_user')->insert([
                'course_id' => $course->id,
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}