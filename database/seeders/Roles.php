<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Roles extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ////Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        //Truncate the 'users' table
        DB::table('roles')->truncate();
        //Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
        //Insert new records into the 'users' table
        DB::table('roles')->insert([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        DB::table('roles')->insert([
            'name' => 'Instructor',
            'guard_name' => 'web',
        ]);

        DB::table('roles')->insert([
            'name' => 'Alumno',
            'guard_name' => 'web',
        ]);
    }
}
