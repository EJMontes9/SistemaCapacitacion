<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('surveys', function (Blueprint $table) {
            // Primero, eliminamos la clave foránea
            $table->dropForeign(['course_id']);

            // Luego, eliminamos la columna course_id
            $table->dropColumn('course_id');

            // Ahora agregamos las nuevas columnas
            $table->string('category');
            $table->morphs('target');
            $table->boolean('has_yes_no')->default(false);
            $table->boolean('has_rating')->default(false);
            $table->boolean('has_comment')->default(false);
            
            // Si la columna 'questions' existe, la eliminamos
            if (Schema::hasColumn('surveys', 'questions')) {
                $table->dropColumn('questions');
            }
        });
    }

    public function down()
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropColumn(['category', 'target_type', 'target_id', 'has_yes_no', 'has_rating', 'has_comment']);
            $table->unsignedBigInteger('course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->json('questions');
        });
    }
};