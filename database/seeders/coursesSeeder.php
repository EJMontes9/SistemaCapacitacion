<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class coursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This method is responsible for populating the 'courses' table with initial data.
     * It first disables foreign key checks, then truncates the 'courses' table, and finally re-enables foreign key checks.
     * After the table has been truncated, it inserts one new record into the 'courses' table.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

        // Truncate the 'courses' table
        DB::table('courses')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        // Insert new records into the 'courses' table
        DB::table('courses')->insert([
            [
                'title' => 'Curso de PHP Nivel Basico',
                'subtitle' => 'Aprendiendo PHP',
                'description' => 'Aprendiendo PHP',
                'status' => '1',
                'slug' => 'curso-de-php',
                'user_id' => '1',
                'level_id' => '1',
                'category_id' => '1',
                'image' => 'course_img.jpg',
            ],
            [
                'title' => 'Curso de JavaScript Nivel Basico',
                'subtitle' => 'Aprendiendo JavaScript',
                'description' => 'Aprendiendo JavaScript',
                'status' => '1',
                'slug' => 'curso-de-javascript',
                'user_id' => '1',
                'level_id' => '1',
                'category_id' => '1',
                'image' => 'course_img.jpg',
            ],
            [
                'title' => 'Curso de Python Nivel Basico',
                'subtitle' => 'Aprendiendo Python',
                'description' => 'Aprendiendo Python',
                'status' => '1',
                'slug' => 'curso-de-python',
                'user_id' => '1',
                'level_id' => '1',
                'category_id' => '1',
                'image' => 'course_img.jpg',
            ],
            [
                'title' => 'Curso de Java Nivel Basico',
                'subtitle' => 'Aprendiendo Java',
                'description' => 'Aprendiendo Java',
                'status' => '1',
                'slug' => 'curso-de-java',
                'user_id' => '1',
                'level_id' => '1',
                'category_id' => '1',
                'image' => 'course_img.jpg',
            ],
            [
                'title' => 'Curso de C# Nivel Basico',
                'subtitle' => 'Aprendiendo C#',
                'description' => 'Aprendiendo C#',
                'status' => '1',
                'slug' => 'curso-de-csharp',
                'user_id' => '1',
                'level_id' => '1',
                'category_id' => '1',
                'image' => 'course_img.jpg',
            ],
            [
                'title' => 'Curso de Ruby Nivel Basico',
                'subtitle' => 'Aprendiendo Ruby',
                'description' => 'Aprendiendo Ruby',
                'status' => '1',
                'slug' => 'curso-de-ruby',
                'user_id' => '1',
                'level_id' => '1',
                'category_id' => '1',
                'image' => 'course_img.jpg',
            ],
            [
                'title' => 'Curso de Swift Nivel Basico',
                'subtitle' => 'Aprendiendo Swift',
                'description' => 'Aprendiendo Swift',
                'status' => '1',
                'slug' => 'curso-de-swift',
                'user_id' => '1',
                'level_id' => '1',
                'category_id' => '1',
                'image' => 'course_img.jpg',
            ],
            [
                'title' => 'Curso de Kotlin Nivel Basico',
                'subtitle' => 'Aprendiendo Kotlin',
                'description' => 'Aprendiendo Kotlin',
                'status' => '1',
                'slug' => 'curso-de-kotlin',
                'user_id' => '1',
                'level_id' => '1',
                'category_id' => '1',
                'image' => 'course_img.jpg',
            ],
            [
                'title' => 'Curso de Go Nivel Basico',
                'subtitle' => 'Aprendiendo Go',
                'description' => 'Aprendiendo Go',
                'status' => '1',
                'slug' => 'curso-de-go',
                'user_id' => '1',
                'level_id' => '1',
                'category_id' => '1',
                'image' => 'course_img.jpg',
            ],
            [
                'title' => 'Curso de Rust Nivel Basico',
                'subtitle' => 'Aprendiendo Rust',
                'description' => 'Aprendiendo Rust',
                'status' => '1',
                'slug' => 'curso-de-rust',
                'user_id' => '1',
                'level_id' => '1',
                'category_id' => '1',
                'image' => 'course_img.jpg',
            ],
            [
                'title' => 'Curso de Scala Nivel Basico',
                'subtitle' => 'Aprendiendo Scala',
                'description' => 'Aprendiendo Scala',
                'status' => '1',
                'slug' => 'curso-de-scala',
                'user_id' => '1',
                'level_id' => '1',
                'category_id' => '1',
                'image' => 'course_img.jpg',
            ],
            [
                'title' => 'Curso de TypeScript Nivel Basico',
                'subtitle' => 'Aprendiendo TypeScript',
                'description' => 'Aprendiendo TypeScript',
                'status' => '1',
                'slug' => 'curso-de-typescript',
                'user_id' => '1',
                'level_id' => '1',
                'category_id' => '1',
                'image' => 'course_img.jpg',
            ],
            [
                'title' => 'Curso de R Nivel Basico',
                'subtitle' => 'Aprendiendo R',
                'description' => 'Aprendiendo R',
                'status' => '1',
                'slug' => 'curso-de-r',
                'user_id' => '1',
                'level_id' => '1',
                'category_id' => '1',
                'image' => 'course_img.jpg',
            ],
            [
                'title' => 'Curso de Julia Nivel Basico',
                'subtitle' => 'Aprendiendo Julia',
                'description' => 'Aprendiendo Julia',
                'status' => '1',
                'slug' => 'curso-de-julia',
                'user_id' => '1',
                'level_id' => '1',
                'category_id' => '1',
                'image' => 'course_img.jpg',
            ],
            [
                'title' => 'Curso de Dart Nivel Basico',
                'subtitle' => 'Aprendiendo Dart',
                'description' => 'Aprendiendo Dart',
                'status' => '1',
                'slug' => 'curso-de-dart',
                'user_id' => '1',
                'level_id' => '1',
                'category_id' => '1',
                'image' => 'course_img.jpg',
            ],
        ]);
    }
}
