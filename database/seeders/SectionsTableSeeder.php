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
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
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
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('sections')->insert($sections);
    }
}