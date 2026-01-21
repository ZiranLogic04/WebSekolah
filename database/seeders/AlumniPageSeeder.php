<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuNavbar;
use App\Models\MenuItem;

class AlumniPageSeeder extends Seeder
{
    public function run()
    {
        $menu = MenuNavbar::where('slug', 'alumni')->first();
        
        if ($menu) {
            $items = [
                ['direktori-alumni', 'Direktori Alumni'],
                ['info-reuni', 'Info Reuni Akbar'],
                ['tracer-study', 'Tracer Study'],
                ['legalisir-online', 'Legalisir Online']
            ];

            foreach ($items as $index => $item) {
                MenuItem::firstOrCreate(
                    [
                        'menu_navbar_id' => $menu->id,
                        'slug' => $item[0]
                    ],
                    [
                        'nama' => $item[1],
                        'is_active' => true,
                        'type' => 'page',
                        'urutan' => $index + 1,
                        'content' => "<p>Halaman <strong>{$item[1]}</strong> untuk Alumni belum diisi. Silakan edit konten ini melalui <strong>Admin Panel > Menu Navbar > Alumni</strong>.</p>"
                    ]
                );
            }
            $this->command->info('Alumni pages seeded successfully.');
        } else {
            $this->command->error('Menu Navbar "alumni" not found.');
        }
    }
}
