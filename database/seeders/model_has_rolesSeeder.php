<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class model_has_rolesSeeder extends Seeder
{
    public function run(): void
    {
        ////Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        //Truncate the 'users' table
        DB::table('model_has_roles')->truncate();
        //Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        DB::table('model_has_roles')->insert([
            [
                'role_id' => 1,
                'model_type' => 'App\Models\User',
                'model_id' => 1,
            ],
            [
                'role_id' => 2,
                'model_type' => 'App\Models\User',
                'model_id' => 1,
            ],
            [
                'role_id' => 1,
                'model_type' => 'App\Models\User',
                'model_id' => 2,
            ],[
                'role_id' => 2,
                'model_type' => 'App\Models\User',
                'model_id' => 2,
            ],[
                'role_id' => 2,
                'model_type' => 'App\Models\User',
                'model_id' => 3,
            ],[
                'role_id' => 3,
                'model_type' => 'App\Models\User',
                'model_id' => 4,
            ],
        ]);
    }
}
