<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify status ENUM to include new values
        DB::statement("ALTER TABLE siswa MODIFY COLUMN status ENUM('Aktif', 'Non-Aktif', 'Pindah', 'Lulus', 'Keluar', 'Dikeluarkan', 'Mengundurkan Diri') DEFAULT 'Aktif'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE siswa MODIFY COLUMN status ENUM('Aktif', 'Non-Aktif', 'Pindah', 'Lulus', 'Keluar') DEFAULT 'Aktif'");
    }
};
