<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('issued_certificates', function (Blueprint $table) {
            if (!Schema::hasColumn('issued_certificates', 'png_path')) {
                $table->string('png_path')->nullable()->after('file_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('issued_certificates', function (Blueprint $table) {
            if (Schema::hasColumn('issued_certificates', 'png_path')) {
                $table->dropColumn('png_path');
            }
        });
    }
};


