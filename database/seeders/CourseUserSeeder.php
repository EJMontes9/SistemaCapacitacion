<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Courses;

class CourseUserSeeder extends Seeder
{
    public function run()
    {
        $users = User::take(4)->get();
        $course = Courses::first();

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