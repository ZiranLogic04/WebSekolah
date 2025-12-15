<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'tagihan_batch_id',
        'siswa_id',
        'jenis_tagihan_id',
        'nama_tagihan',
        'jumlah',
        'terbayar',
        'sisa',
        'status',
        'tenggat_waktu'
    ];

    public function siswa()
    {
        return $this->belongsTo(\App\Models\Siswa::class);
    }

    public function jenisTagihan()
    {
        return $this->belongsTo(JenisTagihan::class);
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function batch()
    {
        return $this->belongsTo(TagihanBatch::class, 'tagihan_batch_id');
    }
}
