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
        Schema::create('application_specialty_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specialty_id')->constrained('application_specialties')->onDelete('cascade');
            $table->string('locale', 2); // ru, kk, en
            $table->string('name');
            $table->timestamps();

            $table->unique(['specialty_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_specialty_translations');
    }
};
