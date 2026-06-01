<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('modalidad_id')->nullable()->constrained('catalog_items')->nullOnDelete();
            $table->foreignId('periodo_id')->nullable()->constrained('catalog_items')->nullOnDelete();
            $table->foreignId('sede_id')->nullable()->constrained('catalog_items')->nullOnDelete();
            $table->foreignId('nivel_id')->nullable()->constrained('catalog_items')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['modalidad_id']);
            $table->dropForeign(['periodo_id']);
            $table->dropForeign(['sede_id']);
            $table->dropForeign(['nivel_id']);
            $table->dropColumn(['modalidad_id', 'periodo_id', 'sede_id', 'nivel_id']);
        });
    }
};
