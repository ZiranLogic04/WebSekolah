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
        Schema::create('guru', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 20)->unique();
            $table->string('nama', 100);
            $table->string('bidang_studi', 50)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 50)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('email', 100)->unique()->nullable();
            $table->string('no_telepon', 15)->nullable();
            $table->enum('status_kepegawaian', ['PNS', 'Honorer', 'Kontrak', 'Tetap'])->nullable();
            $table->enum('status_aktif', ['Aktif', 'Non-Aktif', 'Cuti', 'Pensiun'])->default('Aktif');
            $table->string('pendidikan_terakhir', 100)->nullable();
            $table->date('tanggal_mulai_bekerja')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('nip');
            $table->index('nama');
            $table->index('status_aktif');
            $table->index('status_kepegawaian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru');
    }
};