<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $fillable = ['nama', 'wali_kelas_id'];

    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'wali_kelas_id');
    }

    public function siswa()
    {
        // Assuming siswa table has 'kelas' column storing string name
        // Ideally it should be foreign key, but legacy code validation suggests string: 'kelas' => 'exists:kelas,nama'
        return $this->hasMany(Siswa::class, 'kelas', 'nama');
    }
}
