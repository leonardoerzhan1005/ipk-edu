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
        Schema::table('blog_translations', function (Blueprint $table) {
            // Изменяем тип столбца с text на longText для поддержки больших объемов данных
            // (например, когда в описании есть изображения в base64)
            $table->longText('description')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_translations', function (Blueprint $table) {
            // Возвращаем обратно к text
            $table->text('description')->change();
        });
    }
};

