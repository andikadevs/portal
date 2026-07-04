<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use Illuminate\View\View;

class StatistikController extends Controller
{
    /**
     * Statistik penuh — khusus ketua (PRD F-11/F-14).
     */
    public function __invoke(): View
    {
        $stats = [
            'users' => User::count(),
            'categories' => Category::count(),
            'articles' => Article::count(),
            'comments' => Comment::count(),
        ];

        // Distribusi artikel per kategori (untuk bar sederhana).
        $perCategory = Category::withCount('articles')
            ->orderByDesc('articles_count')
            ->get();

        $maxCount = (int) $perCategory->max('articles_count') ?: 1;

        // Artikel terbanyak per penulis.
        $perAuthor = User::withCount('articles')
            ->orderByDesc('articles_count')
            ->get();

        return view('admin.statistik', compact('stats', 'perCategory', 'maxCount', 'perAuthor'));
    }
}
