<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\View\View;

class PublicArticleController extends Controller
{
    /**
     * Halaman detail artikel + komentar (PRD F-02/F-05).
     */
    public function show(Article $article): View
    {
        abort_if($article->published_at === null || $article->published_at->isFuture(), 404);

        $article->load(['category', 'author', 'comments' => function ($query) {
            $query->latest();
        }]);

        $related = Article::published()
            ->where('category_id', $article->category_id)
            ->whereKeyNot($article->id)
            ->latestPublished()
            ->with(['category', 'author'])
            ->take(3)
            ->get();

        return view('articles.show', compact('article', 'related'));
    }
}
