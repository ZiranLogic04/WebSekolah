<?php

namespace App\Imports;

use App\Models\Guru;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;

class GuruImport implements ToCollection, WithHeadingRow
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
                $nip = $rowArray['nip'] ?? null;
                $nama = $rowArray['nama'] ?? null;
                
                if (empty($nip) && empty($nama)) {
                    continue;
                }

                // Validasi data row
                $validator = Validator::make($rowArray, [
                    'nip' => 'required|unique:guru,nip',
                    'nama' => 'required',
                    'jenis_kelamin' => 'required|in:L,P',
                    'status_aktif' => 'required|in:Aktif,Non-Aktif,Cuti,Pensiun',
                ]);

                if ($validator->fails()) {
                    $this->errorCount++;
                    $this->errors[] = [
                        'row' => $index + 2, // +2 karena index 0 + header row
                        'nip' => $nip,
                        'nama' => $nama,
                        'errors' => $validator->errors()->all()
                    ];
                    continue;
                }

                // Format data
                $data = [
                    'nip' => $rowArray['nip'] ?? null,
                    'nama' => $rowArray['nama'] ?? null,
                    'bidang_studi' => $rowArray['bidang_studi'] ?? null,
                    'jenis_kelamin' => $rowArray['jenis_kelamin'] ?? 'L',
                    'tempat_lahir' => $rowArray['tempat_lahir'] ?? null,
                    'tanggal_lahir' => $this->parseDate($rowArray['tanggal_lahir'] ?? null),
                    'alamat' => $rowArray['alamat'] ?? null,
                    'email' => $rowArray['email'] ?? null,
                    'no_telepon' => $rowArray['no_telepon'] ?? null,
                    'status_kepegawaian' => $rowArray['status_kepegawaian'] ?? null,
                    'status_aktif' => $rowArray['status_aktif'] ?? 'Aktif',
                    'pendidikan_terakhir' => $rowArray['pendidikan_terakhir'] ?? null,
                    'tanggal_mulai_bekerja' => $this->parseDate($rowArray['tanggal_mulai_bekerja'] ?? null),
                ];

                // Create guru
                Guru::create($data);
                $this->successCount++;

            } catch (\Exception $e) {
                $this->errorCount++;
                $this->errors[] = [
                    'row' => $index + 2,
                    'nip' => $rowArray['nip'] ?? null,
                    'nama' => $rowArray['nama'] ?? null,
                    'errors' => [$e->getMessage()]
                ];
            }
        }
    }

    private function parseDate($date)
    {
        if (empty($date)) {
            return null;
        }

        try {
            if (is_numeric($date)) {
                // Handle Excel serial date
                $unixDate = ($date - 25569) * 86400;
                return \Carbon\Carbon::createFromTimestamp($unixDate);
            } else {
                return \Carbon\Carbon::parse($date);
            }
        } catch (\Exception $e) {
            return null;
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