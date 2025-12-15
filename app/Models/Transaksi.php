<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'tagihan_id',
        'kode_transaksi',
        'jumlah_bayar',
        'tanggal_bayar',
        'metode_pembayaran',
        'keterangan'
    ];

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class);
    }
    //
}
