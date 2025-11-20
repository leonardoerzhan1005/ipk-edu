<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Добавляем поля для связи с факультетами и специализациями
            $table->foreignId('faculty_id')->nullable()->after('category_id');
            $table->foreignId('specialty_id')->nullable()->after('faculty_id');
            
            // Добавляем поле для использования в анкетах
            $table->boolean('is_available_for_applications')->default(false)->after('specialty_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['faculty_id']);
            $table->dropForeign(['specialty_id']);
            $table->dropColumn(['faculty_id', 'specialty_id', 'is_available_for_applications']);
        });
    }
};
