<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Homepage dinamis: lede, berita terbaru, seksi per kategori (PRD F-01).
     */
    public function index(): View
    {
        $latest = Article::published()
            ->latestPublished()
            ->with(['category', 'author'])
            ->take(5)
            ->get();

        $lede = $latest->first();
        $terbaru = $latest->skip(1)->take(4);

        // Seksi per kategori: tiap kategori dengan beberapa artikel terbaru.
        $sections = Category::with(['articles' => function ($query) {
            $query->published()->latestPublished()->with(['category', 'author'])->take(3);
        }])
            ->orderBy('name')
            ->get()
            ->filter(fn (Category $category) => $category->articles->isNotEmpty());

        return view('home', compact('lede', 'terbaru', 'sections'));
    }
}
