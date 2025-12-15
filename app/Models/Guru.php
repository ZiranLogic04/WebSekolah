<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guru extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'guru';
    
    protected $fillable = [
        'nip',
        'nama',
        'bidang_studi',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'email',
        'no_telepon',
        'status_kepegawaian',
        'status_aktif',
        'pendidikan_terakhir',
        'tanggal_mulai_bekerja',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_mulai_bekerja' => 'date',
    ];

}