<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lembaga;

class LembagaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Lembaga::updateOrCreate(
            ['email' => 'info@sekolah-demo.sch.id'],
            [
                'nama_sekolah' => 'SMA NEGERI 1 CONTOH',
                'alamat' => 'Jl. Pendidikan No. 123, Kota Demo, Jawa Barat',
                'telepon' => '(021) 1234567',
                'email' => 'info@sekolah-demo.sch.id',
                'kepala_sekolah' => 'Budi Santoso, S.Pd., M.M.',
                // 'website' and 'nip' not in table
            ]
        );
    }
}
