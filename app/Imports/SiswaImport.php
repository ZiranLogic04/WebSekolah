<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;

class SiswaImport implements ToCollection, WithHeadingRow
{
    private $successCount = 0;
    private $errorCount = 0;
    private $errors = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            try {
                $rowArray = $row->toArray();
                
                // Skip baris kosong
                $nis = $rowArray['nis'] ?? null;
                $nama = $rowArray['nama'] ?? null;
                
                if (empty($nis) && empty($nama)) {
                    continue;
                }

                // Validasi kelas
                $kelas = $rowArray['kelas'] ?? null;
                $kelasExists = Kelas::where('nama', $kelas)->exists();
                
                // Validasi data row
                $validator = Validator::make($rowArray, [
                    'nis' => 'required|unique:siswa,nis',
                    'nama' => 'required',
                    'kelas' => 'required',
                    'jenis_kelamin' => 'required|in:L,P',
                ]);

                if ($validator->fails()) {
                    $this->errorCount++;
                    $this->errors[] = [
                        'row' => $index + 2,
                        'nis' => $nis,
                        'nama' => $nama,
                        'errors' => $validator->errors()->all()
                    ];
                    continue;
                }
                
                if (!$kelasExists) {
                    $this->errorCount++;
                    $this->errors[] = [
                        'row' => $index + 2,
                        'nis' => $nis,
                        'nama' => $nama,
                        'errors' => ["Kelas '{$kelas}' tidak ditemukan"]
                    ];
                    continue;
                }

                // Format data
                $data = [
                    'nis' => $rowArray['nis'] ?? null,
                    'nama' => $rowArray['nama'] ?? null,
                    'kelas' => $rowArray['kelas'] ?? null,
                    'jenis_kelamin' => $rowArray['jenis_kelamin'] ?? 'L',
                    'tempat_lahir' => $rowArray['tempat_lahir'] ?? null,
                    'tanggal_lahir' => $this->parseDate($rowArray['tanggal_lahir'] ?? null),
                    'alamat' => $rowArray['alamat'] ?? null,
                    'nama_ayah' => $rowArray['nama_ayah'] ?? null,
                    'nama_ibu' => $rowArray['nama_ibu'] ?? null,
                    'no_telepon' => $rowArray['no_telepon'] ?? null,
                    'pekerjaan_orang_tua' => $rowArray['pekerjaan_orang_tua'] ?? null,
                    'status' => $rowArray['status'] ?? 'Aktif',
                ];

                // Create siswa
                Siswa::create($data);
                $this->successCount++;

            } catch (\Exception $e) {
                $this->errorCount++;
                $this->errors[] = [
                    'row' => $index + 2,
                    'nis' => $rowArray['nis'] ?? null,
                    'nama' => $rowArray['nama'] ?? null,
                    'errors' => [$e->getMessage()]
                ];
            }
        }
    }

    private function parseDate($value)
    {
        if (!$value) return null;

        try {
            if (is_numeric($value)) {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))
                    ->format('Y-m-d');
            }
            return Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
        } catch (\Exception $e) {
            try {
                return Carbon::parse($value)->format('Y-m-d');
            } catch (\Exception $e2) {
                return null;
            }
        }
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getErrorCount()
    {
        return $this->errorCount;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
