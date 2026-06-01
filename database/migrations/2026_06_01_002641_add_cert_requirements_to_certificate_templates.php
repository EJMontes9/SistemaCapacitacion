<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certificate_templates', function (Blueprint $table) {
            $table->integer('min_attendance')->nullable()->default(80)->after('body_html');
            $table->integer('min_grade')->nullable()->default(70)->after('min_attendance');
        });
    }

    public function down(): void
    {
        Schema::table('certificate_templates', function (Blueprint $table) {
            $table->dropColumn(['min_attendance', 'min_grade']);
        });
    }
};
