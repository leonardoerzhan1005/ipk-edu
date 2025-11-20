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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            
            // Основная информация
            $table->string('full_name_cyr'); // Ф.И.О. на кириллице
            $table->string('full_name_lat')->nullable(); // Ф.И.О. на латинице
            $table->boolean('is_foreign')->default(false); // Иностранный гражданин
            
            // Образование
            $table->foreignId('faculty_id')->nullable()->constrained('application_faculties')->onDelete('set null');
            $table->foreignId('specialty_id')->nullable()->constrained('application_specialties')->onDelete('set null');
            $table->foreignId('course_id')->nullable()->constrained('courses')->onDelete('set null');
            $table->foreignId('course_language_id')->nullable()->constrained('application_course_languages')->onDelete('set null');
            
            // Работа
            $table->string('workplace')->nullable(); // Место работы
            $table->foreignId('org_type_id')->nullable()->constrained('application_org_types')->onDelete('set null');
            $table->boolean('is_unemployed')->default(false); // Не работаю
            
            // Адрес
            $table->foreignId('country_id')->nullable()->constrained('application_countries')->onDelete('set null');
            $table->foreignId('city_id')->nullable()->constrained('application_cities')->onDelete('set null');
            $table->string('address_line')->nullable(); // Улица, дом и т.д.
            
            // Дополнительно
            $table->foreignId('degree_id')->nullable()->constrained('application_degrees')->onDelete('set null');
            $table->string('position')->nullable(); // Занимаемая должность
            $table->text('subjects')->nullable(); // Преподаваемые предметы
            
            // Контакты
            $table->string('email');
            $table->string('phone');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};



