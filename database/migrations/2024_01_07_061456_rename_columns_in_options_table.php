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
        Schema::table('options', function (Blueprint $table) {
            $table->renameColumn('option', 'options');
            $table->renameColumn('is_correct', 'correct_answer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('options', function (Blueprint $table) {
            $table->renameColumn('options', 'option');
            $table->renameColumn('correct_answer', 'is_correct');
        });
    }
};
