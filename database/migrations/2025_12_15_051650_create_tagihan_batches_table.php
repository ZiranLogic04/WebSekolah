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
        Schema::create('tagihan_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_tagihan_id')->constrained('jenis_tagihans')->onDelete('cascade');
            $table->string('nama_tagihan');
            $table->string('target_kelas')->nullable()->comment('Ex: 10A, 11B or NULL for all');
            $table->string('target_gender')->nullable()->comment('L, P, or NULL for all');
            $table->decimal('jumlah', 15, 2);
            $table->date('tenggat_waktu');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan_batches');
    }
};
