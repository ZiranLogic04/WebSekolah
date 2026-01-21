<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lembaga extends Model
{
    //
    protected $fillable = [
        'nama_sekolah',
        'alamat',
        'telepon',
        'email',
        'akreditasi',
        'npsn',
        'status',
        'tahun_berdiri',
        'kepala_sekolah',
        'logo',
        'tahun_ajaran_aktif_id',
        'semester_aktif',
        'logo_kiri',
        'logo_kanan',
        'kop_baris1',
        'kop_baris2',
        'kop_baris3',
        'kop_baris4',
        'kop_baris5',
        'facebook',
        'instagram',
        'twitter',
        'youtube',
        'whatsapp',
        'google_maps',
    ];
}
