<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::published()
            ->with('author')
            ->latest('published_at')
            ->paginate(9);
            
        return Inertia::render('Post/Index', [
            'posts' => $posts
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
