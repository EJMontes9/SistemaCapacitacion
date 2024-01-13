<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class categoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This method is responsible for populating the 'categories' table whit initial date.
     * It first disables foreign key checks, then truncates the 'categories' table, and finally re-enables foreign key checks.
     * After the table has been truncated, it inserts six new records into the 'categories' table.
     */
    public function run(): void
    {
        //disable foreign key checks
        DB::Statement('SET FOREIGN_KEY_CHECKS = 0;');
        //truncate the 'categories' table
        DB::table('categories')->truncate();
        //re-enable foreign key checks
        DB::Statement('SET FOREIGN_KEY_CHECKS = 1;');
        //insert new records into the 'categories' table
        DB::table('categories')->insert([
            'name' => 'Desarrollo web',
        ]);
        DB::table('categories')->insert([
            'name' => 'Diseño web',
        ]);
        DB::table('categories')->insert([
            'name' => 'Programación',
        ]);
        DB::table('categories')->insert([
            'name' => 'Base de datos',
        ]);
        DB::table('categories')->insert([
            'name' => 'Sistemas operativos',
        ]);
        DB::table('categories')->insert([
            'name' => 'Ofimática',
        ]);

    }
}
