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
    ];
}
