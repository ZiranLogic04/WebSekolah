<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->default('book.svg');
            $table->string('title');
            $table->text('description');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed default 4 features
        DB::table('features')->insert([
            [
                'icon' => 'book.svg',
                'title' => 'Pendidikan Bermutu',
                'description' => 'Kualitas pembelajaran unggul dengan pendekatan islami dan lingkungan belajar yang inspiratif.',
                'order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'icon' => 'teacher.svg',
                'title' => 'Guru',
                'description' => 'Tenaga pendidik kompeten, berdedikasi, dan menjadi teladan dalam ilmu serta akhlak.',
                'order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'icon' => 'library.svg',
                'title' => 'Kurikulum Terpadu',
                'description' => 'Mengintegrasikan ilmu umum dan agama untuk membentuk siswa cerdas, beriman, dan berkarakter.',
                'order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'icon' => 'building-2.svg',
                'title' => 'Fasilitas dan Kegiatan',
                'description' => 'Sarana lengkap dan kegiatan edukatif yang menumbuhkan potensi akademik, sosial, dan spiritual siswa.',
                'order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('features');
    }
};
