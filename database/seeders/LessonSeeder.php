<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lesson;
use App\Models\Section;

class LessonSeeder extends Seeder
{
    public function run()
    {
        // Asegúrate de que haya secciones y plataformas disponibles
        $sections = Section::all();

        if ($sections->isEmpty() ) {
            $this->command->info('Se necesitan secciones y plataformas para crear lecciones.');
            return;
        }

        $lessons = [
            [
                'name' => 'Introducción al curso',
                'url' => 'https://example.com/lesson1',
                'description'  => 'En esta lección aprenderás los conceptos básicos de la programación',
                'iframe' => '<iframe src="https://example.com/embed1"></iframe>',
            ],
            [
                'name' => 'Conceptos básicos',
                'url' => 'https://example.com/lesson2',
                'description'  => 'En esta lección aprenderás los conceptos básicos de la programación',
                'iframe' => '<iframe src="https://example.com/embed2"></iframe>',
            ],
            [
                'name' => 'Primeros pasos',
                'url' => 'https://example.com/lesson3',
                'description'  => 'En esta lección aprenderás los conceptos básicos de la programación',
                'iframe' => '<iframe src="https://example.com/embed3"></iframe>',
            ],
            [
                'name' => 'Técnicas avanzadas',
                'url' => 'https://example.com/lesson4',
                'description'  => 'En esta lección aprenderás los conceptos básicos de la programación',
                'iframe' => '<iframe src="https://example.com/embed4"></iframe>',
            ],
            [
                'name' => 'Proyecto práctico',
                'url' => 'https://example.com/lesson5',
                'description'  => 'En esta lección aprenderás los conceptos básicos de la programación',
                'iframe' => '<iframe src="https://example.com/embed5"></iframe>',
            ],
            [
                'name' => 'Conclusiones y siguientes pasos',
                'url' => 'https://example.com/lesson6',
                'description'  => 'En esta lección aprenderás los conceptos básicos de la programación',
                'iframe' => '<iframe src="https://example.com/embed6"></iframe>',
            ],
        ];

        foreach ($lessons as $lessonData) {
            Lesson::create([
                'name' => $lessonData['name'],
                'url' => $lessonData['url'],
                'description' => $lessonData['description'],
                'iframe' => $lessonData['iframe'],
                'section_id' => $sections->random()->id,
                'platform_id' => 1,
            ]);
        }
    }
}