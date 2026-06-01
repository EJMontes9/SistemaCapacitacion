<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('original_name');
            $table->string('filename');
            $table->string('path');
            $table->enum('type', ['image', 'video', 'audio', 'document', 'other']);
            $table->string('mime_type');
            $table->bigInteger('size');
            $table->string('disk')->default('local');
            $table->string('thumbnail_path')->nullable();
            $table->boolean('is_compressed')->default(false);
            $table->integer('downloads_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_files');
    }
};
