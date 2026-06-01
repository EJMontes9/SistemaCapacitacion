<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Course;

class CalendarSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::all();
        $admin = \App\Models\User::role('admin')->first();

        if (!$admin) {
            return;
        }

        $events = [
            [
                'title' => 'Clase inaugural',
                'description' => 'Sesión de apertura del curso',
                'type' => 'class',
                'event_date' => now()->addDays(7),
                'color' => '#28a745',
                'is_all_day' => false,
            ],
            [
                'title' => 'Entrega de proyecto final',
                'description' => 'Fecha límite para entrega de proyecto',
                'type' => 'deadline',
                'event_date' => now()->addDays(30),
                'color' => '#dc3545',
                'is_all_day' => true,
            ],
            [
                'title' => 'Examen parcial',
                'description' => 'Evaluación de medio término',
                'type' => 'exam',
                'event_date' => now()->addDays(15),
                'event_time' => '10:00:00',
                'color' => '#ffc107',
                'is_all_day' => false,
            ],
            [
                'title' => 'Reunión de coordinación',
                'description' => 'Reunión con instructores',
                'type' => 'meeting',
                'event_date' => now()->addDays(3),
                'event_time' => '14:00:00',
                'location' => 'Sala de juntas',
                'color' => '#17a2b8',
                'is_all_day' => false,
            ],
            [
                'title' => 'Fin de cursos',
                'description' => 'Cierre del período académico',
                'type' => 'deadline',
                'event_date' => now()->addDays(60),
                'end_date' => now()->addDays(61),
                'color' => '#6c757d',
                'is_all_day' => true,
            ],
        ];

        foreach ($events as $index => $eventData) {
            $eventData['user_id'] = $admin->id;
            $eventData['course_id'] = $courses->isNotEmpty() ? $courses->get($index % $courses->count())->id : null;
            Event::create($eventData);
        }
    }
}
