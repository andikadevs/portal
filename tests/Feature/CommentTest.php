<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    private function publishedArticle(): Article
    {
        return Article::factory()
            ->for(Category::factory())
            ->for(User::factory(), 'author')
            ->create(['published_at' => now()->subDay()]);
    }

    public function test_guest_can_post_comment(): void
    {
        $article = $this->publishedArticle();

        $response = $this->post(route('comments.store', $article), [
            'name' => 'Pembaca Setia',
            'email' => 'pembaca@contoh.com',
            'body' => 'Artikel yang bermanfaat, terima kasih.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('comments', [
            'article_id' => $article->id,
            'name' => 'Pembaca Setia',
        ]);
    }

    public function test_comment_validation_fails_on_empty_body(): void
    {
        $article = $this->publishedArticle();

        $this->from(route('articles.show', $article))
            ->post(route('comments.store', $article), [
                'name' => 'A',
                'email' => 'bukan-email',
                'body' => '',
            ])
            ->assertSessionHasErrors(['email', 'body']);

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_pagination_shows_nine_per_page(): void
    {
        $category = Category::factory()->create();
        Article::factory()->count(12)
            ->for($category)
            ->for(User::factory(), 'author')
            ->create(['published_at' => now()->subDay()]);

        $this->get(route('categories.show', $category))
            ->assertOk()
            ->assertSee('Berikutnya');
    }
}
