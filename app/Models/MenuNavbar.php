<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuNavbar extends Model
{
    use HasFactory;

    protected $table = 'menu_navbar';

    protected $fillable = [
        'slug',
        'nama',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope untuk menu aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope urut berdasarkan urutan
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan');
    }
    /**
     * Relasi ke MenuItem
     */
    public function items()
    {
        return $this->hasMany(MenuItem::class, 'menu_navbar_id', 'id')->orderBy('urutan');
    }
}
