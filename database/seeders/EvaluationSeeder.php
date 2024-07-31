<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Evaluation;
use App\Models\Courses;

class EvaluationSeeder extends Seeder
{
    public function run()
    {
        $courses = Courses::take(3)->get();

        foreach ($courses as $course) {
            Evaluation::create([
                'instructor_id' => 1, // Asumiendo que el ID 1 es un administrador
                'course_id' => $course->id,
                'title' => "Evaluación del curso: {$course->title}",
                'description' => "Evaluación general para el curso {$course->title}",
                'module_id' => 1 // Asumiendo que es una evaluación general del curso
            ]);
        }
    }
}