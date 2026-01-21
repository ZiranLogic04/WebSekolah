<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'content',
        'use_system_kop',
        'paper_size'
    ];

    protected $casts = [
        'use_system_kop' => 'boolean',
    ];

    public function surats()
    {
        return $this->hasMany(Surat::class, 'template_id');
    }
}
