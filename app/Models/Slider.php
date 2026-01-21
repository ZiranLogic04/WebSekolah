<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'image',
        'image_position',
        'top_badge',
        'title',
        'subtitle',
        'button_text',
        'button_url',
        'order',
        'is_active',
    ];
}
