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
        Schema::create('menu_navbar', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // beranda, akademik, guru, dll
            $table->string('nama'); // Nama yang ditampilkan
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // Insert 7 menu default
        DB::table('menu_navbar')->insert([
            ['slug' => 'beranda', 'nama' => 'Beranda', 'is_active' => true, 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'akademik', 'nama' => 'Akademik', 'is_active' => true, 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'guru', 'nama' => 'Guru', 'is_active' => true, 'urutan' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'siswa', 'nama' => 'Siswa', 'is_active' => true, 'urutan' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'alumni', 'nama' => 'Alumni', 'is_active' => true, 'urutan' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'ppdb', 'nama' => 'PPDB', 'is_active' => true, 'urutan' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'berita', 'nama' => 'Berita', 'is_active' => true, 'urutan' => 7, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_navbar');
    }
};
