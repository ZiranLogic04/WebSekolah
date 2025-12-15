<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SiswaTemplateExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function array(): array
    {
        // Ambil satu contoh kelas jika ada, kalau tidak pakai default
        $contohKelas = \App\Models\Kelas::first()->nama ?? '10 IPA 1';

        return [
            [
                '12345',                 // NIS
                'Siswa Contoh',          // Nama
                $contohKelas,            // Kelas
                'L',                     // Jenis Kelamin
                'Jakarta',               // Tempat Lahir
                '2005-01-01',            // Tanggal Lahir (YYYY-MM-DD)
                'Jl. Contoh No. 1',      // Alamat
                'Ayah Contoh',           // Nama Ayah
                'Ibu Contoh',            // Nama Ibu
                '08123456789',           // No Telepon
                'Wiraswasta'             // Pekerjaan Orang Tua
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'NIS',
            'Nama',
            'Kelas', // Wajib sama dengan yang ada di database
            'Jenis Kelamin', // L / P
            'Tempat Lahir',
            'Tanggal Lahir', // format excel atau Text DD/MM/YYYY
            'Alamat',
            'Nama Ayah',
            'Nama Ibu',
            'No Telepon',
            'Pekerjaan Orang Tua'
        ];
    }
}
