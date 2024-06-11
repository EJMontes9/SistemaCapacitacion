<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sections = [
            [
                'name' => 'Introducción',
                'course_id' => 1,
            ],
            [
                'name' => 'Conceptos básicos',
                'course_id' => 1,
            ],
            [
                'name' => 'Programación avanzada',
                'course_id' => 2,
            ],
            [
                'name' => 'Proyectos prácticos',
                'course_id' => 2,
            ],
        ];

        DB::table('sections')->insert($sections);
    }
}