<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicCategoryController extends Controller
{
    /**
     * Daftar artikel per kategori + cari dalam kategori (PRD F-03/F-04/F-13).
     */
    public function show(Request $request, Category $category): View
    {
        $q = trim((string) $request->query('q', ''));

        $articles = $category->articles()
            ->published()
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

        return view('categories.show', compact('category', 'articles', 'q'));
    }
}
