<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuNavbar;
use App\Models\MenuItem;

class SiswaPageSeeder extends Seeder
{
    public function run()
    {
        $menu = MenuNavbar::where('slug', 'siswa')->first();
        
        if ($menu) {
            $items = [
                ['direktori-siswa', 'Direktori Siswa'],
                ['osis', 'OSIS'],
                ['prestasi-siswa', 'Prestasi Siswa'],
                ['beasiswa', 'Beasiswa']
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
                        'content' => "<p>Halaman <strong>{$item[1]}</strong> belum diisi. Silakan edit konten ini melalui <strong>Admin Panel > Menu Navbar > Kesiswaan</strong>.</p>"
                    ]
                );
            }
            $this->command->info('Siswa pages seeded successfully.');
        } else {
            $this->command->error('Menu Navbar "siswa" not found.');
        }
    }
}
