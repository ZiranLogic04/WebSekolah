<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuNavbar;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class MenuItemController extends Controller
{
    /**
     * Display Level 2: Groups/Items for a specific Menu Navbar
     */
    public function index($menuNavbarId)
    {
        $menuNavbar = MenuNavbar::findOrFail($menuNavbarId);
        
        $items = MenuItem::where('menu_navbar_id', $menuNavbarId)
            ->whereNull('parent_id') // Level 2 only
            ->orderBy('urutan')
            ->get();

        return Inertia::render('Admin/MenuItem/Index', [
            'menu' => $menuNavbar,
            'items' => $items,
            'level' => 2,
            'parent' => null
        ]);
    }

    /**
     * Display Level 3: Children of a Group
     */
    public function show($id)
    {
        $group = MenuItem::with('menuNavbar')->findOrFail($id);
        
        // Pastikan ini adalah grup
        if ($group->type !== 'group') {
            return redirect()->route('admin.menu-item.edit-content', $group->id);
        }

        $items = MenuItem::where('parent_id', $id)
            ->orderBy('urutan')
            ->get();

        return Inertia::render('Admin/MenuItem/Index', [
            'menu' => $group->menuNavbar,
            'items' => $items,
            'level' => 3,
            'parent' => $group
        ]);
    }

    /**
     * Store new item (Group or Page)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_navbar_id' => 'required|exists:menu_navbar,id',
            'parent_id' => 'nullable|exists:menu_items,id',
            'nama' => 'required|string|max:100',
            'type' => 'required|in:group,page,url',
            'url' => 'nullable|string',
        ]);

        // Auto slug
        $slug = Str::slug($validated['nama']);
        
        // Ensure unique slug per scope
        $count = MenuItem::where('slug', $slug)
            ->where('menu_navbar_id', $validated['menu_navbar_id'])
            ->where('parent_id', $validated['parent_id'])
            ->count();
        
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        MenuItem::create([
            ...$validated,
            'slug' => $slug,
            'urutan' => MenuItem::where('parent_id', $validated['parent_id'])->max('urutan') + 1,
            'is_active' => true
        ]);

        return back()->with('success', 'Item berhasil ditambahkan');
    }

    /**
     * Update item details (nama, active)
     */
    public function update(Request $request, $id)
    {
        $item = MenuItem::findOrFail($id);
        
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'is_active' => 'boolean'
        ]);

        $item->update($validated);

        // Clear ALL menu caches
        \Illuminate\Support\Facades\Cache::forget('menus_global');
        \Illuminate\Support\Facades\Cache::forget('mega_menu_global');
        \Illuminate\Support\Facades\Cache::forget('guru_menu_global');
        \Illuminate\Support\Facades\Cache::forget('siswa_menu_global');
        \Illuminate\Support\Facades\Cache::forget('alumni_menu_global');
        \Illuminate\Support\Facades\Cache::forget('ppdb_menu_global');

        return back()->with('success', 'Data berhasil diperbarui');
    }
    
    /**
     * Delete item
     */
    public function destroy($id)
    {
        $item = MenuItem::findOrFail($id);
        $item->delete();
        
        // Clear ALL menu caches
        \Illuminate\Support\Facades\Cache::forget('menus_global');
        \Illuminate\Support\Facades\Cache::forget('mega_menu_global');
        \Illuminate\Support\Facades\Cache::forget('guru_menu_global');
        \Illuminate\Support\Facades\Cache::forget('siswa_menu_global');
        \Illuminate\Support\Facades\Cache::forget('alumni_menu_global');
        \Illuminate\Support\Facades\Cache::forget('ppdb_menu_global');
        
        return back()->with('success', 'Item berhasil dihapus');
    }

    /**
     * Edit Content Page (Level 4)
     */
    public function editContent($id)
    {
        $item = MenuItem::with(['menuNavbar', 'parent'])->findOrFail($id);

        if ($item->type !== 'page') {
            return back()->with('error', 'Hanya tipe halaman yang memiliki konten editor.');
        }

        return Inertia::render('Admin/MenuItem/Edit', [
            'item' => $item
        ]);
    }

    /**
     * Update Content
     */
    public function updateContent(Request $request, $id)
    {
        $item = MenuItem::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'nullable|string|max:100',
            'content' => 'nullable|string',
        ]);

        $updateData = ['content' => $validated['content']];
        
        // Update nama dan slug jika nama dikirim
        if (!empty($validated['nama'])) {
            $updateData['nama'] = $validated['nama'];
            $updateData['slug'] = \Illuminate\Support\Str::slug($validated['nama']);
        }

        $item->update($updateData);

        return back()->with('success', 'Data berhasil disimpan');
    }

    /**
     * Mega Menu Editor (untuk menu Akademik dengan 4 kolom)
     */
    public function megaMenu($menuNavbarId)
    {
        $menuNavbar = MenuNavbar::findOrFail($menuNavbarId);
        
        // Pastikan 4 grup mega menu sudah ada HANYA jika bukan menu GURU, SISWA, ALUMNI, atau PPDB
        if (!in_array($menuNavbar->slug, ['guru', 'siswa', 'alumni', 'ppdb'])) {
            $this->ensureMegaMenuGroups($menuNavbar);
        }
        
        // Ambil semua item level 1 (parent_id = null) regardless of type
        $groups = MenuItem::where('menu_navbar_id', $menuNavbarId)
            ->whereNull('parent_id')
            // ->where('type', 'group') // Allow page/url for simple list mode
            ->orderBy('urutan')
            ->get();
        
        // Ambil semua items dari semua grup
        $items = MenuItem::where('menu_navbar_id', $menuNavbarId)
            ->whereNotNull('parent_id')
            ->orderBy('urutan')
            ->get();
        
        // Ambil deskripsi "Tentang Kami" dari grup pertama
        $aboutGroup = $groups->where('slug', 'tentang-kami')->first();
        $aboutDescription = $aboutGroup ? $aboutGroup->content : '';

        return Inertia::render('Admin/MenuItem/MegaMenu', [
            'menu' => $menuNavbar,
            'groups' => $groups,
            'items' => $items,
            'aboutDescription' => $aboutDescription
        ]);
    }

    /**
     * Pastikan 4 grup mega menu dan items statis sudah ada
     */
    private function ensureMegaMenuGroups(MenuNavbar $menuNavbar)
    {
        $defaultGroups = [
            ['nama' => 'Tentang Kami', 'slug' => 'tentang-kami', 'urutan' => 1],
            ['nama' => 'Profil Madrasah', 'slug' => 'profil', 'urutan' => 2],
            ['nama' => 'Akademik & Program', 'slug' => 'akademik-program', 'urutan' => 3],
            ['nama' => 'Layanan & Sumber', 'slug' => 'layanan', 'urutan' => 4],
        ];

        // Static items untuk setiap grup
        $staticItems = [
            'profil' => [
                ['nama' => 'Visi & Misi', 'slug' => 'visi-misi', 'urutan' => 1],
                ['nama' => 'Sejarah Singkat', 'slug' => 'sejarah', 'urutan' => 2],
                ['nama' => 'Struktur Organisasi', 'slug' => 'struktur-organisasi', 'urutan' => 3],
                // ['nama' => 'Sambutan Kepala Sekolah', 'slug' => 'sambutan', 'urutan' => 4], // Removed

                ['nama' => 'Sarana & Prasarana', 'slug' => 'sarana-prasarana', 'urutan' => 5],
                ['nama' => 'Tujuan', 'slug' => 'tujuan', 'urutan' => 6],
                ['nama' => 'Kepala Madrasah', 'slug' => 'kepala-madrasah', 'urutan' => 7],
                ['nama' => 'Prestasi', 'slug' => 'prestasi', 'urutan' => 8],
                ['nama' => 'Kemitraan', 'slug' => 'kemitraan', 'urutan' => 9],
            ],
            'akademik-program' => [
                ['nama' => 'Kurikulum & Mapel', 'slug' => 'kurikulum-mapel', 'urutan' => 1],
                ['nama' => 'Kalender Akademik', 'slug' => 'kalender-akademik', 'urutan' => 2],
                ['nama' => 'Program Tahsin/Tahfiz & BTQ', 'slug' => 'tahsin-tahfiz', 'urutan' => 3],
                ['nama' => 'Literasi & Klinik Numerasi', 'slug' => 'literasi-numerasi', 'urutan' => 4],
                ['nama' => 'Ekstrakurikuler', 'slug' => 'ekstrakurikuler', 'urutan' => 5],
                ['nama' => 'Tata Tertib Akademik', 'slug' => 'tata-tertib', 'urutan' => 6],
            ],
            'layanan' => [
                ['nama' => 'Berita & Pengumuman', 'slug' => 'berita-pengumuman', 'urutan' => 1],
                ['nama' => 'Gallery Foto/Video', 'slug' => 'gallery', 'urutan' => 2],
                ['nama' => 'Unduhan', 'slug' => 'unduhan', 'urutan' => 3],
                ['nama' => 'PPDB Online', 'slug' => 'ppdb-online-app', 'urutan' => 4],
                ['nama' => 'E-Books', 'slug' => 'e-books', 'urutan' => 5],
                ['nama' => 'Kontak & Lokasi', 'slug' => 'kontak-lokasi', 'urutan' => 6],
            ],
        ];

        foreach ($defaultGroups as $group) {
            $groupModel = MenuItem::firstOrCreate(
                [
                    'menu_navbar_id' => $menuNavbar->id,
                    'slug' => $group['slug'],
                    'parent_id' => null
                ],
                [
                    'nama' => $group['nama'],
                    'type' => 'group',
                    'is_active' => true,
                    'urutan' => $group['urutan']
                ]
            );

            // Seed static items logic moved/disabled to allow deletion
            /*
            if (isset($staticItems[$group['slug']])) {
                foreach ($staticItems[$group['slug']] as $item) {
                    MenuItem::firstOrCreate(
                        [
                            'menu_navbar_id' => $menuNavbar->id,
                            'parent_id' => $groupModel->id,
                            'slug' => $item['slug']
                        ],
                        [
                            'nama' => $item['nama'],
                            'type' => 'page',
                            'is_active' => true,
                            'urutan' => $item['urutan']
                        ]
                    );
                }
            }
            */
        }
    }

    /**
     * Store item dalam mega menu grup
     */
    public function storeMegaMenuItem(Request $request, $menuNavbarId)
    {
        $menuNavbar = MenuNavbar::findOrFail($menuNavbarId);
        
        $validated = $request->validate([
            'group_slug' => 'required|string',
            'nama' => 'required|string|max:100',
            'url' => 'nullable|string',
        ]);

        // Cari grup berdasarkan slug
        $group = MenuItem::where('menu_navbar_id', $menuNavbarId)
            ->where('slug', $validated['group_slug'])
            ->whereNull('parent_id')
            ->firstOrFail();

        // Buat slug untuk item baru
        $slug = Str::slug($validated['nama']);
        $count = MenuItem::where('parent_id', $group->id)->where('slug', $slug)->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        MenuItem::create([
            'menu_navbar_id' => $menuNavbarId,
            'parent_id' => $group->id,
            'nama' => $validated['nama'],
            'slug' => $slug,
            'type' => 'page',
            'url' => $validated['url'] ?? null,
            'is_active' => true,
            'urutan' => MenuItem::where('parent_id', $group->id)->max('urutan') + 1
        ]);

        return back()->with('success', 'Item berhasil ditambahkan');
    }

    /**
     * Update deskripsi Tentang Kami
     */
    public function updateMegaMenuAbout(Request $request, $menuNavbarId)
    {
        $validated = $request->validate([
            'description' => 'nullable|string',
        ]);

        // Update content di grup "Tentang Kami"
        MenuItem::where('menu_navbar_id', $menuNavbarId)
            ->where('slug', 'tentang-kami')
            ->whereNull('parent_id')
            ->update(['content' => $validated['description']]);

        return back()->with('success', 'Deskripsi berhasil disimpan');
    }
}
