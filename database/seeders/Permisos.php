<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Permisos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ////Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        //Truncate the 'users' table
        DB::table('permissions')->truncate();
        //Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
        //Insert new records into the 'users' table

        DB::table('permissions')->insert([
            'name' => 'Crear cursos',
            'guard_name' => 'web',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Leer cursos',
            'guard_name' => 'web',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Actualizar cursos',
            'guard_name' => 'web',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Eliminar cursos',
            'guard_name' => 'web',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Ver dashboard',
            'guard_name' => 'web',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Listar Roles',
            'guard_name' => 'web',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Leer Usuarios',
            'guard_name' => 'web',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Leer categorias',
            'guard_name' => 'web',
        ]);
    }
}
