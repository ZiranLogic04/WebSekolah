<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'nis',
        'nisn',
        'nama',
        'kelas',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'nik',
        'nkk',
        'anak_ke',
        'jumlah_saudara',
        'alamat',
        'rt',
        'rw',
        'tinggal_bersama',
        'no_telepon',
        
        // Orang Tua - Ayah
        'nama_ayah',
        'nik_ayah',
        'tempat_lahir_ayah',
        'tanggal_lahir_ayah',
        'pekerjaan_ayah',
        'pendidikan_ayah',
        'penghasilan_ayah',
        
        // Orang Tua - Ibu
        'nama_ibu',
        'nik_ibu',
        'tempat_lahir_ibu',
        'tanggal_lahir_ibu',
        'pekerjaan_ibu',
        'pendidikan_ibu',
        
        // Wali
        'nama_wali',
        'nik_wali',
        'pekerjaan_wali',
        'pendidikan_wali',
        'hubungan_wali',
        
        // Sekolah
        'status',
        'status_masuk',
        'diterima_di_kelas',
        'sekolah_asal',
        'tanggal_masuk',
        'tahun_lulus',
        
        // Kesehatan & Fisik
        'riwayat_penyakit',
        'berat_badan',
        'tinggi_badan',
        'golongan_darah',
        
        // Lain-lain
        'cita_cita',
        'hobi',
        'no_akte',
        
        // Files / Dokumen
        'file_foto',
        'file_akte',
        'file_kk',
        'file_kip',
        'file_ktp_ortu',
        
        // Legacy / Generic (keep just in case)
        'pekerjaan_orang_tua',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_lahir_ayah' => 'date',
        'tanggal_lahir_ibu' => 'date',
        'tanggal_masuk' => 'date',
    ];

    public function riwayatKelas()
    {
        return $this->hasMany(RiwayatKelas::class)->orderBy('created_at', 'desc');
    }
}