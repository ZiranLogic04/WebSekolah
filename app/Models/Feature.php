<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        'icon',
        'title',
        'description',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Get available icons
    public static function getAvailableIcons(): array
    {
        return [
            'book-open.svg' => 'Buku Terbuka (Kurikulum)',
            'open-book.svg' => 'Buku Terbuka',
            'user-check.svg' => 'Guru Verified',
            'layers.svg' => 'Fasilitas (Layers)',
            'award.svg' => 'Penghargaan',
            'book.svg' => 'Buku',
            'building-2.svg' => 'Gedung',
            'teacher.svg' => 'Guru',
            'teacher-2.svg' => 'Guru 2',
            'library.svg' => 'Perpustakaan',
            'graduation.svg' => 'Wisuda',
            'scholarship.svg' => 'Beasiswa',
            'scholarship-2.svg' => 'Beasiswa 2',
            'scholarship-3.svg' => 'Beasiswa 3',
            'course.svg' => 'Kursus',
            'course-material.svg' => 'Materi Kursus',
            'online-course.svg' => 'Kursus Online',
            'global-education.svg' => 'Global',
            'exchange-idea.svg' => 'Diskusi',
            'data.svg' => 'Data',
            'monitor.svg' => 'Monitor',
            'health.svg' => 'Kesehatan',
            'human.svg' => 'Sosial',
            'art.svg' => 'Seni',
            'acting.svg' => 'Akting',
            'law.svg' => 'Hukum',
            'money.svg' => 'Keuangan',
            'information.svg' => 'Informasi',
        ];
    }
}
