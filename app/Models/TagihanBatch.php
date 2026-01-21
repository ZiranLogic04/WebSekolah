<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TagihanBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_tagihan_id',
        'nama_tagihan',
        'target_kelas',
        'target_gender',
        'jumlah',
        'tenggat_waktu',
        'keterangan',
        'tahun_ajaran_id',
        'total_siswa',
    ];

    public function jenisTagihan()
    {
        return $this->belongsTo(JenisTagihan::class);
    }

    public function tagihans()
    {
        return $this->hasMany(Tagihan::class);
    }

    // Accessor for count if needed, or use withCount in controller
}
