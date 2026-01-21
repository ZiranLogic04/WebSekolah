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
        // Tambah kolom snapshot ke tagihan_batches
        Schema::table('tagihan_batches', function (Blueprint $table) {
            $table->integer('total_siswa')->default(0)->after('keterangan');
        });

        // Tambah kolom snapshot ke tagihans
        Schema::table('tagihans', function (Blueprint $table) {
            $table->string('nama_siswa')->nullable()->after('siswa_id');
            $table->string('kelas_siswa')->nullable()->after('nama_siswa');
        });

        // Update existing data: fill total_siswa dari count tagihans
        $batches = \App\Models\TagihanBatch::withCount('tagihans')->get();
        foreach ($batches as $batch) {
            $batch->update(['total_siswa' => $batch->tagihans_count]);
        }

        // Update existing tagihans: fill nama_siswa dan kelas_siswa dari siswa
        $tagihans = \App\Models\Tagihan::with('siswa')->get();
        foreach ($tagihans as $tagihan) {
            if ($tagihan->siswa) {
                $tagihan->update([
                    'nama_siswa' => $tagihan->siswa->nama,
                    'kelas_siswa' => $tagihan->siswa->kelas,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tagihan_batches', function (Blueprint $table) {
            $table->dropColumn('total_siswa');
        });

        Schema::table('tagihans', function (Blueprint $table) {
            $table->dropColumn(['nama_siswa', 'kelas_siswa']);
        });
    }
};
