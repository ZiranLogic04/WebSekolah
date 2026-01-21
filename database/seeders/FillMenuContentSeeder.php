<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuItem;

class FillMenuContentSeeder extends Seeder
{
    public function run()
    {
        // Find items (reset logic to update existing injected ones too if needed, 
        // but user asked to 'test' realistic input. I'll target the ones I modified or all 'page' types)
        // To be safe, let's update ALL 'page' types that don't have parent (level 1) or Level 2.
        // Or just target the ones I likely touched (based on content signature or just re-seed empty ones).
        // Since user complained about the specific 'grid' ones, I should overwrite them.
        
        $items = MenuItem::where('type', 'page')
            ->get();

        $this->command->info("Refilling " . $items->count() . " items with REALISTIC content.");

        foreach ($items as $item) {
            $title = $item->nama;
            
            // Realistic CKEditor Output (No Bootstrap Grid, No Custom Classes)
            $html = <<<HTML
<h2>Tentang $title</h2>
<p>Selamat datang di halaman resmi <strong>$title</strong>.</p>
<p>Halaman ini berisi informasi mendetail mengenai $title di lingkungan madrasah kami. Kami berkomitmen untuk memberikan layanan dan informasi terbaik.</p>

<h3>Informasi Utama</h3>
<p>Berikut adalah poin-poin penting:</p>
<ul>
    <li>Fokus pada kualitas pendidikan.</li>
    <li>Didukung tenaga profesional.</li>
    <li>Fasilitas yang memadai.</li>
</ul>

<figure class="image">
    <img src="/assets/img/img-01.jpg" alt="Dokumentasi $title">
    <figcaption>Dokumentasi Kegiatan $title</figcaption>
</figure>

<p>Untuk informasi lebih lanjut, silakan hubungi bagian administrasi.</p>
HTML;

            // Only update if it currently contains the "illegal" bootstrap classes OR is empty
            // Check for 'row' or 'col-md' which indicates my previous seed
            if (str_contains($item->content, 'col-md-') || empty($item->content) || $item->content === '<p><br></p>') {
                 $item->update(['content' => $html]);
                 $this->command->info("Fixed: {$item->nama}");
            }
        }
    }
}
