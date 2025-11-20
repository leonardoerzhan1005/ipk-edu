<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instructor_profile_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_profile_id')->constrained('instructor_profiles')->cascadeOnDelete();
            $table->string('locale', 5); // en, ru, kk
            $table->string('title')->nullable();
            $table->string('position')->nullable();
            $table->string('short_bio', 500)->nullable();
            $table->text('bio')->nullable();
            $table->text('achievements')->nullable();
            $table->text('highlights')->nullable();
            $table->timestamps();
            // Short index name to satisfy MySQL's 64-char limit
            $table->unique(['instructor_profile_id', 'locale'], 'ipt_profile_locale_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instructor_profile_translations');
    }
};


