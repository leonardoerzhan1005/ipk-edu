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
        Schema::table('applications', function (Blueprint $table) {
            // Удаляем старые поля имени
            if (Schema::hasColumn('applications', 'full_name_cyr')) {
                $table->dropColumn('full_name_cyr');
            }
            if (Schema::hasColumn('applications', 'full_name_lat')) {
                $table->dropColumn('full_name_lat');
            }
            
            // Добавляем новые поля для имени на разных языках
            if (!Schema::hasColumn('applications', 'last_name_ru')) {
                $table->string('last_name_ru')->after('id');
            }
            if (!Schema::hasColumn('applications', 'first_name_ru')) {
                $table->string('first_name_ru')->after('last_name_ru');
            }
            if (!Schema::hasColumn('applications', 'middle_name_ru')) {
                $table->string('middle_name_ru')->nullable()->after('first_name_ru');
            }
            
            if (!Schema::hasColumn('applications', 'last_name_kk')) {
                $table->string('last_name_kk')->nullable()->after('middle_name_ru');
            }
            if (!Schema::hasColumn('applications', 'first_name_kk')) {
                $table->string('first_name_kk')->nullable()->after('last_name_kk');
            }
            if (!Schema::hasColumn('applications', 'middle_name_kk')) {
                $table->string('middle_name_kk')->nullable()->after('first_name_kk');
            }
            
            if (!Schema::hasColumn('applications', 'last_name_en')) {
                $table->string('last_name_en')->nullable()->after('middle_name_kk');
            }
            if (!Schema::hasColumn('applications', 'first_name_en')) {
                $table->string('first_name_en')->nullable()->after('last_name_en');
            }
            if (!Schema::hasColumn('applications', 'middle_name_en')) {
                $table->string('middle_name_en')->nullable()->after('first_name_en');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // Восстанавливаем старые поля
            if (!Schema::hasColumn('applications', 'full_name_cyr')) {
                $table->string('full_name_cyr')->after('id');
            }
            if (!Schema::hasColumn('applications', 'full_name_lat')) {
                $table->string('full_name_lat')->nullable()->after('full_name_cyr');
            }
            
            // Удаляем новые поля
            $columnsToDrop = [
                'last_name_ru', 'first_name_ru', 'middle_name_ru',
                'last_name_kk', 'first_name_kk', 'middle_name_kk',
                'last_name_en', 'first_name_en', 'middle_name_en'
            ];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('applications', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
