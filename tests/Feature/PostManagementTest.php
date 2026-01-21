<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_post_list()
    {
        $user = User::factory()->create();
        Post::factory()->count(3)->create();

        $response = $this->actingAs($user)->get(route('posts.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_create_post()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        
        $file = UploadedFile::fake()->image('cover.jpg');

        $data = [
            'title' => 'Berita Baru Hari Ini',
            'content' => 'Isi berita yang sangat menarik.',
            'status' => 'draft',
            'image' => $file,
        ];

        $response = $this->actingAs($user)->post(route('posts.store'), $data);

        $response->assertRedirect(route('posts.index'));
        $this->assertDatabaseHas('posts', [
            'title' => 'Berita Baru Hari Ini',
            'status' => 'draft',
        ]);
    }

    public function test_admin_can_update_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['author_id' => $user->id]);

        $response = $this->actingAs($user)->put(route('posts.update', $post), [
            'title' => 'Judul Diedit',
            'content' => 'Konten diedit',
            'status' => 'published',
        ]);

        $response->assertRedirect(route('posts.index'));
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Judul Diedit',
            'status' => 'published',
        ]);
    }

    public function test_admin_can_delete_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user)->delete(route('posts.destroy', $post));

        $response->assertRedirect(route('posts.index'));
        $this->assertModelMissing($post);
    }

    public function test_public_user_can_view_published_post()
    {
        $post = Post::factory()->create([
            'status' => 'published',
            'published_at' => now(),
        ]);

        // Assumes route is /berita/{slug}
        $response = $this->get("/berita/{$post->slug}");

        $response->assertStatus(200);
        $response->assertSee($post->title);
    }

    public function test_public_user_cannot_view_draft_post()
    {
        $post = Post::factory()->create([
            'status' => 'draft',
        ]);

        $response = $this->get("/berita/{$post->slug}");
        
        // Should be 404 because user queries typically filter by 'published'
        $response->assertNotFound();
    }
}
