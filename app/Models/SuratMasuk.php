<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SuratMasuk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_agenda',
        'nomor_surat',
        'pengirim',
        'perihal',
        'tgl_surat',
        'tgl_diterima',
        'file_path',
        'keterangan'
    ];
}
