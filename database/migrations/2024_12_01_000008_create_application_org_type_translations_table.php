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
        Schema::create('application_org_type_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_type_id')->constrained('application_org_types')->onDelete('cascade');
            $table->string('locale', 5);
            $table->string('name');
            $table->timestamps();
            
            $table->unique(['org_type_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_org_type_translations');
    }
};
