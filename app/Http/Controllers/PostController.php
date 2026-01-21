<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::published()
            ->with('author')
            ->latest('published_at');

        if ($request->has('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate(9)->withQueryString();
            
        return Inertia::render('Post/Index', [
            'posts' => $posts,
            'filters' => $request->only(['q']) // Optional: pass back to view
        ]);
    }

    public function show($slug)
    {
        $post = Post::published()
            ->with('author')
            ->where('slug', $slug)
            ->firstOrFail();
            
        // Increment views (simple imp)
        // $post->increment('views'); // If I had views column, lets skip for now.

        $recentPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(5)
            ->get();

        return Inertia::render('Post/Show', [
            'post' => $post,
            'recentPosts' => $recentPosts
        ]);
    }
}
