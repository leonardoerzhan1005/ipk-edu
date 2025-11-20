<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('locale', 5)->default('kk');
            $table->string('category', 32); // normative, orders, manuals, templates
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('file_format', 16)->nullable();
            $table->string('file_size', 32)->nullable();
            $table->date('published_at')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->index(['locale', 'category', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};


