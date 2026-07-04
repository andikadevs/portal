<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArticleManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_article_with_thumbnail(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.articles.store'), [
            'title' => 'Berita Uji Coba Pertama',
            'category_id' => $category->id,
            'excerpt' => 'Ringkasan singkat.',
            'body' => '<p>Isi artikel <strong>uji</strong>.</p><script>alert(1)</script>',
            'thumbnail' => UploadedFile::fake()->image('thumb.jpg', 800, 450),
            'published_at' => now()->toDateTimeString(),
        ]);

        $response->assertRedirect(route('admin.articles.index'));

        $article = Article::firstWhere('title', 'Berita Uji Coba Pertama');
        $this->assertNotNull($article);
        $this->assertSame($admin->id, $article->user_id);
        $this->assertNotNull($article->thumbnail);
        Storage::disk('public')->assertExists($article->thumbnail);

        // Body harus tersanitasi (script dibuang oleh purifier).
        $this->assertStringNotContainsString('<script>', $article->body);
        $this->assertStringContainsString('<strong>', $article->body);
    }

    public function test_article_requires_title_and_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->from(route('admin.articles.create'))
            ->post(route('admin.articles.store'), ['body' => 'x'])
            ->assertSessionHasErrors(['title', 'category_id']);
    }

    public function test_admin_can_delete_article(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $article = Article::factory()->for(Category::factory())->for($admin, 'author')->create();

        $this->actingAs($admin)->delete(route('admin.articles.destroy', $article))
            ->assertRedirect(route('admin.articles.index'));

        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }

    public function test_published_article_is_visible_publicly(): void
    {
        $article = Article::factory()
            ->for(Category::factory())
            ->for(User::factory(), 'author')
            ->create(['published_at' => now()->subDay()]);

        $this->get(route('articles.show', $article))->assertOk()->assertSee($article->title);
    }

    public function test_future_article_returns_404(): void
    {
        $article = Article::factory()
            ->for(Category::factory())
            ->for(User::factory(), 'author')
            ->create(['published_at' => now()->addWeek()]);

        $this->get(route('articles.show', $article))->assertNotFound();
    }
}
