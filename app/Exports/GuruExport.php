<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class GuruExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function array(): array
    {
        return [
            [
                '1234567890123456',       // nip
                'Guru Contoh',            // nama
                'Matematika',             // bidang_studi
                'L',                      // jenis_kelamin (L/P)
                'Jakarta',                // tempat_lahir
                '1985-01-01',             // tanggal_lahir
                'Jl. Contoh No. 1',       // alamat
                'guru@email.com',         // email
                '08123456789',            // no_telepon
                'PNS',                    // status_kepegawaian
                'Aktif',                  // status_aktif
                'S1 Pendidikan',          // pendidikan_terakhir
                '2010-01-01'              // tanggal_mulai_bekerja
            ]
        ];
    }

    public function headings(): array
    {
        // PENTING: Header harus snake_case karena WithHeadingRow di GuruImport
        // akan mengkonversi header menjadi snake_case
        return [
            'nip',
            'nama',
            'bidang_studi',
            'jenis_kelamin',            // L atau P
            'tempat_lahir',
            'tanggal_lahir',            // format YYYY-MM-DD
            'alamat',
            'email',
            'no_telepon',
            'status_kepegawaian',       // PNS, Honorer, Kontrak, Tetap
            'status_aktif',             // Aktif, Non-Aktif, Cuti, Pensiun
            'pendidikan_terakhir',
            'tanggal_mulai_bekerja'     // format YYYY-MM-DD
        ];
    }
}
