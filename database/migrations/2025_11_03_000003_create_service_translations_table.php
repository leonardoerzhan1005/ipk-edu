<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->string('locale', 5); // ru, kk, en
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->json('left_items')->nullable();
            $table->json('right_items')->nullable();
            $table->timestamps();
            $table->unique(['service_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_translations');
    }
};


