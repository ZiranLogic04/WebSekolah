<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Gallery::query()->orderBy('created_at', 'desc');

        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        $galleries = $query->paginate(12);
        
        // Get unique categories for filter
        $categories = Gallery::distinct()->pluck('category')->filter()->values();

        return Inertia::render('Admin/Gallery', [
            'galleries' => $galleries,
            'categories' => $categories,
            'filters' => $request->only(['category', 'search']),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        $imagePath = $request->file('image')->store('galleries', 'public');

        Gallery::create([
            'title' => $request->title,
            'image' => $imagePath,
            'category' => $request->category,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Gambar berhasil diupload');
    }

    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data = [
            'title' => $request->title,
            'category' => $request->category,
            'description' => $request->description,
            'is_active' => $request->is_active ?? true,
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($gallery->image) {
                Storage::disk('public')->delete($gallery->image);
            }
            $data['image'] = $request->file('image')->store('galleries', 'public');
        }

        $gallery->update($data);

        return redirect()->back()->with('success', 'Gambar berhasil diupdate');
    }

    public function destroy(Gallery $gallery)
    {
        // Delete image file
        if ($gallery->image) {
            Storage::disk('public')->delete($gallery->image);
        }

        $gallery->delete();

        return redirect()->back()->with('success', 'Gambar berhasil dihapus');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:galleries,id',
        ]);

        $galleries = Gallery::whereIn('id', $request->ids)->get();

        foreach ($galleries as $gallery) {
            if ($gallery->image) {
                Storage::disk('public')->delete($gallery->image);
            }
            $gallery->delete();
        }

        return redirect()->back()->with('success', count($request->ids) . ' gambar berhasil dihapus');
    }
}
