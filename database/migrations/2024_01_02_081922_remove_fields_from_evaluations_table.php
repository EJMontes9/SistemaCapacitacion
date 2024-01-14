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
            $table->dropColumn('question');
            $table->dropColumn('options');
            $table->dropColumn('correct_answer');
        });
    }

    public function down()
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->string('question')->nullable();
            $table->json('options')->nullable();
            $table->string('correct_answer')->nullable();
        });
    }
};
