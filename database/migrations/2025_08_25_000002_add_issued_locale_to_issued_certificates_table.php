<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('issued_certificates', function (Blueprint $table) {
            if (!Schema::hasColumn('issued_certificates', 'issued_locale')) {
                $table->string('issued_locale', 5)->nullable()->after('issued_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('issued_certificates', function (Blueprint $table) {
            if (Schema::hasColumn('issued_certificates', 'issued_locale')) {
                $table->dropColumn('issued_locale');
            }
        });
    }
};


