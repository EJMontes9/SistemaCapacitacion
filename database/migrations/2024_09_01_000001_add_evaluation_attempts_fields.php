<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->integer('max_attempts')->default(3)->nullable()->after('module_id');
            $table->integer('time_limit')->nullable()->after('max_attempts');
            $table->decimal('passing_score', 5, 2)->default(5.00)->nullable()->after('time_limit');
            $table->boolean('allow_retake')->default(true)->after('passing_score');
        });
    }

    public function down(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropColumn(['max_attempts', 'time_limit', 'passing_score', 'allow_retake']);
        });
    }
};
