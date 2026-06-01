<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gradebook_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('evaluation_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('section_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['evaluation', 'manual'])->default('evaluation');
            $table->decimal('score', 5, 2);
            $table->decimal('max_score', 5, 2);
            $table->decimal('weight', 5, 2)->default(1);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->unique(['course_id', 'user_id', 'evaluation_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gradebook_records');
    }
};
