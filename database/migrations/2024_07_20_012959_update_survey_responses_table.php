<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->dropColumn('response');
            $table->string('response_text')->nullable();
            $table->integer('response_number')->nullable();
        });
    }

    public function down()
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->dropColumn(['response_text', 'response_number']);
            $table->enum('response', ['yes', 'no']);
        });
    }
};