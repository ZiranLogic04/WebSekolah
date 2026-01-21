<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Berita terbaru (TETAP DINAMIS)
        $posts = \App\Models\Post::published()->with('author')->latest('published_at')->take(3)->get();
        
        // Statistik dari Section Counter
        $counterSection = \App\Models\Section::where('key', 'counter')->first();
        $counterContent = $counterSection ? $counterSection->content : [];
        
        $stats = [
            'siswa_aktif' => $counterContent['siswa_aktif'] ?? '0',
            'guru' => $counterContent['guru'] ?? '0',
            'rombel' => $counterContent['rombel'] ?? '0',
            'alumni' => $counterContent['alumni'] ?? '0',
        ];


        // Slider Beranda (DINAMIS)
        $sliders = \App\Models\Slider::where('is_active', true)->orderBy('order', 'asc')->get();

        // Features (4 poin di bawah slider)
        $features = \App\Models\Feature::where('is_active', true)->orderBy('order', 'asc')->get();

        // About Section (Tentang Kami)
        $aboutSection = \App\Models\Section::where('key', 'about')->first();
        $about = $aboutSection ? $aboutSection->content : [];

        // Program Section (Header Only)
        $programSection = \App\Models\Section::where('key', 'program')->first();
        $program = $programSection ? $programSection->content : [];

        // Video Section
        $videoSection = \App\Models\Section::where('key', 'video')->first();
        $video = $videoSection ? $videoSection->content : [];

        // Team Section (Guru & Tendik) - manual items
        $teamSection = \App\Models\Section::where('key', 'team')->first();
        $team = $teamSection ? $teamSection->content : [];

        // Choose Us Section
        $chooseSection = \App\Models\Section::where('key', 'choose')->first();
        $choose = $chooseSection ? $chooseSection->content : [];

        // Portfolio Section
        $portfolioSection = \App\Models\Section::where('key', 'portfolio')->first();
        $portfolio = $portfolioSection ? $portfolioSection->content : [];

        // Event Section (Agenda)
        $eventSection = \App\Models\Section::where('key', 'event')->first();
        $event = $eventSection ? $eventSection->content : [];

        // Testimonial Section
        $testimonialSection = \App\Models\Section::where('key', 'testimonial')->first();

        // Partner Section
        $partnerSection = \App\Models\Section::where('key', 'partner')->first();

        return Inertia::render('Beranda', [
            'sliders' => $sliders,
            'features' => $features,
            'about' => $about,
            'program' => $program,
            'video' => $video,
            'team' => $team,
            'choose' => $choose,
            'portfolio' => $portfolio,
            'event' => $event,
            'testimonial' => $testimonialSection,
            'partner' => $partnerSection,
            'posts' => $posts,
            'stats' => $stats,
        ]);
    }
}
