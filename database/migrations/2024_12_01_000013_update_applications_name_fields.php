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
            // Добавляем поле для связи с пользователем (если его нет)
            if (!Schema::hasColumn('applications', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('phone')->constrained('users')->onDelete('set null');
            }
            
            // Добавляем статус заявки (если его нет)
            if (!Schema::hasColumn('applications', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending')->after('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // Удаляем добавленные поля
            if (Schema::hasColumn('applications', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            
            if (Schema::hasColumn('applications', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
