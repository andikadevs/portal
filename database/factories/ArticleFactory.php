<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    public function definition(): array
    {
        $title = rtrim(fake()->sentence(mt_rand(6, 10)), '.');

        // Bangun body HTML sederhana (mensimulasikan output rich text editor).
        $paragraphs = collect(fake()->paragraphs(mt_rand(4, 7)))
            ->map(fn (string $p): string => '<p>'.e($p).'</p>')
            ->implode("\n");

        return [
            'title' => Str::title($title),
            'slug' => Str::slug($title).'-'.Str::lower(Str::random(5)),
            'excerpt' => fake()->sentence(mt_rand(12, 20)),
            'body' => $paragraphs,
            'thumbnail' => null,
            'category_id' => Category::factory(),
            'user_id' => User::factory(),
            'published_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }

    /**
     * Artikel yang belum terbit (draft).
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'published_at' => null,
        ]);
    }
}
