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
        Schema::table('siswa', function (Blueprint $table) {
            // Identity
            $table->string('nisn')->nullable()->unique()->after('nis');
            $table->string('nik')->nullable();
            $table->string('agama')->nullable();
            $table->string('nkk')->nullable();
            $table->integer('anak_ke')->nullable();
            $table->integer('jumlah_saudara')->nullable();
            
            // Address Detail (Alamat already exists, add specific fields)
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('tinggal_bersama')->nullable();
            
            // Parents Detail - Father
            // 'nama_ayah' already exists
            $table->string('nik_ayah')->nullable();
            $table->string('tempat_lahir_ayah')->nullable();
            $table->date('tanggal_lahir_ayah')->nullable();
            $table->string('pekerjaan_ayah')->nullable(); // separate from generic
            $table->string('pendidikan_ayah')->nullable();
            $table->string('penghasilan_ayah')->nullable();
            
            // Parents Detail - Mother
            // 'nama_ibu' already exists
            $table->string('nik_ibu')->nullable();
            $table->string('tempat_lahir_ibu')->nullable();
            $table->date('tanggal_lahir_ibu')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('pendidikan_ibu')->nullable();
            
            // Guardian
            $table->string('nama_wali')->nullable(); // separate from generic
            $table->string('nik_wali')->nullable();
            $table->string('pekerjaan_wali')->nullable();
            $table->string('pendidikan_wali')->nullable();
            $table->string('hubungan_wali')->nullable();
            
            // School Info
            $table->string('status_masuk')->nullable(); // Siswa Basru / Pindahan
            $table->string('diterima_di_kelas')->nullable();
            $table->string('sekolah_asal')->nullable();
            $table->date('tanggal_masuk')->nullable();
            // 'kelas' already exists
            
            // Health & Physical
            $table->text('riwayat_penyakit')->nullable();
            $table->integer('berat_badan')->nullable(); // kg
            $table->integer('tinggi_badan')->nullable(); // cm
            $table->string('golongan_darah', 5)->nullable();
            
            // Misc
            $table->string('cita_cita')->nullable();
            $table->string('hobi')->nullable();
            $table->string('no_akte')->nullable();
            
            // Docs Status
            $table->string('file_foto')->nullable();
            $table->string('file_akte')->nullable();
            $table->string('file_kk')->nullable();
            $table->string('file_kip')->nullable();
            $table->string('file_ktp_ortu')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn([
                'nisn', 'nik', 'agama', 'nkk', 'anak_ke', 'jumlah_saudara',
                'rt', 'rw', 'tinggal_bersama',
                'nik_ayah', 'tempat_lahir_ayah', 'tanggal_lahir_ayah', 'pekerjaan_ayah', 'pendidikan_ayah', 'penghasilan_ayah',
                'nik_ibu', 'tempat_lahir_ibu', 'tanggal_lahir_ibu', 'pekerjaan_ibu', 'pendidikan_ibu',
                'nama_wali', 'nik_wali', 'pekerjaan_wali', 'pendidikan_wali', 'hubungan_wali',
                'status_masuk', 'diterima_di_kelas', 'sekolah_asal', 'tanggal_masuk',
                'riwayat_penyakit', 'berat_badan', 'tinggi_badan', 'golongan_darah',
                'cita_cita', 'hobi', 'no_akte',
                'file_foto', 'file_akte', 'file_kk', 'file_kip', 'file_ktp_ortu'
            ]);
        });
    }
};
