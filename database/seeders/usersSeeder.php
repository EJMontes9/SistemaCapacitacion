<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class usersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This method is responsible for populating the 'users' table with initial data.
     * It first disables foreign key checks, then truncates the 'users' table, and finally re-enables foreign key checks.
     * After the table has been truncated, it inserts one new record into the 'users' table.
     */
    public function run(): void
    {
        //Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        //Truncate the 'users' table
        DB::table('users')->truncate();
        //Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
        //Insert new records into the 'users' table
        DB::table('users')->insert([
            'name' => 'Erick',
            'email' => 'erickmontes77.jm@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        DB::table('users')->insert([
            'name' => 'Guillermo JBM',
            'email' => 'guillermo.baquerizom35@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        DB::table('users')->insert([
            'name' => 'Lucia',
            'email' => 'lucia@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        DB::table('users')->insert([
            'name' => 'María',
            'email' => 'maria@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
    }
}
