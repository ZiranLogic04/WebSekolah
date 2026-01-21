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
        // Menggunakan raw statement karena mengubah enum di Laravel bisa rumit tanpa package doctrine/dbal
        // Pastikan database adalah MySQL/MariaDB
        DB::statement("ALTER TABLE siswa MODIFY COLUMN status ENUM('Aktif', 'Non-Aktif', 'Pindah', 'Lulus', 'Keluar') DEFAULT 'Aktif'");
    }

    public function down(): void
    {
        // Kembalikan ke enum semula (PERINGATAN: Data dengan status 'Keluar' mungkin akan error atau terpotong jika di-rollback)
        DB::statement("ALTER TABLE siswa MODIFY COLUMN status ENUM('Aktif', 'Non-Aktif', 'Pindah', 'Lulus') DEFAULT 'Aktif'");
    }
};
