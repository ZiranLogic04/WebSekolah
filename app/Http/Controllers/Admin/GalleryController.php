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
        ], [
            'title.required' => 'Judul gambar wajib diisi.',
            'image.required' => 'File gambar wajib diunggah.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        // Convert to WebP
        $image = $request->file('image');
        $filename = 'gallery_' . time() . '_' . uniqid() . '.webp';
        
        $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
        $img = $manager->read($image->getRealPath());
        
        // Resize if too large (max 1920px width)
        if ($img->width() > 1920) {
            $img->scale(width: 1920);
        }
        
        // Save as WebP with 85% quality
        $savePath = storage_path('app/public/galleries/' . $filename);
        
        // Ensure directory exists
        if (!file_exists(storage_path('app/public/galleries'))) {
            mkdir(storage_path('app/public/galleries'), 0755, true);
        }
        
        $img->toWebp(85)->save($savePath);

        Gallery::create([
            'title' => $request->title,
            'image' => 'galleries/' . $filename,
            'category' => $request->category,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Berhasil mengunggah gambar baru.');
    }

    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ], [
            'title.required' => 'Judul gambar wajib diisi.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 5MB.',
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
            
            // Convert to WebP
            $image = $request->file('image');
            $filename = 'gallery_' . time() . '_' . uniqid() . '.webp';
            
            $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
            $img = $manager->read($image->getRealPath());
            
            if ($img->width() > 1920) {
                $img->scale(width: 1920);
            }
            
            $savePath = storage_path('app/public/galleries/' . $filename);
            
            if (!file_exists(storage_path('app/public/galleries'))) {
                mkdir(storage_path('app/public/galleries'), 0755, true);
            }
            
            $img->toWebp(85)->save($savePath);
            $data['image'] = 'galleries/' . $filename;
        }

        $gallery->update($data);

        return redirect()->back()->with('success', 'Berhasil memperbarui data gambar.');
    }

    public function destroy(Gallery $gallery)
    {
        // Delete image file
        if ($gallery->image) {
            Storage::disk('public')->delete($gallery->image);
        }

        $gallery->delete();

        return redirect()->back()->with('success', 'Berhasil menghapus gambar.');
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
