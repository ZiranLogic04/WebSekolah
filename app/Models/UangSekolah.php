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
        'is_manual'
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
    
    public function kategori()
    {
        return $this->belongsTo(JenisTagihan::class, 'kategori_id');
    }
}
