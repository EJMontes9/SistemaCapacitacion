<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class platformsSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

        // Truncate the 'platforms' table
        DB::table('platforms')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        // Insert new records into the 'platforms' table
        DB::table('platforms')->insert([
            'name' => 'Youtube',
        ]);
    }
}
