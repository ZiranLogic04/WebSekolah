<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatKelas extends Model
{
    use HasFactory;

    protected $table = 'riwayat_kelas';

    protected $fillable = [
        'siswa_id',
        'kelas_sebelumnya',
        'kelas_sesudahnya',
        'tahun_ajaran_id',
        'status', // 'Naik Kelas', 'Tinggal Kelas', 'Lulus', 'Keluar', 'Pindah'
        'keterangan'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}
