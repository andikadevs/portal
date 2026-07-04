<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    /**
     * Simpan komentar publik pada artikel (PRD F-05).
     */
    public function store(StoreCommentRequest $request, Article $article): RedirectResponse
    {
        abort_if($article->published_at === null || $article->published_at->isFuture(), 404);

        $article->comments()->create($request->validated());

        return redirect()
            ->route('articles.show', $article)
            ->withFragment('komentar')
            ->with('success', 'Komentar terkirim. Terima kasih sudah berdiskusi.');
    }
}
