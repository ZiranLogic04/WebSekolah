<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    protected $fillable = [
        'jenis_surat',
        'kode_surat',
        'kop_baris1',
        'kop_baris2',
        'kop_baris3',
        'kop_baris4',
        'kop_baris5',
        'kop_baris6',
        'isi_surat',
        'use_system_header',
        'template_id', 
        'data_json',
        'status'
    ];

    protected $casts = [
        'data_json' => 'array',
        'use_system_header' => 'boolean',
    ];

    public function template()
    {
        return $this->belongsTo(LetterTemplate::class, 'template_id');
    }
}
