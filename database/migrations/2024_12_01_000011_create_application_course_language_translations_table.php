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
        Schema::create('app_course_lang_trans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_language_id')->constrained('application_course_languages')->onDelete('cascade');
            $table->string('locale', 5);
            $table->string('name');
            $table->timestamps();
            
            $table->unique(['course_language_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_course_lang_trans');
    }
};
