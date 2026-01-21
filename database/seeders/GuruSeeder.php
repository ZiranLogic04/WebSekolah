<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Guru;
use Illuminate\Support\Facades\DB;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data to avoid duplicates if run multiple times (optional, but good for resetting)
        // Guru::truncate(); 

        $guruData = [
            [
                'nip' => '198501152010011001',
                'nama' => 'Drs. H. Ahmad Dahlan, M.Pd.',
                'bidang_studi' => 'Pendidikan Agama Islam',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Yogyakarta',
                'tanggal_lahir' => '1985-01-15',
                'alamat' => 'Jl. Pendidikan No. 10',
                'email' => 'ahmad.dahlan@sekolah.sch.id',
                'no_telepon' => '081234567890',
                'status_kepegawaian' => 'PNS',
                'status_aktif' => 'Aktif',
                'pendidikan_terakhir' => 'S2 Pendidikan Islam',
                'tanggal_mulai_bekerja' => '2010-01-01',
                'jabatan' => 'Kepala Sekolah',
                'facebook' => 'https://facebook.com',
                'instagram' => 'https://instagram.com',
                'linkedin' => 'https://linkedin.com',
                'twitter' => null,
            ],
            [
                'nip' => '199003202015022002',
                'nama' => 'Siti Aminah, S.Pd., M.Si.',
                'bidang_studi' => 'Matematika',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '1990-03-20',
                'alamat' => 'Jl. Merdeka No. 45',
                'email' => 'siti.aminah@sekolah.sch.id',
                'no_telepon' => '081298765432',
                'status_kepegawaian' => 'Tetap',
                'status_aktif' => 'Aktif',
                'pendidikan_terakhir' => 'S2 Matematika',
                'tanggal_mulai_bekerja' => '2015-07-01',
                'jabatan' => 'Wakil Kurikulum',
                'facebook' => 'https://facebook.com',
                'instagram' => 'https://instagram.com',
                'linkedin' => null,
                'twitter' => 'https://twitter.com',
            ],
            [
                'nip' => '198807102012031003',
                'nama' => 'Budi Santoso, S.Kom.',
                'bidang_studi' => 'Teknologi Informasi',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1988-07-10',
                'alamat' => 'Jl. Teknologi No. 88',
                'email' => 'budi.santoso@sekolah.sch.id',
                'no_telepon' => '081345678901',
                'status_kepegawaian' => 'Tetap',
                'status_aktif' => 'Aktif',
                'pendidikan_terakhir' => 'S1 Teknik Informatika',
                'tanggal_mulai_bekerja' => '2012-03-01',
                'jabatan' => 'Guru',
                'facebook' => null,
                'instagram' => 'https://instagram.com',
                'linkedin' => 'https://linkedin.com',
                'twitter' => 'https://twitter.com',
            ],
            [
                'nip' => '199205052018042004',
                'nama' => 'Ratna Sari, S.Pd.',
                'bidang_studi' => 'Bahasa Indonesia',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1992-05-05',
                'alamat' => 'Jl. Sastra No. 21',
                'email' => 'ratna.sari@sekolah.sch.id',
                'no_telepon' => '081456789012',
                'status_kepegawaian' => 'Kontrak',
                'status_aktif' => 'Aktif',
                'pendidikan_terakhir' => 'S1 Pendidikan Bahasa',
                'tanggal_mulai_bekerja' => '2018-04-01',
                'jabatan' => 'Guru',
                'facebook' => 'https://facebook.com',
                'instagram' => null,
                'linkedin' => null,
                'twitter' => null,
            ],
            [
                'nip' => '199512122020051005',
                'nama' => 'Rizky Pratama, S.Pd.Gr.',
                'bidang_studi' => 'Olahraga',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Malang',
                'tanggal_lahir' => '1995-12-12',
                'alamat' => 'Jl. Stadion No. 1',
                'email' => 'rizky.pratama@sekolah.sch.id',
                'no_telepon' => '081567890123',
                'status_kepegawaian' => 'Honorer',
                'status_aktif' => 'Aktif',
                'pendidikan_terakhir' => 'S1 Pendidikan Jasmani',
                'tanggal_mulai_bekerja' => '2020-05-01',
                'jabatan' => 'Guru',
                'facebook' => 'https://facebook.com',
                'instagram' => 'https://instagram.com',
                'linkedin' => 'https://linkedin.com',
                'twitter' => 'https://twitter.com',
            ],
            [
                'nip' => '199309092019062006',
                'nama' => 'Dewi Lestari, S.Si.',
                'bidang_studi' => 'Fisika',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Semarang',
                'tanggal_lahir' => '1993-09-09',
                'alamat' => 'Jl. Alam No. 5',
                'email' => 'dewi.lestari@sekolah.sch.id',
                'no_telepon' => '081678901234',
                'status_kepegawaian' => 'Tetap',
                'status_aktif' => 'Aktif',
                'pendidikan_terakhir' => 'S1 Fisika',
                'tanggal_mulai_bekerja' => '2019-06-01',
                'jabatan' => 'Kepala Lab IPA',
                'facebook' => null,
                'instagram' => null,
                'linkedin' => 'https://linkedin.com',
                'twitter' => null,
            ],
        ];

        foreach ($guruData as $data) {
            // Check if NIP exists to avoid unique constraint error
            if (!Guru::where('nip', $data['nip'])->exists()) {
                Guru::create($data);
            }
        }
    }
}
