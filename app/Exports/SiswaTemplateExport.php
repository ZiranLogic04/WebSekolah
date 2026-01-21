<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class SiswaTemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithColumnFormatting
{
    public function array(): array
    {
        // Ambil satu contoh kelas jika ada, check null safety
        $kelasDB = \App\Models\Kelas::first(); 
        $contohKelas = $kelasDB ? $kelasDB->nama : 'X';

        // Generate NIS Random agar tidak duplicate saat user test import file template
        // Format: Tahun + 4 digit random
        $randomNIS = date('Y') . rand(1000, 9999);
        $randomNISN = '00' . rand(10000000, 99999999);

        return [
            [
                (string)$randomNIS,     // NIS
                (string)$randomNISN,    // NISN
                'Putra Bangsa',         // Nama Siswa
                $contohKelas,           // Kelas (Valid Data)
                'L',                    // L/P
                'Jakarta',              // Tempat Lahir
                '2008-05-20',           // Tgl Lahir
                'Islam',                // Agama
                '3171012005080001',     // NIK
                '3171012005080000',     // KK
                '1',                    // Anak Ke
                '2',                    // Jml Sdr
                'Jl. Sudirman No. 45',  // Alamat
                '005',                  // RT
                '002',                  // RW
                'Orang Tua',            // Tinggal Bersama
                '081234567890',         // No HP
                'Budi Santoso',         // Nama Ayah
                '3201015005700001',     // NIK Ayah
                'Surabaya',             // Tempat Lahir Ayah
                '1970-05-15',           // Tgl Lahir Ayah (YYYY-MM-DD)
                'Wiraswasta',           // Pekerjaan Ayah
                'S1',                   // Pendidikan Ayah
                '5000000',              // Penghasilan
                'Siti Aminah',          // Nama Ibu
                '3201016008750002',     // NIK Ibu
                'Bandung',              // Tempat Lahir Ibu
                '1975-08-20',           // Tgl Lahir Ibu
                'Ibu Rumah Tangga',     // Pekerjaan Ibu
                'SMA',                  // Pendidikan Ibu
                '',                     // Nama Wali (Kosongkan jika tidak ada)
                '',                     // NIK Wali
                '',                     // Pekerjaan Wali
                '',                     // Pendidikan Wali
                '',                     // Hubungan Wali
                'SMP Negeri 1 Jakarta', // Sekolah Asal
                '2024-07-15',           // Tgl Masuk
                '001/P/2008'            // No Akte
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'NIS', 'NISN', 'Nama Siswa', 'Kelas', 'L/P', 'Tempat Lahir', 'Tgl Lahir', 'Agama',
            'NIK', 'No KK', 'Anak Ke', 'Jml Saudara', 
            'Alamat', 'RT', 'RW', 'Tinggal Bersama', 'No HP',
            'Nama Ayah', 'NIK Ayah', 'Tempat Lahir Ayah', 'Tgl Lahir Ayah', 'Pekerjaan Ayah', 'Pendidikan Ayah', 'Penghasilan Ayah',
            'Nama Ibu', 'NIK Ibu', 'Tempat Lahir Ibu', 'Tgl Lahir Ibu', 'Pekerjaan Ibu', 'Pendidikan Ibu',
            'Nama Wali', 'NIK Wali', 'Pekerjaan Wali', 'Pendidikan Wali', 'Hubungan Wali',
            'Sekolah Asal', 'Tgl Masuk', 'No Akte'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT, // NIS
            'B' => NumberFormat::FORMAT_TEXT, // NISN
            'I' => NumberFormat::FORMAT_TEXT, // NIK
            'J' => NumberFormat::FORMAT_TEXT, // No KK
            'Q' => NumberFormat::FORMAT_TEXT, // No HP
            'S' => NumberFormat::FORMAT_TEXT, // NIK Ayah
            'Z' => NumberFormat::FORMAT_TEXT, // NIK Ibu
            'AF' => NumberFormat::FORMAT_TEXT, // NIK Wali
        ];
    }
}
