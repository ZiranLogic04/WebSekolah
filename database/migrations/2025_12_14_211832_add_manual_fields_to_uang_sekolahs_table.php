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
        Schema::table('uang_sekolahs', function (Blueprint $table) {
            $table->string('kategori')->nullable()->after('kategori_id');
            $table->boolean('is_manual')->default(false)->after('keterangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uang_sekolahs', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'is_manual']);
        });
    }
};
