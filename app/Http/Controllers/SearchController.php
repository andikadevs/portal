<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * Pencarian global artikel (PRD F-04/F-13).
     */
    public function __invoke(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $articles = Article::published()
            ->latestPublished()
            ->with(['category', 'author'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('title', 'like', "%{$q}%")
                        ->orWhere('excerpt', 'like', "%{$q}%")
                        ->orWhere('body', 'like', "%{$q}%");
                });
            })
            ->paginate(9)
            ->withQueryString();

        return view('search', compact('articles', 'q'));
    }
}
