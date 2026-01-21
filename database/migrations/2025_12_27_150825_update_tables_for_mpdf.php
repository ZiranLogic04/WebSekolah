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
        Schema::table('lembagas', function (Blueprint $table) {
            $table->string('logo_kiri')->nullable()->after('logo');
            $table->string('logo_kanan')->nullable()->after('logo_kiri');
            $table->string('kop_baris1')->nullable()->after('logo_kanan'); // e.g. PEMERINTAH...
            $table->string('kop_baris2')->nullable()->after('kop_baris1');
            $table->string('kop_baris3')->nullable()->after('kop_baris2');
            $table->string('kop_baris4')->nullable()->after('kop_baris3');
            $table->string('kop_baris5')->nullable()->after('kop_baris4');
        });

        Schema::table('surats', function (Blueprint $table) {
            $table->string('ukuran_kertas')->default('A4')->after('isi_surat'); // A4, F4, Letter
            $table->string('orientation')->default('Portrait')->after('ukuran_kertas'); // Portrait, Landscape
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lembagas', function (Blueprint $table) {
            $table->dropColumn([
                'logo_kiri', 'logo_kanan', 
                'kop_baris1', 'kop_baris2', 'kop_baris3', 'kop_baris4', 'kop_baris5'
            ]);
        });

        Schema::table('surats', function (Blueprint $table) {
            $table->dropColumn(['ukuran_kertas', 'orientation']);
        });
    }
};
