<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class levelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This method is responsible for populating the 'levels' table with initial data.
     * It first disables foreign key checks, then truncates the 'levels' table, and finally re-enables foreign key checks.
     * After the table has been truncated, it inserts three new records into the 'levels' table.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

        // Truncate the 'levels' table
        DB::table('levels')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        // Insert new records into the 'levels' table
        DB::table('levels')->insert([
            'name' => 'Nivel básico',
        ]);
        DB::table('levels')->insert([
            'name' => 'Nivel intermedio',
        ]);
        DB::table('levels')->insert([
            'name' => 'Nivel avanzado',
        ]);
    }
}
