<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuNavbar;
use App\Models\MenuItem;

class AlumniFixSeeder extends Seeder
{
    public function run()
    {
        $menu = MenuNavbar::where('slug', 'alumni')->first();
        
        if ($menu) {
            // Delete extras
            MenuItem::where('menu_navbar_id', $menu->id)
                ->whereIn('slug', ['info-reuni', 'tracer-study', 'legalisir-online'])
                ->delete();

            // Ensure "Alumni Portal" exists
            MenuItem::firstOrCreate(
                ['menu_navbar_id' => $menu->id, 'slug' => 'alumni-portal'],
                [
                    'nama' => 'Alumni Portal',
                    'is_active' => true,
                    'type' => 'page',
                    'urutan' => 2,
                    'content' => '<p>Selamat datang di Portal Alumni. Silakan isikan informasi di sini melalui Admin Panel.</p>'
                ]
            );

            // Ensure urutan is correct
            $dir = MenuItem::where('menu_navbar_id', $menu->id)->where('slug', 'direktori-alumni')->first();
            if ($dir) {
                $dir->update(['urutan' => 1]);
            }
            
            $this->command->info('Alumni menu fixed: Only Direktori and Portal remain.');
        }
    }
}
