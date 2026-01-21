<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $posts = Post::query()
            ->with('author')
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Posts/Index', [
            'posts' => $posts,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Posts/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['author_id'] = auth()->id();
        
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $this->processImage($request->file('image'));
        }

        Post::create($validated);

        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return Inertia::render('Admin/Posts/Edit', [
            'post' => $post
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
        ];

        // Only validate image if it's being uploaded
        if ($request->hasFile('image')) {
            $rules['image'] = 'image|mimes:jpeg,png,jpg,gif,webp|max:2048';
        }

        $validated = $request->validate($rules);

        try {
            // Check slug uniqueness if title changed
            if ($request->filled('title') && $request->title !== $post->title) {
                $originalSlug = Str::slug($validated['title']);
                $slug = $originalSlug;
                $count = 1;
                
                // Ensure unique slug
                while (Post::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
                    $slug = $originalSlug . '-' . $count++;
                }
                $validated['slug'] = $slug;
            }

            if ($validated['status'] === 'published' && $post->status !== 'published') {
                $validated['published_at'] = now();
            }

            if ($request->hasFile('image')) {
                // Delete old image
                if ($post->image) {
                    Storage::disk('public')->delete($post->image);
                }
                // Process new image to WebP
                $validated['image'] = $this->processImage($request->file('image'));
            } else {
                // Keep existing image
                unset($validated['image']);
            }

            $post->update($validated);

            return redirect()->route('posts.index')->with('success', 'Post updated successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui berita: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // 1. Delete Cover Image
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        // 2. Delete Embedded Images in Content
        // Extract all <img src="..."> specific to our storage
        $content = $post->content;
        preg_match_all('/src="\/storage\/(posts\/[^"]+)"/', $content, $matches);
        
        if (!empty($matches[1])) {
            foreach ($matches[1] as $imagePath) {
                // $imagePath will be something like 'posts/xyz.webp'
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
        }
        
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }

    public function toggleStatus(Post $post)
    {
        $newStatus = $post->status === 'published' ? 'draft' : 'published';
        $updateData = ['status' => $newStatus];

        if ($newStatus === 'published' && !$post->published_at) {
            $updateData['published_at'] = now();
        }

        $post->update($updateData);

        return back()->with('success', 'Status post berhasil diperbarui menjadi ' . ucfirst($newStatus));
    }

    /**
     * Convert and resize image to WebP
     */
    private function processImage($file)
    {
        $imageName = Str::random(40) . '.webp';
        $path = storage_path('app/public/posts/' . $imageName);
        
        // Create directory if not exists
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $info = getimagesize($file);
        $mime = $info['mime'];

        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($file);
                break;
            case 'image/png':
                $image = imagecreatefrompng($file);
                // Preserve transparency for PNG
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($file);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($file);
                break;
            default:
                // Fallback for unknown types, just store normally
                 return $file->store('posts', 'public');
        }

        // Save as WebP with 80% quality
        imagewebp($image, $path, 80);
        
        // Free up memory
        imagedestroy($image);

        return 'posts/' . $imageName;
    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('image')) {
            $path = $this->processImage($request->file('image'));
            return response()->json(['url' => Storage::url($path)]);
        }
        return response()->json(['error' => 'No image uploaded'], 400);
    }
}
