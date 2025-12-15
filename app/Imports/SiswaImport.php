<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use App\Models\Kelas;
use Exception;

class SiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // 1. Validasi Kelas: Cek di DB
        $kelas = Kelas::where('nama', $row['kelas'])->first();
        
        if (!$kelas) {
            // Jika kelas tidak ada di database, lemparkan error atau skip
            // Opsi: Throw error agar user tahu
            throw new Exception("Kelas '{$row['kelas']}' tidak ditemukan di database. Tambahkan kelas terlebih dahulu.");
        }

        return new Siswa([
            'nis' => $row['nis'],
            'nama' => $row['nama'],
            'kelas' => $row['kelas'], // Simpan string nama kelas
            'jenis_kelamin' => $row['jenis_kelamin'],
            'tempat_lahir' => $row['tempat_lahir'] ?? null,
            'tanggal_lahir' => $this->parseDate($row['tanggal_lahir'] ?? null),
            'alamat' => $row['alamat'] ?? null,
            'nama_ayah' => $row['nama_ayah'] ?? null,
            'nama_ibu' => $row['nama_ibu'] ?? null,
            'no_telepon' => $row['no_telepon'] ?? null,
            'pekerjaan_orang_tua' => $row['pekerjaan_orang_tua'] ?? null,
        ]);
    }

private function parseDate($value)
{
    if (!$value) return null;

    // Jika Excel date number
    if (is_numeric($value)) {
        return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))
            ->format('Y-m-d');
    }

    // Jika text: DD/MM/YYYY → convert manual
    try {
        return Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    } catch (\Exception $e) {
        return null;
    }
}
}
