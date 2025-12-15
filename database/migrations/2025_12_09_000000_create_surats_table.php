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
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_surat'); // Example: "Surat Keterangan Lulus"
            $table->string('kode_surat')->nullable(); // Example: "SKL"
            $table->string('kop_baris1')->nullable(); // Header Line 1
            $table->string('kop_baris2')->nullable();
            $table->string('kop_baris3')->nullable();
            $table->string('kop_baris4')->nullable(); // Address usually
            $table->string('kop_baris5')->nullable(); // Email/Telp usually
            $table->string('kop_baris6')->nullable(); // Extra info
            $table->longText('isi_surat'); // HTML Body
            $table->boolean('use_system_header')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
