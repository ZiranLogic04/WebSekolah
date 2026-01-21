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
                // Konversi row ke array dengan heading slug
                $rowArray = $row->toArray();
                
                // --- MAPPING FIELD ---
                // Kita harus menangani mapping manual karena header Excel user mungkin duplikat (NIK, Tempat Lahir, dll)
                // Maatwebsite/Excel biasanya meriname duplikat jadi: nik, nik_1, nik_2, dst.
                // Urutan di Excel User:
                // Siswa: NIK (1)
                // Ayah: NIK (2), Tempat Lahir (2), Tgl Lahir (2), Pekerjaan (1), Pendidikan (1)
                // Ibu: NIK (3), Tempat Lahir (3), Tgl Lahir (3), Pekerjaan (2), Pendidikan (2)
                // Wali: NIK (4), Pekerjaan (3), Pendidikan (3)
                
                // Identifikasi Field Utama
                $nis = $rowArray['nis'] ?? null;
                $nisn = $rowArray['nisn'] ?? null;
                
                // Normalisasi Nama (Sesuai Controller Preview)
                $nama = $rowArray['nama_siswa'] ?? ($rowArray['nama_lengkap'] ?? ($rowArray['nama'] ?? null));
                
                // Skip jika identifier utama kosong
                if (empty($nis) && empty($nama)) {
                    continue;
                }

                // --- VALIDASI ---
                // Validasi Kelas
                // Asumsi di excel kolomnya 'KELAS' -> 'kelas' atau 'diterima_di_kelas'
                $kelasNama = $rowArray['kelas'] ?? ($rowArray['diterima_di_kelas'] ?? null);
                
                // Normalisasi Jenis Kelamin (Sangat Robust sesuai Controller)
                // Tangani l_p (template), lp (slug varian), jenis_kelamin, jk, gender
                $jenisKelamin = $rowArray['l_p'] ?? ($rowArray['lp'] ?? ($rowArray['jenis_kelamin'] ?? ($rowArray['jk'] ?? ($rowArray['gender'] ?? null))));
                
                $kelasExists = Kelas::where('nama', $kelasNama)->exists();
                
                // Validasi Data
                $validator = Validator::make([
                    'nis' => $nis,
                    'nama' => $nama,
                    'kelas' => $kelasNama,
                    'jenis_kelamin' => $jenisKelamin,
                ], [
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
                
                // --- PREPARE DATA ---
                
                // Helper untuk ambil value dari beberapa kemungkinan key (karena duplikasi header)
                $getVal = function($keys) use ($rowArray) {
                    foreach ((array)$keys as $k) {
                        if (!empty($rowArray[$k])) return $rowArray[$k];
                    }
                    return null;
                };

                $data = [
                    // Identity
                    'nis' => $nis,
                    'nisn' => $nisn,
                    'nama' => $nama,
                    'kelas' => $kelasNama ?? 'X', // Default X jika kosong? Atau biarkan null
                    'jenis_kelamin' => $getVal(['l_p', 'jenis_kelamin', 'lp']),
                    'tempat_lahir' => $getVal(['tempat_lahir']), // Siswa (biasanya yg pertama)
                    'tanggal_lahir' => $this->parseDate($getVal(['tgl_lahir', 'tanggal_lahir'])),
                    'agama' => $getVal(['agama']),
                    'nik' => $getVal(['nik']), // NIK Siswa (pertama)
                    'nkk' => $getVal(['nkk', 'no_kk']),
                    'anak_ke' => $getVal(['anak_ke']),
                    'jumlah_saudara' => $getVal(['jml_saudara', 'jumlah_saudara']),
                    
                    // Address
                    'alamat' => $getVal(['alamat']),
                    'rt' => $getVal(['rt']),
                    'rw' => $getVal(['rw']),
                    'tinggal_bersama' => $getVal(['tinggal_bersama']),
                    'no_telepon' => $getVal(['no_hp', 'no_telepon', 'hp']),
                    'no_akte' => $getVal(['no_akte', 'no_akta']),
                    
                    // Ayah
                    'nama_ayah' => $getVal(['nama_ayah']),
                    'nik_ayah' => $getVal(['nik_1', 'nik_ayah']), // NIK kedua biasanya _1
                    'tempat_lahir_ayah' => $getVal(['tempat_lahir_1', 'tempat_lahir_ayah']),
                    'tanggal_lahir_ayah' => $this->parseDate($getVal(['tgl_lahir_1', 'tgl_lahir_ayah', 'tanggal_lahir_ayah'])),
                    'pekerjaan_ayah' => $getVal(['pekerjaan', 'pekerjaan_ayah']),
                    'pendidikan_ayah' => $getVal(['pendidikan', 'pendidikan_ayah']),
                    'penghasilan_ayah' => $getVal(['penghasilan_per_bulan', 'penghasilan_ayah']),
                    
                    // Ibu
                    'nama_ibu' => $getVal(['nama_ibu']),
                    'nik_ibu' => $getVal(['nik_2', 'nik_ibu']), 
                    'tempat_lahir_ibu' => $getVal(['tempat_lahir_2', 'tempat_lahir_ibu']),
                    'tanggal_lahir_ibu' => $this->parseDate($getVal(['tgl_lahir_2', 'tgl_lahir_ibu', 'tanggal_lahir_ibu'])),
                    'pekerjaan_ibu' => $getVal(['pekerjaan_1', 'pekerjaan_ibu']), // Pekerjaan kedua
                    'pendidikan_ibu' => $getVal(['pendidikan_1', 'pendidikan_ibu']),
                    
                    // Wali
                    'nama_wali' => $getVal(['nama_wali']),
                    'nik_wali' => $getVal(['nik_3', 'nik_wali']),
                    'pekerjaan_wali' => $getVal(['pekerjaan_2', 'pekerjaan_wali']), // Pekerjaan ketiga
                    'pendidikan_wali' => $getVal(['pendidikan_2', 'pendidikan_wali']),
                    'hubungan_wali' => $getVal(['hubungan_dengan_peserta_didik', 'hubungan_wali']),
                    
                    // School Info
                    'status_masuk' => $getVal(['status_masuk']),
                    'diterima_di_kelas' => $getVal(['diterima_di_kelas']),
                    'sekolah_asal' => $getVal(['sekolah_asal']),
                    'tanggal_masuk' => $this->parseDate($getVal(['tgl_masuk', 'tanggal_masuk', 'tgl_daftar'])),
                    
                    // Physical
                    'riwayat_penyakit' => $getVal(['penyakit_berat_yang_pernah_di_derita', 'penyakit_berat', 'riwayat_penyakit']),
                    'berat_badan' => $getVal(['bb', 'berat_badan']),
                    'tinggi_badan' => $getVal(['tb', 'tinggi_badan']),
                    'golongan_darah' => $getVal(['gol', 'golongan_darah']),
                    
                    // Misc
                    'cita_cita' => $getVal(['cita_cita']),
                    'hobi' => $getVal(['hobi']),
                    'status' => 'Aktif', // Default
                ];
                
                // Fallback Generic Pekerjaan Ortu for compatibility
                if (empty($data['pekerjaan_orang_tua'])) {
                    $data['pekerjaan_orang_tua'] = $data['pekerjaan_ayah'] ?? $data['pekerjaan_ibu'];
                }

                Siswa::create($data);
                $this->successCount++;

            } catch (\Exception $e) {
                $this->errorCount++;
                $this->errors[] = [
                    'row' => $index + 2,
                    'nis' => $rowArray['nis'] ?? '?',
                    'nama' => $rowArray['nama'] ?? '?',
                    'errors' => [$e->getMessage()]
                ];
            }
        }
    }

    private function parseDate($value)
    {
        if (!$value || $value == '-') return null;

        try {
            if (is_numeric($value)) {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))
                    ->format('Y-m-d');
            }
            // Coba berbagai format standard Indonesia
            try {
                return Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
            } catch (\Exception $e) {
                 try {
                    return Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
                } catch (\Exception $e2) {
                    return Carbon::parse($value)->format('Y-m-d');
                }
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
