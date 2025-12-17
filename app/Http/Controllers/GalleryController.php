<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Post;
use Inertia\Inertia;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        // Get gallery images
        $allImages = Gallery::active()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => 'gallery-' . $item->id,
                    'title' => $item->title,
                    'image' => asset('storage/' . $item->image),
                    'category' => $item->category,
                    'description' => $item->description,
                    'source' => 'gallery',
                    'date' => $item->created_at->format('d M Y'),
                ];
            });



        // Get unique categories
        $categories = $allImages->pluck('category')->unique()->filter()->values();

        // Filter by category if requested
        if ($request->has('category') && $request->category) {
            $allImages = $allImages->filter(function ($item) use ($request) {
                return $item['category'] === $request->category;
            })->values();
        }

        return Inertia::render('Gallery/Index', [
            'images' => $allImages,
            'categories' => $categories,
            'selectedCategory' => $request->category,
        ]);
    }
}
