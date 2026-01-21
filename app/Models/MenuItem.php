<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $table = 'menu_items';

    protected $fillable = [
        'menu_navbar_id',
        'parent_id',
        'nama',
        'slug',
        'type',
        'content',
        'url',
        'is_active',
        'urutan',
    ];

    /**
     * Get the menu navbar that owns the item.
     */
    public function menuNavbar()
    {
        return $this->belongsTo(MenuNavbar::class);
    }

    /**
     * Get the parent item (for nested items).
     */
    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /**
     * Get the child items.
     */
    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('urutan');
    }

    /**
     * Scope a query to only include active items.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order items by 'urutan'.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan');
    }
}
