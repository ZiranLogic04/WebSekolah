<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Symfony\Component\HttpFoundation\Response;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'warning' => fn () => $request->session()->get('warning'),
                'info' => fn () => $request->session()->get('info'),
                'print_data' => fn () => $request->session()->get('print_data'),
                'popup_data' => fn () => $request->session()->get('popup_data'),
            ],
            'lembaga' => fn () => \Illuminate\Support\Facades\Cache::remember('lembaga_global', 60*60, function () {
                return \App\Models\Lembaga::first();
            }),
            'aboutSection' => fn () => \Illuminate\Support\Facades\Cache::remember('about_section_global', 60*60, function () {
                $section = \App\Models\Section::where('key', 'about')->first();
                return $section ? $section->content : null;
            }),
            'footerLinks' => fn () => \Illuminate\Support\Facades\Cache::remember('footer_links_global', 60*60, function () {
                $section = \App\Models\Section::where('key', 'footer_links')->first();
                return $section ? $section->content : null;
            }),
            'menus' => fn () => \Illuminate\Support\Facades\Cache::remember('menus_global', 60*60, function () {
                // Return key-value pair for easier access in frontend: { 'beranda': { nama: '...', is_active: true }, ... }
                $menus = \App\Models\MenuNavbar::all();
                return $menus->mapWithKeys(function ($item) {
                    return [$item->slug => [
                        'nama' => $item->nama,
                        'is_active' => (bool) $item->is_active,
                        'urutan' => $item->urutan
                    ]];
                });
            }),
            // Mega Menu untuk Akademik
            'megaMenu' => fn () => \Illuminate\Support\Facades\Cache::remember('mega_menu_global', 60*60, function () {
                $akademikMenu = \App\Models\MenuNavbar::where('slug', 'akademik')->first();
                if (!$akademikMenu) return null;
                
                $groups = \App\Models\MenuItem::where('menu_navbar_id', $akademikMenu->id)
                    ->whereNull('parent_id')
                    ->where('is_active', true)
                    ->orderBy('urutan')
                    ->get();
                
                $result = [];
                foreach ($groups as $group) {
                    $items = \App\Models\MenuItem::where('parent_id', $group->id)
                        ->where('is_active', true)
                        ->orderBy('urutan')
                        ->get(['id', 'nama', 'slug', 'url', 'content']);
                    
                    $result[$group->slug] = [
                        'nama' => $group->nama,
                        'content' => $group->content,
                        'items' => $items
                    ];
                }
                return $result;
            }),
            // Menu Guru Dinamis
            'guruMenu' => fn () => \Illuminate\Support\Facades\Cache::remember('guru_menu_global', 60*60, function () {
                $menu = \App\Models\MenuNavbar::where('slug', 'guru')->first();
                if (!$menu) return [];
                return \App\Models\MenuItem::where('menu_navbar_id', $menu->id)
                    ->whereNull('parent_id')
                    ->where('is_active', true)
                    ->orderBy('urutan')
                    ->get(['id', 'nama', 'slug', 'url', 'type']);
            }),
            // Menu Siswa Dinamis
            'siswaMenu' => fn () => \Illuminate\Support\Facades\Cache::remember('siswa_menu_global', 60*60, function () {
                $menu = \App\Models\MenuNavbar::where('slug', 'siswa')->first();
                if (!$menu) return [];
                return \App\Models\MenuItem::where('menu_navbar_id', $menu->id)
                    ->whereNull('parent_id')
                    ->where('is_active', true)
                    ->orderBy('urutan')
                    ->get(['id', 'nama', 'slug', 'url', 'type']);
            }),
            // Menu Alumni Dinamis
            'alumniMenu' => fn () => \Illuminate\Support\Facades\Cache::remember('alumni_menu_global', 60*60, function () {
                $menu = \App\Models\MenuNavbar::where('slug', 'alumni')->first();
                if (!$menu) return [];
                return \App\Models\MenuItem::where('menu_navbar_id', $menu->id)
                    ->whereNull('parent_id')
                    ->where('is_active', true)
                    ->orderBy('urutan')
                    ->get(['id', 'nama', 'slug', 'url', 'type']);
            }),
            // Menu PPDB Dinamis
            'ppdbMenu' => fn () => \Illuminate\Support\Facades\Cache::remember('ppdb_menu_global', 60*60, function () {
                $menu = \App\Models\MenuNavbar::where('slug', 'ppdb')->first();
                if (!$menu) return [];
                return \App\Models\MenuItem::where('menu_navbar_id', $menu->id)
                    ->whereNull('parent_id')
                    ->where('is_active', true)
                    ->orderBy('urutan')
                    ->get(['id', 'nama', 'slug', 'url', 'type']);
            }),
        ];
    }

    /**
     * Handle the incoming request.
     * 
     * PERBAIKAN: Override handle method untuk force 303
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = parent::handle($request, $next);

        // Force 303 redirect for PUT, PATCH, DELETE requests that are Inertia requests
        if ($response instanceof \Illuminate\Http\RedirectResponse &&
            in_array($request->method(), ['PUT', 'PATCH', 'DELETE', 'POST']) &&
            $request->header('X-Inertia')) {
            $response->setStatusCode(303);
        }

        return $response;
    }
}