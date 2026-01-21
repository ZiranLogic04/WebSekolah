<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Added this line

class UangSekolah extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_id',
        'kategori_id',
        'kategori',
        'sumber',
        'jumlah',
        'tipe',
        'tanggal',
        'keterangan',
        'is_manual',
        'tahun_ajaran_id',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
    
    public function jenisKategori()
    {
        return $this->belongsTo(JenisTagihan::class, 'kategori_id');
    }
}
