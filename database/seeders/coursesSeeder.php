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
            'title' => 'Curso de Laravel Nivel Basico',
            'subtitle' => 'Aprendiendo Laravel',
            'description' => 'Aprendiendo Laravel',
            'status' => '1',
            'slug' => 'curso-de-laravel',
            'user_id' => '1',
            'level_id' => '1',
            'category_id' => '1',
            'image' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?q=80&w=1469&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
        ]);
    }
}
