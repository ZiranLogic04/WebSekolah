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
        Schema::table('guru', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('nama');
            $table->string('jabatan')->nullable()->after('bidang_studi')->comment('Kepala Madrasah, Waka, Guru Mapel, Guru Kelas, dll');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guru', function (Blueprint $table) {
            $table->dropColumn(['foto', 'jabatan']);
        });
    }
};
