<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EvaluationResult;
use App\Models\Evaluation;
use App\Models\User;

class EvaluationResultSeeder extends Seeder
{
    public function run()
    {
        $evaluations = Evaluation::all();
        $users = User::take(3)->get(); // Tomamos 5 usuarios para simular respuestas

        foreach ($evaluations as $evaluation) {
            foreach ($users as $user) {
                EvaluationResult::create([
                    'user_id' => $user->id,
                    'course_id' => $evaluation->course_id,
                    'module_id' => $evaluation->module_id,
                    'evaluation_id' => $evaluation->id,
                    'total_score' => rand(0, 10) // Puntuación aleatoria entre 0 y 10
                ]);
            }
        }
    }
}
