<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // el orden de los seeders es importante 
        $this->call(levelSeeder::class);
        $this->call(categoriesSeeder::class);
        $this->call(usersSeeder::class);
        $this->call(coursesSeeder::class);
        $this->call(roles::class);
        $this->call(Permisos::class);
        $this->call(platformsSeeder::class);
        $this->call(roles_has_permissionsSeeder::class);
        $this->call(model_has_rolesSeeder::class);
        $this->call(SectionsTableSeeder::class);
        $this->call(LessonSeeder::class);
        $this->call(ResourceSeeder::class);
        $this->call(SurveySeeder::class);
        $this->call(EvaluationSeeder::class);
        $this->call(EvaluationResultSeeder::class);
        $this->call(CourseUserSeeder::class);
        
    }
}
