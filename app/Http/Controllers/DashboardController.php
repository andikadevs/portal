<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dasbor dengan konten berbeda per role (PRD F-08/F-11).
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isKetua()) {
            $stats = [
                'users' => User::count(),
                'categories' => Category::count(),
                'articles' => Article::count(),
                'comments' => Comment::count(),
            ];

            $recentArticles = Article::with(['category', 'author'])
                ->latest()
                ->take(5)
                ->get();

            $recentComments = Comment::with('article')
                ->latest()
                ->take(5)
                ->get();

            return view('dashboard.ketua', compact('stats', 'recentArticles', 'recentComments'));
        }

        // Admin: ringkasan konten miliknya.
        $stats = [
            'my_articles' => $user->articles()->count(),
            'categories' => Category::count(),
            'my_comments' => Comment::whereHas('article', fn ($q) => $q->where('user_id', $user->id))->count(),
        ];

        $recentArticles = $user->articles()
            ->with('category')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.admin', compact('stats', 'recentArticles'));
    }
}
