<?php

namespace App\Imports;

use App\Models\Guru;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GuruImport implements ToCollection, WithHeadingRow, WithValidation
{
    private $successCount = 0;
    private $errorCount = 0;
    private $errors = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                // Validasi data row
                $validator = Validator::make($row->toArray(), [
                    'nip' => 'required|unique:guru,nip',
                    'nama' => 'required',
                    'jenis_kelamin' => 'required|in:L,P',
                    'status_aktif' => 'required|in:Aktif,Non-Aktif,Cuti,Pensiun',
                ]);

                if ($validator->fails()) {
                    $this->errorCount++;
                    $this->errors[] = [
                        'row' => $row,
                        'errors' => $validator->errors()->all()
                    ];
                    continue;
                }

                // Format data
                $data = [
                    'nip' => $row['nip'] ?? null,
                    'nama' => $row['nama'] ?? null,
                    'bidang_studi' => $row['bidang_studi'] ?? null,
                    'jenis_kelamin' => $row['jenis_kelamin'] ?? 'L',
                    'tempat_lahir' => $row['tempat_lahir'] ?? null,
                    'tanggal_lahir' => $this->parseDate($row['tanggal_lahir'] ?? null),
                    'alamat' => $row['alamat'] ?? null,
                    'email' => $row['email'] ?? null,
                    'no_telepon' => $row['no_telepon'] ?? null,
                    'status_kepegawaian' => $row['status_kepegawaian'] ?? null,
                    'status_aktif' => $row['status_aktif'] ?? 'Aktif',
                    'pendidikan_terakhir' => $row['pendidikan_terakhir'] ?? null,
                    'tanggal_mulai_bekerja' => $this->parseDate($row['tanggal_mulai_bekerja'] ?? null),
                ];

                // Create guru
                Guru::create($data);
                $this->successCount++;

            } catch (\Exception $e) {
                $this->errorCount++;
                $this->errors[] = [
                    'row' => $row,
                    'errors' => [$e->getMessage()]
                ];
            }
        }
    }

    public function rules(): array
    {
        return [
            '*.nip' => 'required|unique:guru,nip',
            '*.nama' => 'required',
            '*.jenis_kelamin' => 'required|in:L,P',
            '*.status_aktif' => 'required|in:Aktif,Non-Aktif,Cuti,Pensiun',
        ];
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