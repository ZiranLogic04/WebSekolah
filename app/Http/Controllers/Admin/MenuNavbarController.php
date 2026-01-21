<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuNavbar;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MenuNavbarController extends Controller
{
    /**
     * Tampilkan halaman kelola menu navbar
     */
    /**
     * Tampilkan dashboard pengelola beranda
     */
    public function homeSections()
    {
        return Inertia::render('Admin/Halaman/HomeSections');
    }

    /**
     * Tampilkan halaman kelola menu navbar
     */
    public function index()
    {
        $menus = MenuNavbar::ordered()->get();

        return Inertia::render('Admin/Halaman/Index', [
            'menus' => $menus
        ]);
    }

    /**
     * Update nama dan status aktif menu
     */
    public function update(Request $request, MenuNavbar $menu)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'is_active' => 'required|boolean',
        ]);

        $menu->update($validated);
        
        // Hapus SEMUA cache menu agar frontend langsung update
        \Illuminate\Support\Facades\Cache::forget('menus_global');
        \Illuminate\Support\Facades\Cache::forget('mega_menu_global');
        \Illuminate\Support\Facades\Cache::forget('guru_menu_global');
        \Illuminate\Support\Facades\Cache::forget('siswa_menu_global');
        \Illuminate\Support\Facades\Cache::forget('alumni_menu_global');
        \Illuminate\Support\Facades\Cache::forget('ppdb_menu_global');

        return back()->with('success', 'Berhasil memperbarui status menu.');
    }

    /**
     * Update semua menu sekaligus (bulk update)
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'menus' => 'required|array',
            'menus.*.id' => 'required|exists:menu_navbar,id',
            'menus.*.nama' => 'required|string|max:50',
            'menus.*.is_active' => 'required|boolean',
        ]);

        foreach ($validated['menus'] as $menuData) {
            MenuNavbar::where('id', $menuData['id'])->update([
                'nama' => $menuData['nama'],
                'is_active' => $menuData['is_active'],
            ]);
        }
        
        // Hapus SEMUA cache menu agar frontend langsung update
        \Illuminate\Support\Facades\Cache::forget('menus_global');
        \Illuminate\Support\Facades\Cache::forget('mega_menu_global');
        \Illuminate\Support\Facades\Cache::forget('guru_menu_global');
        \Illuminate\Support\Facades\Cache::forget('siswa_menu_global');
        \Illuminate\Support\Facades\Cache::forget('alumni_menu_global');
        \Illuminate\Support\Facades\Cache::forget('ppdb_menu_global');

        return back()->with('success', 'Berhasil memperbarui semua menu.');
    }
}
