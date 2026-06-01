<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Survey;
use App\Models\Course;

class SurveySeeder extends Seeder
{
    public function run()
    {
        $course = Course::first() ?? Course::factory()->create();

        $surveys = [
            [
                'title' => 'Encuesta de satisfacción del curso',
                'description' => 'Ayúdanos a mejorar nuestros cursos con tu opinión',
                'category' => 'course',
                'target_type' => Course::class,
                'target_id' => $course->id,
                'has_yes_no' => true,
                'has_rating' => true,
                'has_comment' => true,
            ],
            [
                'title' => 'Evaluación de la lección',
                'description' => 'Tu opinión sobre esta lección nos ayuda a mejorar',
                'category' => 'lesson',
                'target_type' => 'App\Models\Lesson',
                'target_id' => 1, // Asumiendo que existe una lección con id 1
                'has_yes_no' => true,
                'has_rating' => true,
                'has_comment' => false,
            ],
        ];

        foreach ($surveys as $surveyData) {
            Survey::create($surveyData);
        }
    }
}