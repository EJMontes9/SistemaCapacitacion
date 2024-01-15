<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    // database/migrations/xxxx_xx_xx_xxxxxx_create_evaluations_table.php

    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('users');
            $table->foreignId('course_id')->constrained('courses');
            $table->string('title');
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    //eliminar campos de la tabla options, question, correct_answer
    public function down(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropColumn('options');
            $table->dropColumn('questions');
            $table->dropColumn('correct_answer');
        });
    }
};
