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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('icon_class')->nullable(); // e.g., "fas fa-robot"
            $table->json('left_items')->nullable();   // list of strings
            $table->json('right_items')->nullable();  // list of strings
            $table->string('button_label')->nullable();
            $table->string('button_link')->nullable();
            $table->boolean('status')->default(true);
            $table->unsignedInteger('display_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};


