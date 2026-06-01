<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_quotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->unique()->constrained()->cascadeOnDelete();
            $table->bigInteger('max_size')->default(524288000);
            $table->bigInteger('used_size')->default(0);
            $table->integer('max_files')->default(100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_quotas');
    }
};
