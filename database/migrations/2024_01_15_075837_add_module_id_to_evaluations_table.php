<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('evaluations', function (Blueprint $table) {
            if (Schema::hasColumn('evaluations', 'module_id')) {
                $table->dropForeign(['module_id']); // Elimina la restricción de clave foránea
                $table->dropColumn('module_id');
            }

            // Agrega la columna module_id después de eliminarla
            $table->foreignId('module_id')->nullable()->constrained('sections');
        });
    }

    public function down()
    {
        Schema::table('evaluations', function (Blueprint $table) {
            if (Schema::hasColumn('evaluations', 'module_id')) {
                $table->dropForeign(['module_id']); // Elimina la restricción de clave foránea
                $table->dropColumn('module_id');
            }
        });
    }
};
